<?php
	// The journal list: every entry in content/journal.json, in the order the
	// JSON declares (newest first is the authoring convention). An entry marked
	// "unlisted" (the specimen) still has its page but stays off this list.
	// Links carry $target_query forward like every internal link on the site.
	$journal = load_json('journal.json');

	// The listable entries. Until the first real one lands, the page says so
	// plainly instead of rendering an empty list.
	$listed = array_filter($journal, function ($entry) {
		return empty($entry['unlisted']);
	});
?>

<text-content class='styled journal-index'>

	<h1 class='loud-voice'>Journal</h1>

	<p>Videos and stories from the work. Takes on design and development that might not be what you expect, situations I find myself in, and the occasional tip or trick.</p>

	<?php if (empty($listed)): ?>
		<p>First entries are on the way.</p>
	<?php endif; ?>

	<ol class='entry-list'>
		<?php foreach ($listed as $slug => $entry): ?>
			<li>
				<p class='date high-voice'><?= $entry['date'] ?></p>

				<h2 class='attention-voice'>
					<a href='/journal/<?= $slug ?><?= $target_query ?>'><?= $entry['title'] ?></a>
				</h2>

				<?php if (!empty($entry['summary'])): ?>
					<p class='summary'><?= $entry['summary'] ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>

</text-content>
