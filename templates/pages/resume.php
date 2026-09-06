<?php
$brief = [
	'goal' => 'Route the reader to the right telling. One career, three angles - the reader '
		. 'picks the lane that matches the role they are hiring for, and every lane is the '
		. 'same facts re-led, never a different person.',
];
$resume = load_json('resume.json');
?>

<text-content class='styled'>

	<h1 class='loud-voice'>Resume</h1>

	<p>Fifteen years of the same career, told three ways. Pick the one that matches the role you're hiring for - the facts don't change between them, just what leads.</p>

	<nav class='resume-lanes' aria-label='Resume versions'>

		<ul>

			<?php foreach ($resume['lanes'] as $lane_slug => $lane): ?>
				<li>

					<a class='strong-voice link' href='/resume/<?= $lane_slug ?><?= $target_query ?>'><?= $lane['label'] ?></a>

					<p class='quiet-voice'>For roles like: <?= $lane['covers'] ?></p>

				</li>
			<?php endforeach; ?>

		</ul>

	</nav>

</text-content>
