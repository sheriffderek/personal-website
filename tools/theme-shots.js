// Screenshot every theme combo into ~/Desktop/theme-shots/ (one jpg per
// character x mood x flavor x scheme - 120 shots), for theme-walk review
// and for handing groups of combos to an agent (the filenames are the
// query language: product--*, *--earth--*, *--quiet--default--light.jpg).
//
// How to run (MAMP must be serving the site - Derek starts it):
//   npm install --no-save puppeteer-core     (once, from the repo root)
//   node tools/theme-shots.js
//
// Then regenerate the review pages over the fresh shots:
//   node tools/theme-sheets.js
//
// How it works: drives the installed Chrome headless, loads the home page
// once, and per combo sets the same data-* attributes on <html> that the
// FOUC script sets - honoring the index-0 law (a default value removes the
// attribute instead of writing it). No URL-state support needed.
//
// The scheme axis shoots light and dark explicitly; system is skipped
// because it just resolves to one of those two.
//
// Shots are taken with prefers-reduced-motion emulated, so loops sit on
// their poster frames and every shot is stable and comparable.
//
// The axis value lists below must match CHARACTERS / MOODS / FLAVORS in
// scripts/settings-panel.js (same drift risk the FOUC pattern documents).

var puppeteer = require('puppeteer-core');
var fs = require('fs');
var path = require('path');
var os = require('os');

var SITE = 'http://derek.local:8888/';
var OUT = path.join(os.homedir(), 'Desktop', 'theme-shots');
var CHROME = '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome';

var CHARACTERS = ['product', 'marketing', 'interface', 'editorial', 'terminal'];
var MOODS = ['expressive', 'technical', 'quiet'];
var FLAVORS = ['default', 'earth', 'cool', 'sweet'];
var SCHEMES = ['light', 'dark'];

var TOTAL = CHARACTERS.length * MOODS.length * FLAVORS.length * SCHEMES.length;

// Runs in the page: dress <html> as this combo, exactly like the FOUC script.
function applyCombo(character, mood, flavor, scheme) {
	var html = document.documentElement;

	if (character === 'product') {
		html.removeAttribute('data-brand-character');
	} else {
		html.setAttribute('data-brand-character', character);
	}

	if (mood === 'expressive') {
		html.removeAttribute('data-brand-mood');
	} else {
		html.setAttribute('data-brand-mood', mood);
	}

	if (flavor === 'default') {
		html.removeAttribute('data-flavor');
	} else {
		html.setAttribute('data-flavor', flavor);
	}

	html.setAttribute('data-scheme', scheme);
	window.scrollTo(0, 0);
}

function waitForFonts() {
	return document.fonts.ready;
}

async function main() {
	fs.mkdirSync(OUT, { recursive: true });

	var browser = await puppeteer.launch({
		executablePath: CHROME,
		headless: 'new',
	});

	var page = await browser.newPage();
	await page.setViewport({ width: 1440, height: 1100, deviceScaleFactor: 2 });
	await page.emulateMediaFeatures([{ name: 'prefers-reduced-motion', value: 'reduce' }]);

	await page.goto(SITE, { waitUntil: 'networkidle2', timeout: 60000 });
	await page.evaluate(waitForFonts);

	var count = 0;

	for (var c = 0; c < CHARACTERS.length; c++) {
		for (var m = 0; m < MOODS.length; m++) {
			for (var f = 0; f < FLAVORS.length; f++) {
				for (var s = 0; s < SCHEMES.length; s++) {
					var character = CHARACTERS[c];
					var mood = MOODS[m];
					var flavor = FLAVORS[f];
					var scheme = SCHEMES[s];

					await page.evaluate(applyCombo, character, mood, flavor, scheme);
					await page.evaluate(waitForFonts);

					// let the theme crossfade finish before the shot
					await new Promise(function (resolve) { setTimeout(resolve, 700); });

					var name = character + '--' + mood + '--' + flavor + '--' + scheme + '.jpg';
					await page.screenshot({ path: path.join(OUT, name), type: 'jpeg', quality: 80 });

					count = count + 1;
					console.log(count + '/' + TOTAL + ' ' + name);
				}
			}
		}
	}

	await browser.close();
	console.log('done: ' + OUT);
}

main().catch(function (error) {
	console.error(error);
	process.exit(1);
});
