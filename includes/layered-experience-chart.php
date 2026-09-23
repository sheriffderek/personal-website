<?php /* The layered experience chart - the staircase telling of the practice
	layers (Derek's idea: "I don't just 'switch roles' I add new layers").
	The first layer is the bottom bar; each later layer stacks on top of it,
	starting further right, and every bar runs to the right edge - a layer
	starts and never ends. No years, on purpose: the order is the message.

	Callers pass:
	  layers  the list from content/layered-experience.json (tag, what, and
	          optionally why + skills) - the one source both tellings read

	Each bar is a <details>: click its name to open its lines, each on its
	own (not an accordion). The browser gives that - keyboard, open state,
	screen readers - with no script. Source order is oldest first; the CSS
	draws it bottom-up (styles/modules/layered-experience-chart.css). */ ?>

<ol
	class='layered-experience-chart'
	style='--count: <?= count($layers) ?>'
>
	<?php foreach (array_values($layers) as $index => $layer): ?>
		<li style='--index: <?= $index ?>'>
			<details>
				<summary class='tag label-voice'>
					<?= $layer['tag'] ?>
				</summary>

				<div class='telling'>
					<p class='calm-voice'>
						<?= $layer['what'] ?>
					</p>

					<?php /* The last layer ("What's next?") is one line - no why,
						no skills - so these only render when the layer has them. */ ?>
					<?php if (!empty($layer['why'])): ?>
						<p class='quiet-voice'>
							<?= $layer['why'] ?>
						</p>
					<?php endif; ?>

					<?php if (!empty($layer['skills'])): ?>
						<p class='skills quiet-voice'>
							<?= $layer['skills'] ?>
						</p>
					<?php endif; ?>
				</div>
			</details>
		</li>
	<?php endforeach; ?>
</ol>
