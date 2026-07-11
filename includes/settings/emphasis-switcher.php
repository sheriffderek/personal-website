<?php /* Emphasis - the color axis (a full palette repainted onto the same
         semantic slots; type and shape never change here). Slider index ->
         slug lives in scripts/settings-panel.js (EMPHASES); keep max in sync
         with it. */ ?>
<div class='emphasis-switcher' role='group' aria-labelledby='emphasis-switcher-label'>
	<p class='app-data-voice' id='emphasis-switcher-label'>Emphasis: <span data-emphasis-name>Default</span></p>

	<input type='range' min='0' max='2' step='1' value='0' data-set-emphasis-slider class='plain-range' aria-label='Emphasis'>
</div>
