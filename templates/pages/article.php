<?php
	// The shared article shell: date, title, optional summary, then the
	// article's own body file. $article arrives from the articles block in
	// index.php (the articles.json entry plus its slug). The body is plain
	// sections of markup at templates/articles/<slug>.php - the specimen
	// article (/articles/specimen) is the outline new bodies start from.
?>

<article class='styled article-page'>

	<header class='article-header'>
		<p class='date high-voice'><?= $article['date'] ?></p>

		<h1 class='loud-voice'><?= $article['title'] ?></h1>

		<?php if (!empty($article['summary'])): ?>
			<p class='summary'><?= $article['summary'] ?></p>
		<?php endif; ?>
	</header>

	<?php require TEMPLATES_DIR . '/articles/' . $article['slug'] . '.php'; ?>

</article>
