<?php
/* The cover letter as plain text - /resume/<lane>/cover-letter/text.
   Same document posture as resume-text.php, same guarantee: it reads the
   same letters.json the page and PDF do (bespoke ?target= letters
   included, via resolve_letter), so pasted text can never drift from the
   sent PDF. Saved beside each lane's letter PDF by the export script. */
header('Content-Type: text/plain; charset=utf-8');

$letters = load_json('letters.json');
$letter = resolve_letter($letters, $lane_slug, $target_slug);

echo $resume['header']['name'], "\n";
echo $lane['role'], "\n\n";

echo $resume['header']['location'], ' · ', $resume['header']['phone'], "\n";
echo $resume['header']['website'], ' · ', $resume['header']['email'], ' · ', $resume['header']['linkedin'], "\n\n";

echo $letter['greeting'], "\n\n";

foreach ($letter['body'] as $paragraph) {
	echo strip_tags($paragraph), "\n\n";
}

echo $letter['signoff'], "\n";
echo $letter['signature'] ?? $resume['header']['name'], "\n";
