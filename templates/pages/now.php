<?php
// The "now" page: what Derek's focused on at the moment.
// A ?target=companyname loads a tailored note for that company, same as the
// timeline's target notes on the home page.
$target = null;
if (isset($_GET['target'])) {
	$target_slug = preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['target']));
	if ($target_slug !== '') {
		$candidate = load_json('targets/' . $target_slug . '.json');
		if (!empty($candidate)) $target = $candidate;
	}
}
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
