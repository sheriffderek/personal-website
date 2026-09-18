<?php /* The sheet's identity block - name, role line, contact, intro -
	shared by the resume lanes and the cover letters (same header, same
	sheet family). Expects $resume (the whole resume.json) and $lane
	(the current lane) in scope via partial(). */ ?>
<header class='resume-header'>

	<?php /* The name is a span, not a strong: Chrome's PDF export turns
		<strong> into a /Strong structure tag, which isn't a valid tag
		type in the PDF it writes (pdfinfo -struct-text flags it). */ ?>
	<h1 class='attention-voice'>
		<span class='resume-name'><?= $resume['header']['name'] ?></span>

		<span class='resume-role firm-voice'><?= $lane['role'] ?></span>
	</h1>

	<?php /* The separators are visual only - aria-hidden keeps a screen
		reader from announcing "middle dot" between the contact links,
		while the glyphs stay in the text layer for parsers. */ ?>
	<p>
		<?= $resume['header']['location'] ?>

		<a class='link' href='tel:<?= preg_replace('/[^0-9]/', '', $resume['header']['phone']) ?>'><?= $resume['header']['phone'] ?></a> <span aria-hidden='true'>·</span>

		<a class='link' href='https://<?= $resume['header']['website'] ?>'><?= $resume['header']['website'] ?></a>

		<a class='link' href='mailto:<?= $resume['header']['email'] ?>'><?= $resume['header']['email'] ?></a> <span aria-hidden='true'>|</span>

		<a class='link' href='https://<?= $resume['header']['linkedin'] ?>' target='_blank'><?= $resume['header']['linkedin'] ?></a>
	</p>

	<?php if (!empty($intro_paragraphs)): ?>
		<?php /* The intro is an array of paragraphs (same shape as entry
			bodies) - every lane's, even single-paragraph ones. The letters
			skip it (their body IS the prose). */ ?>
		<text-content class='resume-intro'>

			<?php foreach ($intro_paragraphs as $paragraph): ?>
				<p><?= $paragraph ?></p>
			<?php endforeach; ?>

		</text-content>
	<?php endif; ?>

</header>
