<article class='milestone' data-format='<?= $milestone['format'] ?>' data-flavor='<?= $milestone['flavor'] ?? 'default' ?>' data-weight='<?= $milestone['weight'] ?? 1 ?>'>

	<div class='setup'>
		<p class='year high-voice'><?= $milestone['date'] ?> <span class='weight quiet-voice'>(<?= $milestone['weight'] ?? '?' ?>)</span></p>

		<h2 class='heading attention-voice'><?= $milestone['title'] ?></h2>
	</div>


	<?php
		/* Posters are optional and built top-down (weight 3 first). A card shows
		   one only when it points at a real asset (not the shared placeholder),
		   so poster-presence itself is a visual hierarchy cue. To give a milestone
		   a poster: set its format (photo/video/carousel) and point media at a
		   real file - until then it stays text-only. */
		$carousels_enabled = true;

		$has_poster = has_real_poster($milestone);
	?>
	<?php if ($has_poster): ?>
	<div class='media'>
		<?php if ($carousels_enabled && $milestone['format'] === 'carousel'): ?>
			<div class='carousel' data-flickity='{ "wrapAround": true, "imagesLoaded": true, "prevNextButtons": false }'>
				<div class='slide' data-type='poster'>
					<?php include INCLUDES_DIR . '/posters/poster-shapes.php'; ?>
				</div>
				<?php foreach ($milestone['media'] as $slide):
					$type = $slide['type'];
					$src = $slide['src'];
					$sq = square_variant($src);
				?>
					<?php if ($type === 'photo'): ?>
						<picture class='slide' data-type='photo'>
							<source media='(max-width: 600px)' srcset='<?= $sq ?>'>
							<img src='<?= $src ?>' alt=''>
						</picture>

					<?php elseif ($type === 'loop'): ?>
						<div class='slide' data-type='loop'>
							<?php /* No <source media>: browsers ignore it inside <video>. The right
							         file per breakpoint is chosen by JS from data-src-* (see footer.php). */ ?>
							<video muted loop playsinline preload='metadata' data-src-wide='<?= $src ?>' data-src-square='<?= $sq ?>'>
								<source src='<?= $src ?>' type='video/mp4'>
							</video>
						</div>

					<?php elseif ($type === 'play'): ?>
						<div class='slide' data-type='play'>
							<?php /* Native controls are all-or-nothing on iOS (controlslist is ignored),
							         so we ship our own: a play/pause button and a seek scrubber in a
							         bottom control bar. Tapping the video toggles too. */ ?>
							<video playsinline preload='metadata' data-src-wide='<?= $src ?>' data-src-square='<?= $sq ?>'>
								<source src='<?= $src ?>' type='video/mp4'>
							</video>

							<div class='controls'>
								<button type='button' class='play-pause' aria-label='Play'>
									<span class='icon icon-play'></span>

									<span class='icon icon-pause'></span>
								</button>

								<input class='scrubber' type='range' min='0' max='100' step='0.1' value='0' aria-label='Seek'>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>

		<?php elseif ($milestone['format'] === 'video' && !empty($milestone['media'][0])):
			$src = $milestone['media'][0];
		?>
			<video autoplay muted loop playsinline data-src-wide='<?= $src ?>' data-src-square='<?= square_variant($src) ?>'>
				<source src='<?= $src ?>' type='video/mp4'>
			</video>

		<?php elseif ($milestone['format'] === 'video' && !empty($milestone['vimeo'])):
			$ratio = isset($milestone['ratio']) ? $milestone['ratio'] : '16 / 9';
		?>
			<div style='aspect-ratio: <?= $ratio ?>; position: relative'>
				<iframe
					src='https://player.vimeo.com/video/<?= $milestone['vimeo'] ?>?badge=0&autopause=0&player_id=0&app_id=58479'
					style='position: absolute; inset: 0; width: 100%; height: 100%; border: 0'
					allow='autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share'
					referrerpolicy='strict-origin-when-cross-origin'
					loading='lazy'></iframe>
			</div>

		<?php elseif ($milestone['format'] === 'photo' && !empty($milestone['media'][0])):
			$src = $milestone['media'][0];
			$sq = square_variant($src);
		?>
			<picture class='poster-art' data-type='photo'>
				<source media='(max-width: 600px)' srcset='<?= $sq ?>'>
				<img src='<?= $src ?>' alt=''>
			</picture>
		<?php endif; ?>
	</div>
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
