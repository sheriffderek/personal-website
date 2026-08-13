<?php /* SPLIT. The same mark on two grounds, surviving
   both - the whole accessibility/theming story. */ ?>
<?php /* Phone crop: the full span (270-1330) can't fit the standard 900
   window, so the composition renders smaller instead of cropping - scale
   0.8 opens the window to 1125, holding both circles, the seam, and the
   accent, centered, with ground filling above and below. */ ?>
<svg class='poster-art' viewBox='0 0 1600 900' preserveAspectRatio='xMidYMid slice' xmlns='http://www.w3.org/2000/svg'
	role='img' aria-hidden='true' style='fill-rule: evenodd; stroke-linejoin: round'>

	<rect x='-500' y='-500' width='2600' height='1900' fill='var(--fill-primary)'/>

	<rect x='-500' y='-500' width='1300' height='1900' fill='var(--fill-secondary)'/>

	<circle cx='420' cy='450' r='150' fill='var(--ink-primary)'/>

	<circle cx='1180' cy='450' r='150' fill='none' stroke='var(--ink-primary)' style='stroke-width: var(--line-width-primary)'/>

	<!-- The accent is built as two half circles meeting FLUSH on
	     the seam (Derek 2026-07-28): one circle, but each half takes
	     its side's treatment - the RIGHT half (light ground) runs at
	     80% (same mark, read through its context), which is the
	     poster's whole thesis. r70, flat edges at x=800. -->
	<path d='M800,100 A70,70 0 0 0 800,240 Z' fill='var(--accent)'/>

	<path d='M800,100 A70,70 0 0 1 800,240 Z' fill='var(--accent)' fill-opacity='0.8'/>
</svg>
