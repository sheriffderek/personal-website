<?php /* HORIZON. The calibration card: dome, air, one
   accent. The dome is a TRUE circle cresting through the frame, not a
   half-circle glued to the edge (per Derek's trace 2026-07-28 - the
   flat side coinciding with the frame was a tangent coincidence).
   Grid: circle center (800,1050) r600, so it crests exactly to the
   frame's y=450 midline and the frame honestly crops it. Dot d100 on
   the frame's upper-third line (y=300, lowered from 200 per Derek's
   markup 2026-07-28). */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<defs>
		<pattern id='2026-job-search-tex' width='56' height='56' patternUnits='userSpaceOnUse'>
			<g fill='none' stroke='var(--ink-secondary)' style='stroke-width: var(--line-width-secondary)' stroke-linecap='round' opacity='0.35'>
				<path d='M12,16 L20,8'/>
				<path d='M38,44 L46,36'/>
			</g>
		</pattern>
	</defs>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<circle cx='800' cy='1050' r='600' fill='var(--fill-secondary)'/>

	<circle cx='800' cy='1050' r='600' fill='url(#2026-job-search-tex)'/>

	<circle cx='1100' cy='300' r='50' fill='var(--accent)'/>
</svg>
