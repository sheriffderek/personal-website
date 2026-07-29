<?php /* ECOSYSTEM. Panes coming together dynamically:
   four panes overlap the central one's corners, all transparent so the
   overlaps read as intermixing, accent at the convergence point.
   (Replaced line-node per Derek 2026-07-28 - the idea is bringing the
   panes together, not annotating a line. Refined per Derek's second
   sketch 2026-07-28: the central pane goes OPAQUE and paints on top -
   the satellites tuck behind it - and satellites come in two sizes.)
   
   Grid: central pane 400x300 opaque on the frame center, painted
   last; satellites in two reused measures - large 300x200 (TR/BL),
   small 200x140 (TL/BR, nouveau's panel measure) - placed with
   180-degree rotational symmetry, so the layout is dynamic but
   self-centers on 800/450. Overlaps into the central pane are
   decisive (40-80); satellite-to-satellite clearances 180+. Span
   x 360-1240 sits inside the phone crop. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='480' y='200' width='200' height='140'/>
		<rect class='roundable' x='940' y='180' width='300' height='200'/>
		<rect class='roundable' x='920' y='560' width='200' height='140'/>
		<rect class='roundable' x='360' y='520' width='300' height='200'/>
	</g>

	<rect class='roundable' x='600' y='300' width='400' height='300' fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='800' cy='450' r='50' fill='var(--accent)'/>
</svg>
