/* Design-system tester - make the specimen controls actually work.

   The controls in the "Menus & settings" section are copies, not the real
   thing: they carry no data-set-* hooks, so settings-panel.js ignores them and
   they drive no site state. But a control that does nothing when you click it
   reads as broken rather than as an example - so they toggle THEMSELVES here.
   Local state, nothing persisted, nothing global. A specimen that responds.

   Scoped to .demo-control so this can never reach the real panel: the live
   radios are the ones WITH a data-set-* attribute, and they're wired (and
   owned) by settings-panel.js. */
(function () {
	var groups = document.querySelectorAll('.demo-control [role="radiogroup"]');

	groups.forEach(function (group) {
		var radios = group.querySelectorAll('[role="radio"]');
		if (!radios.length) return;

		/* One tab stop into the group, arrow keys between the options - the
		   WAI-ARIA radiogroup pattern, same as the real switchers. */
		function select(radio, moveFocus) {
			radios.forEach(function (option) {
				var isSelected = option === radio;
				option.setAttribute('aria-checked', isSelected ? 'true' : 'false');
				option.setAttribute('tabindex', isSelected ? '0' : '-1');
			});

			if (moveFocus) radio.focus();
		}

		radios.forEach(function (radio, index) {
			radio.addEventListener('click', function () {
				select(radio, false);
			});

			radio.addEventListener('keydown', function (event) {
				var step = 0;
				if (event.key === 'ArrowRight' || event.key === 'ArrowDown') step = 1;
				if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') step = -1;
				if (!step) return;

				event.preventDefault();
				select(radios[(index + step + radios.length) % radios.length], true);
			});
		});
	});

	/* The specimen filter drives its OWN minimap - the bars carry data-weight,
	   same as the real map, so dragging the slider lights and dims them by the
	   real rule (weight <= tier is surfaced). It reads no timeline and moves no
	   milestones; it just demonstrates the mechanic on its own little page. */
	var filter = document.querySelector('[data-demo-filter]');

	if (filter) {
		var bars = document.querySelectorAll('.demo-filter .mini-map-bars li');
		var count = document.querySelector('[data-demo-filter-count]');
		var name = document.querySelector('[data-demo-filter-name]');

		/* Matched to FILTER_NAMES in settings-panel.js - the tier names the real
		   slider shows. */
		var TIER_NAMES = {
			1: 'Core product work',
			2: '+ major support',
			3: '+ broader projects',
			4: '+ range & R&D',
			5: '+ craft & tooling',
			6: '+ other influences'
		};

		filter.addEventListener('input', function () {
			var tier = parseInt(filter.value, 10);
			var shown = 0;

			bars.forEach(function (bar) {
				var weight = parseInt(bar.getAttribute('data-weight'), 10);
				var isIn = weight <= tier;

				if (isIn) shown++;
				bar.setAttribute('data-state', isIn ? 'in' : 'out');
			});

			if (count) count.textContent = String(shown);
			if (name) name.textContent = TIER_NAMES[tier] || '';
		});
	}
})();

/* Token flow bench - the live circuit diagram of the theming system.

   Three jobs, all read-only against the real system:
   1. Draw the edges - each chart declares its var() chains in EDGES below,
      and the paths are drawn into the chart's .flow-edges overlay from the
      nodes' real positions (so they survive any layout change).
   2. Keep the readouts live - switches show the axis attribute currently on
      <html> (absent = the default, per the selector law), and data-token-value
      spans show the computed value of tokens whose VALUE is the story.
   3. Pulse the route - when an axis attribute changes, that switch and its
      outgoing wires light up for a moment, so flipping the settings panel
      visibly reroutes the diagram.

   The chips need no JS at all: each paints from the token it names via an
   inline var(), so the cascade keeps them truthful for free. */
(function () {
	var charts = document.querySelectorAll('.token-flow');
	if (!charts.length) return;

	var SVG_NS = 'http://www.w3.org/2000/svg';

	/* [from, to, optional class]. These ARE the var() chains - if a token
	   starts reading a different source, this table is what must change. */
	var EDGES = {
		color: [
			['palette', 'mood'],
			['mood', 'slots'],
			['scheme', 'slots'],
			['palette', 'flavor'],
			['mood', 'flavor'],
			['flavor', 'poster'],
			['slots', 'wiring'],
			['slots', 'app'],
			['red-light', 'slots', 'stomp'],
			['red-light', 'poster', 'stomp']
		],
		structure: [
			['stacks', 'pairs'],
			['character', 'pairs'],
			['character', 'scale'],
			['character', 'shape'],
			['pairs', 'voices'],
			['scale', 'voices']
		]
	};

	function drawEdges(chart) {
		var svg = chart.querySelector('.flow-edges');
		var edges = EDGES[chart.getAttribute('data-flow')] || [];

		svg.setAttribute('width', chart.scrollWidth);
		svg.setAttribute('height', chart.scrollHeight);
		svg.innerHTML = '';

		edges.forEach(function (edge) {
			var from = chart.querySelector('[data-node="' + edge[0] + '"]');
			var to = chart.querySelector('[data-node="' + edge[1] + '"]');
			if (!from || !to) return;

			/* Right edge of the source to left edge of the target, as a
			   gentle S-curve. Nodes sit inside static columns, so offsetLeft/
			   offsetTop are already relative to the chart (its offsetParent). */
			var x1 = from.offsetLeft + from.offsetWidth;
			var y1 = from.offsetTop + from.offsetHeight / 2;
			var x2 = to.offsetLeft;
			var y2 = to.offsetTop + to.offsetHeight / 2;
			var bend = Math.max((x2 - x1) / 2, 20);

			var path = document.createElementNS(SVG_NS, 'path');
			path.setAttribute('d', 'M' + x1 + ' ' + y1 + ' C' + (x1 + bend) + ' ' + y1 + ', ' + (x2 - bend) + ' ' + y2 + ', ' + x2 + ' ' + y2);
			path.setAttribute('data-from', edge[0]);
			if (edge[2]) path.classList.add(edge[2]);
			svg.appendChild(path);
		});
	}

	function drawAll() {
		charts.forEach(drawEdges);
	}

	/* A font stack reads as noise in a small node - show just the lead family. */
	function firstFamily(value) {
		return value.split(',')[0].replace(/["']/g, '').trim();
	}

	function refreshReadouts() {
		var root = document.documentElement;
		var styles = getComputedStyle(root);

		document.querySelectorAll('.token-flow [data-token-value]').forEach(function (span) {
			var value = styles.getPropertyValue(span.getAttribute('data-token-value')).trim();
			if (span.getAttribute('data-value-kind') === 'family') value = firstFamily(value);

			span.textContent = value || '—';
		});

		document.querySelectorAll('.token-flow [data-switch-attr]').forEach(function (node) {
			var attr = node.getAttribute('data-switch-attr');
			var value = root.getAttribute(attr);

			/* Red light is a valueless boolean attribute; every other axis
			   removes its attribute at position zero (the selector law), so
			   absence means the declared default. */
			if (attr === 'data-red-light') {
				value = value === null ? 'off' : 'on';
			} else if (value === null) {
				value = node.getAttribute('data-switch-default');
			}

			node.querySelector('[data-switch-readout]').textContent = value;
		});
	}

	/* When an axis moves, light the switch that carries it and its outgoing
	   wires - the "rerouted" moment, made visible. */
	var pulseTimers = {};

	function pulse(attr) {
		document.querySelectorAll('.token-flow [data-switch-attr="' + attr + '"]').forEach(function (node) {
			var name = node.getAttribute('data-node');
			var chart = node.closest('.token-flow');
			var wires = chart.querySelectorAll('[data-from="' + name + '"]');

			node.classList.add('is-live');
			wires.forEach(function (wire) {
				wire.classList.add('is-live');
			});

			clearTimeout(pulseTimers[attr]);
			pulseTimers[attr] = setTimeout(function () {
				node.classList.remove('is-live');
				wires.forEach(function (wire) {
					wire.classList.remove('is-live');
				});
			}, 1200);
		});
	}

	var observer = new MutationObserver(function (mutations) {
		refreshReadouts();
		drawAll();

		mutations.forEach(function (mutation) {
			pulse(mutation.attributeName);
		});
	});

	observer.observe(document.documentElement, {
		attributes: true,
		attributeFilter: ['data-brand-mood', 'data-brand-character', 'data-scheme', 'data-flavor', 'data-red-light']
	});

	/* Node heights move with fonts and readout text, so the wires re-route on
	   any real layout change, not just axis flips. */
	var resizer = new ResizeObserver(drawAll);
	charts.forEach(function (chart) {
		resizer.observe(chart);
	});

	refreshReadouts();
	drawAll();

	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(drawAll);
	}
})();

/* Flavor range bench - the reparameterized hue dial (Derek, 2026-07-28).

   The slider does NOT walk raw hue degrees. It walks this waypoint table:
   an ordered list of curated hue anchors, each carrying its own chroma
   correction, with the muddy neighborhoods simply left out (note the jump
   past the dark-olive zone around 100-135). Between anchors everything
   interpolates, so the drag feels like one smooth range while every frame
   stays inside a judged neighborhood - varying hue speed reads as
   character, not as a skip.

   BOOKMARKS name positions on the range - if the bench proves out, named
   flavors become exactly these: saved positions, not separate palettes.

   Bench-only: paints --bench-* properties on the .flavor-bench section.
   No site token is touched. */
(function () {
	var bench = document.querySelector('.flavor-bench');
	if (!bench) return;

	var slider = document.getElementById('flavor-bench-slider');
	var readout = bench.querySelector('[data-bench-readout]');
	var values = bench.querySelector('[data-bench-values]');

	/* { hue, chroma } - chroma is the mid-tone mass chroma; the other roles
	   derive from it. Tune anchors here, by eye, on this bench. */
	var WAYPOINTS = [
		{ hue: 20, chroma: 0.13 },   /* coral / terracotta */
		{ hue: 55, chroma: 0.13 },   /* amber */
		{ hue: 95, chroma: 0.11 },   /* warm yellow (light only - see jump) */
		{ hue: 140, chroma: 0.12 },  /* green - the olive mud zone is skipped */
		{ hue: 170, chroma: 0.11 },  /* emerald */
		{ hue: 215, chroma: 0.09 },  /* cyan-blue (low gamut ceiling here) */
		{ hue: 255, chroma: 0.12 },  /* blue */
		{ hue: 285, chroma: 0.13 },  /* indigo / violet */
		{ hue: 325, chroma: 0.14 },  /* fuchsia */
		{ hue: 355, chroma: 0.13 }   /* rose */
	];

	var BOOKMARKS = [
		{ at: 140, name: 'nature' },
		{ at: 255, name: 'systems' },
		{ at: 325, name: 'berry' }
	];

	function paletteAt(position) {
		/* position 0..1 -> a spot on the waypoint path (piecewise linear). */
		var span = WAYPOINTS.length - 1;
		var index = Math.min(Math.floor(position * span), span - 1);
		var t = position * span - index;
		var a = WAYPOINTS[index];
		var b = WAYPOINTS[index + 1];

		return {
			hue: a.hue + (b.hue - a.hue) * t,
			chroma: a.chroma + (b.chroma - a.chroma) * t
		};
	}

	function nearestBookmark(hue) {
		var best = null;

		BOOKMARKS.forEach(function (mark) {
			var distance = Math.abs(mark.at - hue);
			if (distance < 20 && (!best || distance < best.distance)) {
				best = { name: mark.name, distance: distance };
			}
		});

		return best ? best.name + '-ish' : 'unnamed';
	}

	function apply() {
		var palette = paletteAt(slider.value / Number(slider.max));
		var h = palette.hue.toFixed(1);
		var c = palette.chroma.toFixed(3);

		/* The role recipe: ground barely tinted, mass mid-light at the
		   anchor's corrected chroma, ink deep and quieter, accent loud and
		   pushed 140deg across the wheel (the cross-hue playfulness the
		   expressive takes established). */
		bench.style.setProperty('--bench-ground', 'oklch(97% ' + (palette.chroma * 0.15).toFixed(3) + ' ' + h + ')');
		bench.style.setProperty('--bench-mass', 'oklch(83% ' + c + ' ' + h + ')');
		bench.style.setProperty('--bench-ink', 'oklch(32% ' + (palette.chroma * 0.55).toFixed(3) + ' ' + h + ')');
		/* Cap the accent's chroma: x1.7 overshoots the sRGB gamut in some
		   zones (emerald worst), and the clipping engaging mid-drag reads
		   as a glitch. 0.19 stays displayable at 64% lightness on every
		   hue. */
		var accentChroma = Math.min(palette.chroma * 1.7, 0.19).toFixed(3);
		bench.style.setProperty('--bench-accent', 'oklch(64% ' + accentChroma + ' ' + ((palette.hue + 140) % 360).toFixed(1) + ')');

		readout.textContent = nearestBookmark(palette.hue);
		values.textContent = 'hue ' + h + ' · chroma ' + c + ' · accent hue ' + ((palette.hue + 140) % 360).toFixed(0);

		/* The REAL test: the page's Poster card specimen (real template, real
		   art) wears the generated palette too - inline on the article, so it
		   out-cascades every take. Bench-only styling on a bench page. */
		var specimen = document.querySelector('.ds-section .timeline .milestone');
		if (specimen) {
			specimen.style.setProperty('--poster-fill-secondary', 'oklch(83% ' + c + ' ' + h + ')');
			specimen.style.setProperty('--poster-ink', 'oklch(32% ' + (palette.chroma * 0.55).toFixed(3) + ' ' + h + ')');
			specimen.style.setProperty('--poster-accent', 'oklch(64% ' + accentChroma + ' ' + ((palette.hue + 140) % 360).toFixed(1) + ')');
			specimen.style.setProperty('--year-fill', 'oklch(90% ' + (palette.chroma * 0.8).toFixed(3) + ' ' + h + ')');
			specimen.style.setProperty('--year-ink', 'oklch(30% ' + (palette.chroma * 0.5).toFixed(3) + ' ' + h + ')');
			specimen.style.setProperty('--year-padding', '0.25em 0.75em');
			specimen.style.setProperty('--year-corners', '1em');
		}
	}

	slider.addEventListener('input', apply);

	apply();
})();
