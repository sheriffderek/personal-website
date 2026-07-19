<?php /* View - List (the readable spine) or Grid (the wall of work). Only
         rendered when GRID_VIEW_ENABLED (config.php), and only VISIBLE from
         the 1200px breakpoint where the grid exists (styles/layouts/
         grid-view.css) - below that everyone gets the list, though a saved
         grid choice keeps waiting for the next big screen. */ ?>
<div class='view-switcher'>
	<p class='app-data-voice' id='view-switcher-label<?= $id_suffix ?? '' ?>'>Layout</p>

	<div class='mode-button-group' role='radiogroup' aria-labelledby='view-switcher-label<?= $id_suffix ?? '' ?>'>
		<button type='button' role='radio' aria-checked='false' data-set-view='list'>List</button>
		<button type='button' role='radio' aria-checked='false' data-set-view='grid'>Grid</button>
	</div>
</div>
