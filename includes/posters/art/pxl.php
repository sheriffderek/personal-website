<?php /* THE BIG SCREEN. DreamWorks.com was a big-screen site: a
   cinema-dark screen with a knockout title bar and an accent moon
   rising in it; the phone game sits IN FRONT as a real device with
   playful marks. (Simplified to Derek's third markup 2026-07-28:
   no title bar, no phone marks - one dark mass, the accent tucked
   top-left inside it, two clean phones sunk into the screen and just
   peeking below.) Grid: screen 880x520 ink at x 360-1240 / y 160-680;
   accent d100 inset 60/60 from the screen's top-left; phones 170x300
   at y 440-740 (240 inside the mass, 60 below it), 40 apart, the
   second 60 clear of the screen's right edge. Span x 360-1240 /
   y 160-740 centered on 800/450, inside the phone crop. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<rect class='roundable' x='360' y='160' width='880' height='520' fill='var(--ink-primary)'/>

	<circle cx='470' cy='270' r='50' fill='var(--accent)'/>

	<g fill='var(--fill-primary)' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='800' y='440' width='170' height='300'/>
		<rect class='roundable' x='1010' y='440' width='170' height='300'/>
	</g>
</svg>
