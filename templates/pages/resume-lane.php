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
   designation stamped on the entry, exactly that voice's job. */
function resume_entry($entry, $is_contract = false) {
?>
	<li class='resume-entry'>

		<p class='stamp-voice'>
			<?= $entry['org'] ?> (<?= $entry['dates'] ?>)<?= $is_contract ? ' · contract' : '' ?>
		</p>

		<h3 class='strong-voice'><?= $entry['title'] ?></h3>

		<?php foreach ($entry['body'] as $paragraph): ?>
			<p><?= $paragraph ?></p>
		<?php endforeach; ?>

	</li>
<?php
}

/* $todo - lane C's speaking_first flag (advocate lane leads with teaching
   evidence) is authored in the JSON but not applied to the layout yet. */
?>

<article class='resume' aria-label='Resume - <?= $lane['label'] ?>'>

	<header class='resume-header'>

		<h1 class='attention-voice'>
			<strong><?= $resume['header']['name'] ?></strong>

			<span class='resume-role strong-voice'><?= $lane['role'] ?></span>
		</h1>

		<p>
			<?= $resume['header']['location'] ?>

			<a class='link' href='tel:<?= preg_replace('/[^0-9]/', '', $resume['header']['phone']) ?>'><?= $resume['header']['phone'] ?></a> ·

			<a class='link' href='https://<?= $resume['header']['website'] ?>'><?= $resume['header']['website'] ?></a>

			<a class='link' href='mailto:<?= $resume['header']['email'] ?>'><?= $resume['header']['email'] ?></a> |

			<a class='link' href='https://<?= $resume['header']['linkedin'] ?>' target='_blank'><?= $resume['header']['linkedin'] ?></a>
		</p>

		<p class='resume-intro'><?= $lane['intro'] ?></p>

	</header>

	<div class='timeline-axis' aria-hidden='true'></div>

	<section class='resume-current'>

		<h2 class='reader-only'><?= $resume['current']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['current']['entries'] as $entry): ?>
				<?php resume_entry($entry); ?>
			<?php endforeach; ?>

		</ol>

	</section>

	<section class='resume-contracts'>

		<h2 class='reader-only'><?= $resume['contracts']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['contracts']['entries'] as $entry): ?>
				<?php resume_entry($entry, true); ?>
			<?php endforeach; ?>

		</ol>

	</section>


	<section class='resume-earlier'>

		<h2 class='reader-only'><?= $resume['earlier']['heading'] ?></h2>

		<ol role='list'>

			<?php foreach ($resume['earlier']['entries'] as $entry): ?>
				<?php resume_entry($entry); ?>
			<?php endforeach; ?>

		</ol>

	</section>

	<section class='resume-more' aria-label='Speaking and education'>

		<section class='resume-speaking'>

			<h2 class='strong-voice'><?= $resume['speaking']['heading'] ?></h2>

			<p><?= $resume['speaking']['body'] ?></p>

		</section>

		<section class='resume-education'>

			<h2 class='strong-voice'><?= $resume['education']['heading'] ?></h2>

			<p><?= $resume['education']['body'] ?></p>

		</section>

	</section>

	<nav class='resume-lane-nav' aria-label='Other resume versions'>

		<p class='quiet-voice'>
			Also told for:

			<?php foreach ($resume['lanes'] as $other_slug => $other_lane): ?>
				<?php if ($other_slug !== $lane_slug): ?>
					<a class='link' href='/resume/<?= $other_slug ?><?= $target_query ?>'><?= $other_lane['label'] ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
		</p>

	</nav>

</article>
