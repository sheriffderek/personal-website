<?php
/* Internal tester - every page's social share preview in one place, the way
   /design-system does for components. Reachable by URL only (no menu key).

   THE TRUTH RULE: nothing here re-derives the meta logic. Each row fetches
   its page's real rendered <head> over HTTP (same server, same code path a
   scraper walks) and shows the og tags that actually came back - so this
   page can never agree with a wrong implementation or disagree with a right
   one. Slow is fine: it's a tester, one visitor, N small requests.

   The card mockup is the generic large-image unfurl shape (iMessage,
   LinkedIn, Slack all read the same og tags) - a layout approximation, not
   any one app's pixel-perfect chrome. The real final test stays: paste a
   URL into an actual message. */
$brief = [
	'goal' => 'See every share card before a recruiter does. Each row is the page\'s real '
		. 'og tags fetched from the live route, drawn as the unfurl a link becomes.',
];

/* The shareable routes: every public page, plus every journal entry
   (unlisted ones included - they share too, just aren't listed). */
$routes = [];

foreach ($pages as $page_slug => $page) {
	if (!empty($page['menu']) || $page_slug === 'home') {
		$routes[] = $page_slug === 'home' ? '/' : '/' . $page_slug;
	}
}

foreach (load_json('journal.json') as $entry_slug => $entry) {
	$routes[] = '/journal/' . $entry_slug;
}

foreach (load_json('resume.json')['lanes'] ?? [] as $lane_slug => $lane) {
	$routes[] = '/resume/' . $lane_slug;
	$routes[] = '/resume/' . $lane_slug . '/cover-letter';
}

/* One page's share facts, read from its real <head>. The fetch origin is
   the request's own host, but ONLY after it matches a known serving host -
   a crafted Host header must never turn this tester into a proxy. */
function share_facts($route) {
	$host = $_SERVER['HTTP_HOST'] ?? '';
	$known_hosts = ['derek.local:8888', parse_url(SITE_URL, PHP_URL_HOST)];

	if (!in_array($host, $known_hosts, true)) {
		return null;
	}

	$origin = $host === parse_url(SITE_URL, PHP_URL_HOST) ? SITE_URL : 'http://' . $host;
	$html = @file_get_contents($origin . $route);

	if (!$html) {
		return null;
	}

	$facts = ['route' => $route];

	foreach (['og:title' => 'title', 'og:description' => 'description', 'og:image' => 'image'] as $property => $key) {
		preg_match("/property='" . preg_quote($property, '/') . "' content='([^']*)'/", $html, $match);
		$facts[$key] = $match[1] ?? '';
	}

	/* Say where the image came from - the three floors of the contract. */
	if (strpos($facts['image'], '/meta-image/cache/') !== false) {
		$facts['source'] = 'generated card';
	} elseif (strpos($facts['image'], '/content/') !== false) {
		$facts['source'] = 'hand-made meta.jpg';
	} else {
		$facts['source'] = 'site default';
	}

	/* The image URL in the tags is absolute to production; swap the host so
	   the preview shows THIS machine's file (including a locally generated
	   card once Imagick is on). */
	$facts['local_image'] = preg_replace('#^https?://[^/]+#', '', $facts['image']);

	return $facts;
}
?>

<text-content class='styled share-previews'>

	<h1 class='loud-voice'>Share previews</h1>

	<p>Every page's real og tags, fetched from the live routes and drawn as the unfurl a pasted link becomes. The final test is still pasting a URL into an actual message - this page is the sweep before that.</p>

	<ul class='preview-list'>

		<?php foreach ($routes as $route): ?>
			<?php $facts = share_facts($route); ?>

			<?php if (!$facts) { continue; } ?>

			<li>

				<p class='stamp-voice'><?= $facts['route'] ?> · <?= $facts['source'] ?></p>

				<share-card>

					<img src='<?= $facts['local_image'] ?>' alt='Share image for <?= quote_safe($facts['route']) ?>' loading='lazy'>

					<card-text>

						<p class='quiet-voice'>derekthomaswood.com</p>

						<p class='firm-voice'><?= $facts['title'] ?></p>

						<p class='quiet-voice'><?= $facts['description'] ?></p>

					</card-text>

				</share-card>

			</li>
		<?php endforeach; ?>

	</ul>

</text-content>
