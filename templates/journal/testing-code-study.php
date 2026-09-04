<?php
	// Trial run of the code-study editor (PE's lesson code system) on a second
	// site. The live examples below ARE the test - this entry documents its own
	// experiment. Mount partial: includes/code-study.php. Theme adapter:
	// styles/components/code-study-bridge.css.
?>

<section>
	<p>Over at <a class='link' href='https://perpetual.education'>Perpetual Education</a>, lessons use a live code editor I built called CodeStudy - real files, syntax highlighting, an output pane, a console. It was made for one site. The obvious question: if I want to use it somewhere else, how simple is it? And if it's not simple - what has to change so it is?</p>

	<p>This entry is the experiment. The editors below are running live, on this site, right now.</p>
</section>

<section>
	<h2 class='attention-voice'>The first test: does it mount?</h2>

	<p>This site is plain PHP - no build step, no bundler. CodeStudy ships as a prebuilt bundle, so adoption is: copy two files in, add a small mount include, pass it some code. Try it - it's editable:</p>

	<?= partial('code-study', [
		'files' => [
			[
				'name' => 'index.html',
				'content' => "<article class='poster'>\n    <h1>Hello from CodeStudy</h1>\n\n    <p>Edit me. The output updates as you type.</p>\n</article>\n",
			],
			[
				'name' => 'style.css',
				'content' => ".poster {\n    padding: 2rem;\n    border: 2px solid currentColor;\n    font-family: system-ui, sans-serif;\n}\n\n.poster h1 {\n    margin: 0 0 0.5rem;\n}\n",
			],
		],
		'zones' => [
			'left' => ['index.html', 'style.css'],
			'right' => ['output'],
			'bottom' => [],
		],
	]) ?>
</section>

<section>
	<h2 class='attention-voice'>The second test: does it theme?</h2>

	<p>The harder question. This site has a theme system with a lot of dials - five characters, three moods, light and dark, the red-light override. A component earns its place here by repainting under all of them without being edited.</p>

	<p>CodeStudy's whole skin is CSS custom properties, so the answer is a single adapter stylesheet: map this site's semantic tokens onto the editor's slots. No fork, no vendored-file edits. Open the settings panel, change anything - the editors on this page follow.</p>

	<p>One design decision fell out of the constraint: CodeStudy's own themes use a multi-hue syntax palette, but this site's token system deliberately carries a single accent. So the code you see here wears a monochrome-plus-accent syntax theme derived entirely from the page's tokens. The constraint made a better-looking choice than I would have made on purpose.</p>

	<?= partial('code-study', [
		'files' => [
			[
				'name' => 'script.js',
				'content' => "var greetings = ['hello', 'bonjour', 'hola'];\n\nfor (var i = 0; i < greetings.length; i++) {\n    console.log(greetings[i] + ', world');\n}\n\nconsole.warn('This one is a warning.');\n",
			],
		],
		'zones' => [
			'left' => ['script.js'],
			'right' => ['console'],
			'bottom' => [],
		],
	]) ?>
</section>

<section>
	<h2 class='attention-voice'>The friction log</h2>

	<p>The honest score. What adoption actually took, and what it surfaced:</p>

	<ul>
		<li><strong>Mounting: genuinely simple.</strong> Two vendored files, one include, one small loader script. The prebuilt-bundle shape is right for no-build-step hosts.</li>

		<li><strong>Bundle weight: notable, so this page lazy-loads it.</strong> The editor is about a megabyte (~360KB over the wire) - CodeMirror is most of it. Fine on a lesson page where the editor is the point; an imposition on a blog post. So nothing downloads until an editor is about to scroll into view - the page itself stays light, and the whole embed recipe on this site is a mount include, a loader script shorter than this list, and one theming stylesheet.</li>

		<li><strong>Theming: one file, with one snag.</strong> Almost every slot themes cleanly from the wrapper. But the bundle re-declares its pane-header tokens at an inner scope with literal values, so the adapter has to match that selector to win. The fix belongs upstream: a component should state its token defaults once, at its root, so a host can always paint from above.</li>

		<li><strong>Contrast is the real theming contract.</strong> The first run had an invisible cursor and unreadable console warnings - CodeStudy's fallback colors are a dark-theme palette, so any token that has to contrast with the host's background (the cursor, selection, console status text) disappears on a light one. The adapter fixes it here, but the lesson is upstream: a component's default colors should just always work - derived from its own background, not fixed values that assume one theme. Related hole on this site's side: the token vocabulary has no semantic status colors for warn and error, so status reads from an icon and a tinted band instead of a hue.</li>
	</ul>

	<p>Verdict: portable enough to be worth finishing. The gap between "works on a second site" and "anyone could drop this into theirs" is mostly the upstream token cleanup and a lighter loading story - both now on CodeStudy's list.</p>
</section>
