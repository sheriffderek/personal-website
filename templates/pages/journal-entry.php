<?php
	$brief = [
		'goal' => 'Get out of the way of one entry. Date, title, body - nothing competing with '
			. 'the writing. If you notice this shell, it\'s failing.',
	];
?>

<article class='styled journal-entry'>

	<header class='entry-header'>
		<p class='date stamp-voice'>
			<time datetime='<?= $entry['date'] ?>'><?= journal_date($entry['date']) ?></time>
		</p>

		<?php /* Only when the entry's substance changed after publishing
			('updated' in journal.json) - a typo fix never sets it. */ ?>
		<?php if (!empty($entry['updated'])): ?>
			<p class='date stamp-voice'>
				Updated <time datetime='<?= $entry['updated'] ?>'><?= journal_date($entry['updated']) ?></time>
			</p>
		<?php endif; ?>

		<h1 class='loud-voice'><?= $entry['title'] ?></h1>

		<?php if (!empty($entry['summary'])): ?>
			<p class='summary'><?= $entry['summary'] ?></p>
		<?php endif; ?>
	</header>

	<?php require TEMPLATES_DIR . '/journal/' . $entry['slug'] . '.php'; ?>

</article>
