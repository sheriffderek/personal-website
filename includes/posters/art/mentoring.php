<?php /* ONE-ON-ONE. Two people the same size (equals),
   connected directly; the mentee wears the growth ring. (Per Derek's
   markup 2026-07-28 - was a big circle and a small one, which read
   as unequal.) Grid: both dots d120 on the y=450 axis, centers 360
   apart (mentor 560, mentee 920); ring r180 concentric on the
   mentee; connector runs edge to edge (620-860), crossing the ring
   deliberately; span x 500-1100 centered on 800. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<!-- Growth ring is quiet (ink-secondary, fine weight - Derek
	     2026-07-28), so it also breathes on hover. -->
	<circle cx='920' cy='450' r='180' fill='none' stroke='var(--ink-secondary)' style='stroke-width: var(--line-width-secondary)'/>

	<path d='M620,450 L860,450' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)' stroke-linecap='round'/>

	<circle cx='560' cy='450' r='60' fill='var(--ink-primary)'/>

	<circle cx='920' cy='450' r='60' fill='var(--accent)'/>
</svg>
