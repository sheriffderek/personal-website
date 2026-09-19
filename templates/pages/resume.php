<?php
$brief = [
	'goal' => 'Route the reader to the right telling. One career, three angles - the reader '
		. 'picks the lane that matches the role they are hiring for, and every lane is the '
		. 'same facts re-led, never a different person.',
	// 'check' is ours - the footer only prints 'goal'.
	'check' => 'The intro is 100% Derek\'s voice - he would say it on camera. A visitor picks a '
		. 'lane in seconds and does not wonder if they picked wrong.',
];
$resume = load_json('resume.json');
?>

<text-content class='styled'>

	<h1 class='loud-voice'>Resume</h1>

	<p>Fifteen years of the same career, told three ways.</p>

	<nav class='resume-lanes' aria-label='Resume versions'>

		<ul>

			<?php foreach ($resume['lanes'] as $lane_slug => $lane): ?>
				<li>

					<a class='strong-voice link' href='/resume/<?= $lane_slug ?><?= $target_query ?>'><?= $lane['label'] ?></a>

					<p class='quiet-voice'>For roles like: <?= $lane['covers'] ?></p>

					<?php /* Each lane travels as a pair - the label opens the
						resume; its letter rides along here. */ ?>
					<p class='quiet-voice'>
						With a matching <a class='link' href='/resume/<?= $lane_slug ?>/cover-letter<?= $target_query ?>'>cover letter</a>.
					</p>

				</li>
			<?php endforeach; ?>

		</ul>

	</nav>

</text-content>
