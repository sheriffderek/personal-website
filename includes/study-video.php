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
?>
<study-video data-state='idle'>
	<iframe
		src='https://player.vimeo.com/video/<?= $vimeo ?>?dnt=1'
		title='<?= quote_safe($title) ?>'
		allow='autoplay; fullscreen; picture-in-picture'
		loading='lazy'></iframe>

	<div class='face'>
		<?= $face ?>
	</div>

	<span class='cue' aria-hidden='true'></span>
</study-video>
