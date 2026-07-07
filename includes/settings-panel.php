<?php /*
	Two menus, two triggers, two popovers (native <popover>, CSS anchor-positioned):

	  Settings (sliders glyph) — theme/scheme/sound + per-page contextual controls
	    (the timeline filter rides here on the home page).
	  Pages (hamburger glyph) — site navigation + (future) per-page contextual links.

	Split out of one panel because page links + settings + filter together got too
	tall. Each row inside a menu is its own partial in includes/settings/.
*/ ?>

<?php /* ---- Settings menu ---- */ ?>
<button
	type='button'
	popovertarget='menu-settings'
	class='toolbox-trigger settings-trigger'
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
	<?= partial('settings/theme-switcher') ?>
	<?= partial('settings/sound-switcher') ?>
	<?php if (!empty($page_controls)): ?>
		<?= partial('settings/' . $page_controls) ?>
	<?php endif; ?>
</div>

<?php /* ---- Pages menu ---- */ ?>
<button
	type='button'
	popovertarget='menu-pages'
	class='toolbox-trigger settings-trigger'
	aria-label='Pages'
>
	<span aria-hidden='true'>
		<svg class='toolbox-glyph' viewBox='0 0 16 16'>
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
	<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug]) ?>
</div>
