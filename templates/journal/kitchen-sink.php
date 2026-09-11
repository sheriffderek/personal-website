<?php
	// The kitchen sink - the living outline for journal entries. Each section
	// demonstrates one module while its prose describes that module, so this
	// page doubles as the documentation. New entries start by copying this
	// file's shapes: plain <section>s of prose, figures with captions, quotes,
	// lists, code, and Vimeo embeds. It also serves as the style-guide surface
	// for the journal - change character, mood, or scheme in the settings panel
	// and check that the whole page holds together. Marked "unlisted" in
	// journal.json, so it never appears on the public /journal list.
?>

<section>
	<h2 class='attention-voice'>Text sections</h2>

	<p>This is a plain text section - a heading in the attention voice, followed by paragraphs in the calm voice. Nothing here is styled per-entry. The paragraph rhythm comes from the shared <code>.styled</code> rules in typography.css, the measure caps at a readable width, and the colors come from whatever mood and scheme the visitor has set.</p>

	<p>An entry is just a stack of these sections. A section can be one paragraph or many, and prose can carry <a class='link' href='/design-system'>links</a>, <em>emphasis</em>, and <strong>strong claims</strong> the same way it does anywhere else on the site.</p>
</section>

<section>
	<h2 class='attention-voice'>Video</h2>

	<p>Most entries lead with a video - a normal Vimeo embed, no custom player, no autoplay rules, none of the timeline's local-video machinery. The iframe sits in a figure, holds its 16:9 frame before the player loads, and can carry a caption the same way an image does.</p>

	<figure class='entry-figure'>
		<iframe src='https://player.vimeo.com/video/76979871' title='Sample Vimeo embed' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>

		<figcaption class='quiet-voice'>A sample embed. Swap the video id in the iframe src for the real one.</figcaption>
	</figure>
</section>

<section>
	<h2 class='attention-voice'>Full-size media</h2>

	<p>Figures come in two sizes. The default holds the prose column. Adding <code>figure-full</code> gives the breakout size - the left edge stays on the prose line and the shape grows rightward into the margin, where the room exists (below 1200px every figure is column-width). For the video or image that IS the entry.</p>

	<figure class='entry-figure figure-full'>
		<iframe src='https://player.vimeo.com/video/76979871' title='Sample full-size Vimeo embed' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>

		<figcaption class='quiet-voice'>The same embed at the full size - compare its right edge with the paragraphs above.</figcaption>
	</figure>
</section>

<section>
	<h2 class='attention-voice'>Figures</h2>

	<p>An image travels inside a figure with a caption. The caption is the quiet voice, and it should say something the image doesn't - what to notice, not what's depicted.</p>

	<figure class='entry-figure'>
		<img src='<?= asset('/content/placeholder/poster-wide.png') ?>' alt='Placeholder poster graphic'>

		<figcaption class='quiet-voice'>The caption sits under the image in the quiet voice. This one is a placeholder graphic standing in for a real screenshot.</figcaption>
	</figure>

	<p>Media files for a real entry live in a folder beside it at <code>content/journal/&lt;slug&gt;/</code>, named the house way, so the folder listing reads as the entry's storyboard.</p>
</section>

<section>
	<h2 class='attention-voice'>Subheadings</h2>

	<p>A longer section can break into parts with a smaller heading in the strong voice. It sits a full step below the section heading, so the hierarchy reads at a glance.</p>

	<h3 class='strong-voice'>Like this one</h3>

	<p>The paragraph under a subheading carries on in the calm voice. If a section wants more than two levels of heading, it probably wants to be two sections.</p>
</section>

<section>
	<h2 class='attention-voice'>Lists</h2>

	<p>Plain lists keep their bullets - the journal register is casual, and a bullet list is often the honest shape for working an idea out loud:</p>

	<ul>
		<li>an unordered list for things without a sequence</li>

		<li>each item a phrase or a sentence, not a paragraph</li>

		<li>if an item grows past two lines, it wants to be prose</li>
	</ul>

	<p>An ordered list is for real sequences - steps that happened in an order, or a ranking that means something:</p>

	<ol>
		<li>first this happened</li>

		<li>then this</li>

		<li>and this is where it landed</li>
	</ol>
</section>

<section>
	<h2 class='attention-voice'>Quotes</h2>

	<p>A blockquote is for someone else's words - a line from a book, a thing a client actually said. The stroke down the side marks the border between their voice and mine.</p>

	<blockquote>
		<p>The details are not the details. They make the design.</p>

		<footer class='quiet-voice'>Charles Eames</footer>
	</blockquote>

	<p>The attribution rides in the quiet voice inside the quote. Skip it when the source is set up by the surrounding prose.</p>
</section>

<section>
	<h2 class='attention-voice'>Code</h2>

	<p>Inline code like <code>--ink-primary</code> wears the code font at prose size. A block of it gets its own quiet panel and scrolls sideways instead of wrapping, so the shape of the code survives on a phone:</p>

	<pre class='entry-code'><code>.journal-entry {
	blockquote {
		border-inline-start: 2px solid var(--stroke-primary);
	}
}</code></pre>

	<p>Code in a journal entry is illustration, not documentation - short excerpts that make the point, never whole files.</p>
</section>

<section>
	<h2 class='attention-voice'>Image pairs</h2>

	<p>Two images side by side when the point IS the comparison - a before and after, two directions considered. One caption under the pair says what changed; on phones they stack.</p>

	<figure class='entry-figure figure-pair'>
		<image-pair>
			<img src='<?= asset('/content/placeholder/poster-wide.png') ?>' alt='Placeholder for the before image'>

			<img src='<?= asset('/content/placeholder/poster-wide.png') ?>' alt='Placeholder for the after image'>
		</image-pair>

		<figcaption class='quiet-voice'>Before on the left, after on the right. One caption carries the comparison.</figcaption>
	</figure>
</section>

<section>
	<h2 class='attention-voice'>Dividers</h2>

	<p>A horizontal rule marks a change of subject inside one entry - the "anyway" move. Use it when the entry genuinely shifts gears, not as decoration between every section.</p>

	<hr>

	<p>And the prose picks back up on the other side, on a new subject.</p>
</section>

<section>
	<h2 class='attention-voice'>How this page is wired</h2>

	<p>An entry is three small pieces. The metadata (title, date, description) is an entry in <code>content/journal.json</code>. The body is a file like this one at <code>templates/journal/&lt;slug&gt;.php</code>. The shared shell at <code>templates/pages/journal-entry.php</code> renders the header and includes the body. Both the JSON entry and the body file have to exist before the URL does, and the /journal list shows everything in the JSON that isn't marked unlisted.</p>
</section>
