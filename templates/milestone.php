<?php
	/*
		<span class='weight quiet-voice'>(<?= $milestone['weight'] ?? '?' ?>)</span>
	*/
?>

<?php
	/* Poster sizes are purpose-made, never derived: the "poster" key is
	   true for the full 16:9 cover, or a named size ("large", "medium",
	   "small") for a heading-graphic frame - same poster-shapes tech,
	   authored per entry in milestones.json. A string value becomes
	   data-poster-size, which milestone.css maps to the frame ratio. */
	$poster_size = is_string($milestone['poster'] ?? null) ? " data-poster-size='" . $milestone['poster'] . "'" : '';
?>

<article id='<?= $milestone['slug'] ?>' class='milestone' data-flavor='<?= $milestone['flavor'] ?? 'default' ?>' data-weight='<?= $milestone['weight'] ?? 6 ?>'<?= $poster_size ?>>

	<div class='setup'>
		<p class='year high-voice'><?= $milestone['date'] ?></p>

		<?php
			/* The optional swappable tail of the title. The one current use:
			   "Now interviewing:" stays put while WHAT is being interviewed for
			   adapts per visitor - the target file's "role" override wins, the
			   milestone's own "role" is the fallback, most milestones have
			   neither. Set in home.php's loop from the ?target= file. */
			$role = $target_role ?? ($milestone['role'] ?? '');
		?>

		<h2 class='heading attention-voice'>
			<a href='#<?= $milestone['slug'] ?>'><?= $milestone['title'] ?><?= $role !== '' ? ' ' . $role : '' ?></a>
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

	<?php if (!empty($milestone['coverless']) && $media_items): ?>

		<?php /* TEMPORARY (pre-covers phase): show just the intro video, bare -
			no poster-shapes cover, no carousel. This deliberately overrides the
			locked "poster-first / no bare media" rule for a card whose real
			cover isn't drawn yet. The card keeps its other media in the JSON,
			simply unshown, until it's ready. To restore the full poster+carousel,
			remove the "coverless" key from the milestone and this branch. */ ?>
		<figure class='media'>
			<?= partial('posters/media-item', ['item' => $media_items[0]]) ?>
		</figure>

	<?php elseif ($media_items): ?>

		<figure class='media'>
			<?php /* wrapAround with only 2 cells (poster + one item) can leave a
			   1px clone seam at the edge - we turned it off for those once
			   (2026-07-08) and losing the loop-through read as broken, which is
			   worse. If the seam shows up again, fix the seam, not the wrap. */ ?>
			<div class='carousel' data-flickity='{ "wrapAround": true, "imagesLoaded": true, "prevNextButtons": false }'>
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
					<span class='calm-voice link'>Read more</span> +
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
			<a class='read-more link' href='<?= $milestone['link'] ?>'<?= $is_external ? " target='_blank'" : '' ?>><?= $label ?></a>
		<?php endif; ?>
	</text-content>
</article>
