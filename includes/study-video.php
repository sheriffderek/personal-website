<?php
	/* One talk-through video in a case study: a graphic you can read at a
	   glance, with a play button for anyone who wants the whole story.

	   The video plays in a plain <video> on our own page, fed by Vimeo's
	   "third-party player" file links (Vimeo still hosts and streams it).
	   Not Vimeo's own player: that's a separate page in an iframe, and iOS
	   only allows sound when the tap lands inside the frame that plays it -
	   so our play button could never start it with sound. Here the button
	   and the video share a page, so one tap plays it, sound and all. On a
	   phone that tap opens the phone's own full-screen player instead of
	   playing inline (scripts/study-video.js says why).

	   The links come from content/videos.json, keyed by the Vimeo id;
	   bin/vimeo-links.php writes that file (its comment says when to run
	   it). An id with no entry there shows the graphic with no way to play -
	   run the script.

	   $vimeo - the Vimeo video id
	   $title - what the video is called (read aloud on the play button)
	   $face  - optional markup for the graphic: an <img>, an inline <svg>, or
	            a partial of plain HTML - whatever the step needs. Empty = a
	            plain themed panel until the graphic is made.

	   Without JavaScript the graphic and button stay hidden and the video
	   shows with the browser's own controls (scripts/study-video.js swaps
	   them in). */

	$face = $face ?? '';
	$video_links = load_json('videos.json')[$vimeo] ?? null;
?>
<study-video>
	<?php if ($video_links): ?>
		<video controls playsinline preload='none' aria-label='<?= quote_safe($title) ?>'>
			<?php /* The adaptive stream where the browser plays it natively
				(Safari, iOS, current Chrome); the plain MP4 everywhere else. */ ?>
			<?php if ($video_links['hls']): ?>
				<source src='<?= $video_links['hls'] ?>' type='application/vnd.apple.mpegurl'>
			<?php endif; ?>

			<?php if ($video_links['mp4']): ?>
				<source src='<?= $video_links['mp4'] ?>' type='video/mp4'>
			<?php endif; ?>
		</video>
	<?php endif; ?>

	<div class='face'>
		<?= $face ?>
	</div>

	<?php if ($video_links): ?>
		<button type='button' class='play'>
			<span class='glyph' aria-hidden='true'></span>

			<span class='reader-only'>Play the video: <?= $title ?></span>
		</button>
	<?php endif; ?>
</study-video>
