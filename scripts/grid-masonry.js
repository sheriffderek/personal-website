/* Lane dealer for grid view (loaded only when GRID_VIEW_ENABLED).

   The wall is N real columns - plain flex stacks - and this script's ONE
   job is the initial deal: distribute the cards across the lanes, in
   order, with a height correction so no lane runs away. After the deal
   the page is on its own: a read-more unfolds and pushes its own lane
   down naturally, media loads, type rescales - no spans, no measuring
   observers, no repacking. The initial setup is what we control; the
   wear from reading is honest and stays.

   (This replaced the measured-row-span masonry, 2026-07-12. Setting
   every card's height meant owning every height change forever - unfold,
   media load, font swap all needed a re-pack, and re-packing shifted the
   whole wall under the reader. Distribute-then-let-flow costs none of
   that.)

   The deal re-runs only at deliberate re-setup moments: entering grid
   view, a filter change (settings-panel.js announces 'timeline:filtered'),
   or the lane count changing at a breakpoint. Leaving grid view hands
   every card back to the one timeline list in source order.

   No-JS fallback: without the dealer the timeline keeps its aligned-rows
   grid (grid-view.css) - correct order, craters accepted. */

(function () {
	var html = document.documentElement;
	var timeline = document.querySelector("[role='list'].timeline");
	if (!timeline) return;

	/* Source order, captured once - the deal and the restore both walk
	   this list, so the chronology can never be lost to reshuffling. */
	var cards = Array.prototype.slice.call(timeline.querySelectorAll(':scope > li'));

	function inGrid() {
		return html.getAttribute('data-view') === 'grid';
	}

	function laneCount() {
		return parseInt(getComputedStyle(html).getPropertyValue('--grid-columns'), 10) || 1;
	}

	/* Put every card back in the timeline itself, in source order. The
	   base for every deal, and the whole of leaving grid view. */
	function restore() {
		cards.forEach(function (li) {
			timeline.appendChild(li);
		});

		timeline.querySelectorAll(':scope > .timeline-lane').forEach(function (lane) {
			lane.remove();
		});

		timeline.classList.remove('is-laned');
	}

	/* The deal: walk the cards left-to-right, one lane per card, like
	   dealing a hand - BUT if the lane the cycle wants is already taller
	   than the shortest lane by more than the tolerance (about half an
	   average card), the card goes to the shortest lane instead and the
	   sweep resumes after it. Mostly chronological rows, self-correcting
	   before any lane runs away. Heights are read once, before dealing -
	   they only steer the assignment, they're never written anywhere. */
	function deal() {
		restore();

		var count = laneCount();
		var pool = cards.filter(function (li) {
			return li.style.display !== 'none';
		});

		if (!pool.length) return;

		/* Measure the CARD (the article), not the item: in the aligned-rows
		   fallback the items stretch to their row's height, so an item
		   measure would feed the deal the row's max instead of the card's
		   own size. */
		var heights = pool.map(function (li) {
			var card = li.firstElementChild;
			return card ? card.getBoundingClientRect().height : 0;
		});

		var total = 0;
		heights.forEach(function (h) { total += h; });
		var average = total / pool.length;

		/* One candidate deal at a given tolerance: returns each card's
		   lane and the resulting bottom spread. Pure arithmetic - nothing
		   touches the DOM until a winner is picked. */
		function tryDeal(tolerance) {
			var laneHeights = [];
			for (var i = 0; i < count; i++) {
				laneHeights.push(0);
			}

			var assignment = [];
			var cursor = 0;
			heights.forEach(function (h) {
				var next = cursor % count;

				var shortest = 0;
				for (var c = 1; c < count; c++) {
					if (laneHeights[c] < laneHeights[shortest]) shortest = c;
				}

				var chosen = laneHeights[next] - laneHeights[shortest] > tolerance ? shortest : next;
				assignment.push(chosen);
				laneHeights[chosen] += h;
				cursor = chosen + 1;
			});

			return {
				assignment: assignment,
				spread: Math.max.apply(null, laneHeights) - Math.min.apply(null, laneHeights),
			};
		}

		/* No single tolerance suits every card set (measured: a factor
		   that's near-perfect at one filter setting is terrible at
		   another), so try a handful and keep the deal with the tightest
		   bottom. Ties go to the LARGER tolerance - fewer corrections,
		   more purely chronological rows. */
		var best = null;
		[1, 0.75, 0.5, 0.375, 0.25].forEach(function (factor) {
			var candidate = tryDeal(average * factor);
			if (!best || candidate.spread < best.spread) {
				best = candidate;
			}
		});

		var lanes = [];
		for (var i = 0; i < count; i++) {
			var lane = document.createElement('div');
			lane.className = 'timeline-lane';
			lane.setAttribute('role', 'presentation');
			lanes.push(lane);
		}

		pool.forEach(function (li, index) {
			lanes[best.assignment[index]].appendChild(li);
		});

		lanes.forEach(function (lane) {
			timeline.appendChild(lane);
		});

		timeline.classList.add('is-laned');
	}

	function sync() {
		if (inGrid()) {
			deal();
		} else {
			restore();
		}
	}

	/* Re-setup moments only - never in response to reading. */

	/* data-view is the single switch for view behavior - watch it flip.
	   Note: settings-panel.js re-stamps the attribute on its debounced
	   resize re-applies, and a MutationObserver fires on any re-stamp -
	   so the wall quietly re-deals as the window resizes, keeping the
	   bottoms even at every width. Deliberate: the deal is cheap (pure
	   arithmetic plus two DOM moves), and this is why resizing "just
	   works" - don't optimize the re-stamps away. */
	new MutationObserver(sync).observe(html, { attributes: true, attributeFilter: ['data-view'] });

	/* The filter changed which cards are visible (announced by
	   settings-panel.js). */
	window.addEventListener('timeline:filtered', function () {
		if (inGrid()) deal();
	});

	/* The lane count changes at a breakpoint (2 lanes -> 3). Only a
	   changed count re-deals - plain width changes just reflow. */
	var dealtCount = null;
	var resizeTimer = null;
	window.addEventListener('resize', function () {
		if (resizeTimer) clearTimeout(resizeTimer);
		resizeTimer = setTimeout(function () {
			if (inGrid() && laneCount() !== dealtCount) sync();
			dealtCount = laneCount();
		}, 150);
	});

	/* Webfonts landing rewrite every card's height, and a deal computed on
	   pre-swap heights optimizes for numbers that no longer exist (seen
	   live: ~15x the bottom spread). Fonts-ready is the last setup moment. */
	if (document.fonts && document.fonts.ready) {
		document.fonts.ready.then(function () {
			if (inGrid()) deal();
		});
	}

	dealtCount = laneCount();
	sync();
})();
