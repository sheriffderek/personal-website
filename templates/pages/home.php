<?php
// The home page: the reverse-chronological timeline of milestones.
// The "how much to show" filter lives here and nowhere else.

$all_milestones = load_json('milestones.json');

// Which lane of the timeline? Defaults to job-relevant entries. We render ALL
// weights (not just the top tier) so the filter slider has lower tiers to
// reveal — the slider's default (tier 1) shows only weight-1 entries on load,
// so the initial view is unchanged.
$filter_tag = isset($_GET['filter']) ? $_GET['filter'] : 'job';

$milestones = array_filter($all_milestones, function ($m) use ($filter_tag) {
	$tags = isset($m['tags']) ? $m['tags'] : [];
	return in_array($filter_tag, $tags);
});

// A ?target=companyname loads tailored notes for specific milestones.
$target = isset($_GET['target']) ? load_target($_GET['target']) : null;
$target_notes = $target['milestones'] ?? [];
?>

<header class='page-header'>
	<h1 class='loud-voice'>
		<span class='name'>Derek Wood</span>
		<span class='role'>Goal-driven Designer</span>
		<?php // specific role text could be target-based too ?>
	</h1>

	<?php
		/* The talking-head welcome video - its own standalone component, separate
		   from the milestone slider (different job: the site's spoken intro, and
		   the clip that drives the guided tour). Resting state is a still image;
		   see includes/welcome-video.php + styles/components/welcome-video.css.
		   Gated with the tour behind TOUR_ENABLED (config.php) - work in progress,
		   flip that one flag on to bring it back. */
	?>
	<?php /* Scroll-sync note: when this video is live, the theme-swap anchor should
	         pin it instead of the intro paragraph below (it's fixed-ratio, so
	         theme-stable). Teach anchorPoint() in scripts/settings-panel.js to
	         prefer .welcome-video - see the .page-header branch there. */ ?>
	<?php if (TOUR_ENABLED): ?>
		<?= partial('welcome-video', [
			'src' => '/content/milestones/2026-job-search/01-play-wide-intro.mp4',
			'src_square' => '/content/milestones/2026-job-search/01-play-square-intro.mp4',
			'poster' => '/content/milestones/2026-job-search/01-play-wide-intro.jpg',
			'poster_square' => '/content/milestones/2026-job-search/01-play-square-intro.jpg',
		]) ?>
	<?php endif; ?>

	<text-content class='styled'>
		<p>I help teams do their best work, whether that's big-picture vision and strategy, research and user testing, interfaces and code, design systems and cross-team collaboration, or auditing and maintaining what's already shipped.</p>

		<p>I've done it across agencies, startups, and product teams. And for the last 6+ years, I've been teaching full-stack product design while keeping a hand in a range of design roles the whole time.</p>

		<?php if (!empty($target['hero'])): ?>
			<p class='target-note'>
				<?= $target['hero'] ?>
			</p>

			<?php
				/* Application documents for this target. Drop the PDFs into
				   content/targets/<slug>/ and each link appears on its own;
				   a file that isn't there just doesn't render. */
				$documents = [
					'cover-letter.pdf' => 'Cover letter',
					'resume.pdf' => 'Résumé',
					'questions.pdf' => 'Additional questions',
				];

				$document_links = [];

				foreach ($documents as $file => $label) {
					$web_path = '/content/targets/' . $target['slug'] . '/' . $file;

					if (is_file(SITE_ROOT . $web_path)) {
						$document_links[$label] = asset($web_path);
					}
				}
			?>

			<?php if ($document_links): ?>
				<ul class='documents' role='list'>

					<?php foreach ($document_links as $label => $href): ?>
						<li>
							<a class='link' href='<?= $href ?>' target='_blank' rel='noopener'><?= $label ?></a>
						</li>
					<?php endforeach; ?>

				</ul>
			<?php endif; ?>
		<?php endif; ?>
	</text-content>

	<details class='more'>
		<summary class='read-more'>
			<span class='calm-voice link'>Read more</span> →
		</summary>

		<text-content class='styled more-body'>
			<p>→</p>

			<p>I never considered being a designer. I certainly used Photoshop a lot in the 90s... but I actually went to school for painting.</p><p>I got into the web by building sites with early Flash and MySpace for my friends and bands. Since then, I've built almost everything a website can be: business cards, landing pages, brochure sites, e-commerce, immersive microsites, educational games, dashboards, and full web applications.</p>

			<p>My title kept changing along the way: front-end developer, UX engineer, design systems consultant, founding product designer, teacher, UI designer - all because that's what the job demanded of me. That's how I attack problems. I'm also comfortable out front: leading teams, giving talks, running workshops, teaching live and in person.</p><p>The timeline below is a longer version, and there are plenty of interviews and blog posts going through my whole life story - but if you've read this far, <a class='relaxed' target='calendar' href='https://calendly.com/perpetual-education/priority-meeting'>let's just get on a call!</a> I'm excited to start the next adventure.</p>
		</text-content>
	</details>
</header>

<ul class='timeline' role='list'>
	<?php foreach ($milestones as $milestone):
		/* A target's entry for a milestone is either a plain note string, or an
		   object holding a "note" and/or field overrides ("role" swaps the
		   swappable tail of the title - see templates/milestone.php). */
		$target_entry = $target_notes[$milestone['slug']] ?? null;
		$target_note = is_array($target_entry) ? ($target_entry['note'] ?? null) : $target_entry;
		$target_role = is_array($target_entry) ? ($target_entry['role'] ?? null) : null;
	?>
		<li>
			<?php require TEMPLATES_DIR . '/milestone.php'; ?>
		</li>
	<?php endforeach; ?>
</ul>
