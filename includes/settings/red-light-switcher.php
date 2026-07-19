<?php /* Red light - a boolean override (Off/On), NOT a mood. It stomps all
         color regardless of character or mood - the forced-colors party trick.
         Off/On radiogroup like Interface sounds; wired via SWITCHERS in
         scripts/settings-panel.js (valuelessAttr: 'on' = bare data-red-light).
         Sits last in the panel - it overrides everything above it. */ ?>
<div class='red-light-switcher'>
	<p class='app-data-voice' id='red-light-switcher-label<?= $id_suffix ?? '' ?>'>Red light</p>

	<div class='mode-button-group' role='radiogroup' aria-labelledby='red-light-switcher-label<?= $id_suffix ?? '' ?>'>
		<button type='button' role='radio' aria-checked='false' data-set-red-light='off'>Off</button>
		<button type='button' role='radio' aria-checked='false' data-set-red-light='on'>On</button>
	</div>
</div>
