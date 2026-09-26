<?php /* One trigger - the round chrome button in the toolbar (and on the
	/app-ui playground). Dumb: everything arrives as props, nothing is read
	from the page.

	Callers pass (via partial()):
	  label  what a screen reader hears (aria-label) - the button has no
	         visible text, the glyph is the face
	  glyph  a file in includes/app-ui/glyphs/ (pages, settings, grid, to-top)
	  panel  optional - the id of the panel this button opens; wires
	         data-panel, aria-controls, and the closed aria-expanded state
	         the panel state machine in settings-panel.js flips
	  class  optional - an extra class beside .trigger (grid-invite)
	  hook   optional - a bare data attribute the JS finds the button by
	         (data-grid-invite, data-to-top) */ ?>
<button
	type='button'
	<?php if (!empty($panel)): ?>
		data-panel='<?= $panel ?>'
		aria-controls='<?= $panel ?>'
	<?php endif; ?>
	class='trigger<?= !empty($class) ? ' ' . $class : '' ?>'
	<?php if (!empty($panel)): ?>
		aria-expanded='false'
	<?php endif; ?>
	<?php if (!empty($hook)): ?>
		<?= $hook ?>
	<?php endif; ?>
	aria-label='<?= $label ?>'
>
	<span aria-hidden='true'>
		<?php include INCLUDES_DIR . '/app-ui/glyphs/' . $glyph . '.php'; ?>
	</span>
</button>
