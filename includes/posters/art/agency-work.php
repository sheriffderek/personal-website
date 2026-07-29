<?php /* SCATTER. Range: different kinds of shapes, one hand. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<defs>
		<pattern id='agency-work-tex' width='56' height='56' patternUnits='userSpaceOnUse'>
			<g fill='none' stroke='var(--ink-secondary)' style='stroke-width: var(--line-width-secondary)' stroke-linecap='round'>
				<path d='M12,16 L20,8'/>
				<path d='M38,44 L46,36'/>
			</g>
		</pattern>
	</defs>

	<!-- Messy desk with good contrast (Derek's third note
	     2026-07-28 - the tidy 2x2 was too aligned): the square
	     lies ON the texture like paper on a desk pad, the
	     triangle's apex sits 59 inside the circle so their lines
	     cross, the accent floats loose top-right to balance the
	     heavy left. Overlaps decisive, remaining gaps 40+. Span
	     x 360-1240 centered on 800; vertically sits a touch high
	     (span y 160-700) - Derek's optical call 2026-07-28,
	     triangle nudged up 40. -->
	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<rect class='roundable' x='790' y='320' width='360' height='280' fill='url(#agency-work-tex)'/>

	<rect class='roundable' x='730' y='240' width='220' height='220' fill='var(--fill-secondary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='510' cy='380' r='150' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<path d='M430,700 L590,423 L750,700 Z' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='1180' cy='220' r='60' fill='var(--accent)'/>
</svg>
