<?php /* VIEWPORTS. The same thing at three sizes: early
   responsive web. Flipped 2026-07-28 per Derek's markup: phone ->
   tablet -> desktop, ascending left to right, BOTTOM edges shared
   (screens grow upward as the era progresses), accent floating above
   the desktop.
   
   Grid (every distance explicitly decided, Derek 2026-07-28): widths
   double (100 / 200 / 400), gaps 40, shared baseline y=680; heights
   200 / 260 / 320 so the screen TOPS step by a constant 60 (they
   stepped 60-then-40 before, which was never decided); desktop is
   5:4, an honest monitor. Dot d100 on the desktop's center axis
   (x=990), a 40 separation above its top edge. Span x 410-1190 /
   y 220-680 centered on 800/450, inside the phone crop. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect width='1600' height='900' fill='var(--fill-primary)'/>

	<!-- Differentiation (Derek 2026-07-28): the screens get more
	     substantial as they grow - phone outline-only, tablet
	     fill-secondary, desktop ink-secondary (the darker derived
	     step). Existing slots only, and the gradation stays
	     monotonic under every flavor including inverted night. -->
	<g stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'>
		<rect class='roundable' x='410' y='480' width='100' height='200' fill='none'/>
		<rect class='roundable' x='550' y='420' width='200' height='260' fill='var(--fill-secondary)'/>
		<rect class='roundable' x='790' y='360' width='400' height='320' fill='var(--ink-secondary)'/>
	</g>

	<circle cx='990' cy='270' r='50' fill='var(--accent)'/>
</svg>
