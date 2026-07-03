<?php /*
	Display settings toolbox.
	Trigger + popover panel. Each row inside the panel is its own partial
	in includes/settings/ — comment out an include to remove that row from
	the panel; uncomment to bring it back.

	Switcher rows follow the rigid shape from toolbox-brief.md §4:
		.{kind}-switcher > p#{kind}-switcher-label + buttons[data-set-{kind}]

	Theme is a bundle — it sets the palette AND the type families.
	Typography is not a separate user-facing concept.
*/ ?>

<button
	type='button'
	popovertarget='toolbox-1'
	class='toolbox-trigger settings-trigger'
	style='anchor-name: --toolbox-1;'
	aria-label='Display settings'
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
	id='toolbox-1'
	popover
	class='toolbox-panel settings-panel'
	data-ui='app'
	style='position-anchor: --toolbox-1;'
	aria-label='Display settings'
>
	<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug]) ?>
	<?= partial('settings/mode-switcher') ?>
	<?= partial('settings/theme-switcher') ?>
	<?= partial('settings/sound-switcher') ?>
	<?php if (!empty($page_controls)): ?>
		<?= partial('settings/' . $page_controls) ?>
	<?php endif; ?>
</div>
