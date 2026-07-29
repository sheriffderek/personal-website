<?php /* THE DRIP. A dotted timeline of days, one of them
   ringed: go at your own pace, you are here. (Motif moved over from
   pe-calendar-milestones 2026-07-28 - Derek: this is what self-paced
   looks like; the checkpoint squares went to better-life.) Beefed up
   2026-07-28 - the faint dotted line read as broken: now a solid wire
   with beads on it. Grid: wire x 300-1300 on the y=450 axis at the
   primary weight; days d60 at 200 intervals; the current day rings
   d120 around an accent core d60. Small band. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<path d='M300,450 L1300,450' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)' stroke-linecap='round'/>

	<g fill='var(--ink-primary)'>
		<circle cx='400' cy='450' r='30'/>
		<circle cx='600' cy='450' r='30'/>
		<circle cx='800' cy='450' r='30'/>
		<circle cx='1200' cy='450' r='30'/>
	</g>

	<circle cx='1000' cy='450' r='60' fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='1000' cy='450' r='30' fill='var(--accent)'/>
</svg>
