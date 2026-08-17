<fieldset class='mode-switcher'>
	<legend class='app-data-voice'>Preferred color scheme</legend>

	<div class='mode-button-group' role='radiogroup'>
		<?php /* Label "Auto", value 'system' (Derek, 2026-08-14): the slug is wired
         through storage, the FOUC script, and the JS - the label is just
         what the visitor reads. */ ?>
		<button type='button' role='radio' aria-checked='false' data-set-scheme='system'>Auto</button>
		<button type='button' role='radio' aria-checked='false' data-set-scheme='light'>Light</button>
		<button type='button' role='radio' aria-checked='false' data-set-scheme='dark'>Dark</button>
	</div>
</fieldset>
