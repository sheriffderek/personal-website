<?php
	// The journal's RSS feed - the whole document, chrome-free. Routed from
	// index.php at /journal/feed; everything here prints XML, not HTML.
	// Same source and same rules as the journal page: content/journal.json,
	// in the order the JSON declares (newest first), unlisted entries skipped.

	// Prose placed inside an XML element - a bare & or < would end the story
	// early. Readers un-escape this on display, so any markup in a summary
	// still renders as markup on their side.
	function xml_safe($text) {
		return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_XML1);
	}

	$journal = load_json('journal.json');

	$listed = array_filter($journal, function ($entry) {
		return empty($entry['unlisted']);
	});

	header('Content-Type: application/rss+xml; charset=utf-8');

	echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<rss version='2.0' xmlns:atom='http://www.w3.org/2005/Atom'>
	<channel>
		<title><?= xml_safe(SITE_TITLE) ?> - Journal</title>
		<link><?= SITE_URL ?>/journal</link>
		<atom:link href='<?= SITE_URL ?>/journal/feed' rel='self' type='application/rss+xml'/>
		<description><?= xml_safe($pages['journal']['description']) ?></description>
		<language>en-us</language>
		<lastBuildDate><?= date(DATE_RSS, filemtime(CONTENT_DIR . '/journal.json')) ?></lastBuildDate>

		<?php foreach ($listed as $entry_slug => $entry): ?>
			<item>
				<title><?= xml_safe($entry['title']) ?></title>
				<link><?= SITE_URL ?>/journal/<?= $entry_slug ?></link>
				<guid><?= SITE_URL ?>/journal/<?= $entry_slug ?></guid>
				<?php
					// Entry dates are authored loose ("September 2026") on
					// purpose - the feed pins each to the first of its month,
					// which is enough for readers to sort by. A date PHP can't
					// parse just goes without.
					$published = strtotime($entry['date']);
				?>
				<?php if ($published): ?>
					<pubDate><?= date(DATE_RSS, $published) ?></pubDate>
				<?php endif; ?>
				<description><?= xml_safe($entry['summary'] ?? $entry['description']) ?></description>
			</item>
		<?php endforeach; ?>
	</channel>
</rss>
