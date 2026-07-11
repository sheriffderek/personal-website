/* Z-order masonry for grid view (loaded only when GRID_VIEW_ENABLED).

   The wall must read in a Z - across the columns, then down - AND pack
   tight despite very different card heights. No stable browser has native
   masonry yet, so this is the measured-row-span technique: the timeline
   grid uses 1px auto-rows (grid-view.css, .is-packed), each card gets a
   row span matching its measured height, and grid auto-placement walks
   the cards left-to-right - the Z comes free.

   Heights change constantly - a brand swap rescales the type, media
   finishes loading, a read-more unfolds, the window resizes a column -
   so one ResizeObserver on every card keeps its span true. No manual
   resize or slider hooks; anything that changes a card's box re-spans it.

   When native masonry ("grid lanes") ships, delete this file and switch
   .is-packed to the native declaration. */

(function () {
	var html = document.documentElement;
	var timeline = document.querySelector("[role='list'].timeline");
	if (!timeline) return;

	/* The span ruler: must match .is-packed { grid-auto-rows: 8px }. Coarser
	   than 1px on purpose - the spans read as sane numbers in devtools
	   (span 72, not span 572) and the browser tracks 8x fewer implicit
	   rows. Cards round UP to the next ruler line, so the cost is at most
	   7px of extra air below a card - invisible at wall scale. */
	var ROW_UNIT = 8;

	/* Breathing room below each card, baked into its span (packed mode has
	   no row-gap). Matches the 4rem row-gap of the unpacked fallback. */
	var CARD_GAP = 64;

	function inGrid() {
		return html.getAttribute('data-view') === 'grid';
	}

	function items() {
		return timeline.querySelectorAll(':scope > li');
	}

	/* Every visible card gets two placements:
	     - its LANE: item i goes to column (i % lane count), so the order
	       reads strictly across then down - 1 2 3 / 4 5 6 - never "whichever
	       lane is shortest" (which could make a row read backwards).
	     - its SPAN: the card's measured height on the 1px row ruler. The
	       card (the article) is measured rather than the item, because the
	       item's height IS the span - measuring it would feed back into
	       itself.
	   Always a full pass: the filter changes which cards are visible, and
	   every index after a removed card shifts by one. */
	function packAll() {
		var laneCount = parseInt(getComputedStyle(html).getPropertyValue('--grid-columns'), 10) || 1;
		var lane = 0;

		/* Measure everything first, then write everything: interleaving a
		   style write with the next card's measurement would force the
		   browser to re-run layout once per card. */
		var measured = [];

		items().forEach(function (li) {
			var card = li.firstElementChild;
			if (!card) return;
			measured.push({ li: li, height: card.getBoundingClientRect().height });
		});

		measured.forEach(function (entry) {
			if (entry.height === 0) return; /* filtered out - nothing to place */

			entry.li.style.gridColumn = String((lane % laneCount) + 1);
			entry.li.style.gridRowEnd = 'span ' + Math.max(1, Math.ceil((entry.height + CARD_GAP) / ROW_UNIT));
			lane++;
		});
	}

	function unpackAll() {
		items().forEach(function (li) {
			li.style.gridColumn = '';
			li.style.gridRowEnd = '';
		});
	}

	function sync() {
		if (inGrid()) {
			timeline.classList.add('is-packed');
			packAll();
		} else {
			timeline.classList.remove('is-packed');
			unpackAll();
		}
	}

	/* Re-pack whenever any card's box changes - a brand swap rescales type,
	   media loads, a read-more unfolds, the filter hides a card (size 0), a
	   resize changes column width. One full pass per frame at most: lane
	   assignment depends on every card's visibility, so per-card updates
	   aren't enough. Observing the card (not the item) avoids a feedback
	   loop: setting the span changes the ITEM's height, never the card's. */
	var repackFrame = null;

	function queueRepack() {
		if (repackFrame) return;
		repackFrame = requestAnimationFrame(function () {
			repackFrame = null;
			packAll();
		});
	}

	var observer = new ResizeObserver(function () {
		if (!inGrid()) return;
		queueRepack();
	});

	items().forEach(function (li) {
		if (li.firstElementChild) observer.observe(li.firstElementChild);
	});

	/* data-view is the single switch for view behavior - watch it flip. */
	new MutationObserver(sync).observe(html, { attributes: true, attributeFilter: ['data-view'] });

	sync();
})();
