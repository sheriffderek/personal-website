<?php
	/* One talk-through video in a case study: a graphic you can read at a
	   glance, with a play cue for anyone who wants the whole story.

	   The real Vimeo player sits underneath, lazy-loaded as the section nears
	   the screen. The graphic and the cue lie on top but let every tap fall
	   through to the player. That's load-bearing: iOS only allows sound when
	   the tap lands inside the frame that plays it, and a play started from
	   our page (the Player API) comes out muted. So the first tap is always
	   Vimeo's own. scripts/study-video.js then fades the graphic away once
	   the video is really playing, and Vimeo's controls do the rest.

	   $vimeo - the Vimeo video id
	   $title - what the video is called (the iframe's title)
	   $face  - optional markup for the graphic: an <img>, an inline <svg>, or
	            a partial of plain HTML - whatever the step needs. Empty = a
	            plain themed panel until the graphic is made. */

	$face = $face ?? '';

	/* The player, trimmed to what a talk-through needs: play, the progress
	   bar, volume, captions, fullscreen. Vimeo's play button sits bottom-left,
	   under our cue, so the tap that starts it is on the cue. */
	$player_options = 'dnt=1&play_button_position=bottom&title=0&byline=0&portrait=0'
		. '&speed=0&pip=0&transcript=0&quality_selector=0&chromecast=0&vimeo_logo=0';
?>
<study-video data-state='idle'>
	<iframe
		src='https://player.vimeo.com/video/<?= $vimeo ?>?<?= $player_options ?>'
		title='<?= quote_safe($title) ?>'
		allow='autoplay; fullscreen; picture-in-picture'
		loading='lazy'></iframe>

	<div class='face'>
		<?= $face ?>
	</div>

	<span class='cue' aria-hidden='true'></span>
</study-video>
