<?php
	/* Entry preview - the compact stand-in for a journal entry on the /journal
	   list (named by role, same doctrine as ShowPreview: it previews and links
	   to the full thing). Expects $slug + $entry (a journal.json row); the
	   caller owns the list markup, this owns one entry's card. */
?>
<article class='entry-preview'>

	<p class='date stamp-voice'><?= $entry['date'] ?></p>

	<h2 class='attention-voice'>
		<a href='/journal/<?= $slug ?><?= $target_query ?>'><?= $entry['title'] ?></a>
	</h2>

	<?php if (!empty($entry['summary'])): ?>
		<p class='summary'><?= $entry['summary'] ?></p>
	<?php endif; ?>

</article>
