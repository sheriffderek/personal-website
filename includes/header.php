<!doctype html>

<html lang='en'>

<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>
	<title><?= $page_title ?? SITE_TITLE ?></title>
	<meta name='description' content='<?= quote_safe($page_description ?? SITE_DESCRIPTION) ?>'>
	<script>
		(function () {
			var html = document.documentElement;
			try {
				var scheme = localStorage.getItem('scheme-preference');
				if (scheme && scheme !== 'system') html.setAttribute('data-scheme', scheme);

				var theme = localStorage.getItem('theme-preference') || 'default';
				html.setAttribute('data-theme', theme);
			} catch (error) {
				/* private-mode storage throw — still set the default so
				   theme-scoped selectors (like flavor variants) match. */
				html.setAttribute('data-theme', 'default');
			}
		})();
	</script>
	<link rel='preconnect' href='https://api.fontshare.com'>
	<link rel='preconnect' href='https://cdn.fontshare.com' crossorigin>

	<?php /* Primary fonts (the default look) load normally so first paint is stable.
		Swap the families in styles/font-scales.css to change the site's type. */ ?>
	<link rel='stylesheet' href='https://api.fontshare.com/v2/css?f[]=general-sans@1&f[]=boska@1&display=swap'>

	<?php /* Alternate-theme + audition fonts, loaded non-blocking: media=print keeps
		them off the render path, then onload flips them to all. noscript covers
		the JS-off case. All variable (@1) except single-weight gambarino/bebas-neue. */ ?>
	<link rel='stylesheet' media='print' onload="this.media='all'" href='https://api.fontshare.com/v2/css?f[]=supreme@1&f[]=switzer@1&f[]=spline-sans@1&f[]=quicksand@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
	<noscript>
		<link rel='stylesheet' href='https://api.fontshare.com/v2/css?f[]=supreme@1&f[]=switzer@1&f[]=spline-sans@1&f[]=quicksand@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
	</noscript>

	<?php foreach (stylesheet_paths() as $sheet): ?>
		<link rel='stylesheet' href='<?= asset($sheet) ?>'>
	<?php endforeach; ?>
	<link rel='stylesheet' href='https://unpkg.com/flickity@2/dist/flickity.min.css'>
	<script src='https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js' defer></script>
	<script src='https://player.vimeo.com/api/player.js' defer></script>
	<script src='<?= asset('/scripts/audio.js') ?>' defer></script>
	<script src='<?= asset('/scripts/settings-panel.js') ?>' defer></script>
	<script src='<?= asset('/scripts/sticky-header.js') ?>' defer></script>
</head>

<body>
	<div class='page-wrapper'>
		<?php if ($slug === 'home'): ?>
		<header class='site-header'>
			<text-content class='styled'>
				<h1 class='loud-voice'>Derek Wood: Designer</h1>

				<p>I help teams do their best work, whether that's big-picture vision and strategy, research and user testing, interfaces and code, design systems and cross-team collaboration, or auditing and maintaining what's already shipped.</p>

				<p>I've done it across agencies, startups, and product teams. And for the last 6+ years I've been teaching full-stack product design all while leading lean end-to-end projects and folding the learnings back into PE's curriculum.</p>
			</text-content>

			<details class='more'>
				<summary class='read-more'>
					<span class='calm-voice link'>Read more</span> →
				</summary>

				<text-content class='styled more-body'>
					<p>→</p>

					<p>I never considered being a designer. I certainly used Photoshop a lot in the 90s... but I actually went to school for painting. I got into the web by building sites with early Flash and MySpace for my friends and bands. Since then I've built almost everything a website can be: business cards, landing pages, brochure sites, e-commerce, immersive micro-sites, educational games, dashboards, and full web applications.</p>

					<p>My title kept changing along the way: front-end developer, UX engineer, design systems consultant, founding product designer, teacher, UI designer - all because that's what the job demanded of me. That's how I attack problems. I'm also comfortable out front: leading teams, giving talks, running workshops, teaching live and in person. The timeline below is a longer version and there's plenty of interviews and blog posts going through my whole life story - but if you've read this far: <a class='relaxed' href='https://calendly.com/perpetual-education/priority-meeting'>let's just get on a call!</a> I'm excited to start the next adventure.</p>
				</text-content>
			</details>
		</header>
		<?php endif; ?>

		<?php /* Zero-height marker: once it scrolls out the top, the rail is
			stuck. sticky-header.js watches it and toggles .is-stuck. */ ?>
		<div class='rail-sentinel' aria-hidden='true'></div>

		<aside class='page-rail'>
			<?php include INCLUDES_DIR . '/settings-panel.php'; ?>
		</aside>

		<main>
