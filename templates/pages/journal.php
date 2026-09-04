<?php
	$brief = [
		'goal' => 'The person behind the timeline. Lower polish than the milestone cards on '
			. 'purpose - after reading an entry you should feel like you\'ve heard Derek '
			. 'talk. The register matters more than any topic.',
	];
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
				<?= partial('entry-preview', ['slug' => $slug, 'entry' => $entry, 'target_query' => $target_query]) ?>
			</li>
		<?php endforeach; ?>
	</ol>

</text-content>
