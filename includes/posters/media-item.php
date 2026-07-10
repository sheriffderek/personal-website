<?php
	/* Renders ONE media item ({type, src}) as a .slide[data-type], for each
	   slide inside a carousel. Media always lives in a carousel behind the
	   poster-shapes cover — there is no bare, poster-less slide/video.

	   Responsive: photos swap via <picture> (native); videos carry
	   data-src-wide / data-src-square and JS (footer.php) picks the file per
	   breakpoint, because <source media> is ignored inside <video>. The wide
	   file is the <source> fallback so it still plays with JS off. */
	$type = $item['type'];
	$src = $item['src'];
	$sq = square_variant($src);

	/* Freeze-frame poster, videos only. Derived from the video's own filename, so
	   each cut gets its matching still. Empty when none has been made yet. */
	$poster = '';
	$poster_square = '';

	if ($type === 'loop' || $type === 'play') {
		$poster = poster_variant($src);
		$poster_square = poster_variant($sq);
	}

	/* Cache-bust media by mtime (same as CSS/JS via asset()) so a re-encoded or
	   replaced file gets a fresh URL. Without this a browser can serve a stale
	   copy - e.g. the pre-fast-start video Safari couldn't play would keep coming
	   back from cache even after the file is fixed. Vimeo is an external id, not
	   a local file, so it's left alone. */
	if ($type !== 'vimeo') {
		$src = asset($src);
		$sq = asset($sq);
		$poster = $poster ? asset($poster) : '';
		$poster_square = $poster_square ? asset($poster_square) : '';
	}

	/* The <video>'s poster attributes. JS swaps data-poster-* alongside the
	   source at the phone breakpoint, so the still always matches the cut.
	   Nothing is emitted when there's no still. */
	$poster_attributes = '';

	if ($poster) {
		$poster_attributes = " poster='{$poster}' data-poster-wide='{$poster}'";

		if ($poster_square) {
			$poster_attributes .= " data-poster-square='{$poster_square}'";
		}
	}
?>
<?php if ($type === 'photo'): ?>
	<picture class='slide' data-type='photo'>
		<source media='(max-width: 600px)' srcset='<?= $sq ?>'>

		<img src='<?= $src ?>' alt=''>
	</picture>

<?php elseif ($type === 'loop'): ?>
	<div class='slide' data-type='loop'>
		<video muted loop playsinline preload='metadata'<?= $poster_attributes ?> data-src-wide='<?= $src ?>' data-src-square='<?= $sq ?>'>
			<source src='<?= $src ?>' type='video/mp4'>
		</video>
	</div>

<?php elseif ($type === 'play'): ?>
	<div class='slide' data-type='play'>
		<video playsinline preload='metadata'<?= $poster_attributes ?> data-src-wide='<?= $src ?>' data-src-square='<?= $sq ?>'>
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
