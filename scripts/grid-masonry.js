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

	/* The span ruler: must match .is-packed { grid-auto-rows: 1px }. */
	var ROW_UNIT = 1;

	/* Breathing room below each card, baked into its span (packed mode has
	   no row-gap). Matches the 4rem row-gap of the unpacked fallback. */
	var CARD_GAP = 64;

	function inGrid() {
		return html.getAttribute('data-view') === 'grid';
	}

	/* Span the list item to its card's height. The card (the article) is
	   measured rather than the item itself, because the item's height IS
	   the span - measuring it would feed back into itself. */
	function packItem(li) {
		var card = li.firstElementChild;
		if (!card) return;

		var height = card.getBoundingClientRect().height;
		if (height === 0) return; /* filtered out - nothing to place */

		li.style.gridRowEnd = 'span ' + Math.max(1, Math.ceil((height + CARD_GAP) / ROW_UNIT));
	}

	function items() {
		return timeline.querySelectorAll(':scope > li');
	}

	function packAll() {
		items().forEach(packItem);
	}

	function unpackAll() {
		items().forEach(function (li) {
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

	/* Re-span a card whenever its box changes. Observing the card (not the
	   item) avoids a feedback loop: setting the span changes the ITEM's
	   height, never the card's. */
	var observer = new ResizeObserver(function (entries) {
		if (!inGrid()) return;

		entries.forEach(function (entry) {
			var li = entry.target.parentElement;
			if (li) packItem(li);
		});
	});

	items().forEach(function (li) {
		if (li.firstElementChild) observer.observe(li.firstElementChild);
	});

	/* data-view is the single switch for view behavior - watch it flip. */
	new MutationObserver(sync).observe(html, { attributes: true, attributeFilter: ['data-view'] });

	sync();
})();
