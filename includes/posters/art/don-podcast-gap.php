<?php /* THE GAP. The gap drawn as a held emptiness:
   two parentheses framing a wide void, the speaker standing before it,
   the arrow pointing at how far there still is to go. (Redrawn per
   Derek 2026-07-28 - the old take had the accent FILLING the gap,
   which is backwards.) Small band: parens chords at x 580/1020
   (symmetric about 800), arcs y 350-550, dot d100 at left, arrow
   mirroring it at right. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<defs>
		<marker id='don-podcast-gap-arrow' viewBox='0 0 3 4.5' refX='0' refY='2.25'
			markerWidth='3' markerHeight='4.5' markerUnits='strokeWidth' orient='auto'>
			<path d='M0,0 L3,2.25 L0,4.5 Z' fill='context-stroke'/>
		</marker>
	</defs>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)' stroke-linecap='round'>
		<path d='M580,350 A140,140 0 0 0 580,550'/>
		<path d='M1020,350 A140,140 0 0 1 1020,550'/>
	</g>

	<circle cx='400' cy='450' r='50' fill='var(--accent)'/>

	<path d='M1140,450 L1220,450' fill='none' stroke='var(--ink-primary)'
		style='stroke-width: var(--line-width-primary)' stroke-linecap='round'
		marker-end='url(#don-podcast-gap-arrow)'/>
</svg>
