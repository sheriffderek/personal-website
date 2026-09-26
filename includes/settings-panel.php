<?php /*
	Two menus, two triggers, two panels (absolute children of the tray, placed
	by CSS per posture; open/close is the manual state machine in
	scripts/settings-panel.js - de-popovered 2026-08-11):

	  Pages (hamburger glyph) — site navigation. The PRIMARY member: every
	    page, every scroll depth, first in the toolbar's DOM so the corner
	    rules land it corner-most. The hamburger glyph is reserved for it -
	    only real navigation may wear it.
	  Settings (sliders glyph) — theme/scheme/sound + per-page contextual
	    controls (the timeline filter rides here on the home page).

	Split into two panels because page links + settings + filter together got
	too tall. Each row inside the settings is its own partial in
	includes/settings/.
*/ ?>

<?php /* Grid view exists only where the timeline does - the page carrying
	the timeline's own controls (home). Everywhere else the invite and the
	Layout row would be doors to nothing. */ ?>
<?php $page_has_grid = GRID_VIEW_ENABLED && ($page_controls ?? null) === 'filter-control'; ?>


<?php /* ---- The toolbar ----
	One flex parent for the trigger glyphs so they arrange as a GROUP (this is
	the "these all need to be in a parent" note that used to live here).

	It earns its keep twice over: it lets the triggers be styled/spaced as a set
	independent of the tray, and its axis (flex-direction) tracks where panels
	open - row means the group sits above the content so panels sit BELOW it;
	column means it sits beside, so panels open in-column or BESIDE. The tray
	is the panels' containing block; the toolbar owns ARRANGEMENT.

	The panels stay OUTSIDE the toolbar (siblings, below) so the toolbar can
	stack ABOVE them - the circles ride clear of the card's top edge. */ ?>
<div class='toolbar'>

<?php /* DOM order is IMPORTANCE order, primary first (the toolbar's
	row-reverse puts the first member in the corner-most slot - see the locked
	rules on .toolbar in styles/modules/settings-panel.css). The rank: pages
	menu (primary) > settings (secondary) > contextual extras (grid invite,
	back-to-top). A new trigger is inserted at the rank it earns, never
	appended to the end. */ ?>

<?php /* ---- Pages menu (PRIMARY) ---- */ ?>
<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages', 'panel' => 'pages-menu']) ?>

<?php /* ---- Settings menu ----
	Behind SETTINGS_ENABLED (config.php), together with its panel below. */ ?>
<?php if (SETTINGS_ENABLED): ?>
	<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'panel' => 'settings-panel']) ?>
<?php endif; ?>

<?php /* ---- Grid invite (contextual) ----
	The door INTO grid view - one-way, by design (the way back out is the
	settings panel's Layout row). Only exists where the grid exists and only
	in list view; pulses "touch me" until first used - discovery for the
	magic. Chrome/visibility/pulse live in styles/layouts/grid-view.css;
	wiring in settings-panel.js (view section). */ ?>
<?php if ($page_has_grid): ?>
	<?= partial('app-ui/trigger', ['label' => 'View as grid', 'glyph' => 'grid', 'class' => 'grid-invite', 'hook' => 'data-grid-invite']) ?>
<?php endif; ?>

<?php /* ---- Back to top (contextual, last) ----
	A reveal member: hidden everywhere except grid view >= 1450 after the
	settings band has scrolled away (data-scrolled on <html>) - the moment
	its job exists. Visibility lives in grid-view.css; wiring in
	settings-panel.js. */ ?>
<?php if ($page_has_grid): ?>
	<?= partial('app-ui/trigger', ['label' => 'Back to top', 'glyph' => 'to-top', 'hook' => 'data-to-top']) ?>
<?php endif; ?>

</div><?php /* end .toolbar */ ?>

<?php /* ---- The panels ----
	Absolute children of the tray, siblings of the toolbar - a button opens
	an element just below it, placed per posture by CSS (.panel in
	settings-panel.css), driven by the manual state machine in
	settings-panel.js. Sticky positioning makes the tray the containing
	block, so the browser keeps panel and toolbar together natively -
	no placement JS. Each panel's content sits in a .panel-scroll layer
	(scroll + padding live there; the settings scroller doubles as the
	.settings-panel rows grid, which the band instance wears with no
	scroller - templates/pages/home.php). Closed = display:none via
	.panel:not(.is-open). Without JS they never open - a decision, not a
	gap: the settings are dead switches without JS anyway, and the footer's
	site-map carries the same navigation the pages menu does.

	The pages panel is a plain container - the nav landmark lives in the
	partial. */ ?>

<div
	id='pages-menu'
	class='panel app-card'
	data-ui='app'
>
	<div class='panel-scroll'>
		<h2 class='app-data-voice panel-heading'>Menu</h2>

		<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug, 'target_query' => $target_query ?? '']) ?>
	</div>
</div>

<?php if (SETTINGS_ENABLED): ?>
	<div
		id='settings-panel'
		class='panel app-card'
		data-ui='app'
		aria-label='Display settings'
	>
		<div class='panel-scroll settings-panel'>
			<?= partial('settings-rows', [
				'id_suffix' => '',
				'page_has_grid' => $page_has_grid,
				'page_controls' => $page_controls ?? null,
			]) ?>
		</div>
	</div>
<?php endif; ?>

<?php /* (The corner island used to float here - retired with the lab port.
	Its jobs moved into the tray itself: the tray is a sticky column that rides
	the page, and settings / back-to-top are REVEAL members that appear via
	data-scrolled once the settings band leaves the viewport. Same conditional-
	cluster idea, no second floating chrome to maintain.) */ ?>
