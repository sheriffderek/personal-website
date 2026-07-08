<?php
	/* Renders ONE media item ({type, src}) as a .slide[data-type]. Used
	   identically for a single standalone poster and for each slide inside a
	   carousel — same markup, same behavior in either shape.

	   Responsive: photos swap via <picture> (native); videos carry
	   data-src-wide / data-src-square and JS (footer.php) picks the file per
	   breakpoint, because <source media> is ignored inside <video>. The wide
	   file is the <source> fallback so it still plays with JS off. */
	$type = $item['type'];
	$src = $item['src'];
	$sq = square_variant($src);
?>
<?php if ($type === 'photo'): ?>
	<picture class='slide' data-type='photo'>
		<source media='(max-width: 600px)' srcset='<?= $sq ?>'>

		<img src='<?= $src ?>' alt=''>
	</picture>

<?php elseif ($type === 'loop'): ?>
	<div class='slide' data-type='loop'>
		<video muted loop playsinline preload='metadata' data-src-wide='<?= $src ?>' data-src-square='<?= $sq ?>'>
			<source src='<?= $src ?>' type='video/mp4'>
		</video>
	</div>

<?php elseif ($type === 'play'): ?>
	<div class='slide' data-type='play'>
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

<?php elseif ($type === 'vimeo'): ?>
	<div class='slide' data-type='vimeo'>
		<iframe
			src='https://player.vimeo.com/video/<?= $src ?>?badge=0&autopause=0&player_id=0&app_id=58479'
			allow='autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share'
			referrerpolicy='strict-origin-when-cross-origin'
			loading='lazy'></iframe>
	</div>
<?php endif; ?>
