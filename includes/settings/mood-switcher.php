<?php /* Mood - the color axis (a full palette repainted onto the same semantic
         slots; type and shape never change here). Three moods, in order:
         Expressive (default), Technical, Quiet. Slider index -> slug lives in
         scripts/settings-panel.js (MOODS); keep max in sync with it. */ ?>
<div class='mood-switcher' role='group' aria-labelledby='mood-switcher-label<?= $id_suffix ?? '' ?>'>
	<p class='app-data-voice' id='mood-switcher-label<?= $id_suffix ?? '' ?>'>Mood: <span data-mood-name>Expressive</span></p>

	<input type='range' min='0' max='2' step='1' value='0' data-set-mood-slider class='plain-range' aria-label='Brand mood'>
</div>
