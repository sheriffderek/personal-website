/* Display settings toolbox — single data-driven loop for switcher rows,
   plus the timeline filter slider (its own shape, not a switcher).
   Native <popover> handles open/close, Esc, and light-dismiss. */
(function () {
	var html = document.documentElement;

	/* Public control surface for the guided-tour spike (scripts/tour.js).
	   The tour drives the REAL controls, but must never touch the visitor's
	   saved preferences - so every applier takes {persist:false}, and this map
	   lets the tour reach the per-switcher appliers by kind ('scheme'/'sound'). */
	var applyByKind = {};

	function shouldPersist(opts) {
		return !opts || opts.persist !== false;
	}

	/* Keep-your-place-through-a-reflow.

	   Theme swaps change the type scale and font metrics, so every milestone
	   grows or shrinks and the whole page height shifts under the reader. If
	   we just leave scrollY alone, the card they were reading jumps away.

	   The fix isn't to remember a scroll number (that number means something
	   different after the reflow). It's to remember an ELEMENT. We pick the
	   milestone sitting closest to the middle of the screen, note how far its
	   top is from the top of the viewport, run the change, then on the next
	   frame nudge the scroll by however much that same top moved. The card
	   stays put under the reader's eye; the rest of the page reflows around
	   it. */
	/* Anchor candidates are the page header plus every milestone - any major
	   section the reader might be sitting on. Anchoring only to milestones
	   broke near the top: with the header filling the viewport middle, there's
	   no centered milestone, so we'd pin to the first card below the fold and
	   the header would jump by that card's reflow delta.

	   Optional `willSurvive(card)` lets a caller anchor only to sections that
	   will still exist after the change. A theme swap keeps everything, so it
	   passes nothing. A narrowing filter can delete the milestone under the
	   reader's eye - so it passes a test, and we anchor to the nearest section
	   that stays rather than pinning to one about to vanish (which would break
	   the math). The header never filters away, so the test only gates
	   milestones. */
	function centeredSection(willSurvive) {
		var middle = window.innerHeight / 2;
		var sections = document.querySelectorAll('.page-header, .milestone');
		var closest = null;
		var closestDistance = Infinity;

		sections.forEach(function (section) {
			var box = section.getBoundingClientRect();

			/* Filtered-out cards collapse to zero height - skip them. */
			if (box.height === 0) return;

			if (willSurvive && section.matches('.milestone') && !willSurvive(section)) return;

			var sectionCenter = box.top + box.height / 2;
			var distance = Math.abs(sectionCenter - middle);

			if (distance < closestDistance) {
				closestDistance = distance;
				closest = section;
			}
		});

		return closest;
	}

	/* Which point of the section we actually pin. We pick the section by its
	   whole box (centeredSection), but pinning a section's TOP pins its heading,
	   and a theme swap resizes that heading - most dramatically the Display
	   theme, which balloons the h1 and shoves everything below it down. You can
	   hold the heading still OR the body still, never both, because the space
	   between them is what's changing. The body is what the reader is actually
	   reading (the heading is a landmark they've already passed), so we pin the
	   first block BELOW the heading and let the heading grow upward off the top:

	     - milestone with media  -> the media frame (theme-stable: its height is
	       aspect-ratio locked and width-driven, so font metrics never move it)
	     - text-only milestone    -> its body copy (.info)
	     - page header            -> its first paragraph (the year/setup isn't in
	       the header, so the first <p> is the intro - the first thing to hold)

	   One rule everywhere: pin the first thing below the heading. Anything with
	   no such block falls back to its own top. */
	function anchorPoint(section) {
		if (section.matches('.page-header')) {
			/* The header pins the top of its h1. When the welcome video is
			   live (<figure class='welcome-video'>, gated by TOUR_ENABLED in
			   config.php - see templates/pages/home.php), the anchor should pin
			   THAT instead: it sits above the intro and, being a fixed-ratio video,
			   is theme-stable like a milestone's media. So prefer it here -
			   `section.querySelector('.welcome-video, h1')` - and it wins whenever
			   it's rendered, falling through to the h1 when the tour is off. */
			return section.querySelector('h1') || section;
		}

		return section.querySelector('.media, .info') || section;
	}

	/* One correction can be owed at a time. A fast slider drag fires several
	   `input` events before the browser paints, so the frame-delayed shift from
	   the previous event may not have run yet. If we measure the next `topBefore`
	   now, we're reading a layout we disturbed but never put back, and the two
	   shifts fight over where the card belongs - it lands at the displaced spot.
	   So each syncScroll pays off any pending correction first, then measures. */
	var pendingFrame = null;
	var pendingShift = null;

	function settlePending() {
		if (!pendingShift) return;

		cancelAnimationFrame(pendingFrame);
		var shiftNow = pendingShift;
		pendingFrame = null;
		pendingShift = null;
		shiftNow();
	}

	function syncScroll(applyChange, willSurvive) {
		/* Per-view interaction decisions key off data-view - this is the one
		   switch. In grid view the lanes re-pack wholesale on any change, so
		   there's no stable "card under the reader's eye" to hold; anchoring
		   would just fight the re-pack. Apply the change plainly and let the
		   wall reflow. */
		if (html.getAttribute('data-view') === 'grid') {
			/* Discard (never run) any correction still owed from list view -
			   its measurements described a layout that no longer exists, and
			   letting it fire after the lanes re-pack would jump the page. */
			if (pendingFrame !== null) cancelAnimationFrame(pendingFrame);
			pendingFrame = null;
			pendingShift = null;

			applyChange();
			return;
		}

		settlePending();

		var anchor = centeredSection(willSurvive);
		var pin = anchor ? anchorPoint(anchor) : null;
		var topBefore = pin ? pin.getBoundingClientRect().top : 0;

		applyChange();

		if (!pin) return;

		/* Wait one frame so the browser has recalculated layout with the new
		   type scale before we measure where the pin landed. */
		pendingShift = function () {
			var topAfter = pin.getBoundingClientRect().top;
			var shift = topAfter - topBefore;

			if (shift) window.scrollBy(0, shift);
		};

		pendingFrame = requestAnimationFrame(settlePending);
	}

	var SWITCHERS = [
		{ kind: 'scheme', attr: 'data-scheme', storageKey: 'scheme-preference', defaultValue: 'system' },
		{ kind: 'sound',  attr: 'data-sound',  storageKey: 'sound-preference',  defaultValue: 'off' }
	];

	/* Each switcher is a single-select radiogroup (role=radio buttons). We
	   reflect the choice with aria-checked and keep exactly one radio tabbable
	   (roving tabindex) so Tab moves in/out of the group and arrow keys move
	   between the options — the WAI-ARIA radiogroup pattern. */
	SWITCHERS.forEach(function (cfg) {
		var buttons = document.querySelectorAll('[data-set-' + cfg.kind + ']');
		if (!buttons.length) return;

		var persists = !!cfg.storageKey;
		var saved = null;
		try {
			if (persists) saved = localStorage.getItem(cfg.storageKey);
		} catch (error) {
			/* private-mode storage throw — treat as unset */
		}
		var current = saved || cfg.defaultValue;

		function valueOf(button) {
			return button.getAttribute('data-set-' + cfg.kind);
		}

		/* Show which radio is checked and move the single tab stop to it. */
		function reflect(selectedValue) {
			buttons.forEach(function (button) {
				var isSelected = valueOf(button) === selectedValue;
				button.setAttribute('aria-checked', isSelected ? 'true' : 'false');
				button.setAttribute('tabindex', isSelected ? '0' : '-1');
			});
		}

		/* Apply the value to <html> and reflect the radios. Persistence is a
		   separate concern: real user actions persist; the tour passes
		   {persist:false} so the demo never overwrites saved preferences. */
		function apply(value, opts) {
			if (value === cfg.defaultValue) {
				html.removeAttribute(cfg.attr);
				if (persists && shouldPersist(opts)) {
					try { localStorage.removeItem(cfg.storageKey); } catch (error) {}
				}
			} else {
				html.setAttribute(cfg.attr, cfg.valuelessAttr ? '' : value);
				if (persists && shouldPersist(opts)) {
					try { localStorage.setItem(cfg.storageKey, value); } catch (error) {}
				}
			}

			reflect(value);
		}

		applyByKind[cfg.kind] = apply;

		function choose(button, moveFocus) {
			var value = valueOf(button);

			apply(value);
			if (moveFocus) button.focus();

			if (cfg.kind === 'sound' && window.ui && window.ui.sound) {
				/* Plays only when going on (audio.js gates on data-sound='on').
				   Going off is intentionally silent. */
				window.ui.sound(value === 'on' ? 'toggle-on' : 'toggle-off');
			}
		}

		/* Apply the saved state to <html> on load — otherwise the selection
		   shows but the attribute-selector hooks (data-sound, data-scheme)
		   aren't actually in effect until the user clicks. */
		if (current !== cfg.defaultValue) {
			html.setAttribute(cfg.attr, cfg.valuelessAttr ? '' : current);
		}
		reflect(current);

		buttons.forEach(function (button, index) {
			button.addEventListener('click', function () {
				choose(button, false);
			});

			button.addEventListener('keydown', function (event) {
				var step = 0;
				if (event.key === 'ArrowRight' || event.key === 'ArrowDown') step = 1;
				if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') step = -1;
				if (!step) return;

				event.preventDefault();
				var next = (index + step + buttons.length) % buttons.length;
				choose(buttons[next], true);
			});
		});
	});

	/* Brand + emphasis — the two design-system axes, each a slider (not
	   buttons). Index maps to a slug. The first slug is the default: it means
	   "no attribute on <html>" (the :root block in settings.css IS the
	   default), and no storage key is written for it so first-load defaults
	   stay clean. Both sliders share one wiring, so they can't drift apart.

	     Brand    (data-brand)    - type pair, corners, scale rhythm.
	     Emphasis (data-emphasis) - color palette only.

	   A brand swap changes the type scale, so both go through syncScroll to
	   hold the reader's place through the reflow (color-only emphasis swaps
	   don't strictly need it, but the shared path keeps the story simple). */
	function sliderSwitcher(cfg) {
		var slider = document.querySelector('[data-set-' + cfg.kind + '-slider]');
		var nameEl = document.querySelector('[data-' + cfg.kind + '-name]');

		function apply(idx, opts) {
			var clamped = Math.max(0, Math.min(cfg.values.length - 1, idx));
			var value = cfg.values[clamped];
			if (value === cfg.values[0]) {
				html.removeAttribute(cfg.attr);
			} else {
				html.setAttribute(cfg.attr, value);
			}
			if (shouldPersist(opts)) {
				try {
					if (value === cfg.values[0]) {
						localStorage.removeItem(cfg.storageKey);
					} else {
						localStorage.setItem(cfg.storageKey, value);
					}
				} catch (error) {}
			}
			if (nameEl) nameEl.textContent = cfg.names[clamped];
			if (slider) slider.value = String(clamped);
		}

		applyByKind[cfg.kind] = apply;

		if (slider) {
			var saved = null;
			try { saved = localStorage.getItem(cfg.storageKey); } catch (error) {}
			var initialIdx = saved ? cfg.values.indexOf(saved) : 0;
			if (initialIdx < 0) initialIdx = 0;
			apply(initialIdx, { persist: false });
			slider.addEventListener('input', function () {
				syncScroll(function () {
					apply(parseInt(slider.value, 10) || 0);
				});
				if (window.ui && window.ui.sound) {
					var t = parseFloat(slider.value) / (cfg.values.length - 1);
					window.ui.sound('tick', t);
				}
			});
		}

		return apply;
	}

	/* Keep these lists matched to the FOUC script in includes/header.php and
	   the sliders' max in includes/settings/{brand,emphasis}-switcher.php. */
	var BRANDS         = ['personal', 'marketing', 'product', 'documentation'];
	var BRAND_NAMES    = ['Personal', 'Marketing', 'Product', 'Documentation'];
	var EMPHASES       = ['default', 'muted', 'immersive', 'red-light'];
	var EMPHASIS_NAMES = ['Default', 'Muted', 'Immersive', 'Red light'];

	var applyBrand = sliderSwitcher({
		kind: 'brand',
		attr: 'data-brand',
		storageKey: 'brand-preference',
		values: BRANDS,
		names: BRAND_NAMES
	});

	var applyEmphasis = sliderSwitcher({
		kind: 'emphasis',
		attr: 'data-emphasis',
		storageKey: 'emphasis-preference',
		values: EMPHASES,
		names: EMPHASIS_NAMES
	});

	/* Timeline filter — slider sets number of weight tiers shown, cumulative.
	   1 = weight-1 entries only (the gap-covered product-design pitch),
	   6 = everything. Weight 1 is the HIGHEST tier — the slider value is also
	   the deepest weight shown, so "show tiers 1..n" is just weight <= n.
	   Six tiers per the weight rubric in CLAUDE.md. Names read cumulatively —
	   each step ADDS to the view above it. 2–5 are working phrasings; reword
	   freely (the label also shows the live count / total). */
	var FILTER_NAMES = {
		1: 'Core product work',
		2: '+ major support',
		3: '+ broader projects',
		4: '+ range & R&D',
		5: '+ craft & tooling',
		6: '+ other influences'
	};
	var filterSlider = document.querySelector('[data-set-filter]');
	var miniMap      = document.querySelector('.mini-map-bars');
	var filterName   = document.querySelector('[data-filter-name]');
	var filterCount  = document.querySelector('[data-filter-count]');
	var filterTotal  = document.querySelector('[data-filter-total]');
	/* Both depths on purpose: in grid view the lane dealer re-parents the
	   items into .timeline-lane wrappers. A bare '.timeline li' would also
	   catch list items INSIDE card content (tag lists, document links). */
	var entries      = document.querySelectorAll('.timeline > li, .timeline > .timeline-lane > li');
	var MAX_WEIGHT   = 6;

	/* Total is the count in this tag lane (what actually rendered), so the
	   label reads e.g. "14 / 35" and tops out at "35 / 35". */
	if (filterTotal) { filterTotal.textContent = String(entries.length); }

	if (miniMap && entries.length) {
		entries.forEach(function (li) {
			var article = li.querySelector('[data-weight]');
			var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : MAX_WEIGHT;
			var bar = document.createElement('li');
			bar.setAttribute('data-weight', String(weight));
			miniMap.appendChild(bar);
		});
	}

	var FILTER_DEFAULT = 1;

	function hookSettleSound(flkty, c) {
		if (!flkty || c.dataset.soundHooked) return;
		c.dataset.soundHooked = '1';
		/* Wait through the init window — Flickity fires settle once per
		   carousel as it sets up, and with many carousels those stack into
		   one loud burst. Only listen for real user-initiated settles. */
		setTimeout(function () {
			flkty.on('settle', function () {
				/* $todo: settle sound disabled for now — the idea seemed good but it
				   wasn't landing, felt off. Re-enable by uncommenting the line below. */
				// if (window.ui && window.ui.sound) window.ui.sound('settle');
			});
		}, 300);
	}

	function ensureCarousels(li) {
		if (!window.Flickity) return;
		requestAnimationFrame(function () {
			li.querySelectorAll('.carousel').forEach(function (c) {
				var flkty = window.Flickity.data(c);
				if (flkty && flkty.size && flkty.size.width > 0) {
					flkty.resize();
					hookSettleSound(flkty, c);
					return;
				}
				if (flkty) flkty.destroy();
				var opts = c.getAttribute('data-flickity');
				try { opts = opts ? JSON.parse(opts) : {}; } catch (error) { opts = {}; }
				flkty = new window.Flickity(c, opts);
				hookSettleSound(flkty, c);
			});
		});
	}

	function applyFilter(tiersShown, opts) {
		var bars = miniMap ? miniMap.children : [];
		var inCount = 0;
		entries.forEach(function (li, i) {
			var article = li.querySelector('[data-weight]');
			var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : MAX_WEIGHT;
			var isIn = weight <= tiersShown;
			if (isIn) inCount++;
			li.style.display = isIn ? '' : 'none';
			if (bars[i]) bars[i].setAttribute('data-state', isIn ? 'in' : 'out');
			if (isIn) ensureCarousels(li);
		});
		if (filterName) filterName.textContent = FILTER_NAMES[tiersShown] || '';
		if (filterCount) filterCount.textContent = String(inCount);
		/* The visible tier name is decorative (small, hidden on narrow screens),
		   so the slider itself announces the tier for assistive tech. */
		if (filterSlider) {
			filterSlider.setAttribute('aria-valuetext', (FILTER_NAMES[tiersShown] || String(tiersShown)) + ', ' + inCount + ' entries shown');
		}
		if (shouldPersist(opts)) {
			try {
				if (tiersShown === FILTER_DEFAULT) {
					localStorage.removeItem('filter-preference');
				} else {
					localStorage.setItem('filter-preference', String(tiersShown));
				}
			} catch (error) {}

			/* A moved filter outdates any #milestone in the URL - the visitor
			   has re-scoped the timeline (the hash may even point at a card
			   the filter just hid), so the link to one moment in it is done.
			   replaceState clears it without scrolling or adding a history
			   entry. Only on real moves - restoring a saved filter at load
			   must not eat a hash the visitor arrived with. */
			if (window.location.hash) {
				history.replaceState(null, '', window.location.pathname + window.location.search);
			}
		}
		if (filterSlider) filterSlider.value = String(tiersShown);

		/* Announce the new visible set - the grid's lane dealer re-deals on
		   this (a filter change is a deliberate re-setup moment). */
		window.dispatchEvent(new CustomEvent('timeline:filtered'));
	}

	var savedFilter = null;
	try { savedFilter = localStorage.getItem('filter-preference'); } catch (error) {}
	var initialFilter = savedFilter ? parseInt(savedFilter, 10) : FILTER_DEFAULT;
	if (isNaN(initialFilter) || initialFilter < 1 || initialFilter > MAX_WEIGHT) initialFilter = FILTER_DEFAULT;

	function cardWeight(card) {
		var article = card.matches('[data-weight]') ? card : card.querySelector('[data-weight]');
		return article ? parseInt(article.getAttribute('data-weight'), 10) : MAX_WEIGHT;
	}

	if (filterSlider) {
		filterSlider.addEventListener('input', function () {
			var tiersShown = parseInt(filterSlider.value, 10);

			syncScroll(
				function () {
					applyFilter(tiersShown);
				},
				function willSurvive(card) {
					return cardWeight(card) <= tiersShown;
				}
			);

			if (window.ui && window.ui.sound) {
				var t = (parseFloat(filterSlider.value) - 1) / (MAX_WEIGHT - 1);
				window.ui.sound('tick', t);
			}
		});
	}

	/* Deep-link vs. filter: a shared link like /#pe-figure-cms-options can point
	   at a milestone the default filter (weight 1 only) hides. At load that
	   target is display:none, so the browser's native hash-scroll lands on a
	   collapsed element - the link looks broken. So before the first applyFilter,
	   if the hash names a milestone, widen the initial filter just enough to
	   reveal that milestone's weight, then scroll it in ourselves (the native
	   scroll already ran against the hidden element and missed). The slider ends
	   up reading the widened tier, honestly reflecting what's on screen.
	   With weight 1 as the top tier, a card's weight IS the tier count that
	   reveals it. */
	function tiersToReveal(weight) {
		return weight;
	}

	var hashTarget = null;
	if (window.location.hash.length > 1) {
		var slug = decodeURIComponent(window.location.hash.slice(1));
		hashTarget = document.getElementById(slug);
		if (hashTarget && hashTarget.matches('.milestone')) {
			var needed = tiersToReveal(cardWeight(hashTarget));
			if (needed > initialFilter) initialFilter = needed;
		} else {
			hashTarget = null;
		}
	}

	/* persist: false - this is restoring (or hash-widening) a view, not the
	   reader choosing one. Without it, following a #milestone link SAVED the
	   widened tier as if it were a preference, and every later visit came
	   back at that spot. Only a real slider input persists. */
	if (entries.length) applyFilter(initialFilter, { persist: false });

	if (hashTarget) {
		/* Park the revealed card (scroll-margin-top keeps the heading off the
		   viewport top). We scroll on the next frame for an immediate landing,
		   then again on load: this script is deferred, so at first it runs before
		   the carousels above the target initialize and images decode - that
		   reflow moves the target out from under us. The load pass corrects for
		   it, unless the reader has already taken over and scrolled themselves. */
		var parkTarget = function () {
			hashTarget.scrollIntoView();
		};

		requestAnimationFrame(parkTarget);

		var userTookOver = false;
		window.addEventListener('wheel', function () { userTookOver = true; }, { passive: true, once: true });
		window.addEventListener('touchmove', function () { userTookOver = true; }, { passive: true, once: true });

		window.addEventListener('load', function () {
			if (!userTookOver) parkTarget();
		});
	}

	/* View - List or Grid (the wall of work). Rendered only when
	   GRID_VIEW_ENABLED (config.php); when the flag is off the buttons don't
	   exist and this whole section stands down.

	   It looks like the SWITCHERS radios above but earns its own wiring for
	   two reasons: the saved PREFERENCE and the APPLIED state differ (grid
	   only exists from 1600px - below that a saved grid choice waits,
	   unapplied, for the next big screen), and applying it swaps real chrome
	   (the settings panel leaves its popover and sits inline at the top of
	   the page). Keep GRID_MIN matched to the breakpoint in
	   styles/layouts/grid-view.css and the FOUC script in header.php. */
	var viewButtons = document.querySelectorAll('[data-set-view]');
	var GRID_MIN = window.matchMedia('(min-width: 1600px)');
	var currentView = 'list';

	/* The grid invite (rail button, markup in settings-panel.php) pulses
	   until the visitor has entered grid view once - by any door, the invite
	   or the panel's Grid pill - then settles into a plain toggle forever
	   (localStorage breadcrumb, same pattern as the passkey button). */
	var inviteButton = document.querySelector('[data-grid-invite]');

	function markInviteSeen() {
		try { localStorage.setItem('grid-invite-seen', '1'); } catch (error) {}
		if (inviteButton) inviteButton.classList.add('is-seen');
	}

	function applyView(value, opts) {
		currentView = value;

		var applied = value === 'grid' && GRID_MIN.matches ? 'grid' : 'list';

		/* Deliberately ENTERING grid view retires the invite's pulse - the
		   applied state, not the preference, so a too-narrow window (where
		   the grid never showed) doesn't count as having seen it. */
		if (applied === 'grid' && shouldPersist(opts)) markInviteSeen();

		/* Suppress the panel's open/close opacity transition across the swap.
		   grid<->list flips the panel between its inline and popover styling in
		   one tick; without this, the list popover's opacity:0 target would FADE
		   from the visible grid state - a boxed panel briefly fading out where it
		   should never be seen. Cleared next frame, so the trigger-driven
		   open/close animation still plays normally. */
		html.classList.add('is-switching-view');

		if (applied === 'grid') {
			html.setAttribute('data-view', 'grid');
		} else {
			html.removeAttribute('data-view');
		}

		/* The panel is a popover in list view, a plain inline top bar in grid
		   view. Removing the attribute is what makes it a normal, always-
		   visible div; grid-view.css handles everything visual. */
		var panel = document.getElementById('menu-settings');
		if (panel) {
			if (applied === 'grid') {
				panel.removeAttribute('popover');
			} else if (!panel.hasAttribute('popover')) {
				panel.setAttribute('popover', '');
			}
		}

		/* Re-enable transitions once the transition-free swap has painted (double
		   rAF: the first frame commits the new state instantly, the second lets
		   normal open/close animations resume). */
		requestAnimationFrame(function () {
			requestAnimationFrame(function () {
				html.classList.remove('is-switching-view');
			});
		});

		if (shouldPersist(opts)) {
			try {
				if (value === 'list') {
					localStorage.removeItem('view-preference');
				} else {
					localStorage.setItem('view-preference', value);
				}
			} catch (error) {}
		}

		viewButtons.forEach(function (button) {
			var isSelected = button.getAttribute('data-set-view') === value;
			button.setAttribute('aria-checked', isSelected ? 'true' : 'false');
			button.setAttribute('tabindex', isSelected ? '0' : '-1');
		});

		/* The columns change every carousel's width, so Flickity must
		   re-measure; a synthetic scroll re-runs the loop autoplay checks in
		   footer.php against the new layout (they stand down in grid view). */
		entries.forEach(function (li) {
			if (li.style.display !== 'none') ensureCarousels(li);
		});
		window.dispatchEvent(new Event('scroll'));
	}

	if (viewButtons.length) {
		applyByKind.view = applyView;

		var inviteSeen = null;
		try { inviteSeen = localStorage.getItem('grid-invite-seen'); } catch (error) {}
		if (inviteSeen && inviteButton) inviteButton.classList.add('is-seen');

		if (inviteButton) {
			inviteButton.addEventListener('click', function () {
				applyView('grid');
				window.scrollTo(0, 0);
			});
		}

		var savedView = null;
		try { savedView = localStorage.getItem('view-preference'); } catch (error) {}
		applyView(savedView === 'grid' ? 'grid' : 'list', { persist: false });

		/* Same radiogroup keyboard pattern as the SWITCHERS above: one tab
		   stop (the checked radio, via the roving tabindex in applyView),
		   arrow keys move between the options. */
		/* A deliberate view switch scrolls to the top: the two layouts share no
		   scroll geometry (the wall is a fraction of the list's height), so
		   "where I was" means nothing across the change - and in grid view the
		   top is where the control bar lives. Only real clicks scroll; the
		   resize/media-query re-applies above never move the reader. */
		viewButtons.forEach(function (button, index) {
			button.addEventListener('click', function () {
				applyView(button.getAttribute('data-set-view'));
				window.scrollTo(0, 0);
			});

			button.addEventListener('keydown', function (event) {
				var step = 0;
				if (event.key === 'ArrowRight' || event.key === 'ArrowDown') step = 1;
				if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') step = -1;
				if (!step) return;

				event.preventDefault();
				var next = viewButtons[(index + step + viewButtons.length) % viewButtons.length];
				applyView(next.getAttribute('data-set-view'));
				next.focus();
			});
		});

		/* Crossing 1600px re-resolves the same preference: a saved grid choice
		   engages on growing past it and falls back to list on shrinking. The
		   debounced resize listener covers environments where the media-query
		   change event doesn't fire; re-applying an unchanged state is a no-op
		   visually, so the redundancy is harmless. */
		GRID_MIN.addEventListener('change', function () {
			applyView(currentView, { persist: false });
		});

		var viewResizeTimer = null;
		window.addEventListener('resize', function () {
			if (viewResizeTimer) clearTimeout(viewResizeTimer);
			viewResizeTimer = setTimeout(function () {
				applyView(currentView, { persist: false });
			}, 150);
		});

		/* Milestone title clicks are plain hash links in BOTH views. The grid
		   briefly diverted them into list view ("let me read this one"), but
		   a click that swaps the whole layout confuses more than a scroll
		   ever did - the cells carry the full card, so reading in place
		   works. Removed 2026-07-12; don't re-add without a rethink. */
	}

	/* Corner island (grid view only - markup in settings-panel.php, chrome in
	   grid-view.css, design spec in CLAUDE.md). Visible only while the inline
	   settings panel is off-screen: that is exactly the moment its jobs are
	   orphaned, so panel-exit IS the condition - no magic scroll depth. The
	   observer just reports the truth; the CSS gates it to grid view. */
	var island = document.querySelector('.corner-island');
	if (island) {
		var islandSettings = island.querySelector('[data-island-settings]');
		var islandTop = island.querySelector('[data-island-top]');
		var islandPanel = document.getElementById('menu-settings');

		if (islandPanel && 'IntersectionObserver' in window) {
			new IntersectionObserver(function (observed) {
				/* Only the INLINE panel's visibility is the condition. In
				   popover mode (opened from the island) the same node is
				   suddenly "visible" again - acting on that would hide the
				   island out from under its own open popover. Hold state
				   until the panel is back in its inline home. */
				if (islandPanel.hasAttribute('popover')) return;

				/* Gate on grid view, don't lean on the CSS display:none to
				   mask a wrong class. In list view the panel is a CLOSED
				   popover (display:none = "not intersecting"), which would set
				   is-visible=true and carry over the moment you toggle to grid -
				   the island flashing at the top. data-view is the one switch. */
				var inGrid = html.getAttribute('data-view') === 'grid';

				island.classList.toggle('is-visible', inGrid && !observed[0].isIntersecting);
			}).observe(islandPanel);
		}

		/* Settings: re-add the popover attribute to the SAME panel node and
		   pop it beside the island (positioning in grid-view.css). One panel
		   instance, never a duplicate. Closing (Esc, light-dismiss, or the
		   toggle below) returns it to its inline home at the top. */
		if (islandSettings && islandPanel) {
			islandSettings.addEventListener('click', function () {
				if (islandPanel.hasAttribute('popover')) {
					islandPanel.hidePopover();
					return;
				}
				islandPanel.setAttribute('popover', '');
				islandPanel.showPopover();
				islandSettings.setAttribute('aria-expanded', 'true');
			});

			islandPanel.addEventListener('toggle', function (event) {
				if (event.newState !== 'closed') return;
				islandSettings.setAttribute('aria-expanded', 'false');

				/* Only strip the attribute while the grid is applied - in
				   list view the panel is SUPPOSED to be a popover, and
				   applyView owns that state. */
				if (html.getAttribute('data-view') === 'grid') {
					islandPanel.removeAttribute('popover');
				}
			});
		}

		if (islandTop) {
			islandTop.addEventListener('click', function () {
				var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
			});
		}
	}

	/* Outside-tap dismiss, for BOTH menus (Settings + Pages). Native popover
	   light-dismiss is unreliable on iOS Safari (a styled ::backdrop swallows
	   the tap, and support only landed in 18.3), so we close it ourselves.
	   Only one auto-popover is open at a time; we track which from the `toggle`
	   event (rather than :popover-open, which can throw on older Safari) and
	   listen for both pointerdown and touchstart - touchstart is the raw touch
	   event iOS always fires. We compare the tap point to the panel's box, not
	   the event target (a ::backdrop tap reports the popover itself as target,
	   so a contains() check would wrongly keep it open). Any trigger is skipped
	   so its own tap toggles natively without a close-then-reopen race. */
	var toolboxTriggers = document.querySelectorAll('.toolbox-trigger');
	var toolboxPanels = document.querySelectorAll('.toolbox-panel');
	var openPanel = null;

	/* One shared dim behind whichever menu is open (see .menu-scrim in
	   modules/settings-panel.css for why it's one element, not per-popover
	   ::backdrop). We track the set of open panels rather than a single flag so a
	   SWITCH reconciles correctly.

	   The switch is the whole reason for the rAF: tapping the other menu fires
	   two toggle events - the open one closes, the new one opens. If we set the
	   scrim on each event it would blink off then on. Instead each toggle just
	   marks the set and queues one reconcile for the next frame; by then the set
	   holds the final state, so a switch (still one panel open) leaves the scrim
	   on and untouched. Only a real full-close empties the set and fades it out. */
	var scrim = document.querySelector('.menu-scrim');
	var openPanels = new Set();
	var scrimSyncQueued = false;

	function syncScrim() {
		scrimSyncQueued = false;
		if (scrim) {
			scrim.classList.toggle('is-visible', openPanels.size > 0);
		}
	}

	function queueScrimSync() {
		if (scrimSyncQueued) {
			return;
		}
		scrimSyncQueued = true;
		requestAnimationFrame(syncScrim);
	}

	toolboxPanels.forEach(function (panel) {
		panel.addEventListener('toggle', function (event) {
			var isOpen = event.newState === 'open';

			if (isOpen) {
				openPanel = panel;
				openPanels.add(panel);
			} else {
				openPanels.delete(panel);
				if (openPanel === panel) {
					openPanel = null;
				}
			}

			queueScrimSync();

			/* Keep the trigger's disclosure state in sync for assistive tech. */
			var trigger = document.querySelector('[popovertarget="' + panel.id + '"]');
			if (trigger) {
				trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			}
		});
	});

	function tapIsOnTrigger(target) {
		for (var i = 0; i < toolboxTriggers.length; i++) {
			if (toolboxTriggers[i].contains(target)) {
				return true;
			}
		}
		return false;
	}

	function dismissIfOutside(clientX, clientY, target) {
		if (!openPanel || tapIsOnTrigger(target)) {
			return;
		}

		var box = openPanel.getBoundingClientRect();
		var insidePanel =
			clientX >= box.left &&
			clientX <= box.right &&
			clientY >= box.top &&
			clientY <= box.bottom;

		if (!insidePanel && openPanel.hidePopover) {
			openPanel.hidePopover();

			/* A softer click than the trigger's — half volume (gated by
			   data-sound like every other UI sound). */
			if (window.ui && window.ui.sound) {
				window.ui.sound('click-soft');
			}
		}
	}

	document.addEventListener('pointerdown', function (event) {
		dismissIfOutside(event.clientX, event.clientY, event.target);
	});

	document.addEventListener('touchstart', function (event) {
		var touch = event.touches[0];
		if (touch) {
			dismissIfOutside(touch.clientX, touch.clientY, event.target);
		}
	}, { passive: true });

	/* --- Guided-tour control surface (spike, see scripts/tour.js) ---
	   The tour drives the real UI with {persist:false}, then calls restore()
	   to snap the view back to the visitor's saved prefs. Because the tour
	   never wrote localStorage, restore is just a re-read of what was already
	   there - any real choice the visitor made mid-tour DID persist, so it
	   survives; only the tour's own persist:false changes get undone. */
	function restore() {
		var savedBrand = null;
		var savedEmphasis = null;
		var savedFilter = null;
		try { savedBrand = localStorage.getItem('brand-preference'); } catch (error) {}
		try { savedEmphasis = localStorage.getItem('emphasis-preference'); } catch (error) {}
		try { savedFilter = localStorage.getItem('filter-preference'); } catch (error) {}

		var brandIdx = savedBrand ? BRANDS.indexOf(savedBrand) : 0;
		if (brandIdx < 0) brandIdx = 0;
		applyBrand(brandIdx, { persist: false });

		var emphasisIdx = savedEmphasis ? EMPHASES.indexOf(savedEmphasis) : 0;
		if (emphasisIdx < 0) emphasisIdx = 0;
		applyEmphasis(emphasisIdx, { persist: false });

		SWITCHERS.forEach(function (cfg) {
			var apply = applyByKind[cfg.kind];
			if (!apply) return;
			var saved = null;
			try { saved = localStorage.getItem(cfg.storageKey); } catch (error) {}
			apply(saved || cfg.defaultValue, { persist: false });
		});

		if (entries.length) {
			var tiers = savedFilter ? parseInt(savedFilter, 10) : FILTER_DEFAULT;
			if (isNaN(tiers) || tiers < 1 || tiers > MAX_WEIGHT) tiers = FILTER_DEFAULT;
			applyFilter(tiers, { persist: false });
		}

		if (viewButtons.length) {
			var savedView = null;
			try { savedView = localStorage.getItem('view-preference'); } catch (error) {}
			applyView(savedView === 'grid' ? 'grid' : 'list', { persist: false });
		}
	}

	window.settings = {
		applyBrand: applyBrand,
		applyEmphasis: applyEmphasis,
		applyFilter: applyFilter,
		set: function (kind, value, opts) {
			if (applyByKind[kind]) applyByKind[kind](value, opts);
		},
		restore: restore,
		panel: document.getElementById('menu-settings')
	};
})();
