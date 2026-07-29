<?php /* ORBIT. Voices around a call, one of them lit.
   Positions are principled, not eyeballed (formalized 2026-07-28 when
   Derek asked how they were decided): ring r300 on the frame center;
   every dot center sits exactly ON the ring's path, spaced at even
   thirds (120 degrees), phase-rotated so one voice is at 9:00 (180)
   and the other two mirror across the horizontal axis (60 and 300 -
   cos 60 = 1/2, so x = 800+120 exactly; y = 450 -/+ 208). The accent
   is the 60-degree voice. Sized down 2026-07-28 per Derek (ring r300
   -> r240, dots to the family d100). */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<circle cx='800' cy='450' r='240' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<circle cx='560' cy='450' r='50' fill='var(--ink-primary)'/>

	<circle cx='920' cy='658' r='50' fill='var(--ink-primary)'/>

	<circle cx='920' cy='242' r='50' fill='var(--accent)'/>
</svg>
