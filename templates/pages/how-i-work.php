<?php
$brief = [
	'goal' => 'Make the process legible. After this page you should be able to say how '
		. 'Derek approaches a project in one sentence - and want to hear him talk '
		. 'through a real one.',
];
?>

<text-content class='styled'>
	<h1 class='loud-voice'>How I work</h1>

	<p>There’s no official formula that works for every situation (sorry;). It all depends on scope: are we auditing a feature, adding a new feature, building a microsite, a new arm of the company, or founding a brand new company? But there is a general mindset and some common phases (that may repeat). I’ll show examples from a cross-section of work so you can see the what and why.</p>

	<?php /* The process, as Derek sketched it in his notebook. */ ?>
	<figure class='page-figure'>
		<img
			src='<?= asset('/content/pages/how-i-work/process-sketch.jpg') ?>'
			alt='A notebook sketch across two pages: rows of boxes, linked by yellow highlighter paths.'
			width='1600'
			height='1162'
			loading='lazy'
		>
	</figure>

	<p>This is a brand new site (not even v1) so, I’m putting this together over the next week as I film some product walkthroughs. This will be broken up and edited from those as a flow that shows examples from all my projects. I think this is probably the best entry point / compared to individual case studies, but it’s here for you to make that choice.</p>

	<p>It’s not ready! But here’s where it will live: and if you’re wishing it was here -- I can guarantee you a much more valuable conversation in person! So, I’ll be working away - but you can set up a time to talk as soon as you like <a class='link' href='<?= CALL_URL ?>'>here</a>.</p>

	<?php /* Temp video while the page is written - same embed shape as the
		vimeo media items (includes/posters/media-item.php). */ ?>
	<figure class='page-video'>
		<iframe
			src='https://player.vimeo.com/video/1221261727?badge=0&autopause=0&player_id=0&app_id=58479'
			title='How I work'
			allow='autoplay; fullscreen; picture-in-picture; clipboard-write; encrypted-media; web-share'
			referrerpolicy='strict-origin-when-cross-origin'
			loading='lazy'></iframe>
	</figure>
</text-content>
