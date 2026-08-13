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

	// One radio-pill group - the shape behind scheme / sound / layout.
	// aria-checked marks the active pill (the accent-filled state); the others
	// show the resting state, so a single group demonstrates both at once. The
	// selected pill carries the group's only tab stop (roving tabindex); from
	// there scripts/design-system.js takes over the toggling.
	function demo_switcher($label, $options, $selected) {
		$pills = '';
		foreach ($options as $value => $text) {
			$is_selected = $value === $selected;
			$checked = $is_selected ? 'true' : 'false';
			$tabindex = $is_selected ? '0' : '-1';
			$pills .= "<button type='button' role='radio' aria-checked='{$checked}' tabindex='{$tabindex}'>{$text}</button>";
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

				<input type='range' min='0' max='{$max}' step='1' value='{$value}' class='plain-range' aria-label='{$label}'>
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
		'variant' => 'warm',
		'poster' => true,
		'title' => 'Sample poster card',
		'description' => '<p>The live poster card, rendered through the real milestone template. Change brand, emphasis, or scheme in the settings panel and watch this repaint with everything above it.</p>',
	];
?>

<text-content class='styled design-system'>

	<h1 class='loud-voice'>Design system</h1>

	<p>Every token and voice on one surface. Change the axes in the settings panel to see them all react together. The journal template has its own surface: <a class='link' href='/journal/specimen'>the specimen entry</a> renders every module an entry can use.</p>

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

	<?php /* Token flow bench: the theming system drawn as a live circuit.
		Nodes are real elements; chips paint from the token they name (so the
		diagram repaints with the system it depicts); switches read the axis
		attributes on <html>. The edges - the actual var() chains - are drawn
		by scripts/design-system.js, which also pulses a switch and its wires
		when that axis changes. Bench-local until the shape proves out. */ ?>
	<?php
		// One chip row per token. The chip's background IS the documentation -
		// no JS, no sync problem; the cascade keeps it truthful.
		function flow_chip_rows($tokens) {
			$rows = '';
			foreach ($tokens as $token) {
				$rows .= "<li><span class='chip' style='background: var({$token})'></span><code class='quiet-voice'>{$token}</code></li>";
			}

			return "<ul class='flow-rows'>{$rows}</ul>";
		}

		// A token whose VALUE is the story (a font name, a ratio) gets a live
		// readout instead of a chip - design-system.js fills the span from
		// getComputedStyle and re-fills on every axis change.
		function flow_value_rows($tokens) {
			$rows = '';
			foreach ($tokens as $token => $kind) {
				$rows .= "<li><code class='quiet-voice'>{$token}</code><span class='flow-readout quiet-voice' data-token-value='{$token}' data-value-kind='{$kind}'>&mdash;</span></li>";
			}

			return "<ul class='flow-rows'>{$rows}</ul>";
		}

		// An axis switch. The attribute lives on <html>; absent = the default
		// (the selector law: index-0 values are removal, never written out).
		function flow_switch($node, $label, $attr, $default) {
			return "
				<div class='flow-node flow-switch' data-node='{$node}' data-switch-attr='{$attr}' data-switch-default='{$default}'>
					<p class='label-voice'>{$label}</p>

					<p class='switch-position strong-voice' data-switch-readout>&mdash;</p>

					<code class='quiet-voice'>[{$attr}]</code>
				</div>";
		}
	?>
	<section class='ds-section'>
		<h2 class='attention-voice'>Token flow (bench)</h2>

		<p>The theming system as a live circuit. Every chip paints from the real token it names, every switch shows its current position, and the edges are the actual <code>var()</code> chains - drive the settings panel and watch the routes light up as the values flow through. Two trees, and the whole contract is that they never touch: moods own the color tree, characters own the structure tree.</p>

		<h3 class='strong-voice'>Color - pigment to paint</h3>

		<div class='token-flow' data-flow='color'>
			<svg class='flow-edges' aria-hidden='true'></svg>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Raw pigment</p>

				<div class='flow-node' data-node='palette'>
					<p class='label-voice'>Palette scales</p>

					<span class='family-strip' aria-hidden='true'>
						<span style='background: var(--color-stone-400)'></span><span style='background: var(--color-olive-400)'></span><span style='background: var(--color-amber-400)'></span><span style='background: var(--color-rose-400)'></span><span style='background: var(--color-blue-400)'></span><span style='background: var(--color-violet-400)'></span>
					</span>

					<code class='quiet-voice'>--color-&lt;family&gt;-&lt;50&hellip;950&gt;</code>

					<p class='quiet-voice'>~20 families. Components never read these directly.</p>
				</div>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Switches</p>

				<?= flow_switch('mood', 'Mood', 'data-brand-mood', 'expressive') ?>

				<?= flow_switch('scheme', 'Scheme', 'data-scheme', 'system') ?>

				<?= flow_switch('flavor', 'Flavor', 'data-flavor', 'default') ?>

				<?= flow_switch('red-light', 'Red light', 'data-red-light', 'off') ?>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Semantic slots</p>

				<div class='flow-node' data-node='slots'>
					<p class='label-voice'>The mood contract</p>

					<?= flow_chip_rows([
						'--fill-primary', '--fill-secondary', '--fill-auxiliary',
						'--ink-primary', '--ink-secondary', '--ink-auxiliary',
						'--stroke-primary', '--stroke-secondary', '--stroke-auxiliary',
						'--accent',
						'--selection-fill', '--selection-ink',
					]) ?>

					<p class='quiet-voice'>Every mood declares all of these, as <code>light-dark()</code> pairs - the scheme resolves inside them.</p>
				</div>

				<div class='flow-node' data-node='poster' data-variant='warm'>
					<p class='label-voice'>Variant slots</p>

					<?= flow_chip_rows([
						'--poster-fill', '--poster-fill-secondary', '--poster-ink', '--poster-accent',
						'--year-fill', '--year-ink',
					]) ?>

					<p class='quiet-voice'>What the slots mean in pigment, per mood &times; character cell. Probed here at <code>data-variant='warm'</code>.</p>
				</div>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Derived &amp; scoped</p>

				<div class='flow-node' data-node='wiring'>
					<p class='label-voice'>Wiring</p>

					<?= flow_chip_rows(['--link-color', '--link-underline-color', '--scrim']) ?>

					<p class='quiet-voice'><code>--link-color</code> reads <code>--accent</code>; the underline reads the link. Derived, never assigned.</p>
				</div>

				<div class='flow-node' data-node='app' data-ui='app'>
					<p class='label-voice'>App chrome</p>

					<?= flow_chip_rows(['--app-fill', '--app-ink', '--app-stroke', '--app-accent']) ?>

					<p class='quiet-voice'>Aliases of the slots inside <code>[data-ui='app']</code> - color flows in, geometry is frozen.</p>
				</div>
			</div>
		</div>

		<h3 class='strong-voice'>Structure - families to voices</h3>

		<div class='token-flow' data-flow='structure'>
			<svg class='flow-edges' aria-hidden='true'></svg>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Raw families</p>

				<div class='flow-node' data-node='stacks'>
					<p class='label-voice'>Font stacks</p>

					<?= flow_value_rows(['--font-sans' => 'family', '--font-serif' => 'family', '--font-mono' => 'family']) ?>

					<p class='quiet-voice'>The audition stacks (font-scales.css). Voices never read these directly.</p>
				</div>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Switch</p>

				<?= flow_switch('character', 'Character', 'data-brand-character', 'product') ?>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Pair &amp; shape tokens</p>

				<div class='flow-node' data-node='pairs'>
					<p class='label-voice'>The pairing</p>

					<?= flow_value_rows(['--font-heading' => 'family', '--font-body' => 'family', '--font-ui' => 'family', '--font-code' => 'family']) ?>
				</div>

				<div class='flow-node' data-node='scale'>
					<p class='label-voice'>Scale</p>

					<?= flow_value_rows(['--scale-ratio' => 'raw']) ?>

					<p class='quiet-voice'>One ratio &rarr; the <code>--step-*</code> ladder &rarr; every voice size.</p>
				</div>

				<div class='flow-node' data-node='shape'>
					<p class='label-voice'>Shape</p>

					<?= flow_value_rows(['--corners' => 'raw']) ?>

					<p class='quiet-voice'>Consumed by the media frame, chips, and cards - never by the frozen app chrome.</p>
				</div>
			</div>

			<div class='flow-column'>
				<p class='flow-column-title quiet-voice'>Voices</p>

				<div class='flow-node' data-node='voices'>
					<p class='label-voice'>Type voices</p>

					<p><span class='calm-voice'>The quick brown fox</span></p>

					<p class='quiet-voice'>Each voice owns a <code>--&lt;voice&gt;-font-*</code> token set reading the pair and the ladder - a character tunes those, never the voice classes.</p>
				</div>
			</div>
		</div>
	</section>

	<?php /* Phase 0 bench: is a palette composable, or must it be hand-authored?
		Engine lives in styles/modules/design-system.css - bench-local until it
		earns promotion, same as the grain layers. The reference row reads the
		REAL [data-variant] tokens, so the shipped design sits next to the
		generated one. Rows 3 and 4 are the actual test: they cost one value
		each, and either they hold up or the model is wrong. */ ?>
	<section class='ds-section'>
		<h2 class='attention-voice'>Harmony engine (bench)</h2>

		<p>Band = lightness. Flavor = hue. Theme = the hue set plus a chroma multiplier. Same four slots every row - only the channels change.</p>

		<h3 class='strong-voice'>Hand-tuned - the shipped 70s rainbow</h3>

		<ol class='harmony-reference'>
			<li data-variant='rose'><span class='quiet-voice'>rose</span></li>

			<li data-variant='warm'><span class='quiet-voice'>warm</span></li>

			<li data-variant='moss'><span class='quiet-voice'>moss</span></li>

			<li data-variant='cool'><span class='quiet-voice'>cool</span></li>
		</ol>

		<h3 class='strong-voice'>Generated - Happy (rainbow)</h3>

		<p class='quiet-voice'>Uses the hand-tuned numbers, so this row proves only that the engine can express the design. It is the control, not the test.</p>

		<ol class='harmony'>
			<li><span class='quiet-voice'>rose</span></li>

			<li><span class='quiet-voice'>warm</span></li>

			<li><span class='quiet-voice'>moss</span></li>

			<li><span class='quiet-voice'>cool</span></li>
		</ol>

		<h3 class='strong-voice'>Generated - Muted (one value: chroma x 0.25)</h3>

		<p class='quiet-voice'>The whole Muted column, for free. Does it hold up?</p>

		<ol class='harmony' data-harmony='muted'>
			<li><span class='quiet-voice'>rose</span></li>

			<li><span class='quiet-voice'>warm</span></li>

			<li><span class='quiet-voice'>moss</span></li>

			<li><span class='quiet-voice'>cool</span></li>
		</ol>

		<h3 class='strong-voice'>Generated - Techy (analogous hue set)</h3>

		<p class='quiet-voice'>Same engine, hues collapsed into one violet neighbourhood instead of spread across the wheel.</p>

		<ol class='harmony' data-harmony='techy'>
			<li><span class='quiet-voice'>1</span></li>

			<li><span class='quiet-voice'>2</span></li>

			<li><span class='quiet-voice'>3</span></li>

			<li><span class='quiet-voice'>4</span></li>
		</ol>

		<h3 class='strong-voice'>Generated - Happy, Immersive band (lightness only)</h3>

		<p class='quiet-voice'>The same hues, deep. This is what <code>night</code> was reaching for before it got filed under flavor - and it works on every hue, not just brown.</p>

		<ol class='harmony' data-band='immersive'>
			<li><span class='quiet-voice'>rose</span></li>

			<li><span class='quiet-voice'>warm</span></li>

			<li><span class='quiet-voice'>moss</span></li>

			<li><span class='quiet-voice'>cool</span></li>
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
				<p class='app-data-voice'>Filter: <span class='filter-count'><span data-demo-filter-count><?= $mini_in ?></span> / <?= count($mini_weights) ?></span></p>

				<div class='filter-body'>
					<input type='range' min='1' max='6' step='1' value='<?= $mini_tier ?>' class='plain-range' aria-label='Filter level' data-demo-filter>

					<p class='filter-level-name app-data-voice' data-demo-filter-name>+ major support</p>

					<div class='mini-map' aria-hidden='true'>
						<ol class='mini-map-bars'><?= $mini_bars ?></ol>

						<div class='mini-map-panel'></div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Collage specimen</h2>

		<p>One graphic, three recipes. The markup is <em>identical</em> in every frame - each recipe only turns pieces on/off and swaps fill for outline (via <code>data-recipe</code>). That is the whole idea: add, remove, restyle the same pieces and the same collage feels totally different, with nothing redrawn. Piece colors are placeholders until the palette lands - the mechanic is the point.</p>

		<ol class='collage-recipes'>
			<li>
				<?= partial('posters/collage-specimen', ['recipe' => 'expressive', 'uid' => 'ce']) ?>

				<code class='quiet-voice'>expressive · all on, filled, colorful</code>
			</li>

			<li>
				<?= partial('posters/collage-specimen', ['recipe' => 'technical', 'uid' => 'ct']) ?>

				<code class='quiet-voice'>technical · outlined subset, one cool accent</code>
			</li>

			<li>
				<?= partial('posters/collage-specimen', ['recipe' => 'quiet', 'uid' => 'cq']) ?>

				<code class='quiet-voice'>quiet · one block, one accent, negative space</code>
			</li>
		</ol>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Poster card</h2>

		<div class='timeline'>
			<?php require TEMPLATES_DIR . '/milestone.php'; ?>
		</div>
	</section>

	<section class='ds-section'>
		<h2 class='attention-voice'>Flavor range bench</h2>

		<p>The whole hue wheel as one smooth drag - with a cheat. The slider travels a <em>waypoint table</em> (scripts/design-system.js): only hue neighborhoods that actually look good carry anchors, each with its own chroma correction, and the bad zones simply contain no stops. The ride feels continuous; every frame is curated. The specimens below paint from the interpolated palette - nothing here touches real site tokens. If this proves out, named flavors become <em>bookmarked positions</em> on this range.</p>

		<?php /* data-ui='app': the bench slider is .plain-range, whose track and
			thumb paint from the --app-* chrome tokens - unresolved outside the
			scope, the control renders invisible (same lesson as the tray
			triggers). Every demo control on this page wears the scope. */ ?>
		<div class='flavor-bench' data-ui='app'>
			<label class='app-data-voice' for='flavor-bench-slider'>Range: <span data-bench-readout>—</span></label>

			<input type='range' id='flavor-bench-slider' min='0' max='1000' step='1' value='380' class='plain-range' aria-label='Flavor range position'>

			<ol class='bench-specimens' role='list'>
				<li>
					<svg viewBox='0 0 320 180' xmlns='http://www.w3.org/2000/svg' role='img' aria-hidden='true'>
						<rect width='320' height='180' fill='var(--bench-ground)'/>

						<rect x='60' y='50' width='120' height='80' fill='var(--bench-mass)'/>

						<circle cx='230' cy='60' r='18' fill='var(--bench-accent)'/>
					</svg>
				</li>

				<li>
					<svg viewBox='0 0 320 180' xmlns='http://www.w3.org/2000/svg' role='img' aria-hidden='true'>
						<rect width='320' height='180' fill='var(--bench-ground)'/>

						<circle cx='120' cy='90' r='48' fill='none' stroke='var(--bench-ink)' stroke-width='4'/>

						<circle cx='120' cy='90' r='12' fill='var(--bench-accent)'/>

						<rect x='200' y='60' width='70' height='60' fill='var(--bench-mass)'/>
					</svg>
				</li>

				<li>
					<svg viewBox='0 0 320 180' xmlns='http://www.w3.org/2000/svg' role='img' aria-hidden='true'>
						<rect width='320' height='180' fill='var(--bench-mass)'/>

						<path d='M40,130 L120,60 L200,130' fill='none' stroke='var(--bench-ink)' stroke-width='4' stroke-linecap='round'/>

						<circle cx='250' cy='70' r='16' fill='var(--bench-accent)'/>
					</svg>
				</li>
			</ol>

			<p class='quiet-voice' data-bench-values>—</p>
		</div>
	</section>

</text-content>
