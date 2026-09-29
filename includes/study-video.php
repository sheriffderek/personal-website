<?php
	/* One talk-through video in a case study: a graphic you can read at a
	   glance, with a play button for anyone who wants the whole story.

	   Nothing from Vimeo loads until the visitor presses play. Then
	   scripts/study-video.js builds the Vimeo player behind the graphic and
	   fades the graphic away once the video is really playing. While it plays,
	   Vimeo's own controls do the work (scrubbing, captions, speed) - we don't
	   rebuild those.

	   $vimeo - the Vimeo video id
	   $title - what the video is called (read aloud on the play button, and
	            given to the player's iframe)
	   $face  - optional markup for the graphic: an <img>, an inline <svg>, or
	            a partial of plain HTML - whatever the step needs. Empty = a
	            plain themed panel until the graphic is made.

	   Without JavaScript the play button is just a link to the video on
	   Vimeo, so it still plays. */

	$face = $face ?? '';
?>
<study-video data-vimeo='<?= $vimeo ?>' data-title='<?= quote_safe($title) ?>' data-state='idle'>
	<div class='face'>
		<?= $face ?>
	</div>

	<a class='play' href='https://vimeo.com/<?= $vimeo ?>'>
		<span class='glyph' aria-hidden='true'></span>

		<span class='reader-only'>Play the video: <?= $title ?></span>
	</a>
</study-video>
