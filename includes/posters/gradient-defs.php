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
   paint servers in some engines.

   The zero size MUST be in the inline style, not just the attributes:
   setup.css gives every svg width: 100%, and CSS beats attributes - so the
   attribute-only version silently inflated to full viewport width and,
   sitting 1rem into main, ended 16px past the right edge. WebKit extends
   the page's scroll canvas for that; Blink doesn't - THE iOS-only
   horizontal-scroll bug on the home page (2026-08-12). */ ?>
<svg class='poster-gradient-defs' style='position: absolute; width: 0; height: 0' aria-hidden='true' focusable='false'>
	<defs>
		<linearGradient id='poster-ramp' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--poster-ramp-from, var(--fill-secondary))'/>
			<stop offset='1' style='stop-color: var(--poster-ramp-to, var(--fill-secondary))'/>
		</linearGradient>

		<?php /* House x technical's color (Derek, 2026-08-23, refined same
		   day): NOT one full rainbow - at chip size the whole spectrum is
		   noise. Instead the rainbow is cut into analogous SLICES, one per
		   variant slot (the door rules in flavors.css assign them): big
		   masses show a real fade, small chips read near-solid, and the
		   wall's slices add up to the spectrum. Fixed stops on purpose -
		   these ARE House's pigment statement. */ ?>
		<linearGradient id='poster-slice-warm' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--color-fuchsia-500)'/>
			<stop offset='1' style='stop-color: var(--color-amber-400)'/>
		</linearGradient>

		<linearGradient id='poster-slice-cool' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--color-teal-400)'/>
			<stop offset='1' style='stop-color: var(--color-sky-400)'/>
		</linearGradient>

		<linearGradient id='poster-slice-stone' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--color-sky-400)'/>
			<stop offset='1' style='stop-color: var(--color-indigo-400)'/>
		</linearGradient>

		<linearGradient id='poster-slice-moss' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--color-emerald-400)'/>
			<stop offset='1' style='stop-color: var(--color-teal-400)'/>
		</linearGradient>

		<linearGradient id='poster-slice-rose' x1='0' y1='0' x2='1' y2='1'>
			<stop offset='0' style='stop-color: var(--color-indigo-400)'/>
			<stop offset='1' style='stop-color: var(--color-fuchsia-500)'/>
		</linearGradient>
	</defs>
</svg>
