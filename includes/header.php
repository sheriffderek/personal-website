<!doctype html>

<html lang='en'>

<head>
	<meta charset='utf-8'>
	<meta name='viewport' content='width=device-width, initial-scale=1'>

	<?php /* Declares that we support both schemes, so the browser themes its own
		UI (scrollbars, form controls, the initial canvas) instead of painting a
		white flash before our CSS lands. Also the switch light-dark() reads: the
		color-scheme wiring in settings.css maps data-scheme -> color-scheme. */ ?>
	<meta name='color-scheme' content='light dark'>

	<?php
		/* Two title lanes (see config.php): $meta_title is the plain browser
		   <title> (page name, or the site name on the home page); $share_title
		   is the punchier SITE_META_TITLE used only on the share cards. Description
		   and image are shared across both so they never drift; a page can override
		   the image with $page_image (web-absolute path) before including this header. */
		$meta_title = $page_title ?? SITE_TITLE;
		$share_title = SITE_META_TITLE;
		$meta_description = $page_description ?? SITE_DESCRIPTION;
		$meta_image = SITE_URL . ($page_image ?? SITE_SHARE_IMAGE);
		$meta_url = SITE_URL . strtok($_SERVER['REQUEST_URI'], '?');
	?>
	<title><?= $meta_title ?></title>
	<meta name='description' content='<?= quote_safe($meta_description) ?>'>

	<?php /* Share cards: Open Graph (Facebook/LinkedIn/iMessage) + Twitter. */ ?>
	<meta property='og:type' content='website'>
	<meta property='og:site_name' content='<?= SITE_TITLE ?>'>
	<meta property='og:title' content='<?= quote_safe($share_title) ?>'>
	<meta property='og:description' content='<?= quote_safe($meta_description) ?>'>
	<meta property='og:url' content='<?= $meta_url ?>'>
	<meta property='og:image' content='<?= $meta_image ?>'>

	<meta name='twitter:card' content='summary_large_image'>
	<meta name='twitter:title' content='<?= quote_safe($share_title) ?>'>
	<meta name='twitter:description' content='<?= quote_safe($meta_description) ?>'>
	<meta name='twitter:image' content='<?= $meta_image ?>'>
	<script>
		(function () {
			var html = document.documentElement;
			try {
				var scheme = localStorage.getItem('scheme-preference');
				if (scheme && scheme !== 'system') html.setAttribute('data-scheme', scheme);

				/* Character (type + shape) and mood (color) are separate axes.
				   Absent attribute = the default (Product character / TODO mood),
				   so only a non-default saved choice gets written. Unknown values
				   (e.g. a stale 'brand-preference'-era slug) are ignored. Keep
				   these lists matched to CHARACTERS / MOODS in settings-panel.js. */
				var character = localStorage.getItem('character-preference');
				if (['marketing', 'interface', 'editorial', 'terminal'].indexOf(character) !== -1) html.setAttribute('data-brand-character', character);

				var mood = localStorage.getItem('mood-preference');
				if (['technical', 'quiet'].indexOf(mood) !== -1) html.setAttribute('data-brand-mood', mood);

				var flavor = localStorage.getItem('flavor-preference');
				if (['earth', 'cool', 'sweet'].indexOf(flavor) !== -1) html.setAttribute('data-flavor', flavor);

				/* Red light is a boolean override, its own key - 'on' = a bare
				   data-red-light attribute (no value). */
				if (localStorage.getItem('red-light-preference') === 'on') html.setAttribute('data-red-light', '');

				<?php if (GRID_VIEW_ENABLED && ($page_controls ?? null) === 'filter-control'): ?>
					/* Grid only exists from 1200px (the breakpoint in
					   styles/layouts/grid-view.css - keep the two matched); below
					   it the preference waits, unapplied, and settings-panel.js
					   re-checks on resize. Gated to the timeline page - a saved
					   grid preference means nothing anywhere else. */
					var view = localStorage.getItem('view-preference');
					if (view === 'grid' && window.matchMedia('(min-width: 1200px)').matches) html.setAttribute('data-view', 'grid');
				<?php endif; ?>
			} catch (error) {
				/* private-mode storage throw — the defaults need no attributes. */
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
	<link rel='stylesheet' media='print' onload="this.media='all'" href='https://api.fontshare.com/v2/css?f[]=general-sans@1&f[]=boska@1&f[]=supreme@1&f[]=switzer@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=sentient@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
	<noscript>
		<link rel='stylesheet' href='https://api.fontshare.com/v2/css?f[]=general-sans@1&f[]=boska@1&f[]=supreme@1&f[]=switzer@1&f[]=hind@1&f[]=poppins@1&f[]=nunito@1&f[]=chubbo@1&f[]=pilcrow-rounded@1&f[]=bonny@1&f[]=sentient@1&f[]=gambarino@400&f[]=bebas-neue@400&display=swap'>
	</noscript>

	<?php foreach (stylesheet_paths() as $sheet): ?>
		<link rel='stylesheet' href='<?= asset($sheet) ?>'>
	<?php endforeach; ?>
	<?php /* Flickity loads only when the carousel system is on (config.php -
		off since 2026-08-12, the iOS horizontal-scroll suspect). */ ?>
	<?php if (CAROUSEL_ENABLED): ?>
		<link rel='stylesheet' href='https://unpkg.com/flickity@2/dist/flickity.min.css'>
		<script src='https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js' defer></script>
	<?php endif; ?>
	<script src='https://player.vimeo.com/api/player.js' defer></script>
	<script src='<?= asset('/scripts/audio.js') ?>' defer></script>
	<?php /* The settings script and the panel markup (in <body> below) gate
		together on $settings_panel_on (index.php) - one flag to pull the whole
		apparatus if it ever needs to go dark again. */ ?>
	<?php if ($settings_panel_on ?? false): ?>
		<script src='<?= asset('/scripts/settings-panel.js') ?>' defer></script>
	<?php endif; ?>

	<?php /* The design-system tester's specimen controls (the inline copies in
		its "Menus & settings" section) toggle themselves - they drive no site
		state, but a control that does nothing when clicked reads as broken.
		Only this page has them. */ ?>
	<?php if ($slug === 'design-system'): ?>
		<script src='<?= asset('/scripts/design-system.js') ?>' defer></script>
	<?php endif; ?>

	<?php /* The tour experiment ships dark: its stylesheet AND scripts only load
		when the flag is on, honoring the "no weight when off" contract in
		config.php (which is why welcome-video.css is not in components.css). */ ?>
	<?php /* Grid view ships behind its flag with the same "no weight when off"
		contract as the tour: the stylesheet only loads (and the toggle only
		renders, see settings-panel.php) when the flag is on. */ ?>
	<?php if (GRID_VIEW_ENABLED): ?>
		<link rel='stylesheet' href='<?= asset('/styles/layouts/grid-view.css') ?>'>

		<?php /* Escape hatch: true pauses the lane dealer and the wall
			renders the no-JS fallback - aligned rows, level top edges,
			holes accepted (approved as fallback-only, 2026-07-12). */ ?>
		<?php $masonry_paused = false; ?>

		<?php /* The masonry script only serves the timeline page - same gate
			as the view toggle and the FOUC data-view line. */ ?>
		<?php if (!$masonry_paused && ($page_controls ?? null) === 'filter-control'): ?>
			<script src='<?= asset('/scripts/grid-masonry.js') ?>' defer></script>
		<?php endif; ?>
	<?php endif; ?>

	<?php /* The slider hint ships behind its flag with the same "no weight when
		off" contract: the script only loads when the flag is on, and it no-ops
		on pages without carousels. */ ?>
	<?php if (SLIDER_HINT_ENABLED): ?>
		<script src='<?= asset('/scripts/slider-hint.js') ?>' defer></script>
	<?php endif; ?>

	<?php if (TOUR_ENABLED): ?>
		<link rel='stylesheet' href='<?= asset('/styles/components/welcome-video.css') ?>'>
		<script src='<?= asset('/scripts/welcome-video.js') ?>' defer></script>
		<script src='<?= asset('/scripts/choreo.js') ?>' defer></script>
		<script src='<?= asset('/scripts/tour.js') ?>' defer></script>
	<?php endif; ?>

	<script src='<?= asset('/scripts/sticky-header.js') ?>' defer></script>

	<?php /* Per-poster phone-crop windows - same gate as the timeline scripts
		(posters only render there). */ ?>
	<?php if (($page_controls ?? null) === 'filter-control'): ?>
		<script src='<?= asset('/scripts/poster-crops.js') ?>' defer></script>
	<?php endif; ?>
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
		<?php /* Zero-height marker: once it scrolls out the top, the tray is
			stuck. sticky-header.js watches it and toggles .is-stuck. */ ?>
		<div class='tray-sentinel' aria-hidden='true'></div>

		<?php /* data-ui='app': the tray IS core UI, so the whole strip lives in
			the sealed app-chrome scope (settings-panel.css) - without it the
			toolbar triggers read --app-* tokens that never resolve. */ ?>
		<header class='site-tray' data-ui='app'>
			<!--<a class='site-name' href='<?= '/' . ($target_query ?? '') ?>'>Derek Wood</a>-->

			<?php /* Dev stamp - which code am I looking at, at a glance. Top-left
				so phone QA never scrolls to the footer's version line: commit hash
				plus the clock time of the newest code-file save (an uncommitted
				save bumps it too - see dev_stamp() in render.php). The host check
				IS the gate: renders on every serve that isn't production, no flag
				to forget. */ ?>
			<?php if (strpos($_SERVER['HTTP_HOST'] ?? '', 'derekthomaswood.com') === false): ?>
				<?php /* app-data-voice, NOT the page's data-voice: the stamp
					lives inside the chrome, and chrome text wears chrome
					voices only (the app-ui contract, settings-panel.css). */ ?>
				<p class='dev-stamp app-data-voice'><?= dev_stamp() ?></p>
			<?php endif; ?>

			<?php /* Gates together with the settings-panel.js <link> in <head>
				via $settings_panel_on (index.php). */ ?>
			<?php if ($settings_panel_on ?? false): ?>
				<?php include INCLUDES_DIR . '/settings-panel.php'; ?>
			<?php endif; ?>
		</header>

		<?php /* One dim behind an open menu (phones/tablets). Root-level so it
			isn't trapped in the tray's stacking context. Shown/hidden by
			settings-panel.js; see .site-shade in modules/settings-panel.css for
			why it's one shared element and not a per-popover ::backdrop. */ ?>
		<div class='site-shade' aria-hidden='true'></div>

		<main>
