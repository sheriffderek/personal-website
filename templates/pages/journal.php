<?php
	$brief = [
		'goal' => 'The person behind the timeline. Lower polish than the milestone cards on '
			. 'purpose - after reading an entry you should feel like you\'ve heard Derek '
			. 'talk. The register matters more than any topic.',
		// 'check' is ours - the footer only prints 'goal'.
		'check' => 'Finished thoughts only - never a pile of started things, never filler, even '
			. 'while the journal is new. Every entry can say in one line what it shows about Derek.',
	];
	$journal = load_json('journal.json');

	// The listable entries. Until the first real one lands, the page says so
	// plainly instead of rendering an empty list.
	$listed = array_filter($journal, function ($entry) {
		return empty($entry['unlisted']);
	});

	// Newest at the top - the ISO date is the sortable truth, so the list
	// never depends on the JSON's authoring order.
	uasort($listed, function ($a, $b) {
		return strcmp($b['date'], $a['date']);
	});
?>

<text-content class='styled journal-index'>

	<h1 class='loud-voice'>Journal</h1>

	<p>So, here's what I'm thinking... </p>

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

	<?php /* The past-writing shelf (notes/journal-plan.md): the receipts that
		predate this journal, on the index itself so every visitor sees the
		history without opening anything. Basic links for now - Derek's
		card treatment comes later. */ ?>
	<section class='past-writing'>

		<h2 class='attention-voice'>Earlier writing, elsewhere</h2>

		<h3 class='strong-voice'>Answering questions</h3>

		<ul>

			<li>

				<a class='link' href='https://stackoverflow.com/users/1399456/sheriffderek' target='_blank'>Stack Overflow</a>

			</li>

			<li>

				<a class='link' href='https://www.quora.com/profile/Derek-Thomas-Wood' target='_blank'>Quora</a>

			</li>

			<li>

				<a class='link' href='https://dev.to/sheriffderek' target='_blank'>dev.to</a>

			</li>

		</ul>

		<h3 class='strong-voice'>Articles</h3>

		<ul>

			<li>

				<a class='link' href='https://css-tricks.com/on-type-patterns-and-style-guides/' target='_blank'>CSS-Tricks: On Type Patterns and Style Guides</a>

			</li>

			<li>

				<a class='link' href='https://sheriffderek.substack.com/' target='_blank'>Some thoughts on Substack (that I'll likely move over here)</a>

			</li>

		</ul>

		<h3 class='strong-voice'>Perpetual Education</h3>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/resources/almost-all-user-interface-comes-down-to-this/' target='_blank'>Almost all user interface comes down to this</a>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/an-analogy-for-the-designer-developer-relationship/' target='_blank'>An analogy for the designer-developer relationship</a>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/resources/figma-variable-collection-composition/' target='_blank'>Figma variable collection composition explorations</a>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/resources/visual-design-foundations-fast/' target='_blank'>Visual design foundations, fast</a>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/stories/snow-fall/' target='_blank'>Snow Fall and immersive/interactive editorial</a>

			</li>

		</ul>

		<h3 class='strong-voice'>Every week</h3>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/open-office-hours/' target='_blank'>Open office hours</a>

			</li>

			<li>

				<a class='link' href='https://discord.gg/css' target='_blank'>The CSS Discord</a>

			</li>

		</ul>

		<h3 class='strong-voice'>On camera</h3>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/stories/is-your-portfolio-doing-its-job-with-don-the-developer/' target='_blank'>Is your portfolio doing its job?</a>

			</li>

		</ul>

	</section>

</text-content>
