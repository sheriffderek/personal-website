<?php /* THE AUDIT. A page wireframe mid-audit: solid
   outlines are settled, dashed outlines are regions under review,
   the accent dot flags the finding, and one block is already fixed
   (filled). (Redrawn from nested frames per Derek's sketch
   2026-07-28.) Grid: outer page 800x500 centered on 800/450 with 60
   insets all around; two columns (440 / 200) and rows of 100 with 40
   gaps everywhere; dashes 24/20 round. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='400' y='200' width='800' height='500'/>
		<rect class='roundable' x='460' y='260' width='440' height='100'/>
	</g>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-secondary)' stroke-linecap='round' stroke-dasharray='24 20'>
		<rect class='roundable' x='940' y='260' width='200' height='100'/>
		<rect class='roundable' x='460' y='400' width='440' height='240'/>
		<rect class='roundable' x='940' y='400' width='200' height='100'/>
	</g>

	<rect class='roundable' x='940' y='540' width='200' height='100' fill='var(--fill-secondary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='680' cy='520' r='30' fill='var(--accent)'/>
</svg>
