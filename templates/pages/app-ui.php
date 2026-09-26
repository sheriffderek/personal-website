<?php /* The app-ui playground - every chrome part in every style, one page
	(Derek, 2026-09-25). Lives under /design-system/app-ui and is linked
	from /design-system - the design-system proof is public (Derek).

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
		(today), dropped below it, containing it, and floating free of it
		(the Claude/ChatGPT menu) - each with Ring and
		with Ghost (flush) triggers. Flip the scheme or mood with the rows
		above and every frame repaints. The placements and takes live in
		app-ui.css only; the live chrome never sees them. */ ?>
	<?php /* FAMILIES - a whole family painted down the whole panel:
		triggers, card, pills, sliders, in a phone frame at its chosen
		placement, light and dark side by side. Interface first (Claude
		Code light, Linear dark, the flat-ring edge - the contract in
		settings-panel.css). Token values only, app-ui.css FAMILIES. */ ?>
	<h2 class='strong-voice context-heading'>Families</h2>

	<div class='app-ui-board context-board'>
		<?= partial('app-ui/phone-context', ['placement' => 'float', 'take' => 'ghost', 'family' => 'interface', 'scheme' => 'light', 'caption' => 'Interface · light (Claude Code)', 'id_suffix' => '-family-interface-light']) ?>

		<?= partial('app-ui/phone-context', ['placement' => 'float', 'take' => 'ghost', 'family' => 'interface', 'scheme' => 'dark', 'caption' => 'Interface · dark (Linear)', 'id_suffix' => '-family-interface-dark']) ?>
	</div>

	<?php /* MENU - the real pages menu, painted after real products. Each
		example is only token values on the menu's slots (link, row, icon,
		card) - app-ui.css, MENU FAMILIES - so a family can't move a row,
		only paint it. "Now" is shown as the current page. Inert: they show
		how it looks, the live menu is the one to use. */
		$menu_families = [
			'today' => 'Today',
			'classic' => 'Classic web (Wikipedia)',
			'finder' => 'Finder (macOS)',
			'shadcn' => 'shadcn dropdown',
			'reveal' => 'Reveal (Claude, Linear)',
			'claude-code' => 'Claude Code (desktop app)',
			'win95' => 'Windows 95',
			'terminal' => 'Terminal',
		]; ?>

	<h2 class='strong-voice context-heading'>Menu</h2>

	<div class='app-ui-board context-board'>
		<?php foreach ($menu_families as $family => $family_name): ?>
			<figure
				class='menu-example'
				data-family='<?= $family ?>'
				inert
			>
				<figcaption class='quiet-voice'><?= $family_name ?></figcaption>

				<div
					class='app-card'
					data-ui='app'
				>
					<div class='panel-scroll'>
						<h2 class='app-data-voice panel-heading'>Menu</h2>

						<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => 'now', 'target_query' => '']) ?>
					</div>
				</div>
			</figure>
		<?php endforeach; ?>
	</div>

	<?php /* What the real-phone test ruled out (Derek, 2026-09-25) - kept
		in the grid as evidence, labeled so they never read as options. */
		$ruled_out = ['ghost-straddle', 'ring-below', 'ghost-below']; ?>

	<h2 class='strong-voice context-heading'>Context</h2>

	<?php foreach ($app_ui_takes as $take => $take_name): ?>
		<div class='app-ui-board context-board'>
			<?php foreach ($app_ui_placements as $placement => $placement_name): ?>
				<?= partial('app-ui/phone-context', [
					'placement' => $placement,
					'take' => $take,
					'caption' => $take_name . ' · ' . $placement_name . (in_array($take . '-' . $placement, $ruled_out, true) ? ' - ruled out' : ''),
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
