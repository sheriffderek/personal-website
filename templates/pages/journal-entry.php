<?php
	// The shared journal-entry shell: date, title, optional summary, then the
	// entry's own body file. $entry arrives from the journal block in
	// index.php (the journal.json entry plus its slug). The body is plain
	// sections of markup at templates/journal/<slug>.php - the specimen
	// entry (/journal/specimen) is the outline new bodies start from.
?>

<article class='styled journal-entry'>

	<header class='entry-header'>
		<p class='date high-voice'><?= $entry['date'] ?></p>

		<h1 class='loud-voice'><?= $entry['title'] ?></h1>

		<?php if (!empty($entry['summary'])): ?>
			<p class='summary'><?= $entry['summary'] ?></p>
		<?php endif; ?>
	</header>

	<?php require TEMPLATES_DIR . '/journal/' . $entry['slug'] . '.php'; ?>

</article>
