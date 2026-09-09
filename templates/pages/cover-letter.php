<?php
/* One lane's cover letter. The router (index.php) has already loaded
   content/resume.json into $resume, picked this lane into $lane, and put its
   slug in $lane_slug. The letter prose comes from content/letters.json -
   the public rendering of the letter files in job-search/briefing/, not a
   fork. The identity header is shared with the resume lanes
   (includes/resume-header.php) - same header, same sheet family, same
   print pipeline.

   A ?target=<slug> visit checks the letters' targets map: a bespoke letter
   authored for that target replaces the lane body wholesale (per key, so a
   target may override just the body and keep the lane's signoff). */
$brief = [
	'goal' => 'The cover letter as the resume\'s sibling sheet: same identity header, same '
		. 'timeline arrow, one column of letter. It prints through the same pipeline, so '
		. 'every application ships a matched pair.',
];

$letters = load_json('letters.json');
$letter = resolve_letter($letters, $lane_slug, $target_slug);
?>

<article class='resume cover-letter' aria-label='Cover letter - <?= $lane['label'] ?>'>

	<?= partial('resume-header', ['resume' => $resume, 'lane' => $lane]) ?>

	<div class='timeline-axis' aria-hidden='true'></div>

	<section class='letter-body'>

		<p class='firm-voice'><?= $letter['greeting'] ?></p>

		<?php foreach ($letter['body'] as $paragraph): ?>
			<p><?= $paragraph ?></p>
		<?php endforeach; ?>

		<p class='letter-signoff'><?= $letter['signoff'] ?></p>

		<?php /* Approved letters sign with the full name (the letter's own
			signature key); until then the header identity signs. */ ?>
		<p class='letter-signature firm-voice'><?= $letter['signature'] ?? $resume['header']['name'] ?></p>

	</section>

	<nav class='resume-lane-nav' aria-label='Other cover letter versions'>

		<?php /* Same lens picker as the resume lanes - all three letters,
			current one marked and unlinked - plus the door back to this
			lane's resume (they travel as a pair). */ ?>
		<?php
		$lens_parts = [];

		foreach ($letters['lanes'] as $nav_slug => $nav_letter) {
			$nav_label = $resume['lanes'][$nav_slug]['label'];

			if ($nav_slug === $lane_slug) {
				$lens_parts[] = "<span aria-current='page'>" . $nav_label . '</span>';
			} else {
				$lens_parts[] = "<a class='link' href='/resume/" . $nav_slug . '/cover-letter' . $target_query . "'>" . $nav_label . '</a>';
			}
		}
		?>

		<p class='quiet-voice'>
			Through the lens of: <?= implode(', ', $lens_parts) ?>
		</p>

		<p class='quiet-voice'>
			The matching resume: <a class='link' href='/resume/<?= $lane_slug ?><?= $target_query ?>'><?= $lane['label'] ?></a>
		</p>

	</nav>

</article>
