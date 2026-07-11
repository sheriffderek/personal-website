<?php
// The "now" page: what Derek's focused on at the moment.
// A ?target=companyname loads a tailored note for that company, same as the
// timeline's target notes on the home page.
$target = isset($_GET['target']) ? load_target($_GET['target']) : null;
?>

<text-content class='styled'>
	<h1 class='loud-voice'>Now</h1>

	<p>Placeholder. What I'm focused on at the moment.</p>

	<?php if (!empty($target['now'])): ?>
		<p class='target-note'>
			<?= $target['now'] ?>
		</p>
	<?php endif; ?>
</text-content>
