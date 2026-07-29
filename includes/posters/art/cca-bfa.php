<?php /* FOUNDATIONS. Circle, square, triangle overlapping: art
   school, where the shapes started. Default flavor. (Accent moved
   below the square per Derek's markup 2026-07-28, and the poster got
   its grid pass at the same time - it predated the law.) Grid:
   circle d300 at (760,370); square 220 at 860,360 - the circle
   reaches 50 into it; triangle base 300 / height 260 (equilateral),
   apex 47 inside the circle; accent d60 below the square (40 gap),
   its underside sharing the triangle's y=680 baseline. Span
   x 520-1080 / y 220-680 centered on 800/450. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<circle cx='760' cy='370' r='150'/>
		<rect class='roundable' x='860' y='360' width='220' height='220'/>
		<path d='M520,680 L670,420 L820,680 Z'/>
	</g>

	<circle cx='930' cy='650' r='30' fill='var(--accent)'/>
</svg>
