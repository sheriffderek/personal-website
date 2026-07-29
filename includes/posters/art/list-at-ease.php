<?php /* CHECKLIST. The product: a complex form crossed
   with education, walking a homeowner through the sale step by step.
   Top-down rows of checkbox + line: done, done, you are here, still
   ahead. (Replaced the parts bin per Derek 2026-07-28; bars made even
   and ink-solid per his markup the same day.) Grid: boxes 80 on a 140
   row pitch (rows y 200/340/480/620, span centered on 450); bars all
   400x40 ink - h40 = half the box, 40 gap from the box; the current
   step's dot d40 = half the box. Span x 540-1060, centered on 800. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='540' y='200' width='80' height='80' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='540' y='340' width='80' height='80' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='540' y='480' width='80' height='80' fill='none'/>
		<rect class='roundable' x='540' y='620' width='80' height='80' fill='none'/>
	</g>

	<g fill='var(--ink-primary)'>
		<rect class='roundable' x='660' y='220' width='400' height='40'/>
		<rect class='roundable' x='660' y='360' width='400' height='40'/>
		<rect class='roundable' x='660' y='500' width='400' height='40'/>
		<rect class='roundable' x='660' y='640' width='400' height='40'/>
	</g>

	<circle cx='580' cy='520' r='20' fill='var(--accent)'/>
</svg>
