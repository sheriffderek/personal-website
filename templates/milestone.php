<?php
	/*
		<span class='weight quiet-voice'>(<?= $milestone['weight'] ?? '?' ?>)</span>
	*/
?>

<article id='<?= $milestone['slug'] ?>' class='milestone' data-flavor='<?= $milestone['flavor'] ?? 'default' ?>' data-weight='<?= $milestone['weight'] ?? 6 ?>'>

	<div class='setup'>
		<p class='year high-voice'><?= $milestone['date'] ?></p>

		<h2 class='heading attention-voice'>
			<a href='#<?= $milestone['slug'] ?>'><?= $milestone['title'] ?></a>
		</h2>
	</div>

	<?php
		/* Three media shapes, all built on the themable poster-shapes cover:
		     1) no poster       → text-only (nothing rendered here)
		     2) poster only      → the poster-shapes alone (a card wants a visual
		                          but has no slides/videos yet; opt in with
		                          "poster": true in the JSON)
		     3) poster + media   → the poster-shapes cover first, then every real
		                          slide/video, in a carousel
		   The poster is ALWAYS the cover — slides and videos are additional, never
		   a replacement for it. real_media_items() drops placeholders (render.php);
		   the one shared partial (posters/media-item) renders each item, so the
		   responsive swap + ratio-lock frame apply throughout.
		   Authoring the poster cover art itself (the SVG in poster-shapes.php:
		   tokens, stroke widths, texture rules) is specced in poster-tech-brief.md. */
		$media_items = real_media_items($milestone);
		$poster_only = empty($media_items) && !empty($milestone['poster']);
	?>

	<?php if ($media_items): ?>

		<?php
			/* wrapAround only loops cleanly with 3+ cells; with 2 (poster + one
			   item) Flickity clones and can leave a 1px seam at the edge. Cell
			   count = 1 poster + the media items. */
			$wrap_around = (count($media_items) + 1) >= 3 ? 'true' : 'false';
		?>

		<figure class='media'>
			<div class='carousel' data-flickity='{ "wrapAround": <?= $wrap_around ?>, "imagesLoaded": true, "prevNextButtons": false }'>
				<div class='slide' data-type='poster'>
					<?php include INCLUDES_DIR . '/posters/poster-shapes.php'; ?>
				</div>

				<?php foreach ($media_items as $item): ?>
					<?= partial('posters/media-item', ['item' => $item]) ?>
				<?php endforeach; ?>
			</div>
		</figure>

	<?php elseif ($poster_only): ?>

		<figure class='media'>
			<div class='slide' data-type='poster'>
				<?php include INCLUDES_DIR . '/posters/poster-shapes.php'; ?>
			</div>
		</figure>

	<?php endif; ?>


	<text-content class='styled info'>
		<?= $milestone['description'] ?>

		<?php if (!empty($target_note)): ?>
			<p class='target-note'><?= $target_note ?></p>
		<?php endif; ?>

		<?php if (!empty($milestone['details'])): ?>
			<details class='more'>
				<summary class='read-more'>
					<span class='calm-voice link'>Read more</span> →
				</summary>

				<text-content class='styled more-body'>
					<div>→</div>
					<?= $milestone['details'] ?>
				</text-content>
			</details>
		<?php elseif (!empty($milestone['link'])):
			$is_external = strpos($milestone['link'], 'http') === 0;
			$label = isset($milestone['link_label']) ? $milestone['link_label'] : 'Read more';
		?>
			<a class='read-more link' href='<?= $milestone['link'] ?>'<?= $is_external ? " target='_blank' rel='noopener'" : '' ?>><?= $label ?> →</a>
		<?php endif; ?>
	</text-content>
</article>
