<?php
/* The resume as plain text - /resume/<lane>/text. A document, not a page
   (same posture as the journal feed): it renders its own output and skips
   the site chrome entirely. Purpose: application portals that want pasted
   text get a guaranteed-current source - this reads the same resume.json
   the page and PDF do, so it can never drift from them. The export script
   (bin/resume-fit-check.sh) saves it beside each lane's PDF. */
header('Content-Type: text/plain; charset=utf-8');

function text_entry($entry, $lane, $is_contract = false) {
	$entry = resolve_entry($entry, $lane);

	echo $entry['org'], ' (', $entry['dates'], ')', $is_contract ? ' · contract' : '', "\n";
	echo $entry['title'], "\n";

	foreach ($entry['body'] as $paragraph) {
		echo strip_tags($paragraph), "\n";
	}

	echo "\n";
}

echo $resume['header']['name'], "\n";
echo $lane['role'], "\n\n";

echo $resume['header']['location'], ' · ', $resume['header']['phone'], "\n";
echo $resume['header']['website'], ' · ', $resume['header']['email'], ' · ', $resume['header']['linkedin'], "\n\n";

foreach ($lane['intro'] as $paragraph) {
	echo strip_tags($paragraph), "\n";
}

echo "\n";
echo 'Skills: ', implode(', ', $lane['skills']), "\n\n";

echo strtoupper($resume['current']['heading']), "\n\n";

foreach ($resume['current']['entries'] as $entry) {
	text_entry($entry, $lane);
}

foreach ($resume['contracts']['entries'] as $entry) {
	text_entry($entry, $lane, true);
}

foreach ($resume['earlier']['entries'] as $entry) {
	text_entry($entry, $lane);
}

echo strtoupper($resume['speaking']['heading']), "\n";
echo strip_tags($resume['speaking']['body']), "\n\n";

echo strtoupper($resume['education']['heading']), "\n";
echo strip_tags($resume['education']['body']), "\n";
