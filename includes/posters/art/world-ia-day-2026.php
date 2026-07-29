<?php /* THE DAY. A generic calendar page with the
   one day marked - it's World IA DAY, and Derek ran the day. (Third
   take 2026-07-28: the ripple went to pe-founded's ring family, the
   chaos-to-order take made its point but Derek wanted plain calendar.)
   Grid: page 600x500 at x 500-1100 / y 200-700, centered on 800/450;
   header band h100 filled; body 4x4 cells (150x100) ruled at the
   fine weight; the marked day's dot d60 lands at (875,450) - on the
   frame's midline. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<rect class='roundable' x='500' y='200' width='600' height='100' fill='var(--fill-secondary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-secondary)'>
		<path d='M500,400 L1100,400'/>
		<path d='M500,500 L1100,500'/>
		<path d='M500,600 L1100,600'/>
		<path d='M650,300 L650,700'/>
		<path d='M800,300 L800,700'/>
		<path d='M950,300 L950,700'/>
	</g>

	<rect class='roundable' x='500' y='200' width='600' height='500' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<path d='M500,300 L1100,300' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='875' cy='450' r='30' fill='var(--accent)'/>
</svg>
