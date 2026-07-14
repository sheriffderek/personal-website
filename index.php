<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/render.php';

// What page did the browser ask for?
// "/how-i-work?target=x" becomes "how-i-work". An empty path means home.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$slug = trim($path, '/');
if ($slug === '') {
	$slug = 'home';
}

// A ?target=companyname tailors content for a specific visitor (see the pages
// in templates/pages/). It's a whole-visit context, so every internal link we
// render carries it forward - otherwise the first click drops them back to the
// generic site. We sanitize once here and hand the ready-made query suffix to
// the header, menu, and footer. Same character rule the pages use.
$target_slug = isset($_GET['target']) ? preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['target'])) : '';
$target_query = $target_slug !== '' ? '?target=' . $target_slug : '';

// The pages this site has. Each one is a body file in templates/pages/,
// plus the <title> and meta description that go in its <head>.
// 'menu' is the short label shown in the menu + footer (pages without it,
// like 404, stay out of the menu). 'controls' names an optional partial
// of page-specific controls that rides in the menu panel.
$pages = [
	'home' => [
		'file' => 'home.php',
		'menu' => 'Home',
		'title' => SITE_TITLE,
		'description' => SITE_DESCRIPTION,
		'controls' => 'filter-control',
	],

	'how-i-work' => [
		'file' => 'how-i-work.php',
		'menu' => 'How I work',
		'title' => 'How I work - ' . SITE_TITLE,
		'description' => 'How Derek Wood approaches a project, stage by stage, with examples from real work.',
	],

	'now' => [
		'file' => 'now.php',
		'menu' => 'Now',
		'title' => 'Now - ' . SITE_TITLE,
		'description' => 'What Derek Wood is focused on right now.',
	],

	'contact' => [
		'file' => 'contact.php',
		'menu' => 'Contact',
		'title' => 'Contact - ' . SITE_TITLE,
		'description' => 'Get in touch with Derek Wood about design and product roles.',
	],

	// Internal tester - every token, voice, and the poster card in one place,
	// so a brand/emphasis/scheme change can be eyeballed against everything at
	// once. No 'menu' key on purpose: reachable by URL, kept out of the public
	// nav. This is the style-guide surface the house conventions call for.
	'design-system' => [
		'file' => 'design-system.php',
		'title' => 'Design system - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],

	// Internal tester for the shell itself - the nav, the settings apparatus,
	// and how they place across screen sizes - in the barest possible HTML,
	// away from real content. No 'menu' key: reachable by URL only.
	'layout-lab' => [
		'file' => 'layout-lab.php',
		'title' => 'Layout lab - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],
];

// Didn't recognize it? Show a 404 - still a real page with our normal chrome.
if (!isset($pages[$slug])) {
	http_response_code(404);
	$slug = 'not-found';
	$pages['not-found'] = [
		'file' => 'not-found.php',
		'title' => 'Page not found - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	];
}

$current = $pages[$slug];
$page_title = $current['title'];
$page_description = $current['description'];
$page_controls = $current['controls'] ?? null;

// The settings panel is off site-wide right now (mobile scroll-freeze hunt -
// see the gates in includes/header.php). The design-system tester is the one
// exception: the brand/emphasis/scheme controls ARE its job, and it's an
// internal desktop page the freeze doesn't reach. One flag, read in header.php.
$settings_panel_on = ($slug === 'design-system');

// Build the page: shared header, this page's body, shared footer.
require __DIR__ . '/includes/header.php';
require TEMPLATES_DIR . '/pages/' . $current['file'];
require __DIR__ . '/includes/footer.php';
