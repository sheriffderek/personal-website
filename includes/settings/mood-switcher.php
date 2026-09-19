<?php /* Mood - the color axis (a full palette repainted onto the same semantic
         slots; type and shape never change here). Three moods, in slider
         order: Quiet (the starting mood, so the thumb starts at the left like
         every other slider), Expressive (the CSS default), Technical. Slider index -> slug
         lives in scripts/settings-panel.js (MOODS); keep max in sync with it.
         The thumb and label START at DEFAULT_MOOD (config.php) so the first
         paint already matches what the JS will reflect. */
	$mood_names = ['quiet' => 'Quiet', 'expressive' => 'Expressive', 'technical' => 'Technical'];
	$mood_start = array_search(DEFAULT_MOOD, array_keys($mood_names));
?>
<div class='mood-switcher' role='group' aria-labelledby='mood-switcher-label<?= $id_suffix ?? '' ?>'>
	<p class='app-data-voice' id='mood-switcher-label<?= $id_suffix ?? '' ?>'>Mood: <span data-mood-name><?= $mood_names[DEFAULT_MOOD] ?></span></p>

	<input type='range' min='0' max='2' step='any' value='<?= $mood_start ?>' data-set-mood-slider class='plain-range' aria-label='Brand mood'>
</div>
