<?php
	$brief = [
		'goal' => 'A note to one person: get the video watched, then get a reply. '
			. 'One thing to do, nothing competing with it - the door deeper is the '
			. 'related links, never the page itself.',
	];

	/* $hello comes from the /hello/<slug> route in index.php. Every word on
	   this page is authored per person in content/hello/<slug>/hello.json:
	     name         who it's for (the tab title and share card)
	     greeting     optional - the headline, right above the video
	     message      optional HTML - the few lines under the video
	     sections     optional list of { heading, items: [{ them, me }] } - their
	                  posting mirrored line by line (them = their words,
	                  me = Derek's answer as <p> paragraphs; a `me` can link to its proof,
	                  e.g. /?target=acme#list-at-ease)
	     resumes      optional list of resume lane slugs, in order - e.g.
	                  ["advocate", "product-designer"] (lanes in resume.json)
	     closing      optional HTML - the "let's talk" line
	     related      optional list of { label, href } - the door deeper
	     description  optional - the share card's line under the title
	   The page has to work WITHOUT the video too (at work, on a phone,
	   sound off): the message and the connections should carry the point. */
	/* A link to Derek's booking calendar is written href='CALL_URL' in the
	   JSON (which can't read PHP constants) and filled in here, so changing
	   schedulers stays the one-line edit in config.php. */
	$hello = json_decode(str_replace("'CALL_URL'", "'" . CALL_URL . "'", json_encode($hello)), true);

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

		<?php /* Every text field is optional: a folder with just the name and
			the video renders, and each piece appears as Derek writes it -
			nothing is drafted for him (house copy rule). The page always has
			its one h1: the greeting, or their name until there is one. */ ?>
		<h1 class='attention-voice'><?= $hello['greeting'] ?? $hello['name'] ?></h1>
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
				title='<?= quote_safe('Video for ' . $hello['name']) ?>'
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

	<?php if (!empty($hello['message'])): ?>
		<text-content class='styled message'>
			<?= $hello['message'] ?>
		</text-content>
	<?php endif; ?>

	<?php /* The mirror: their posting, line by line, with Derek's answer beside
		each - a ledger, so they never do the matching themselves. Grouped
		under their own headings; an item with no `them` is just Derek talking.
		(Tried 2026-09-23: a quote-bar over each answer read as template
		output; answers alone lost the pairing. The ledger keeps the pairing
		and reads as a filled-in document.) */ ?>
	<?php foreach ($hello['sections'] ?? [] as $section): ?>
		<section class='mirror'>
			<h2 class='strong-voice'><?= $section['heading'] ?></h2>

			<ul>
				<?php foreach ($section['items'] as $item): ?>
					<li>
						<?php if (!empty($item['them'])): ?>
							<p class='them quiet-voice'><?= $item['them'] ?></p>
						<?php endif; ?>

						<?php /* `me` is authored as <p> paragraphs (an answer
							can run to a few), which also keeps a link inside it
							inline - the site's links are blocks everywhere but
							`p a`. */ ?>
						<div class='me'><?= $item['me'] ?></div>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endforeach; ?>

	<?php /* Every link that leaves this page opens a new tab (Derek,
		2026-09-23) - the note and its video stay right where they left them.

		The next step - the page ends on doors, not on the last answer.
		`resumes` in hello.json names which lanes to offer, in order (their
		labels come from content/resume.json's lanes); booking and the phone
		number always show - the phone from resume.json's header, so it lives
		in one place. */
		$resume = load_json('resume.json');
		$phone = $resume['header']['phone'] ?? '';
	?>
	<div class='next-steps'>
		<?php /* Two rows: the résumés, then the ways to reach him. */ ?>
		<?php if (!empty($hello['resumes'])): ?>
			<ul class='resumes' role='list'>
				<?php foreach ($hello['resumes'] as $lane_slug): ?>
					<?php if (isset($resume['lanes'][$lane_slug])): ?>
						<li>
							<a class='link' target='_blank' href='/resume/<?= $lane_slug ?>'>Resume: <?= $resume['lanes'][$lane_slug]['label'] ?></a>
						</li>
					<?php endif; ?>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<ul class='contact' role='list'>
			<li>
				<a class='link' target='calendar' href='<?= CALL_URL ?>'>Book a time</a>
			</li>

			<?php if ($phone !== ''): ?>
				<li>
					<a class='link' href='tel:<?= preg_replace('/[^0-9+]/', '', $phone) ?>'><?= $phone ?></a>
				</li>
			<?php endif; ?>
		</ul>
	</div>

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
							target='_blank'
						><?= $link['label'] ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php endif; ?>

</article>
