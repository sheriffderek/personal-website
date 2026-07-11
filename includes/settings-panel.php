<?php /*
	Two menus, two triggers, two popovers (native <popover>, CSS anchor-positioned):

	  Settings (sliders glyph) — theme/scheme/sound + per-page contextual controls
	    (the timeline filter rides here on the home page).
	  Pages (hamburger glyph) — site navigation + (future) per-page contextual links.

	Split out of one panel because page links + settings + filter together got too
	tall. Each row inside a menu is its own partial in includes/settings/.

	The Pages menu is commented out for now: How I work / Now / Contact are still
	placeholders, so home is the only finished page and a nav to it is noise. The
	routes and templates are untouched - uncomment when those pages are real.
*/ ?>

<?php /* Grid view exists only where the timeline does - the page carrying
	the timeline's own controls (home). Everywhere else the invite and the
	Layout row would be doors to nothing. */ ?>
<?php $page_has_grid = GRID_VIEW_ENABLED && ($page_controls ?? null) === 'filter-control'; ?>

<?php /* ---- Grid invite ----
	A little toggle that only exists where the grid exists (>= 1600px, list
	view) and pulses "touch me" until first used - discovery for the magic.
	Chrome/visibility/pulse live in styles/layouts/grid-view.css; wiring in
	settings-panel.js (view section). */ ?>
<?php if ($page_has_grid): ?>
	<button
		type='button'
		class='toolbox-trigger settings-trigger grid-invite'
		data-grid-invite
		aria-label='Switch to the grid layout'
	>
		<span aria-hidden='true'>
			<svg
				class='toolbox-glyph'
				viewBox='0 0 16 16'
				fill='none'
				stroke='currentColor'
				stroke-width='1.4'
				stroke-linecap='round'
				focusable='false'
			>
				<rect x='2.6' y='2.6' width='4.5' height='4.5' rx='0.9' />

				<rect x='8.9' y='2.6' width='4.5' height='4.5' rx='0.9' />

				<rect x='2.6' y='8.9' width='4.5' height='4.5' rx='0.9' />

				<rect x='8.9' y='8.9' width='4.5' height='4.5' rx='0.9' />
			</svg>
		</span>
	</button>
<?php endif; ?>

<?php /* ---- Settings menu ---- */ ?>
<button
	type='button'
	popovertarget='menu-settings'
	class='toolbox-trigger settings-trigger'
	aria-expanded='false'
	aria-label='Display settings'
>
	<span aria-hidden='true'>
		<svg
			class='toolbox-glyph'
			viewBox='0 0 16 16'
			fill='none'
			stroke='currentColor'
			stroke-width='1.4'
			stroke-linecap='round'
			focusable='false'
		>
			<line x1='2.5' y1='5' x2='13.5' y2='5' />

			<circle cx='10' cy='5' r='1.9' fill='currentColor' stroke='none' />

			<line x1='2.5' y1='11' x2='13.5' y2='11' />

			<circle cx='6' cy='11' r='1.9' fill='currentColor' stroke='none' />
		</svg>
	</span>
</button>

<div
	id='menu-settings'
	popover
	class='toolbox-panel settings-panel'
	data-ui='app'
	aria-label='Display settings'
>
	<?= partial('settings/mode-switcher') ?>
	<?= partial('settings/brand-switcher') ?>
	<?= partial('settings/emphasis-switcher') ?>
	<?php if ($page_has_grid): ?>
		<?= partial('settings/view-switcher') ?>
	<?php endif; ?>
	<?= partial('settings/sound-switcher') ?>
	<?php if (!empty($page_controls)): ?>
		<?= partial('settings/' . $page_controls) ?>
	<?php endif; ?>
</div>

<?php /* ---- Pages menu - disabled until the sub-pages are real ----

<button
	type='button'
	popovertarget='menu-pages'
	class='toolbox-trigger settings-trigger'
	aria-expanded='false'
	aria-label='Pages'
>
	<span aria-hidden='true'>
		<svg class='toolbox-glyph' viewBox='0 0 16 16' focusable='false'>
			<rect class='glyph-line glyph-line-1' />
			<rect class='glyph-line glyph-line-2' />
			<rect class='glyph-line glyph-line-3' />
		</svg>
	</span>
</button>

<div
	id='menu-pages'
	popover
	class='toolbox-panel settings-panel'
	data-ui='app'
	aria-label='Pages'
>
	<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug, 'target_query' => $target_query ?? '']) ?>
</div>

---- end Pages menu ---- */ ?>
