<?php /* Flavor - the color-range axis: which pigment family the current
         mood x character cell wears. Default is each cell's own take; a
         named range (flavors.css) re-pigments only what it states and the
         rest falls through. Slider index -> slug lives in
         scripts/settings-panel.js (FLAVORS); keep max in sync with it. */ ?>
<div class='flavor-switcher' role='group' aria-labelledby='flavor-switcher-label<?= $id_suffix ?? '' ?>'>
	<p class='app-data-voice' id='flavor-switcher-label<?= $id_suffix ?? '' ?>'>Flavor: <span data-flavor-name>Default</span></p>

	<input type='range' min='0' max='3' step='1' value='0' data-set-flavor-slider class='plain-range' aria-label='Color flavor'>
</div>
