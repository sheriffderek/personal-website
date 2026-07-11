<?php /* Brand - the structural axis (type pair, corners, scale rhythm).
         Four surfaces of one organization: Personal (default), Marketing,
         Product, Documentation. Slider index -> slug lives in
         scripts/settings-panel.js (BRANDS); keep max in sync with it. */ ?>
<div class='brand-switcher' role='group' aria-labelledby='brand-switcher-label'>
	<p class='app-data-voice' id='brand-switcher-label'>Brand: <span data-brand-name>Personal</span></p>

	<input type='range' min='0' max='3' step='1' value='0' data-set-brand-slider class='plain-range' aria-label='Brand'>
</div>
