// Resume theme check - the sheet must be theme-proof. Prints each given
// resume route under every theme a visitor can set (character, mood,
// flavor, scheme, red light) and asserts they all come out as ONE sheet,
// pixel for pixel. The pin that makes this true is the "THE SHEET IS
// THEME-PROOF" block in styles/modules/resume.css; this is its proof. If
// a theme layer learns a new token and the pin doesn't restate it, this
// fails and names the theme that leaked.
//
// Run by bin/resume-fit-check.sh; runnable by hand:
//   node bin/resume-theme-check.mjs product-designer product-designer/cover-letter
// Needs MAMP serving derek.local:8888, Node 22+ (built-in WebSocket), and
// pdftoppm (poppler). Drives Chrome over the DevTools protocol, because the
// theme has to be set ON the live page - the plain --print-to-pdf command
// line can't set attributes.

import { spawn, execFileSync } from 'node:child_process';
import { createHash } from 'node:crypto';
import { mkdtempSync, writeFileSync, readFileSync, rmSync } from 'node:fs';
import { tmpdir } from 'node:os';
import { join } from 'node:path';

const CHROME = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';
const BASE = 'http://derek.local:8888/resume/';
const PORT = 9333;

// null = the page as served (the control). The rest cover every axis and
// the combinations most likely to leak: dark scheme with each structure
// change, and red light (the layer that stomps all color).
const THEMES = [
	null,
	{ 'data-brand-mood': 'expressive' },
	{ 'data-brand-mood': 'technical' },
	{ 'data-brand-mood': 'quiet' },
	{ 'data-brand-character': 'terminal' },
	{ 'data-brand-character': 'marketing', 'data-flavor': 'sweet' },
	{ 'data-brand-character': 'editorial', 'data-scheme': 'dark' },
	{ 'data-brand-character': 'interface', 'data-flavor': 'earth' },
	{ 'data-brand-mood': 'technical', 'data-flavor': 'cool', 'data-scheme': 'dark' },
	{ 'data-scheme': 'light' },
	{ 'data-red-light': '' },
];

const THEME_ATTRIBUTES = ['data-brand-mood', 'data-brand-character', 'data-flavor', 'data-scheme', 'data-red-light'];

const routes = process.argv.slice(2);
if (routes.length === 0) {
	console.error('usage: node bin/resume-theme-check.mjs <route> [route...]');
	process.exit(2);
}

const work = mkdtempSync(join(tmpdir(), 'resume-theme-'));
const chrome = spawn(CHROME, ['--headless', '--disable-gpu', '--remote-debugging-port=' + PORT, '--user-data-dir=' + join(work, 'profile'), 'about:blank'], { stdio: 'ignore' });

function pause(ms) {
	return new Promise(function (resolve) {
		setTimeout(resolve, ms);
	});
}

// Chrome takes a moment to open its debugging port.
async function pageSocketUrl() {
	for (let attempt = 0; attempt < 50; attempt++) {
		try {
			const targets = await (await fetch('http://127.0.0.1:' + PORT + '/json')).json();
			const page = targets.find(function (target) {
				return target.type === 'page';
			});

			if (page) {
				return page.webSocketDebuggerUrl;
			}
		} catch (notUpYet) {
			// keep waiting
		}

		await pause(200);
	}

	throw new Error('Chrome never opened its debugging port');
}

// One question at a time over the socket: send a command, wait for its
// answer; or wait for a named event (the page finishing its load).
let socket;
let nextId = 1;
const answers = new Map();
const eventWaiters = [];

function send(method, params) {
	const id = nextId++;
	socket.send(JSON.stringify({ id, method, params: params || {} }));

	return new Promise(function (resolve) {
		answers.set(id, resolve);
	});
}

function waitForEvent(method) {
	return new Promise(function (resolve) {
		eventWaiters.push({ method, resolve });
	});
}

// The raster of the sheet, as a fingerprint - equal fingerprints mean the
// same pixels.
function sheetFingerprint(pdfPath) {
	execFileSync('pdftoppm', ['-r', '100', '-singlefile', pdfPath, pdfPath]);
	return createHash('md5').update(readFileSync(pdfPath + '.ppm')).digest('hex');
}

let failed = false;

try {
	socket = new WebSocket(await pageSocketUrl());
	await new Promise(function (resolve) {
		socket.onopen = resolve;
	});

	socket.onmessage = function (message) {
		const data = JSON.parse(message.data);

		if (data.id && answers.has(data.id)) {
			answers.get(data.id)(data);
			answers.delete(data.id);
		}

		if (data.method) {
			eventWaiters
				.filter(function (waiter) {
					return waiter.method === data.method;
				})
				.forEach(function (waiter) {
					waiter.resolve();
				});
		}
	};

	await send('Page.enable');

	for (const route of routes) {
		let control = null;
		const leaks = [];

		for (let index = 0; index < THEMES.length; index++) {
			const theme = THEMES[index];
			const loaded = waitForEvent('Page.loadEventFired');
			await send('Page.navigate', { url: BASE + route });
			await loaded;

			if (theme) {
				const script = `(function () {
					var html = document.documentElement;
					${JSON.stringify(THEME_ATTRIBUTES)}.forEach(function (name) { html.removeAttribute(name); });
					var theme = ${JSON.stringify(theme)};
					Object.keys(theme).forEach(function (name) { html.setAttribute(name, theme[name]); });
				})()`;
				await send('Runtime.evaluate', { expression: script });
			}

			await send('Runtime.evaluate', { expression: 'document.fonts.ready.then(function () { return true; })', awaitPromise: true });
			await pause(300);

			const printed = await send('Page.printToPDF', { preferCSSPageSize: true, displayHeaderFooter: false });
			const pdfPath = join(work, route.replace(/\//g, '-') + '-' + index + '.pdf');
			writeFileSync(pdfPath, Buffer.from(printed.result.data, 'base64'));
			const fingerprint = sheetFingerprint(pdfPath);

			if (theme === null) {
				control = fingerprint;
			} else if (fingerprint !== control) {
				leaks.push(JSON.stringify(theme));
			}
		}

		if (leaks.length > 0) {
			failed = true;
			console.log('FAIL ' + route + ' - the sheet changes under: ' + leaks.join(', '));
		} else {
			console.log('PASS ' + route + ' - ' + THEMES.length + ' themes, one sheet');
		}
	}
} catch (error) {
	failed = true;
	console.log('FAIL theme check could not run: ' + error.message);
} finally {
	if (socket) {
		socket.close();
	}

	// Chrome keeps writing its profile for a moment after it's told to
	// stop - wait for it to actually exit before clearing the folder.
	const exited = new Promise(function (resolve) {
		chrome.once('exit', resolve);
	});
	chrome.kill();
	await Promise.race([exited, pause(5000)]);
	rmSync(work, { recursive: true, force: true, maxRetries: 5, retryDelay: 200 });
}

process.exit(failed ? 1 : 0);
