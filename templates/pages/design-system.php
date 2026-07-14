<?php
	// The design-system tester. It renders the site's raw materials - color
	// tokens, type voices, and one real poster card - so any brand/emphasis/
	// scheme change (from the settings panel) can be checked against all of
	// them at once. Finishes (grain) land here first, next to the solid tokens.

	// The semantic color slots, in hierarchy order. Each swatch paints itself
	// with the token it names, so the strip repaints live as the axes change.
	$color_tokens = [
		'--fill-primary' => 'Primary fill',
		'--fill-secondary' => 'Secondary fill',
		'--ink-primary' => 'Primary ink',
		'--ink-secondary' => 'Secondary ink',
		'--stroke-primary' => 'Primary stroke',
		'--accent' => 'Accent',
	];

	// The type voices, quietest to loudest. One line each catches a regression
	// in size, weight, or rhythm the moment a brand swaps its pairing.
	$voices = [
		'quiet-voice',
		'calm-voice',
		'label-voice',
		'strong-voice',
		'attention-voice',
		'loud-voice',
		'high-voice',
	];

	// Media-finish experiments. Each cell drops one treatment onto the same
	// sample mark so they compare side by side. Adding an experiment is one
	// array entry - this grid is meant to grow (grain now, squiggle and
	// edge-erosion later). No emphasis wiring yet: each cell names its finish
	// explicitly, so nothing here depends on the material-axis decision.
	$finish_experiments = [
		['label' => 'Solid (no finish)', 'class' => ''],
		['label' => 'grain fine · multiply', 'class' => 'grain-primary'],
		['label' => 'grain layered · multiply', 'class' => 'grain-layered'],
		['label' => 'grain fiber · directional', 'class' => 'grain-fiber'],
		['label' => 'grain layered · soft-light (theme-safe blend)', 'class' => 'grain-softlight'],
		['label' => 'halftone · vector + token ink', 'class' => 'halftone'],
		['label' => 'hatch · vector + token ink', 'class' => 'hatch'],
		['label' => 'vignette · depth, token ink', 'class' => 'vignette'],
	];

	// One crisp ink mark, reused in every cell - a rect, a circle, a rule, all
	// stroked (no fills, no IDs), so it repeats safely and shows that strokes
	// stay sharp over a grained fill. viewBox is 16:9 to match the frame.
	$sample_mark = "
		<svg class='sample-mark' viewBox='0 0 160 90' fill='none' xmlns='http://www.w3.org/2000/svg'>
			<rect x='14' y='20' width='46' height='46' stroke='var(--ink-primary)' stroke-width='2' />

			<circle cx='118' cy='34' r='20' stroke='var(--ink-primary)' stroke-width='2' />

			<path d='M14 78 L146 78' stroke='var(--stroke-primary)' stroke-width='2' />
		</svg>";

	// --- Inert demos of the menu / settings controls ------------------------
	// The real, working controls live in the gear popover (this is the one page
	// where settings-panel.js runs). These copies are STATIC: no data-set-*
	// hooks and no ids, so the live script leaves them alone and each keeps the
	// state authored here - letting every state sit on the surface at once. The
	// pill / slider / minimap styling all comes from the real component classes.
	// One group per control TYPE, not one per option. A radiogroup already shows
	// the active pill next to a resting one, so a second copy with a different
	// option checked proves nothing the first didn't.
	$radio_options = ['off' => 'Off', 'on' => 'On'];

	// One inert radio-pill group - the shape behind scheme / sound / layout.
	// aria-checked marks the active pill (the accent-filled state); the others
	// show the resting state, so a single group demonstrates both at once.
	function demo_switcher($label, $options, $selected) {
		$pills = '';
		foreach ($options as $value => $text) {
			$checked = $value === $selected ? 'true' : 'false';
			$pills .= "<button type='button' role='radio' aria-checked='{$checked}' tabindex='-1'>{$text}</button>";
		}

		return "
			<div class='demo-switcher'>
				<p class='app-data-voice'>{$label}</p>

				<div class='mode-button-group' role='radiogroup' aria-label='{$label}'>{$pills}</div>
			</div>";
	}

	// One inert slider - the brand / emphasis shape. The name span mirrors the
	// live label that updates as the thumb moves; here it just states the value.
	function demo_slider($label, $value_name, $max, $value) {
		return "
			<div class='demo-switcher'>
				<p class='app-data-voice'>{$label}: <span>{$value_name}</span></p>

				<input type='range' min='0' max='{$max}' step='1' value='{$value}' class='plain-range' aria-label='{$label}' tabindex='-1'>
			</div>";
	}

	// A little static timeline for the minimap schematic: a spread of weights so
	// some bars read "in" (surfaced) and some faint ("out"), the way the real
	// map looks mid-filter. Shown at demo tier 2 (weight <= 2 is surfaced).
	$mini_weights = [1, 1, 2, 1, 3, 1, 2, 4, 1, 5, 1, 6, 2, 1, 3, 1];
	$mini_tier = 2;
	$mini_in = 0;
	$mini_bars = '';
	foreach ($mini_weights as $weight) {
		$state = $weight <= $mini_tier ? 'in' : 'out';
		if ($state === 'in') { $mini_in++; }
		$mini_bars .= "<li data-weight='{$weight}' data-state='{$state}'></li>";
	}

	// A synthetic milestone drives the poster card from outside - the tester
	// feeds every state in, exactly as the "components are dumb, pages are
	// smart" rule requires. poster:true + no media = the poster-shapes cover
	// alone (the surface finishes actually paint), no file dependencies.
	$milestone = [
		'slug' => 'design-system-sample',
		'date' => '2026',
		'weight' => 1,
		'flavor' => 'warm',
		'poster' => true,
		'title' => 'Sample poster card',
		'description' => '<p>The live poster card, rendered through the real milestone template. Change brand, emphasis, or scheme in the settings panel and watch this repaint with everything above it.</p>',
	];
?>

<text-content class='styled design-system'>

	<h1 class='loud-voice'>Design system</h1>

	<p>Every token and voice on one surface. Change the axes in the settings panel to see them all react together.</p>

	<section class='ds-section'>
		<h2 class='attention-voice'>Color tokens</h2>

		<ol class='swatches'>
			<?php foreach ($color_tokens as $token => $label): ?>
				<li class='swatch'>
					<span class='chip' style='background: var(<?= $token ?>)'></span>

					<span class='label-voice'><?= $label ?></span>

					<code class='quiet-voice'><?= $token ?></code>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Voices</h2>

		<ol class='voices'>
			<?php foreach ($voices as $voice): ?>
				<li>
					<span class='<?= $voice ?>'>The quick brown fox</span>

					<code class='quiet-voice'><?= $voice ?></code>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Media finishes</h2>

		<p>The same mark under every finish, to compare directions. Two families: <em>grain</em> (procedural noise, blended into the fill - organic but DPR-sensitive) and <em>vector</em> (halftone / hatch / vignette - CSS gradients in token colors, so they stay crisp at any resolution and repaint with the theme). Flip the emphasis and scheme in the settings panel to see which survive dark and colored fills - that is the "works for any theme" test. The <code>multiply</code> grains will mud out on dark; the soft-light and vector ones should hold.</p>

		<ol class='experiments'>
			<?php foreach ($finish_experiments as $experiment): ?>
				<li class='experiment-cell'>
					<span class='media-sample <?= $experiment['class'] ?>'>
						<?= $sample_mark ?>
					</span>

					<code class='quiet-voice'><?= $experiment['label'] ?></code>
				</li>
			<?php endforeach; ?>
		</ol>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Menus &amp; settings</h2>

		<p>The raw controls the settings menu is built from - one of each, shown inline. These are static copies (no wiring); the live, working versions sit in the gear popover at the top of this page. The pill, slider, and minimap styling all come from the real component classes, so anything that changes there changes here too.</p>

		<div class='control-states'>
			<div class='settings-panel demo-control' data-ui='app'><?= demo_switcher('Radio choices', $radio_options, 'off') ?></div>

			<div class='settings-panel demo-control' data-ui='app'><?= demo_slider('Slider', 'Third stop', 3, 2) ?></div>
		</div>

		<h3 class='strong-voice'>Timeline filter</h3>

		<p class='quiet-voice'>The filter label, slider, and minimap - a scaled schematic of the page itself (it gains the fake side panel and second column from the 1024px breakpoint, mirroring the real layout). Dark bars are surfaced by the filter; faint bars are hidden.</p>

		<div class='settings-panel demo-control demo-filter' data-ui='app'>
			<div class='filter-control'>
				<p class='app-data-voice'>Filter: <span class='filter-count'><?= $mini_in ?> / <?= count($mini_weights) ?></span></p>

				<div class='filter-body'>
					<input type='range' min='1' max='6' step='1' value='<?= $mini_tier ?>' class='plain-range' aria-label='Filter level' tabindex='-1'>

					<p class='filter-level-name app-data-voice'>+ major support</p>

					<div class='mini-map' aria-hidden='true'>
						<ol class='mini-map-bars'><?= $mini_bars ?></ol>

						<div class='mini-map-panel'></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Poster card</h2>

		<div class='timeline'>
			<?php require TEMPLATES_DIR . '/milestone.php'; ?>
		</div>
	</section>

</text-content>
