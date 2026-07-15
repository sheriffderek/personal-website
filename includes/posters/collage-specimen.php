<?php
	/*
		Collage specimen (design-system bench only).

		ONE graphic. Every recipe renders the identical markup below - the only
		difference is the data-recipe on the frame, which the CSS reads to turn
		pieces on/off and swap fill for outline. That is the whole concept:
		add / remove / restyle the same pieces -> a totally different feel, with
		zero redrawing. Geometry is authored once, here; themes only subtract and
		repaint.

		Each piece is a role-tagged <g> (mass / block / wedge / orb / mark /
		field / connector). Fills and strokes come from CSS per recipe, so the
		SVG itself hardcodes no color. Defs (dot pattern, fade mask, arrow
		marker) are prefixed with $uid so three instances never collide on ids.

		Vars in: $recipe (the recipe name -> data-recipe), $uid (unique id seed).
	*/
	$uid = $uid ?? 'c1';
	$recipe = $recipe ?? 'expressive';
?>

<div class='collage-frame' data-recipe='<?= $recipe ?>'>
	<svg class='collage' viewBox='0 0 1600 900' xmlns='http://www.w3.org/2000/svg' role='img' aria-label='Collage specimen'>
		<defs>
			<pattern id='<?= $uid ?>-dots' width='48' height='48' patternUnits='userSpaceOnUse'>
				<circle cx='11' cy='11' r='6' fill='var(--ink-secondary)' />
			</pattern>

			<linearGradient id='<?= $uid ?>-fadegrad' x1='0' y1='0' x2='1' y2='0'>
				<stop offset='0' stop-color='white' stop-opacity='1' />

				<stop offset='1' stop-color='white' stop-opacity='0' />
			</linearGradient>

			<mask id='<?= $uid ?>-fade'>
				<rect x='1010' y='300' width='300' height='300' fill='url(#<?= $uid ?>-fadegrad)' />
			</mask>

			<marker id='<?= $uid ?>-arrow' viewBox='0 0 10 10' refX='8' refY='5' markerWidth='6' markerHeight='6' orient='auto' markerUnits='strokeWidth'>
				<path d='M0,0 L10,5 L0,10 Z' fill='context-stroke' />
			</marker>
		</defs>

		<g class='piece mass'>
			<path d='M300 470 A220 220 0 0 1 740 470 Z' />
		</g>

		<g class='piece block'>
			<rect class='roundable' x='150' y='520' width='240' height='240' />
		</g>

		<g class='piece wedge'>
			<path d='M470 820 L690 820 A220 220 0 0 0 470 600 Z' />
		</g>

		<g class='piece field'>
			<rect class='field-frame' x='1010' y='300' width='300' height='300' fill='none' />

			<rect x='1010' y='300' width='300' height='300' fill='url(#<?= $uid ?>-dots)' mask='url(#<?= $uid ?>-fade)' />
		</g>

		<g class='piece connector'>
			<path d='M180 840 L980 840' marker-end='url(#<?= $uid ?>-arrow)' />
		</g>

		<g class='piece orb'>
			<circle cx='1180' cy='250' r='140' />
		</g>

		<g class='piece mark'>
			<rect x='1120' y='120' width='68' height='68' />
		</g>
	</svg>
</div>
