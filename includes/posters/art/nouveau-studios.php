<?php /* LAYERS. A layered system stepping into depth:
   panels cascading down-right inside the frame, the deepest one lit.
   (Was the small "arrival" road; promoted to medium and redrawn per
   Derek's layered/system reference 2026-07-28 - it was a big job and
   the poster should carry that weight; revised to Derek's second
   markup the same day; final form per his fourth mock 2026-07-28 -
   NO container at all.) Just the stack: three layers cascading
   down-right and deepening into ink - quiet, quieter, loud - with
   the accent observing from the left. Grid: panels 300x260 at
   constant (60,60) offsets - back secondary (670,260), mid primary
   (730,320), front ink (790,380), both back layers wearing the fine
   stroke; dot d100 on the y=450 axis, 60 clear of the back panel.
   Span x 510-1090 / y 260-640 centered on 800/450. Medium band. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<rect class='roundable' x='670' y='260' width='300' height='260' fill='var(--fill-secondary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-secondary)'/>

	<rect class='roundable' x='730' y='320' width='300' height='260' fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-secondary)'/>

	<rect class='roundable' x='790' y='380' width='300' height='260' fill='var(--ink-primary)'/>

	<circle cx='560' cy='450' r='50' fill='var(--accent)'/>
</svg>
