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
