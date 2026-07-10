<div class='filter-control' role='group' aria-labelledby='filter-control-label'>
	<p class='app-data-voice' id='filter-control-label'>Filter: <span class='filter-count'><span data-filter-count>0</span> / <span data-filter-total>0</span></span></p>

	<div class='filter-body'>
		<input type='range' min='1' max='6' step='1' value='1' data-set-filter class='plain-range' aria-label='Filter level'>

		<?php /* The tier's descriptive name, under the slider. Hidden on the narrow
		         popover (it would wrap); shown from the 1024px breakpoint where
		         there's room. JS fills it from FILTER_NAMES. */ ?>
		<p class='filter-level-name app-data-voice' data-filter-name></p>

		<?php /* Scaled schematic of the page itself: one column of bars (the
		         milestone list) on phones; on large screens it gains a fake
		         settings panel on the right, mirroring the real layout's rail.
		         JS fills .mini-map-bars, one bar per milestone. */ ?>
		<div class='mini-map' aria-hidden='true'>
			<ol class='mini-map-bars'></ol>

			<div class='mini-map-panel'></div>
		</div>
	</div>
</div>
