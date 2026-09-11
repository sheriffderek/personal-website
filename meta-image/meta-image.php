<?php

/* Auto-generated share cards (og:image / twitter:image).

   Adapted from the Perpetual Education generator (perpetual-2019 theme,
   functions/og-images.php + docs/share-images.md) - same server family, so
   the same approach holds: draw the card directly with the Imagick extension.
   No build step, no Node, no external service. This file IS the whole system;
   nothing outside it knows how cards are made.

   The contract, from the caller's side (index.php):

     meta_image_url('journal-' . $entry_slug, $entry['title'])

   returns a web-absolute path to a cached PNG, or null. Null means "no card" -
   the caller falls through to the site default, and no error ever reaches the
   page. A hand-made image (the meta.jpg presence contract) always wins BEFORE
   this is called; the generator is the floor, not the ceiling.

   The whole service lives in THIS folder - code, card font, and the cache/
   of generated PNGs (gitignored - derived output, each machine grows its
   own). Cards cache keyed by the text's hash, so an edited title regenerates
   itself and a stale file just sits there unused (harmless; sweep the folder
   if it ever matters). Deleting this folder and the two index.php lines
   removes the system completely; swapping the renderer behind
   meta_image_url() touches no call sites.

   The card wears the site's default look (Product character, Expressive mood,
   light scheme): warm-white ground, near-black Quicksand title, quiet domain
   footer. A share card is one frozen frame, so it commits to the default
   rather than trying to follow the theme system.

   Server needs: the Imagick PHP extension and the quicksand-700.ttf beside
   this file
   (Imagick can't read woff2, so the card font is a one-time TTF conversion of
   the same Quicksand 700 the site already ships). Missing either = null = the
   default image serves. Local gotcha, learned at PE: MAMP's Apache PHP and CLI
   PHP have separate configs - Imagick often exists in only one of them. */

function meta_image_url($key, $text) {
	if (!extension_loaded('imagick') || !$text) {
		return null;
	}

	$cache_path = '/meta-image/cache';
	$cache_dir = SITE_ROOT . $cache_path;

	if (!is_dir($cache_dir)) {
		mkdir($cache_dir, 0755, true);
	}

	$file_name = $key . '-' . substr(md5($text), 0, 10) . '.png';
	$file = $cache_dir . '/' . $file_name;

	if (!is_file($file)) {
		if (!meta_image_render($text, $file)) {
			return null;
		}
	}

	return $cache_path . '/' . $file_name;
}

function meta_image_render($text, $out_path) {
	$font = __DIR__ . '/quicksand-700.ttf';
	if (!is_file($font)) {
		return false;
	}

	/* 1200x630 is the standard Open Graph frame every platform crops from. */
	$width = 1200;
	$height = 630;
	$padding = 90;
	$max_line_width = $width - ($padding * 2);

	/* The default palette, frozen from the live tokens (moods.css, Expressive
	   light + color-scales.css): olive-50 ground, stone-900 ink, stone-600
	   quiet ink. If the default mood ever changes, re-freeze these and clear
	   the cache folder. */
	$ground = '#fbfaf4';
	$ink = '#1c1917';
	$ink_quiet = '#57534e';

	/* Auto-fit ladder: try the biggest size first; step down until the text
	   wraps into few enough lines. If even the smallest overflows, truncate
	   the last line with an ellipsis. */
	$size_ladder = [
		['size' => 76, 'leading' => 92, 'max_lines' => 3],
		['size' => 62, 'leading' => 76, 'max_lines' => 4],
		['size' => 50, 'leading' => 62, 'max_lines' => 5],
	];

	try {
		$img = new Imagick();
		$img->newImage($width, $height, new ImagickPixel($ground));
		$img->setImageFormat('png');

		$chosen = null;
		$lines = [];
		foreach ($size_ladder as $rung) {
			$probe = new ImagickDraw();
			$probe->setFont($font);
			$probe->setFontSize($rung['size']);
			$wrapped = meta_image_wrap($img, $probe, $text, $max_line_width);
			if (count($wrapped) <= $rung['max_lines']) {
				$chosen = $rung;
				$lines = $wrapped;
				break;
			}
		}

		if ($chosen === null) {
			$chosen = end($size_ladder);
			$probe = new ImagickDraw();
			$probe->setFont($font);
			$probe->setFontSize($chosen['size']);
			$wrapped = meta_image_wrap($img, $probe, $text, $max_line_width);
			$lines = array_slice($wrapped, 0, $chosen['max_lines']);
			$last = count($lines) - 1;
			$lines[$last] = meta_image_truncate($img, $probe, $lines[$last] . '…', $max_line_width);
		}

		/* Title block, vertically centered in the zone above the footer. */
		$title_draw = new ImagickDraw();
		$title_draw->setFont($font);
		$title_draw->setFontSize($chosen['size']);
		$title_draw->setFillColor(new ImagickPixel($ink));
		$title_draw->setTextAntialias(true);

		$zone_top = 120;
		$zone_bottom = 500;
		$block_height = count($lines) * $chosen['leading'];
		$y = $zone_top + (($zone_bottom - $zone_top - $block_height) / 2) + $chosen['size'];

		foreach ($lines as $line) {
			$title_draw->annotation($padding, $y, $line);
			$y += $chosen['leading'];
		}
		$img->drawImage($title_draw);

		$footer = new ImagickDraw();
		$footer->setFont($font);
		$footer->setFontSize(28);
		$footer->setFillColor(new ImagickPixel($ink_quiet));
		$footer->setTextAntialias(true);
		$footer->annotation($padding, 560, 'derekthomaswood.com');
		$img->drawImage($footer);

		$img->writeImage($out_path);
		$img->clear();
		return true;
	} catch (Exception $e) {
		error_log('meta_image_render failed: ' . $e->getMessage());
		return false;
	}
}

/* Greedy word wrap using the font's real measured widths (not a character
   count), so lines fill the frame honestly at any size. */
function meta_image_wrap($img, $draw, $text, $max_width) {
	$words = preg_split('/\s+/', trim($text));
	$lines = [];
	$current = '';

	foreach ($words as $word) {
		$try = $current === '' ? $word : $current . ' ' . $word;
		$metrics = $img->queryFontMetrics($draw, $try);
		if ($metrics['textWidth'] > $max_width && $current !== '') {
			$lines[] = $current;
			$current = $word;
		} else {
			$current = $try;
		}
	}
	if ($current !== '') {
		$lines[] = $current;
	}
	return $lines;
}

/* Trim words off the end of an already-too-long line until it fits, keeping
   the ellipsis on. Only reached when the whole ladder overflowed. */
function meta_image_truncate($img, $draw, $line, $max_width) {
	if ($img->queryFontMetrics($draw, $line)['textWidth'] <= $max_width) {
		return $line;
	}
	$stripped = rtrim($line, '…');
	$words = preg_split('/\s+/', trim($stripped));
	while (count($words) > 1) {
		array_pop($words);
		$candidate = implode(' ', $words) . '…';
		if ($img->queryFontMetrics($draw, $candidate)['textWidth'] <= $max_width) {
			return $candidate;
		}
	}
	return $words[0] . '…';
}
