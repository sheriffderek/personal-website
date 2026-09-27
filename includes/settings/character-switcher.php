<?php /* Character - the structural axis (type pair, corners, scale rhythm).
         Five surfaces of one company (the mapping lives in the header of
         styles/settings/characters.css), in slider order: Marketing, Product,
         Interface, Editorial, Terminal. The thumb STARTS on Product (index
         1, the default look) so the first paint matches what the JS will
         reflect. Slider index -> slug lives in scripts/settings-panel.js
         (CHARACTERS); keep max and the starting value in sync with it. */ ?>
<div class='character-switcher' role='group' aria-labelledby='character-switcher-label<?= $id_suffix ?? '' ?>'>
	<p class='app-data-voice' id='character-switcher-label<?= $id_suffix ?? '' ?>'>Character: <span data-character-name>Product</span></p>

	<input type='range' min='0' max='4' step='any' value='1' data-set-character-slider class='plain-range' aria-label='Brand character'>
</div>
