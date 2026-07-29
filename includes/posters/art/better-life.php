<?php /* HABIT CHECKS. The tracker row: done, done, today,
   then the open days. (Checkpoint squares moved over from
   pe-self-paced 2026-07-28 - Derek: checkboxes read habit tracking;
   the network went to pe-study-hall.) Grid: squares 120 on a 180
   pitch (gap 60), span x 380-1220 / y 390-510 centered on 800/450;
   today's dot d60 = half the square, at dead center of the frame. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='380' y='390' width='120' height='120' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='560' y='390' width='120' height='120' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='740' y='390' width='120' height='120' fill='none'/>
		<rect class='roundable' x='920' y='390' width='120' height='120' fill='none'/>
		<rect class='roundable' x='1100' y='390' width='120' height='120' fill='none'/>
	</g>

	<circle cx='800' cy='450' r='30' fill='var(--accent)'/>
</svg>
