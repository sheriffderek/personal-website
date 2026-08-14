<?php

function load_json($path) {
	$full = CONTENT_DIR . '/' . $path;
	if (!file_exists($full)) return [];
	return json_decode(file_get_contents($full), true) ?? [];
}

/* The ?target= file. Everything tailored to one company lives in one folder -
   content/targets/<slug>/ - holding target.json (the words) beside the
   application PDFs (the files). Scrubbing the slug to a-z 0-9 dash keeps a
   crafted URL from walking the filesystem. Returns the decoded JSON with the
   clean slug added under 'slug' (pages need it to build the PDF paths), or
   null when there's no such target. */
function load_target($requested) {
	$slug = preg_replace('/[^a-z0-9-]/', '', strtolower($requested));

	if ($slug === '') {
		return null;
	}

	$target = load_json('targets/' . $slug . '/target.json');

	if (empty($target)) {
		return null;
	}

	$target['slug'] = $slug;

	return $target;
}

function load_markdown($path) {
	$full = CONTENT_DIR . '/' . $path;
	if (!file_exists($full)) return ['meta' => [], 'body' => ''];

	$raw = file_get_contents($full);
	$meta = [];
	$body = $raw;

	if (strpos($raw, '---') === 0) {
		$parts = explode("---", $raw, 3);
		if (count($parts) === 3) {
			foreach (explode("\n", trim($parts[1])) as $line) {
				if (strpos($line, ':') !== false) {
					[$k, $v] = explode(':', $line, 2);
					$meta[trim($k)] = trim($v);
				}
			}
			$body = trim($parts[2]);
		}
	}

	require_once SITE_ROOT . '/lib/Parsedown.php';
	$parser = new Parsedown();
	return ['meta' => $meta, 'body' => $parser->text($body)];
}

function render($template, $data = []) {
	extract($data);
	require TEMPLATES_DIR . '/' . $template . '.php';
}

function partial($name, $data = []) {
	extract($data);
	ob_start();
	include INCLUDES_DIR . '/' . $name . '.php';
	return ob_get_clean();
}

/* Free-form prose placed inside a quoted HTML attribute — converts quote
   characters to entities so an apostrophe can't end the attribute early. */
function quote_safe($text) {
	return htmlspecialchars($text ?? '', ENT_QUOTES);
}

/* The phone crop for a media file. Files are named
   <order>-<type>-<size>[-<slug>].<ext>, so the size token is 'wide' followed by
   either a slug ('-wide-intro.mp4') or the extension ('-wide.jpg'). Swap it for
   'square'. When no square cut has been made yet, fall back to the wide file so
   it simply serves at every width instead of 404-ing on a phone. */
function square_variant($path) {
	$square = str_replace(['-wide-', '-wide.'], ['-square-', '-square.'], $path);

	return is_file(SITE_ROOT . $square) ? $square : $path;
}

/* The freeze-frame poster for a video: the same basename with an image extension
   (01-play-wide-intro.mp4 -> 01-play-wide-intro.jpg). Deriving it from the video's
   own name means the square cut gets the square poster for free — no extra naming
   rule. Returns '' when no still has been made, and the <video> simply ships
   without a poster attribute. */
function poster_variant($path) {
	$dot = strrpos($path, '.');

	if ($dot === false) {
		return '';
	}

	$base = substr($path, 0, $dot);

	foreach (['jpg', 'jpeg', 'png', 'webp'] as $extension) {
		$still = $base . '.' . $extension;

		if (is_file(SITE_ROOT . $still)) {
			return $still;
		}
	}

	return '';
}

/* A milestone's poster art: the per-milestone SVG partial from the poster
   language lab (includes/posters/art/<slug>.php - the folder listing is the
   poster index), falling back to the original poster-shapes collage for any
   card without its own art yet. Returns the path for include. */
function poster_art_path($slug) {
	$art = INCLUDES_DIR . '/posters/art/' . $slug . '.php';

	return is_file($art) ? $art : INCLUDES_DIR . '/posters/poster-shapes.php';
}

/* The ascii twin of the poster art - the Terminal character's take on the
   cover (it rides along dormant in every poster slide; milestone.css swaps it
   in under data-brand-character='terminal'). Same fallback shape as
   poster_art_path: a per-milestone twin from includes/posters/art-ascii/
   <slug>.php - each one a few glyphs mirroring its drawn svg - falling back
   to the generic poster-ascii for any card without its own yet. */
function poster_ascii_path($slug) {
	$ascii = INCLUDES_DIR . '/posters/art-ascii/' . $slug . '.php';

	return is_file($ascii) ? $ascii : INCLUDES_DIR . '/posters/poster-ascii.php';
}

/* A milestone's real media items — the typed {type, src} entries that point at
   a made asset (not the shared placeholder). The template (templates/milestone.php)
   uses this to pick the media shape:
     any real media       → the poster-shapes cover FIRST, then the items, in a carousel
     none + "poster": true → the poster-shapes alone
     none                  → text-only
   The poster is never optional when there's media: a slide/video is NEVER
   rendered without the poster cover in front of it. There is no bare-media
   shape. Placeholder paths and empty entries are dropped, so the count
   reflects real media only. A bare string is treated as a photo. */
function real_media_items($milestone) {
	$items = [];

	foreach ($milestone['media'] ?? [] as $item) {
		$src = is_array($item) ? ($item['src'] ?? '') : $item;

		if (empty($src) || strpos($src, '/content/placeholder/') !== false) {
			continue;
		}

		$items[] = is_array($item) ? $item : ['type' => 'photo', 'src' => $item];
	}

	return $items;
}

/* Cache-busting: append a file's mtime to its URL so a changed file gets a new
   URL (forces a fresh fetch) while an unchanged file still caches. $path is
   web-absolute (/scripts/x.js). */
function asset($path) {
	$full = SITE_ROOT . $path;
	$version = is_file($full) ? filemtime($full) : '';
	return $path . ($version ? '?v=' . $version : '');
}

/* The site's stylesheets in PSSST order, each emitted as its own versioned
   <link>. styles/index.css is the single source of order. We walk the @import
   tree and emit a link for every file that has its own rules, because an
   @import chain can't be cache-busted from a parent's query string (each
   imported file caches under its own URL). A file that is PURE @import (a
   manifest like index.css / modules.css) isn't emitted — we recurse into it
   instead. Comments are stripped first so a commented-out @import is ignored. */
function stylesheet_paths($web_path = '/styles/index.css') {
	$full = SITE_ROOT . $web_path;

	if (!is_file($full)) {
		return [];
	}

	/* Strip CSS block comments so a commented-out @import isn't picked up. */
	$css = preg_replace('#/\*.*?\*/#s', '', file_get_contents($full));

	preg_match_all("/@import\s+'([^']+)'/", $css, $matches);
	$imports = $matches[1];

	$without_imports = preg_replace("/@import\s+'[^']+'\s*;?/", '', $css);
	$has_own_rules = trim($without_imports) !== '';

	/* Pure manifest (imports, no rules of its own): recurse, don't emit it. */
	if ($imports && !$has_own_rules) {
		$base = dirname($web_path);
		$paths = [];

		foreach ($imports as $import) {
			$paths = array_merge($paths, stylesheet_paths($base . '/' . $import));
		}

		return $paths;
	}

	/* Leaf (or a file that mixes rules with an import): emit it. */
	return [$web_path];
}

/* The code files that make up a build - one list shared by the dev stamp and
   the fingerprint, so they can never disagree about what "the code" is. */
function code_files() {
	$patterns = [
		SITE_ROOT . '/index.php',
		SITE_ROOT . '/includes/*.php',
		SITE_ROOT . '/includes/*/*.php',
		SITE_ROOT . '/templates/*.php',
		SITE_ROOT . '/templates/*/*.php',
		SITE_ROOT . '/scripts/*.js',
		SITE_ROOT . '/styles/*.css',
		SITE_ROOT . '/styles/*/*.css',
	];

	$files = [];

	foreach ($patterns as $pattern) {
		foreach (glob($pattern) as $file) {
			$files[] = $file;
		}
	}

	return $files;
}

/* The build fingerprint: 7 characters derived from the CONTENTS of every
   code file. Same files = same fingerprint, on any machine, however the
   files got there - no git, no deploy step, nothing to wire. Its one job
   (Derek, 2026-08-13: "I don't care if it's real - I just need something
   that confirms we're looking at the right thing"): when the phone and the
   editor show the same seven characters, they are looking at the same code. */
function build_fingerprint() {
	$digest = hash_init('md5');

	foreach (code_files() as $file) {
		/* The path relative to the site root - an absolute path would bake
		   the machine's directory layout into the fingerprint and no two
		   machines would ever match. */
		hash_update($digest, substr($file, strlen(SITE_ROOT)));
		hash_update_file($digest, $file);
	}

	return substr(hash_final($digest), 0, 7);
}

/* The fingerprint, itemized: ?stamp=debug prints one line per code file
   (path + its own 7 chars), so two machines that disagree can be diffed to
   the exact file - ghost files a deploy never deleted, a file a sync
   skipped. Plain text, no chrome; called from index.php before any page
   renders. */
function stamp_debug() {
	header('Content-Type: text/plain; charset=utf-8');

	foreach (code_files() as $file) {
		echo substr(md5_file($file), 0, 7) . '  ' . substr($file, strlen(SITE_ROOT)) . "\n";
	}

	echo "\n" . build_fingerprint() . "  (the whole build)\n";
	exit;
}

/* The dev stamp: "<fingerprint> · <H:MM:SS>" where the time is the NEWEST
   save across every code file - so a just-saved, not-yet-committed change
   still bumps it. Phone QA reads the corner instead of guessing about
   caches: if the stamp matches what was just changed, the page is fresh.
   Rendered only off production (the host gate lives at the call site in
   header.php). */
function newest_save() {
	$newest = 0;

	foreach (code_files() as $file) {
		$time = filemtime($file);

		if ($time > $newest) {
			$newest = $time;
		}
	}

	return $newest;
}

function dev_stamp() {
	return build_fingerprint() . ' · ' . date('G:i:s', newest_save());
}

/* (deployed_version - the .git/version.txt reader - lived here until
   2026-08-13, replaced by build_fingerprint above: a content hash needs no
   deploy wiring at all.) */
