<?php
	// Derek's own map of the whole site. Everything on this page is DERIVED -
	// the $pages array in index.php, journal.json, the content/targets/
	// folders, the experiments/ directory, and the feature flags in config.php
	// are each read live, so this page states nothing it could get wrong.
	// Internal only (no 'menu' key in the router), but nothing here is secret -
	// it links to pages, it doesn't reveal content.

	$journal = load_json('journal.json');

	// A target preview exists when its folder does; the fixed-name PDFs are
	// rendered only if present, so list which ones each target carries.
	$target_dirs = glob(CONTENT_DIR . '/targets/*', GLOB_ONLYDIR);
	$target_pdfs = ['cover-letter.pdf', 'resume.pdf', 'questions.pdf'];

	$experiment_files = glob(SITE_ROOT . '/experiments/*.html');

	// The bolt-on feature flags from config.php, read live.
	$flags = [
		'TOUR_ENABLED' => TOUR_ENABLED,
		'GRID_VIEW_ENABLED' => GRID_VIEW_ENABLED,
		'CAROUSEL_ENABLED' => CAROUSEL_ENABLED,
		'SLIDER_HINT_ENABLED' => SLIDER_HINT_ENABLED,
		'FILTER_ENABLED' => FILTER_ENABLED,
	];
?>

<text-content class='styled site-index'>

	<h1 class='loud-voice'>Site index</h1>

	<p>Every door in the house, derived live from the router, the content folders, and the flags. Internal page - not in the menu.</p>

	<section class='ds-section'>
		<h2 class='attention-voice'>Pages</h2>

		<ul>
			<?php foreach ($pages as $page_slug => $page): ?>
				<?php if (strpos($page_slug, 'journal/') === 0) { continue; } ?>

				<li>
					<a class='link' href='<?= $page_slug === 'home' ? '/' : '/' . $page_slug ?>'><?= $page_slug === 'home' ? '/' : '/' . $page_slug ?></a>

					<span class='quiet-voice'><?= $page['title'] ?><?= empty($page['menu']) ? ' &middot; internal' : '' ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Journal entries</h2>

		<?php if ($journal): ?>
			<ul>
				<?php foreach ($journal as $entry_slug => $entry): ?>
					<li>
						<a class='link' href='/journal/<?= $entry_slug ?>'>/journal/<?= $entry_slug ?></a>

						<span class='quiet-voice'><?= $entry['title'] ?? '' ?><?= is_file(TEMPLATES_DIR . '/journal/' . $entry_slug . '.php') ? '' : ' &middot; NO BODY FILE (404s)' ?></span>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php else: ?>
			<p class='quiet-voice'>None yet.</p>
		<?php endif; ?>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Target previews</h2>

		<ul>
			<?php foreach ($target_dirs as $dir): ?>
				<?php
					$target = basename($dir);
					$pdfs = array_filter($target_pdfs, function ($pdf) use ($dir) {
						return is_file($dir . '/' . $pdf);
					});
				?>

				<li>
					<a class='link' href='/?target=<?= $target ?>'>/?target=<?= $target ?></a>

					<span class='quiet-voice'><?= $pdfs ? implode(', ', $pdfs) : 'no PDFs' ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Experiments</h2>

		<p class='quiet-voice'>Static standalones in <code>experiments/</code> - served as plain files, outside the router and the site chrome.</p>

		<ul>
			<?php foreach ($experiment_files as $file): ?>
				<li>
					<a class='link' href='/experiments/<?= basename($file) ?>'>/experiments/<?= basename($file) ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Feature flags</h2>

		<p class='quiet-voice'>Read live from <code>includes/config.php</code> - that file is where they change.</p>

		<ul>
			<?php foreach ($flags as $flag => $on): ?>
				<li>
					<code><?= $flag ?></code>

					<span class='quiet-voice'><?= $on ? 'on' : 'off' ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>QA knobs</h2>

		<ul>
			<li>
				<a class='link' href='/?bare=chrome'>/?bare=chrome</a>

				<span class='quiet-voice'>drops the tray / panel apparatus</span>
			</li>

			<li>
				<a class='link' href='/?bare=carousel'>/?bare=carousel</a>

				<span class='quiet-voice'>drops Flickity</span>
			</li>

			<li>
				<a class='link' href='/?bare=all'>/?bare=all</a>

				<span class='quiet-voice'>drops both</span>
			</li>
		</ul>
	</section>

</text-content>
