<?php /* SHEETS. The import order as actual stylesheets:
   three portrait sheets stacking forward, each on top of the last -
   order IS the methodology, and the front sheet carries the accent.
   (Redrawn from the shingled bars per Derek's markup 2026-07-28.)
   Grid: sheets 300x450 (2:3), offsets 160 across / 40 down (decisive
   140 overlap), span x 490-1110 / y 185-715 centered on 800/450; dot
   d100 at the front sheet's center. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='490' y='185' width='300' height='450'/>
		<rect class='roundable' x='650' y='225' width='300' height='450'/>
		<rect class='roundable' x='810' y='265' width='300' height='450'/>
	</g>

	<circle cx='960' cy='490' r='50' fill='var(--accent)'/>
</svg>
