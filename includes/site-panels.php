<?php /*
	The two menu panels (Pages + Settings) - plain fixed-position boxes
	driven by the manual state machine in scripts/settings-panel.js
	(de-popovered 2026-08-11; its state comment carries the why). Both wear
	the same .panel chrome and are placed by placePanel against the
	toolbar's box, so they share one clean edge.

	Included by header.php AFTER the tray closes, never inside it: the
	tray's translateZ(0) makes it the containing block for fixed
	descendants, and these must place against the viewport. Reads
	$page_has_grid from settings-panel.php's scope - include order matters.

	Closed = display:none via .panel:not(.is-open) (settings-panel.css).
	Without JS they never open - a decision, not a gap: the settings are
	dead switches without JS anyway, and the footer's site-map carries the
	same navigation the pages menu does.

	The pages panel is a plain container - the nav landmark lives in the
	partial. */ ?>

<?php /* Each panel's content sits in a .panel-scroll layer (the scroll +
	padding live there, not on .panel, so the perch replicas can straddle
	the panel's top edge unclipped - settings-panel.css). The settings
	scroller doubles as the .settings-panel rows grid; the band instance
	(templates/pages/home.php) wears that class with no scroller, which is
	why it lives here and not on the panel box. The .panel-triggers replica
	cluster is appended to each panel by settings-panel.js - clones, never
	authored here, so they can't drift from the toolbar. */ ?>

<div
	id='pages-menu'
	class='panel'
	data-ui='app'
>
	<div class='panel-scroll'>
		<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug, 'target_query' => $target_query ?? '']) ?>
	</div>
</div>

<div
	id='settings-panel'
	class='panel'
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
