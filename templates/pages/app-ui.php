<?php /* The app-ui playground - every chrome part in every style, one page
	(Derek, 2026-09-25). Internal: reachable by URL only (no 'menu' key),
	like /share-previews and /layout-lab.

	The axes: ROWS are the parts (triggers, the panel card with its real
	rows), COLUMNS are the styles - one column per take, in the chrome's
	default neutral color only, so shape/edge/depth get judged apart from
	hue (color takes are their own axis, later). Today there is one column:
	the chrome as it ships.

	Everything here is the REAL chrome - the trigger component, .app-card,
	and the shared settings rows - never a mockup, so a take judged here
	is a take that ships. The rows are live (the mirror model): moving a
	slider here re-themes the site, same as the panel. The demo triggers
	are inert - they only show their states.

	"Show boxes" outlines each control's real box - the tap/grab area the
	same-box rule protects - so sizes are seen, not argued. CSS only
	(:has), styles/modules/app-ui.css. */ ?>

<div class='app-ui-playground'>

	<text-content class='styled'>
		<h1 class='loud-voice'>App UI</h1>
	</text-content>

	<label class='show-boxes app-data-voice' data-ui='app'>
		<input type='checkbox'>
		Show boxes
	</label>

	<div class='app-ui-board'>

		<section class='app-ui-column' aria-labelledby='take-today'>

			<h2 class='strong-voice' id='take-today'>Today</h2>

			<p class='quiet-voice'>Triggers</p>

			<div class='toolbar' data-ui='app' inert>
				<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

				<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings']) ?>
			</div>

			<p class='quiet-voice'>Triggers, one open</p>

			<div class='toolbar' data-ui='app' inert>
				<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

				<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'expanded' => true]) ?>
			</div>

			<p class='quiet-voice'>Panel</p>

			<div class='app-card' data-ui='app'>
				<div class='panel-scroll settings-panel'>
					<?= partial('settings-rows', [
						'id_suffix' => '-app-ui',
						'page_has_grid' => false,
						'page_controls' => null,
					]) ?>
				</div>
			</div>

		</section>

	</div>

	<?php /* CONTEXT - where the parts meet the page on a phone, the tricky
		part (Derek, 2026-09-25). Every pairing of button style and
		placement, because neither can be judged alone: straddling the bar
		(today), dropped below it, and containing it - each with ringed and
		with ghost (flush) triggers. Flip the scheme or mood with the rows
		above and all six repaint. The placements and takes live in
		app-ui.css only; the live chrome never sees them. */ ?>
	<h2 class='strong-voice context-heading'>Context</h2>

	<?php foreach ($app_ui_takes as $take => $take_name): ?>
		<div class='app-ui-board context-board'>
			<?php foreach ($app_ui_placements as $placement => $placement_name): ?>
				<?= partial('app-ui/phone-context', [
					'placement' => $placement,
					'take' => $take,
					'caption' => $take_name . ' · ' . $placement_name,
					'id_suffix' => '-context-' . $take . '-' . $placement,
				]) ?>
			<?php endforeach; ?>
		</div>
	<?php endforeach; ?>

	<?php /* The frames are a map; the phone is the test. Each link opens
		the real home page with that pairing switched on for the one visit
		(?try=, index.php), so it can be felt on an actual phone - thumb,
		scroll, dim, Safari's bars and all. */ ?>
	<h2 class='strong-voice context-heading'>Try on a phone</h2>

	<ul
		class='try-links'
		role='list'
	>
		<?php foreach ($app_ui_takes as $take => $take_name): ?>
			<?php foreach ($app_ui_placements as $placement => $placement_name): ?>
				<li>
					<a
						class='link'
						href='/?try=<?= $take ?>-<?= $placement ?>'
					><?= $take_name ?> · <?= $placement_name ?></a>
				</li>
			<?php endforeach; ?>
		<?php endforeach; ?>
	</ul>

</div>
