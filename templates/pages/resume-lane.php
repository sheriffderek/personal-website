<?php
/* One lane of the resume. The router (index.php) has already loaded
   content/resume.json into $resume, picked this lane into $lane, and put its
   slug in $lane_slug. The lane owns only the intro and the skills line;
   everything else is shared, per the lane system in
   job-search/briefing/resume-two-lanes.md (the wording source of truth).

   The markup is grouped and semantic (named lists, reader-only group
   headings); on wide screens the groups go display:contents and every entry
   places into the page grid by its authored --row/--span (resume.css). */
$brief = [
	'goal' => 'The resume as a real page, not a PDF stand-in. On a wide screen the layout '
		. 'shows what a flat list can\'t: the contract work running in parallel with the '
		. 'primary roles, on one shared time axis.',
];

/* Renders one entry (an <li>) - same shape for work and contract groups.
   stamp-voice is the eyebrow: org + dates + the contract mark are a
   designation stamped on the entry, exactly that voice's job.
   A lane may override an entry's fields via its entry_overrides map
   (keyed by org) - whole fields only, merged over the shared entry. */
function resume_entry($entry, $lane, $is_contract = false) {
	if (isset($lane['entry_overrides'][$entry['org']])) {
		$entry = array_merge($entry, $lane['entry_overrides'][$entry['org']]);
	}
?>
	<li class='resume-entry'>

		<?php /* The eyebrow and title are one designation cluster - hgroup
			makes that real (a p before the heading is valid hgroup
			content), so even their tight spacing is a container gap. */ ?>
		<hgroup>

			<p class='stamp-voice'>
				<?= $entry['org'] ?> (<?= $entry['dates'] ?>)<?= $is_contract ? ' · contract' : '' ?>
			</p>

			<h3 class='firm-voice'><?= $entry['title'] ?></h3>

		</hgroup>

		<?php foreach ($entry['body'] as $paragraph): ?>
			<p><?= $paragraph ?></p>
		<?php endforeach; ?>

	</li>
<?php
}

?>

<?php /* One layout for all lanes - contracts, then Speaking & teaching,
   then Education - no per-lane section reordering, ever (Derek,
   2026-09-07, after seeing a speaking-first variant and reverting it).
   Lane variance is content-only: role line, intro, entry overrides. */ ?>
<article class='resume' aria-label='Resume - <?= $lane['label'] ?>'>

	<?php /* The identity block is shared with the cover letters - same
		header, same sheet family (includes/resume-header.php). */ ?>
	<?= partial('resume-header', ['resume' => $resume, 'lane' => $lane, 'intro_paragraphs' => $lane['intro']]) ?>

	<div class='timeline-axis' aria-hidden='true'></div>

	<section class='resume-current'>

		<h2 class='reader-only'><?= $resume['current']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['current']['entries'] as $entry): ?>
				<?php resume_entry($entry, $lane); ?>
			<?php endforeach; ?>

		</ol>

	</section>

	<section class='resume-contracts'>

		<h2 class='reader-only'><?= $resume['contracts']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['contracts']['entries'] as $entry): ?>
				<?php resume_entry($entry, $lane, true); ?>
			<?php endforeach; ?>

		</ol>

	</section>


	<section class='resume-earlier'>

		<h2 class='reader-only'><?= $resume['earlier']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['earlier']['entries'] as $entry): ?>
				<?php resume_entry($entry, $lane); ?>
			<?php endforeach; ?>

		</ol>

	</section>

	<section class='resume-more' aria-label='Speaking and education'>

		<section class='resume-speaking'>

			<h2 class='firm-voice'><?= $resume['speaking']['heading'] ?></h2>

			<p><?= $resume['speaking']['body'] ?></p>

		</section>

		<section class='resume-education'>

			<h2 class='firm-voice'><?= $resume['education']['heading'] ?></h2>

			<p><?= $resume['education']['body'] ?></p>

		</section>

	</section>

	<nav class='resume-lane-nav' aria-label='Other resume versions'>

		<?php /* All three lanes, current one included (marked, unlinked) -
			it reads as a lens picker, and the reader always knows which
			lens they're on. Assembled in PHP so the commas sit tight
			against their words. */ ?>
		<?php
		$lens_parts = [];

		foreach ($resume['lanes'] as $nav_slug => $nav_lane) {
			if ($nav_slug === $lane_slug) {
				$lens_parts[] = "<span aria-current='page'>" . $nav_lane['label'] . '</span>';
			} else {
				$lens_parts[] = "<a class='link' href='/resume/" . $nav_slug . $target_query . "'>" . $nav_lane['label'] . '</a>';
			}
		}
		?>

		<p class='quiet-voice'>
			Through the lens of: <?= implode(', ', $lens_parts) ?>
		</p>

	</nav>

</article>
