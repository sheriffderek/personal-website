<?php
	// The specimen article - the living outline for article pages. Each section
	// demonstrates one module while its prose describes that module, so this
	// page doubles as the documentation. New articles start by copying this
	// file's shapes: plain <section>s of prose, figures with captions, and
	// Vimeo embeds. It also serves as the style-guide surface for articles -
	// change brand, mood, or scheme in the settings panel and check that the
	// whole page holds together.
?>

<section>
	<h2 class='attention-voice'>Text sections</h2>

	<p>This is a plain text section - a heading in the attention voice, followed by paragraphs in the calm voice. Nothing here is styled per-article. The paragraph rhythm comes from the shared <code>.styled</code> rules in typography.css, the measure caps at a readable width, and the colors come from whatever mood and scheme the visitor has set.</p>

	<p>An article is just a stack of these sections. A section can be one paragraph or many, and prose can carry <a class='link' href='/design-system'>links</a>, <em>emphasis</em>, and <strong>strong claims</strong> the same way it does anywhere else on the site.</p>
</section>

<section>
	<h2 class='attention-voice'>Figures</h2>

	<p>An image travels inside a figure with a caption. The caption is the quiet voice, and it should say something the image doesn't - what to notice, not what's depicted.</p>

	<figure class='article-figure'>
		<img src='<?= asset('/content/placeholder/poster-wide.png') ?>' alt='Placeholder poster graphic'>

		<figcaption class='quiet-voice'>The caption sits under the image in the quiet voice. This one is a placeholder graphic standing in for a real screenshot.</figcaption>
	</figure>

	<p>Media files for a real article live in a folder beside it at <code>content/articles/&lt;slug&gt;/</code>, named the house way, so the folder listing reads as the article's storyboard.</p>
</section>

<section>
	<h2 class='attention-voice'>Video</h2>

	<p>Video in an article is a normal Vimeo embed - no custom player, no autoplay rules, none of the timeline's local-video machinery. The iframe sits in the same figure shape as an image, holds its 16:9 frame before the player loads, and can carry a caption the same way.</p>

	<figure class='article-figure'>
		<iframe src='https://player.vimeo.com/video/76979871' title='Sample Vimeo embed' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>

		<figcaption class='quiet-voice'>A sample embed. Swap the video id in the iframe src for the real one.</figcaption>
	</figure>
</section>

<section>
	<h2 class='attention-voice'>How this page is wired</h2>

	<p>An article is three small pieces. The metadata (title, date, description) is an entry in <code>content/articles.json</code>. The prose is a body file like this one at <code>templates/articles/&lt;slug&gt;.php</code>. The shared shell at <code>templates/pages/article.php</code> renders the header and includes the body. Both the JSON entry and the body file have to exist before the URL does.</p>
</section>
