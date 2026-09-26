<?php
require __DIR__ . '/includes/config.php';
require __DIR__ . '/includes/render.php';
require __DIR__ . '/meta-image/meta-image.php';

// The build fingerprint, itemized (?stamp=debug): plain-text list of every
// hashed code file, for diffing two machines to the exact differing file.
if (($_GET['stamp'] ?? '') === 'debug') {
	stamp_debug();
}

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

// A ?try=<take>-<placement> link previews one app-ui pairing on the LIVE
// chrome, for that page view only - nothing saved, nothing default, and
// anything off the lists is ignored. It exists so a pairing can be felt on
// a real phone (the /app-ui playground lists the links); the rules it
// switches on live in styles/modules/settings-panel.css (CHROME TAKES).
// These two lists are also the playground's rows and columns.
$app_ui_takes = ['ringed' => 'Ringed', 'ghost' => 'Ghost'];
$app_ui_placements = ['straddle' => 'Straddle', 'below' => 'Below', 'contain' => 'Contain'];
$try_take = '';
$try_placement = '';
if (isset($_GET['try'])) {
	$try_parts = explode('-', (string) $_GET['try'], 2);
	if (count($try_parts) === 2 && isset($app_ui_takes[$try_parts[0]]) && isset($app_ui_placements[$try_parts[1]])) {
		$try_take = $try_parts[0];
		$try_placement = $try_parts[1];
	}
}

// The pages this site has. Each one is a body file in templates/pages/,
// plus the <title> and meta description that go in its <head>.
// 'menu' is the short label shown in the menu + footer (pages without it,
// like 404, stay out of the menu). 'controls' names an optional partial
// of page-specific controls that rides in the menu panel.
// Each page template may also open with a $brief block - the page's design
// brief, stated where the page lives ('goal' is the first key; the footer
// prints it to the console for curious visitors).
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

	'resume' => [
		'file' => 'resume.php',
		'menu' => 'Resume',
		// The tab title matches the share card on purpose (Derek, 2026-09-24):
		// apps that read <title> instead of og:title get the same line.
		// A recruiter holding this link needs two facts: it's Derek, and it's
		// a resume. Observed in iMessage (2026-09-24): "Derek Wood: Resume"
		// arrived as just "Resume" while Slack showed it whole; this title
		// arrives whole. So iMessage drops a leading "<og:site_name>: " from
		// the title - any title opening with "Derek Wood:" loses the name
		// there (the site-wide SITE_META_TITLE included).
		'title' => 'Resume: Derek Wood, Technical Product Designer',
		'share_title' => 'Resume: Derek Wood, Technical Product Designer',
		'description' => 'Technical Product Designer, Advocate/Educator, Design Engineer - how does it stack up?',
	],

	'now' => [
		'file' => 'now.php',
		'menu' => 'Now',
		'title' => 'Now - ' . SITE_TITLE,
		'description' => 'What Derek Wood is focused on right now.',
	],

	'journal' => [
		'file' => 'journal.php',
		'menu' => 'Journal',
		'title' => 'Journal - ' . SITE_TITLE,
		'description' => 'Videos and stories from Derek Wood - takes on design and development, situations from real work, tips and tricks.',
	],

	// The index of case studies. No 'menu' door yet (not in MENU_PAGES) -
	// reachable by URL and from wherever Derek links it.
	'case-studies' => [
		'file' => 'case-studies.php',
		'title' => 'Case studies - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
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

	// Index of standalone experiments (shell, posters, trio, tap lab).
	// No 'menu' key: reachable by URL only.
	'experiments' => [
		'file' => 'experiments.php',
		'title' => 'Experiments - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],

	// Internal tester - every page's social share preview (real og tags,
	// fetched from the live routes, drawn as unfurl cards). No 'menu' key:
	// reachable by URL only, like the other testers.
	'share-previews' => [
		'file' => 'share-previews.php',
		'title' => 'Share previews - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],

	// Internal tester - the app-ui playground: every chrome part in every
	// style (one column per take), default color only. No 'menu' key:
	// reachable by URL only, like the other testers.
	'app-ui' => [
		'file' => 'app-ui.php',
		'title' => 'App UI - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],

	// Private log of every job applied to, when, and why - read live from
	// the shared job-search repo's pipeline.md. Walled off from production
	// AND non-loopback IPs below; no 'menu' key, no MENU_PAGES entry, no
	// public discoverability. See templates/pages/applications.php.
	'applications' => [
		'file' => 'applications.php',
		'title' => 'Applications - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],

	// Derek's own index of everything - every page (public and internal),
	// journal entries, target previews, experiments, feature flags. All
	// derived live from the real sources, so it can't go stale. No 'menu'
	// key: reachable by URL only.
	'site-map' => [
		'file' => 'site-map.php',
		'title' => 'Site index - ' . SITE_TITLE,
		'description' => SITE_DESCRIPTION,
	],
];

// Journal entries live at /journal/<slug>. The metadata layer (title, date,
// description) is content/journal.json; the body itself is a file at
// templates/journal/<slug>.php (markup is template-land, data is content-land).
// Both must exist for the page to exist - a JSON entry without a body file, or
// a stray body file without its entry, is still a 404.
// The journal's RSS feed - a document, not a page, so it renders its own XML
// (templates/journal-feed.php) and skips the site chrome entirely. Claimed
// before the entry lookup below so "feed" is never mistaken for an entry slug.
if ($slug === 'journal/feed') {
	require TEMPLATES_DIR . '/journal-feed.php';
	exit;
}

if (strpos($slug, 'journal/') === 0) {
	$entry_slug = substr($slug, strlen('journal/'));
	$journal = load_json('journal.json');

	if (isset($journal[$entry_slug]) && is_file(TEMPLATES_DIR . '/journal/' . $entry_slug . '.php')) {
		$entry = $journal[$entry_slug];
		$entry['slug'] = $entry_slug;

		$pages[$slug] = [
			'file' => 'journal-entry.php',
			'title' => $entry['title'] . ' - ' . SITE_TITLE,
			'description' => $entry['description'],
			/* Marks this page as an article for the head (header.php): share
			   cards say "article" and lead with the entry's own title, and
			   search engines get a BlogPosting block. 'updated' is optional
			   in journal.json - set it only when the entry's substance
			   changed, never for a typo fix. */
			'article' => [
				'headline' => $entry['title'],
				'published' => $entry['date'],
				'updated' => $entry['updated'] ?? null,
			],
		];

		/* Share image, three floors down: a hand-made meta.jpg in the entry's
		   media folder wins (presence contract, same as the target PDFs);
		   else a card auto-generated from the title (the meta-image/ service);
		   else the site default via the header's normal fallback. */
		$entry_image = '/content/journal/' . $entry_slug . '/meta.jpg';
		if (is_file(SITE_ROOT . $entry_image)) {
			$pages[$slug]['image'] = $entry_image;
		} else {
			$generated_image = meta_image_url('journal-' . $entry_slug, $entry['title']);
			if ($generated_image) {
				$pages[$slug]['image'] = $generated_image;
			}
		}
	}
}

// Case studies live at /case-studies/<slug> - the same two-layer shape as the
// journal: metadata in content/case-studies.json, the body at
// templates/case-studies/<slug>.php, and both must exist or it's a 404.
// Deliberately NOT journal entries: undated, revised over time, never in the
// feed, and built from sections rather than one prose column. A study is
// shared as ITSELF, like a journal entry: its title is the card's headline,
// and its 'description' the card's text - optional in the JSON; until Derek
// writes one, the teaser stands in (tags stripped - meta is plain text).
if (strpos($slug, 'case-studies/') === 0) {
	$study_slug = substr($slug, strlen('case-studies/'));
	$case_studies = load_json('case-studies.json');

	if (isset($case_studies[$study_slug]) && is_file(TEMPLATES_DIR . '/case-studies/' . $study_slug . '.php')) {
		$study = $case_studies[$study_slug];
		$study['slug'] = $study_slug;

		$pages[$slug] = [
			'file' => 'case-study.php',
			'title' => $study['title'] . ' - ' . SITE_TITLE,
			'share_title' => $study['title'],
			'description' => $study['description'] ?? strip_tags($study['teaser']),
		];
	}
}

// Resume lanes live at /resume/<lane>. One data file, content/resume.json,
// drives every lane page (the /resume index is a normal page that links
// here and reads none of it): the `lanes` map holds what
// differs per lane (intro, skills order), everything else is shared. The
// wording source of truth is job-search/briefing/resume-base.md + the lane
// spec beside it - this JSON is the public rendering of that, not a fork.
// An unknown lane stays a 404.
if (strpos($slug, 'resume/') === 0) {
	$lane_slug = substr($slug, strlen('resume/'));
	$resume = load_json('resume.json');

	// A trailing /text asks for the plain-text rendering - a document,
	// like the journal feed: it renders its own output and skips the
	// chrome. It reads the same JSON as the page and PDF, so pasted
	// text can never drift from the sent artifacts. The export script
	// saves these beside the PDFs.
	$is_text = false;
	if (substr($lane_slug, -strlen('/text')) === '/text') {
		$lane_slug = substr($lane_slug, 0, -strlen('/text'));
		$is_text = true;
	}

	// Each lane also carries its cover letter at /resume/<lane>/cover-letter -
	// same sheet family, same print pipeline. Letter prose lives in
	// content/letters.json (wording source of truth: the letter files in
	// job-search/briefing/); the identity header comes from resume.json.
	$is_cover_letter = false;
	if (substr($lane_slug, -strlen('/cover-letter')) === '/cover-letter') {
		$lane_slug = substr($lane_slug, 0, -strlen('/cover-letter'));
		$is_cover_letter = true;
	}

	// A letter route needs its lane in BOTH files - a lane missing from
	// letters.json stays a 404, never a blank letter with a 200 (the
	// export check reads status codes, so a 404 fails it loudly).
	$has_letter = true;
	if ($is_cover_letter) {
		$letters = load_json('letters.json');
		$has_letter = isset($letters['lanes'][$lane_slug]);
	}

	if (isset($resume['lanes'][$lane_slug]) && $has_letter) {
		$lane = $resume['lanes'][$lane_slug];

		if ($is_text) {
			require TEMPLATES_DIR . '/' . ($is_cover_letter ? 'cover-letter-text.php' : 'resume-text.php');
			exit;
		}

		if ($is_cover_letter) {
			$pages[$slug] = [
				'file' => 'cover-letter.php',
				'title' => 'Cover letter: ' . $lane['label'] . ' - ' . SITE_TITLE,
				'description' => 'Derek Wood\'s cover letter for ' . strtolower($lane['label']) . ' roles.',
			];
		} else {
			$pages[$slug] = [
				'file' => 'resume-lane.php',
				'title' => 'Resume: ' . $lane['label'] . ' - ' . SITE_TITLE,
				'description' => 'Derek Wood\'s resume, angled for ' . strtolower($lane['label']) . ' roles.',
			];
		}
	}
}

// Personal introductions live at /hello/<their-name> (Derek, 2026-09-23):
// a short video on why he's reaching out, a few lines, the exact
// connections, and a way to reply. One folder per person or company -
// content/hello/<slug>/ - holding hello.json (the words) beside video.mp4
// and an optional video.jpg poster (fixed names, presence = rendered, the
// same contract as the target folders). UNLISTED on purpose: no menu, no
// feed, noindex - the link only works for whoever Derek sends it to, and
// an unknown name is a plain 404, so guessing names reveals nothing.
if (strpos($slug, 'hello/') === 0) {
	// The whole slug must already be clean - anything else is a 404, never a
	// scrubbed near-match that could land on someone else's folder.
	$hello_slug = substr($slug, strlen('hello/'));
	$hello = preg_match('/^[a-z0-9-]+$/', $hello_slug) ? load_json('hello/' . $hello_slug . '/hello.json') : [];

	if (!empty($hello)) {
		$hello['slug'] = $hello_slug;
		$hello_poster = '/content/hello/' . $hello_slug . '/video.jpg';

		// The link preview is the first thing they see, so it's the note's own:
		// the greeting as its headline, and its line a `description` if one is
		// written, else Derek's opening lines from the message itself.
		$hello_opening = trim(preg_replace('/\s+/', ' ', strip_tags($hello['message'] ?? '')));

		$pages[$slug] = [
			'file' => 'hello.php',
			'title' => 'Hello ' . $hello['name'] . ' - ' . SITE_TITLE,
			'share_title' => $hello['greeting'] ?? null,
			'description' => $hello['description'] ?? ($hello_opening !== '' ? $hello_opening : SITE_DESCRIPTION),
			// the video's poster doubles as the share card, so the link
			// preview in their inbox is Derek's face, not the site default
			'image' => is_file(SITE_ROOT . $hello_poster) ? $hello_poster : null,
			'noindex' => true,
		];
	}
}

// The menu derives from the MENU_PAGES list (config.php) - a page shows a
// menu door only if the list says so for this environment. Every surface
// that lists menu'd pages (the Pages panel, the site-map, the
// share-previews sweep) follows automatically.
foreach (array_keys($pages) as $menu_slug) {
	$menu_status = MENU_PAGES[$menu_slug] ?? null;
	$menu_shows = $menu_status === 'live' || ($menu_status === 'local' && !IS_PRODUCTION);

	if (!$menu_shows) {
		unset($pages[$menu_slug]['menu']);
	}
}

// Private routes: reachable only off production AND from a loopback IP.
// Anything caught here 404s before the page lookup, so a slug that isn't
// safe to expose can never leak by being added to the $pages map alone.
// The list stays short and named - not a directory of "internal stuff",
// a specific allowlist of pages that read local-only sources.
$private_slugs = ['applications'];
if (in_array($slug, $private_slugs, true)) {
	$remote = $_SERVER['REMOTE_ADDR'] ?? '';
	$is_loopback = ($remote === '127.0.0.1' || $remote === '::1');
	if (IS_PRODUCTION || !$is_loopback) {
		unset($pages[$slug]);
	}
}

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
$page_image = $current['image'] ?? null;
$page_article = $current['article'] ?? null;
$page_controls = $current['controls'] ?? null;
$page_noindex = $current['noindex'] ?? false;
$page_share_title = $current['share_title'] ?? null;

// The settings panel is back on site-wide with the lab-port shell (JS panel
// placement + the data-over shade replaced the machinery the old mobile
// scroll-freeze was suspected to live in - watch for it on phones). One
// flag, read in header.php; set per-slug to gate it again if needed.
$settings_panel_on = true;

// QA bisect ladder (iOS horizontal-scroll hunt, 2026-08-12 - remove when
// solved): ?bare=chrome drops the whole tray/panel apparatus;
// ?bare=carousel drops Flickity (header.php); ?bare=all drops both.
$bare = $_GET['bare'] ?? '';
if ($bare === 'chrome' || $bare === 'all') {
	$settings_panel_on = false;
}

// Build the page: shared header, this page's body, shared footer.
require __DIR__ . '/includes/header.php';
require TEMPLATES_DIR . '/pages/' . $current['file'];
require __DIR__ . '/includes/footer.php';
