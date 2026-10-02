<?php
/* Looks up the playable file links for every Vimeo video the site shows, and
   writes them to content/videos.json for includes/study-video.php to read.

   Why: Vimeo's own player is a separate page inside an iframe, and iOS only
   allows sound when the tap lands inside the frame that plays it - so a play
   button on OUR page can't start their player with sound. Vimeo's
   "third-party player" file links let us play the same video in a plain
   <video> on our own page instead, still hosted and streamed by Vimeo.

   Run it after adding a video to a template (or replacing one on Vimeo):

       php bin/vimeo-links.php

   It finds every 'vimeo' => '<id>' in templates/, asks Vimeo's API for each
   one, and rewrites content/videos.json from scratch.

   Needs:
   - A Vimeo personal access token (scopes: public, private, video_files) in
     .vimeo-token at the project root. That file is gitignored, so it never
     reaches the server - the site itself only ever reads the JSON.
   - A Vimeo plan with file links (Pro has them; Free/Starter/Basic/Plus
     don't, and the links stop working if the account drops to one of those).

   The links don't expire, BUT each one carries the token's id
   (oauth2_token_id). Deleting the token - or the "Derek Thomas Wood website"
   app it belongs to on developer.vimeo.com - will very likely break every
   video on the site. Keep both. */

$root = dirname(__DIR__);
$token_file = $root . '/.vimeo-token';

if (!is_file($token_file)) {
	fwrite(STDERR, "No .vimeo-token at the project root - see the comment at the top of this script.\n");
	exit(1);
}

$token = trim(file_get_contents($token_file));

// Every Vimeo id the templates use, in the order they're found.
$ids = [];
$template_files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/templates'));

foreach ($template_files as $file) {
	if ($file->getExtension() !== 'php') {
		continue;
	}

	preg_match_all('/[\'"]vimeo[\'"]\s*=>\s*[\'"](\d+)[\'"]/', file_get_contents($file->getPathname()), $matches);

	foreach ($matches[1] as $id) {
		$ids[$id] = true;
	}
}

$videos = [];
$failed = false;

foreach (array_keys($ids) as $id) {
	$request = stream_context_create([
		'http' => [
			'header' => "Authorization: bearer {$token}\r\n",
			'ignore_errors' => true,
		],
	]);

	$response = json_decode(file_get_contents("https://api.vimeo.com/videos/{$id}?fields=name,width,height,files", false, $request), true);

	if (empty($response['files'])) {
		fwrite(STDERR, "{$id}: no file links came back" . (isset($response['error']) ? " ({$response['error']})" : '') . "\n");
		$failed = true;
		continue;
	}

	// The adaptive stream (HLS) for every browser that plays it natively,
	// and one plain MP4 for the ones that don't: 720p when Vimeo made one
	// (plenty for a talk-through, and lighter than 1080p), otherwise the
	// largest it did make. Not every upload gets every size.
	$hls = '';
	$mp4 = '';
	$mp4_width = 0;

	foreach ($response['files'] as $file) {
		if ($file['quality'] === 'hls') {
			$hls = $file['link'];
			continue;
		}

		if ($file['type'] !== 'video/mp4' || $mp4_width === 1280) {
			continue;
		}

		if ($file['width'] === 1280 || $file['width'] > $mp4_width) {
			$mp4 = $file['link'];
			$mp4_width = $file['width'];
		}
	}

	// The MP4 is the one every browser plays (Firefox has no native HLS),
	// so it's required; the HLS stream is the better ride where it works.
	if (!$mp4) {
		fwrite(STDERR, "{$id}: no MP4 among its file links\n");
		$failed = true;
		continue;
	}

	$videos[$id] = [
		'name' => $response['name'],
		'width' => $response['width'],
		'height' => $response['height'],
		'hls' => $hls,
		'mp4' => $mp4,
	];

	echo "{$id}: {$response['name']}\n";
}

if ($failed) {
	fwrite(STDERR, "Nothing written - fix the ones above and run it again.\n");
	exit(1);
}

$json = json_encode($videos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

// The site's JSON files are tab-indented.
$json = preg_replace_callback('/^( {4})+/m', function ($indent) {
	return str_repeat("\t", strlen($indent[0]) / 4);
}, $json);

if (file_put_contents($root . '/content/videos.json', $json . "\n") === false) {
	fwrite(STDERR, "Couldn't write content/videos.json\n");
	exit(1);
}

echo 'Wrote content/videos.json (' . count($videos) . " videos)\n";
