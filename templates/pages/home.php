<?php
// The home page: the reverse-chronological timeline of milestones.
// The "how much to show" filter lives here and nowhere else.

$all_milestones = load_json('milestones.json');

// Which slice of the timeline? Defaults to job-relevant, weight-3 entries.
$filter_tag = isset($_GET['filter']) ? $_GET['filter'] : 'job';

$milestones = array_filter($all_milestones, function ($m) use ($filter_tag) {
	$tags = isset($m['tags']) ? $m['tags'] : [];
	$weight = isset($m['weight']) ? $m['weight'] : 1;
	return in_array($filter_tag, $tags) && $weight >= 3;
});

// A ?target=companyname loads tailored notes for specific milestones.
$target = null;
if (isset($_GET['target'])) {
	$target_slug = preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['target']));
	if ($target_slug !== '') {
		$candidate = load_json('targets/' . $target_slug . '.json');
		if (!empty($candidate)) $target = $candidate;
	}
}
$target_notes = $target['milestones'] ?? [];
?>

<header class='page-header'>
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

<?php if (!empty($target['hero'])): ?>
	<p class='target-note'>
		<?= $target['hero'] ?>
	</p>
<?php endif; ?>

<ul class='timeline' role='list'>
	<?php foreach ($milestones as $milestone):
		$target_note = $target_notes[$milestone['slug']] ?? null;
	?>
		<li>
			<?php require TEMPLATES_DIR . '/milestone.php'; ?>
		</li>
	<?php endforeach; ?>
</ul>
