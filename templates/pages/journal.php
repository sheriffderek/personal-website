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

	// Newest at the top - the ISO date is the sortable truth, so the list
	// never depends on the JSON's authoring order.
	uasort($listed, function ($a, $b) {
		return strcmp($b['date'], $a['date']);
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

	<?php /* The past-writing shelf (journal-plan.md): the receipts that
		predate this journal, on the index itself so every visitor sees the
		history without opening anything. Basic links for now - Derek's
		card treatment comes later. */ ?>
	<section class='past-writing'>

		<h2 class='attention-voice'>Earlier writing, elsewhere</h2>

		<p>This journal is new; the writing isn't. Before consolidating here, it lived all over:</p>

		<h3 class='strong-voice'>Answering questions</h3>

		<ul>

			<li>

				<a class='link' href='https://stackoverflow.com/users/1399456/sheriffderek' target='_blank'>Stack Overflow</a>

				<p class='quiet-voice'>14 years, 2.3 million people reached - mostly HTML, CSS, and how the web actually works.</p>

			</li>

			<li>

				<a class='link' href='https://www.quora.com/profile/Derek-Thomas-Wood' target='_blank'>Quora</a>

				<p class='quiet-voice'>Around 1,500 answers about design, code, and learning.</p>

			</li>

			<li>

				<a class='link' href='https://dev.to/sheriffderek' target='_blank'>dev.to</a>

				<p class='quiet-voice'>Posts and conversations in the dev community.</p>

			</li>

		</ul>

		<h3 class='strong-voice'>Articles</h3>

		<ul>

			<li>

				<a class='link' href='https://css-tricks.com/on-type-patterns-and-style-guides/' target='_blank'>On Type Patterns and Style Guides</a>

				<p class='quiet-voice'>For CSS-Tricks (2021) - the type-pattern thinking that still runs this site.</p>

			</li>

			<li>

				<a class='link' href='https://sheriffderek.substack.com/' target='_blank'>The Substack</a>

				<p class='quiet-voice'>Working ideas out loud - design, development, and how people learn them.</p>

			</li>

		</ul>

		<h3 class='strong-voice'>Perpetual Education</h3>

		<p>Hundreds of workshops for the school, and hundreds more resources, stories, and exercises around them. A few that show the range:</p>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/resources/almost-all-user-interface-comes-down-to-this/' target='_blank'>Almost all user interface comes down to this</a>

				<p class='quiet-voice'>A core UI principle, taught in one sitting.</p>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/an-analogy-for-the-designer-developer-relationship/' target='_blank'>An analogy for the designer-developer relationship</a>

				<p class='quiet-voice'>The seam this whole career keeps working on.</p>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/resources/figma-variable-collection-composition/' target='_blank'>Figma variable collection composition</a>

				<p class='quiet-voice'>Tooling deep-dives - design tokens where designers actually work.</p>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/resources/visual-design-foundations-fast/' target='_blank'>Visual design foundations, fast</a>

				<p class='quiet-voice'>A timeboxed learning sprint - the teaching-format experiments.</p>

			</li>

			<li>

				<a class='link' href='https://perpetual.education/stories/snow-fall/' target='_blank'>Snow Fall</a>

				<p class='quiet-voice'>A story from the school.</p>

			</li>

		</ul>

		<h3 class='strong-voice'>Every week</h3>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/open-office-hours/' target='_blank'>Open office hours</a>

				<p class='quiet-voice'>Weekly public sessions - bring a design or code question, leave with a plan.</p>

			</li>

			<li>

				<a class='link' href='https://discord.gg/css' target='_blank'>The CSS Discord</a>

				<p class='quiet-voice'>Helping out in the community where the CSS questions live now.</p>

			</li>

		</ul>

		<h3 class='strong-voice'>On camera</h3>

		<ul>

			<li>

				<a class='link' href='https://perpetual.education/stories/is-your-portfolio-doing-its-job-with-don-the-developer/' target='_blank'>Is your portfolio doing its job?</a>

				<p class='quiet-voice'>With Don the Developer - the first of the podcast conversations.</p>

			</li>

		</ul>

	</section>

</text-content>
