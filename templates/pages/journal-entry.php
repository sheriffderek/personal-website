<?php
	$brief = [
		'goal' => 'Get out of the way of one entry. Date, title, body - nothing competing with '
			. 'the writing. If you notice this shell, it\'s failing.',
	];
?>

<article class='styled journal-entry'>

	<header class='entry-header'>
		<p class='date stamp-voice'><?= journal_date($entry['date']) ?></p>

		<h1 class='loud-voice'><?= $entry['title'] ?></h1>

		<?php if (!empty($entry['summary'])): ?>
			<p class='summary'><?= $entry['summary'] ?></p>
		<?php endif; ?>
	</header>

	<?php require TEMPLATES_DIR . '/journal/' . $entry['slug'] . '.php'; ?>

</article>
