<?php /* MONTH GRID. A month of days, every day
   a ring-and-dot unit; milestones wear thin quiet halos, today wears
   the bold accent halo - scattered through the month, not clustered
   (per Derek's markup 2026-07-28; refined per his second mock the
   same day: today is a solid accent COIN under its day, and the
   milestone halos tightened to crisp fine ink rings). Grid: day
   units d50 (ink ring, secondary center) on 140 columns / 150 rows
   centered on 800/450; halos d90 - ring to day is the module 20;
   today's coin d100 under a standard day unit. Medium band. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<circle cx='940' cy='450' r='50' fill='var(--accent)'/>

	<g fill='var(--fill-secondary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<circle cx='520' cy='300' r='25'/>
		<circle cx='660' cy='300' r='25'/>
		<circle cx='800' cy='300' r='25'/>
		<circle cx='940' cy='300' r='25'/>
		<circle cx='1080' cy='300' r='25'/>
		<circle cx='520' cy='450' r='25'/>
		<circle cx='660' cy='450' r='25'/>
		<circle cx='800' cy='450' r='25'/>
		<circle cx='940' cy='450' r='25'/>
		<circle cx='1080' cy='450' r='25'/>
		<circle cx='520' cy='600' r='25'/>
		<circle cx='660' cy='600' r='25'/>
		<circle cx='800' cy='600' r='25'/>
		<circle cx='940' cy='600' r='25'/>
		<circle cx='1080' cy='600' r='25'/>
	</g>

	<g fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-secondary)'>
		<circle cx='660' cy='300' r='45'/>
		<circle cx='520' cy='600' r='45'/>
	</g>
</svg>
