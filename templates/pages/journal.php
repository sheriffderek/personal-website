<?php
	// The journal list: every entry in content/journal.json, in the order the
	// JSON declares (newest first is the authoring convention). An entry marked
	// "unlisted" (the specimen) still has its page but stays off this list.
	// Links carry $target_query forward like every internal link on the site.
	$journal = load_json('journal.json');
?>

<text-content class='styled journal-index'>

	<h1 class='loud-voice'>Journal</h1>

	<p>Videos and stories from the work. Takes on design and development that might not be what you expect, situations I find myself in, and the occasional tip or trick.</p>

	<ol class='entry-list'>
		<?php foreach ($journal as $slug => $entry): ?>
			<?php if (!empty($entry['unlisted'])) continue; ?>

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
