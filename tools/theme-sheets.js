// Generate the review pages over the shots in ~/Desktop/theme-shots/,
// character-first (the systematic human walk: pick the structural decision,
// then flip through everything it can wear):
//
//   index.html        - pick a character (each shown in the house default)
//   <character>.html  - that character's whole wardrobe: a section per
//                       mood, a row per flavor, light and dark side by
//                       side as the pair they're authored as (24 shots)
//
// How to run (after tools/theme-shots.js has produced the jpgs):
//   node tools/theme-sheets.js
//
// The axis value lists below must match CHARACTERS / MOODS / FLAVORS in
// scripts/settings-panel.js (same drift risk the FOUC pattern documents).

var fs = require('fs');
var path = require('path');
var os = require('os');

var OUT = path.join(os.homedir(), 'Desktop', 'theme-shots');

var CHARACTERS = ['product', 'marketing', 'interface', 'editorial', 'terminal'];
var MOODS = ['expressive', 'technical', 'quiet'];
var FLAVORS = ['default', 'earth', 'cool', 'sweet'];

var STYLE = [
	'<style>',
	':root { color-scheme: light dark; }',
	'body { margin: 0; padding: 2rem; font-family: system-ui, sans-serif; background: light-dark(#f2f0ec, #1a1a1e); color: light-dark(#222, #ddd); max-width: 1500px; margin-inline: auto; }',
	'h1 { font-size: 1.4rem; text-transform: capitalize; }',
	'h2 { font-size: 1.15rem; margin-top: 3.5rem; text-transform: capitalize; border-bottom: 1px solid light-dark(#ccc, #444); padding-bottom: 0.5rem; }',
	'nav { font-size: 0.9rem; margin-bottom: 2rem; }',
	'nav a { margin-right: 1rem; text-transform: capitalize; }',
	'.flavor-row { display: grid; grid-template-columns: 6rem 1fr 1fr; gap: 1rem; align-items: start; margin-top: 1.5rem; }',
	'.flavor-row h3 { font-size: 0.85rem; text-transform: capitalize; margin: 0; padding-top: 0.5rem; opacity: 0.8; }',
	'.characters { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; }',
	'figure { margin: 0; }',
	'figcaption { font-size: 0.7rem; opacity: 0.7; margin-top: 0.3rem; text-transform: capitalize; }',
	'img { width: 100%; display: block; border: 1px solid light-dark(#ccc, #444); }',
	'a { color: inherit; }',
	'</style>',
].join('\n');

function page(title, body) {
	return [
		'<!doctype html>',
		'<html lang="en">',
		'<head>',
		'<meta charset="utf-8">',
		'<meta name="viewport" content="width=device-width, initial-scale=1">',
		'<title>' + title + '</title>',
		STYLE,
		'</head>',
		'<body>',
		body,
		'</body>',
		'</html>',
	].join('\n');
}

function shot(character, mood, flavor, scheme, caption) {
	var name = character + '--' + mood + '--' + flavor + '--' + scheme + '.jpg';
	return '<figure><a href="' + name + '"><img loading="lazy" src="' + name + '" alt=""></a><figcaption>' + caption + '</figcaption></figure>';
}

function characterNav(current) {
	var links = ['<a href="index.html">index</a>'];

	CHARACTERS.forEach(function (character) {
		if (character === current) {
			links.push('<strong style="margin-right:1rem;text-transform:capitalize">' + character + '</strong>');
		} else {
			links.push('<a href="' + character + '.html">' + character + '</a>');
		}
	});

	return '<nav>' + links.join('') + '</nav>';
}

// One page per character: every option under it.
CHARACTERS.forEach(function (character) {
	var body = [characterNav(character), '<h1>' + character + ' - every option</h1>'];

	MOODS.forEach(function (mood) {
		body.push('<h2>' + mood + '</h2>');

		FLAVORS.forEach(function (flavor) {
			body.push(
				'<div class="flavor-row">' +
				'<h3>' + flavor + '</h3>' +
				shot(character, mood, flavor, 'light', 'light') +
				shot(character, mood, flavor, 'dark', 'dark') +
				'</div>'
			);
		});
	});

	fs.writeFileSync(path.join(OUT, character + '.html'), page(character + ' - theme shots', body.join('\n')));
});

// The index: pick a character.
var index = [
	'<h1>Theme shots - pick a character</h1>',
	'<p>Each character page shows every option under it: three moods, each with the four flavors as light/dark pairs (24 shots per character, 120 total). Thumbnails below are each character in the house default (expressive / default / light).</p>',
	'<div class="characters">',
];

CHARACTERS.forEach(function (character) {
	var name = character + '--expressive--default--light.jpg';

	index.push(
		'<figure><a href="' + character + '.html"><img loading="lazy" src="' + name + '" alt=""></a>' +
		'<figcaption><a href="' + character + '.html">' + character + '</a></figcaption></figure>'
	);
});

index.push('</div>');
fs.writeFileSync(path.join(OUT, 'index.html'), page('Theme shots', index.join('\n')));

console.log('wrote index.html + ' + CHARACTERS.length + ' character pages in ' + OUT);
