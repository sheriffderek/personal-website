<?php /* COLLECTIONS. Two variable collections feed one
   theme layer; a choice point (the mode toggle - the accent, the one focal
   mark) forks it into the same card painted two ways: inked with a paper
   button, paper with an inked button. From Derek's sketches (2026-09-29).
   Medium band. Grid: collections x 100-400 on rows y 220-420 / 480-680
   (gap 60), shared by the two cards at x 1200-1500; the theme layer is a
   quiet mass at x 600-900, y 300-600; the choice point is a d100 on
   (1000, 450), joined to the layer by a 50 stub. Everything centers on
   800 / 450. Inbound arrows are straight, start inside their collection
   and land inside the layer; the forks leave from under the circle and
   arrive level, joining the cards' edges the way the stub joins the layer
   (no heads - Derek, 2026-09-29: the choice point already says which
   way). Chevron legs are 40 long at 40 degrees off the shaft, rounded to
   whole units. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='100' y='220' width='300' height='200'/>
		<rect class='roundable' x='100' y='480' width='300' height='200'/>
		<rect class='roundable' x='1200' y='480' width='300' height='200'/>
	</g>

	<rect class='roundable' x='600' y='300' width='300' height='300' fill='var(--fill-secondary)'/>

	<rect class='roundable' x='1200' y='220' width='300' height='200' fill='var(--ink-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<rect class='roundable' x='1340' y='340' width='120' height='40' fill='var(--fill-primary)'/>
	<rect class='roundable' x='1340' y='600' width='120' height='40' fill='var(--ink-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)' stroke-linecap='round'>
		<path d='M300,320 L720,400'/>
		<path d='M685,420 L720,400 L695,369'/>

		<path d='M300,580 L780,500'/>
		<path d='M754,530 L780,500 L746,480'/>

		<path d='M900,450 L1000,450'/>

		<path d='M1000,450 C1100,450 1100,320 1200,320'/>

		<path d='M1000,450 C1100,450 1100,580 1200,580'/>
	</g>

	<circle cx='1000' cy='450' r='50' fill='var(--accent)'/>
</svg>
