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
		part (Derek, 2026-09-25). Same real triggers and card, placed three
		ways in a phone-sized frame over a stand-in page: straddling the
		bar (today), dropped below it, and containing it. Flush (ghost)
		buttons only work where the card can meet the bar cleanly - that is
		what these test. The placements and the ghost take live in
		app-ui.css only; the live chrome never sees them. */ ?>
	<h2 class='strong-voice context-heading'>Context</h2>

	<div class='app-ui-board'>

		<figure class='phone-context' data-ui='app' data-placement='straddle' data-take='ringed'>
			<div class='stand-in-page' aria-hidden='true'></div>

			<div class='context-bar'>
				<div class='toolbar' data-ui='app' inert>
					<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

					<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'expanded' => true]) ?>
				</div>
			</div>

			<div class='app-card context-card' data-ui='app'>
				<div class='panel-scroll settings-panel'>
					<?= partial('settings-rows', ['id_suffix' => '-context-straddle', 'page_has_grid' => false, 'page_controls' => null]) ?>
				</div>
			</div>

			<figcaption class='quiet-voice'>Straddle (today)</figcaption>
		</figure>

		<figure class='phone-context' data-ui='app' data-placement='below' data-take='ghost'>
			<div class='stand-in-page' aria-hidden='true'></div>

			<div class='context-bar'>
				<div class='toolbar' data-ui='app' inert>
					<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

					<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'expanded' => true]) ?>
				</div>
			</div>

			<div class='app-card context-card' data-ui='app'>
				<div class='panel-scroll settings-panel'>
					<?= partial('settings-rows', ['id_suffix' => '-context-below', 'page_has_grid' => false, 'page_controls' => null]) ?>
				</div>
			</div>

			<figcaption class='quiet-voice'>Below</figcaption>
		</figure>

		<figure class='phone-context' data-ui='app' data-placement='contain' data-take='ghost'>
			<div class='stand-in-page' aria-hidden='true'></div>

			<div class='context-bar'>
				<div class='toolbar' data-ui='app' inert>
					<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

					<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'expanded' => true]) ?>
				</div>
			</div>

			<div class='app-card context-card' data-ui='app'>
				<div class='panel-scroll settings-panel'>
					<?= partial('settings-rows', ['id_suffix' => '-context-contain', 'page_has_grid' => false, 'page_controls' => null]) ?>
				</div>
			</div>

			<figcaption class='quiet-voice'>Contain</figcaption>
		</figure>

	</div>

</div>
