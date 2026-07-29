<?php /* The shared poster gradient paint server (built 2026-07-28 for the
   technical cell's "gradients on the shapes" rule - the sheet's 70% paper /
   15% gradient split). SVG masses can't take CSS background gradients, so a
   mass opts in by painting fill: url(#poster-ramp) (the cell rule in
   flavors.css does this for every secondary-fill mass; art files stay
   untouched).

   The stops read --poster-ramp-from / --poster-ramp-to at THIS svg's own
   scope - a paint server resolves var() where it lives, not at the
   referencing shape - so the ramp is tuned per mood/cell via html-level
   tokens and is the SAME ramp across the wall (which is the point: one
   analogous system). If a cell ever needs per-flavor ramps, it adds named
   defs here. Zero-size but never display:none - hidden svgs can drop their
   paint servers in some engines. */ ?>
<svg class='poster-gradient-defs' width='0' height='0' style='position: absolute' aria-hidden='true' focusable='false'>
	<defs>
		<linearGradient id='poster-ramp' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--poster-ramp-from, var(--fill-secondary))'/>
			<stop offset='1' style='stop-color: var(--poster-ramp-to, var(--fill-secondary))'/>
		</linearGradient>
	</defs>
</svg>
