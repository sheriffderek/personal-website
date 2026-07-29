<?php /* MODULES. A page as separate stacked modules
   with real gaps - interchangeable blocks, not one box with dividers
   (per Derek's markup 2026-07-28). One module is filled (a different
   kind of section); one contains a little dome rising from its floor -
   the horizon motif living inside a module. Grid: modules 480x120 on
   a 160 pitch (gap 40 - widened 2026-07-28 with the viewports:
   modules are siblings, so their gaps are separations, not
   breathing), span y 150-750 / x 560-1040 centered on 800/450; dome
   r60 based on its module's bottom edge, 60 clear of every module
   edge. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<!-- The dome paints BEFORE the modules so the module's floor
	     line crosses its base crisply (it rises from behind the
	     line, never eats the border - Derek's catch 2026-07-28). -->
	<path d='M860,590 A60,60 0 0 1 980,590 Z' fill='var(--accent)'/>

	<g stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='560' y='150' width='480' height='120' fill='none'/>
		<rect class='roundable' x='560' y='310' width='480' height='120' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='560' y='470' width='480' height='120' fill='none'/>
		<rect class='roundable' x='560' y='630' width='480' height='120' fill='none'/>
	</g>
</svg>
