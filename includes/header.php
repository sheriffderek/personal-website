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

	<?php /* Primary pair (the default look) loads normally so first paint is stable:
		Quicksand (heading) + Spline Sans (body). The pair itself is set in
		styles/settings.css - keep this render-blocking link matched to it. */ ?>
	<link rel='stylesheet' href='https://api.fontshare.com/v2/css?f[]=quicksand@1&f[]=spline-sans@1&display=swap'>

	<?php /* Alternate-theme + audition fonts, loaded non-blocking: media=print keeps
		them off the render path, then onload flips them to all. noscript covers
		the JS-off case. All variable (@1) except single-weight gambarino/bebas-neue. */ ?>
	<link rel='stylesheet' media='print' onload="this.media='all'" href='https://api.fontshare.com/v2/css?f[]=general-sans@1&f[]=boska@1&f[]=supreme@1&f[]=switzer@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
	<noscript>
		<link rel='stylesheet' href='https://api.fontshare.com/v2/css?f[]=general-sans@1&f[]=boska@1&f[]=supreme@1&f[]=switzer@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
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
	<?php /* Phone-in-landscape scold. CSS shows it only on short landscape touch
		screens; it leaves the real content in the DOM (visual gag only, so AT
		users are unaffected). */ ?>
	<div class='rotate-notice'>
		<p class='loud-voice'>Are you kidding me right now?</p>

		<p>This isn't television. Sit up straight. Turn that phone around.</p>
	</div>

	<div class='page-wrapper'>
		<?php /* Zero-height marker: once it scrolls out the top, the rail is
			stuck. sticky-header.js watches it and toggles .is-stuck. */ ?>
		<div class='rail-sentinel' aria-hidden='true'></div>

		<header class='page-rail'>
			<a class='site-name' href='/'>Derek Wood</a>

			<?php include INCLUDES_DIR . '/settings-panel.php'; ?>
		</header>

		<main>
