<?php
	/* Welcome video - a standalone talking-head at the top of home.

	   Deliberately its OWN component, separate from the milestone media/carousel:
	   different job (the site's spoken intro, and the clip that drives the guided
	   tour via data-tour-video), and it wants its own resting state and controls.

	   Resting state is a still image (the video's poster). A muted loop could
	   replace the still later - the markup would barely change, just the resting
	   layer. The video streams on press (preload='none').

	   Responsive source + poster: data-src-wide/square and data-poster-wide/square
	   are picked per breakpoint by the shared script in includes/footer.php (that
	   logic targets any video[data-src-wide], not just milestone cards). The wide
	   file is also a plain <source> so the element is valid without that swap
	   script - but the player itself needs JS (the trigger is the only control),
	   so with JS off the poster still is the whole experience. */
	$src_square = $src_square ?? '';
	$poster = $poster ?? '';
	$poster_square = $poster_square ?? '';
?>
<figure class='welcome-video'>
	<video
		class='talking-head'
		playsinline
		preload='none'
		data-tour-video
		<?= $poster ? "poster='" . asset($poster) . "' data-poster-wide='" . asset($poster) . "'" : '' ?>
		<?= $poster_square ? "data-poster-square='" . asset($poster_square) . "'" : '' ?>
		data-src-wide='<?= asset($src) ?>'
		<?= $src_square ? "data-src-square='" . asset($src_square) . "'" : '' ?>
	>
		<source src='<?= asset($src) ?>' type='video/mp4'>
	</video>

	<button
		type='button'
		class='play-trigger'
		aria-label='Play the intro'
	>
		<span class='play-icon' aria-hidden='true'></span>
	</button>
</figure>
