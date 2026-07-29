<?php /* FRAME OPTIONS. The product is the frames
   themselves: the same figure wearing three nested frames at once,
   like mat options around a print (per Derek 2026-07-28; sized to
   medium the same day - the outer frame at y 200-700 sits with the
   band's exact 50 padding, so the composition ported unchanged).
   Grid: exact 40 insets between frames (inner 500x340, middle
   580x420, outer 660x500, all centered on 800/450); weights alternate
   fine / bold / fine so the outer and inner frames breathe on hover.
   Mountain is equilateral (base 300, height 260) with symmetric 40s
   above the apex and below the base; sun d100, clear of everything. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)'>
		<rect class='roundable' x='470' y='200' width='660' height='500' style='stroke-width: var(--line-width-secondary)'/>
		<rect class='roundable' x='510' y='240' width='580' height='420' style='stroke-width: var(--line-width-primary)'/>
		<rect class='roundable' x='550' y='280' width='500' height='340' style='stroke-width: var(--line-width-secondary)'/>
	</g>

	<path d='M650,580 L800,320 L950,580' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)' stroke-linecap='round'/>

	<circle cx='960' cy='380' r='50' fill='var(--accent)'/>
</svg>
