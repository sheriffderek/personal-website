<?php /* Character - the structural axis (type pair, corners, scale rhythm).
         Three lanes of one person's work: Product (default - the personal /
         Stripe-ish lane), Marketing (the GoFundMe brochure lane), Interface
         (the Claude/OpenAI dashboard lane). Slider index -> slug lives in
         scripts/settings-panel.js (CHARACTERS); keep max in sync with it. */ ?>
<div class='character-switcher' role='group' aria-labelledby='character-switcher-label<?= $id_suffix ?? '' ?>'>
	<p class='app-data-voice' id='character-switcher-label<?= $id_suffix ?? '' ?>'>Character: <span data-character-name>Product</span></p>

	<input type='range' min='0' max='2' step='1' value='0' data-set-character-slider class='plain-range' aria-label='Brand character'>
</div>
