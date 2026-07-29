<?php /* FLASHCARDS. What study hall actually is: a quiz
   system keyed to where you are in the course, built from progress
   nodes. A deck of flashcards; the front card shows the prompt bar
   and the accent as the answer. (Fourth take per Derek 2026-07-28;
   scaled up and simplified the same day - the tiny progress nodes
   were finer detail than anything else in the family.) Grid: cards
   560x360 stacked at (60,50) offsets - back (460,220), mid (520,270),
   front (580,320) - opaque so the stack occludes; span x 460-1140 /
   y 220-680 centered on 800/450; prompt bar 280x60 inset 60; answer
   dot d100 at 60 from the front card's corner. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='460' y='220' width='560' height='360'/>
		<rect class='roundable' x='520' y='270' width='560' height='360'/>
		<rect class='roundable' x='580' y='320' width='560' height='360'/>
	</g>

	<rect class='roundable' x='640' y='380' width='280' height='60' fill='var(--fill-secondary)'/>

	<circle cx='1030' cy='570' r='50' fill='var(--accent)'/>
</svg>
