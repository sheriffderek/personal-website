<article class='milestone' data-flavor='<?= $milestone['flavor'] ?? 'default' ?>' data-weight='<?= $milestone['weight'] ?? 1 ?>'>

	<div class='setup'>
		<p class='year high-voice'><?= $milestone['date'] ?> <span class='weight quiet-voice'>(<?= $milestone['weight'] ?? '?' ?>)</span></p>

		<h2 class='heading attention-voice'><?= $milestone['title'] ?></h2>
	</div>


	<?php
		/* Media shape follows the count of real items — never a hand-set flag,
		   so it can't drift. real_media_items() drops placeholders (render.php).
		     0  → text-only card (nothing rendered here)
		     1  → a single standalone poster (no carousel)
		     2+ → a carousel, poster-shapes cover slide first
		   Every item is rendered by the one shared partial (posters/media-item),
		   so a photo/loop/play/vimeo looks and behaves the same in either shape.
		   Responsive wide/square swap + the ratio-lock frame apply in all cases. */
		$media_items = real_media_items($milestone);
		$media_count = count($media_items);
	?>
	<?php if ($media_count === 1): ?>
		<figure class='media'>
			<?= partial('posters/media-item', ['item' => $media_items[0]]) ?>
		</figure>

	<?php elseif ($media_count >= 2): ?>
		<figure class='media'>
			<div class='carousel' data-flickity='{ "wrapAround": true, "imagesLoaded": true, "prevNextButtons": false }'>
				<div class='slide' data-type='poster'>
					<?php include INCLUDES_DIR . '/posters/poster-shapes.php'; ?>
				</div>

				<?php foreach ($media_items as $item): ?>
					<?= partial('posters/media-item', ['item' => $item]) ?>
				<?php endforeach; ?>
			</div>
		</figure>
	<?php endif; ?>


	<div class='info'>
		<p><?=$milestone['description']?></p>

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
	</div>
</article>
