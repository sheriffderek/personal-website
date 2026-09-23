<?php
	$brief = [
		'goal' => 'A note to one person: get the video watched, then get a reply. '
			. 'One thing to do, nothing competing with it - the door deeper is the '
			. 'related links, never the page itself.',
	];

	/* $hello comes from the /hello/<slug> route in index.php. Every word on
	   this page is authored per person in content/hello/<slug>/hello.json:
	     name         who it's for (the tab title and share card)
	     greeting     the headline, right above the video
	     message      HTML - the few lines under the video
	     connections  optional list (HTML items) - how Derek fits what they're
	                  looking for; each can link to its proof, e.g. a card on
	                  their tailored timeline: /?target=acme#list-at-ease
	     closing      optional HTML - the "let's talk" line
	     related      optional list of { label, href } - the door deeper
	     description  optional - the share card's line under the title
	   The page has to work WITHOUT the video too (at work, on a phone,
	   sound off): the message and the connections should carry the point. */
	$folder = '/content/hello/' . $hello['slug'];
	$video = $folder . '/video.mp4';
	$poster = $folder . '/video.jpg';
?>

<article class='styled hello'>

	<header class='hello-header'>
		<p class='lockup'>
			<span class='name loud-voice'>Derek Wood</span>

			<span class='role stamp-voice'>Technical Product Designer</span>
		</p>

		<h1 class='attention-voice'><?= $hello['greeting'] ?></h1>
	</header>

	<?php /* The video: a Vimeo embed when hello.json names one ("vimeo": the
		id, plus "vimeo_hash" for an unlisted video - the ?h= part of its
		embed link), else a local video.mp4 in the folder.
		Local files get native controls on purpose: the site's custom `play`
		player is wired to the carousel, which is off. A voice can't autoplay
		(browsers block sound until a click), so the poster is the whole
		invitation - it should be Derek's face, mid-sentence. A local file MUST
		be fast-start or Safari shows a blank frame (see Media file convention
		in CLAUDE.md); Vimeo handles that itself. */ ?>
	<?php if (!empty($hello['vimeo'])): ?>
		<figure class='hello-video'>
			<iframe
				src='https://player.vimeo.com/video/<?= $hello['vimeo'] ?><?= !empty($hello['vimeo_hash']) ? '?h=' . $hello['vimeo_hash'] : '' ?>'
				title='<?= quote_safe($hello['greeting']) ?>'
				allow='fullscreen; picture-in-picture'
			></iframe>
		</figure>
	<?php elseif (is_file(SITE_ROOT . $video)): ?>
		<figure class='hello-video'>
			<video
				src='<?= asset($video) ?>'
				<?php if (is_file(SITE_ROOT . $poster)): ?>poster='<?= asset($poster) ?>'<?php endif; ?>
				controls
				playsinline
				preload='metadata'
			></video>
		</figure>
	<?php endif; ?>

	<text-content class='styled message'>
		<?= $hello['message'] ?>
	</text-content>

	<?php if (!empty($hello['connections'])): ?>
		<ul class='connections'>
			<?php foreach ($hello['connections'] as $connection): ?>
				<?php /* In a <p> so a link inside reads as prose: the site's
					links are blocks everywhere but `p a` (setup.css). */ ?>
				<li>
					<p><?= $connection ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php if (!empty($hello['closing'])): ?>
		<text-content class='styled closing'>
			<?= $hello['closing'] ?>
		</text-content>
	<?php endif; ?>

	<?php if (!empty($hello['related'])): ?>
		<section class='related'>
			<h2 class='strong-voice'>Related work or writing</h2>

			<ul>
				<?php foreach ($hello['related'] as $link): ?>
					<li>
						<a
							class='link'
							href='<?= quote_safe($link['href']) ?>'
							<?php if (strpos($link['href'], '/') !== 0): ?>target='_blank'<?php endif; ?>
						><?= $link['label'] ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

</article>
