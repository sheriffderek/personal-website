/* Display settings (triggers + panels) — single data-driven loop for switcher
   rows, plus the timeline filter slider (its own shape, not a switcher).
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
		{ kind: 'sound',  attr: 'data-sound',  storageKey: 'sound-preference',  defaultValue: 'off' },
		/* Red light is a boolean override (Off/On), not a mood. valuelessAttr
		   means 'on' sets a bare data-red-light attribute (no value); 'off'
		   removes it. Sits below Mood in the panel - it stomps all color. */
		{ kind: 'red-light', attr: 'data-red-light', storageKey: 'red-light-preference', defaultValue: 'off', valuelessAttr: true }
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

		buttons.forEach(function (button) {
			button.addEventListener('click', function () {
				choose(button, false);
			});

			button.addEventListener('keydown', function (event) {
				var step = 0;
				if (event.key === 'ArrowRight' || event.key === 'ArrowDown') step = 1;
				if (event.key === 'ArrowLeft' || event.key === 'ArrowUp') step = -1;
				if (!step) return;

				event.preventDefault();

				/* Arrow keys move within THIS surface's radiogroup only. The
				   mirror model renders the same controls on several surfaces
				   (panel + band); stepping through the flat all-instances
				   list would hop the focus between surfaces. */
				var group = button.closest('[role="radiogroup"]') || button.parentElement;
				var siblings = group.querySelectorAll('[data-set-' + cfg.kind + ']');
				var position = Array.prototype.indexOf.call(siblings, button);
				var next = siblings[(position + step + siblings.length) % siblings.length];
				choose(next, true);
			});
		});
	});

	/* Character + emphasis — the two design-system axes, each a slider (not
	   buttons). Index maps to a slug. The first slug is the default: it means
	   "no attribute on <html>" (the :root block in settings.css IS the
	   default), and no storage key is written for it so first-load defaults
	   stay clean. Both sliders share one wiring, so they can't drift apart.

	     Character (data-brand-character) - type pair, corners, scale rhythm.
	     Mood      (data-brand-mood)      - color palette only.

	   A character swap changes the type scale, so both go through syncScroll to
	   hold the reader's place through the reflow (color-only mood swaps
	   don't strictly need it, but the shared path keeps the story simple). */
	function sliderSwitcher(cfg) {
		/* All instances, on every surface (mirror model: panel + band render
		   the same slider; apply() reflects them all, none owns the state). */
		var sliders = document.querySelectorAll('[data-set-' + cfg.kind + '-slider]');
		var nameEls = document.querySelectorAll('[data-' + cfg.kind + '-name]');

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
			nameEls.forEach(function (nameEl) {
				nameEl.textContent = cfg.names[clamped];
			});
			sliders.forEach(function (slider) {
				slider.value = String(clamped);
			});
		}

		applyByKind[cfg.kind] = apply;

		if (sliders.length) {
			var saved = null;
			try { saved = localStorage.getItem(cfg.storageKey); } catch (error) {}
			var initialIdx = saved ? cfg.values.indexOf(saved) : 0;
			if (initialIdx < 0) initialIdx = 0;
			apply(initialIdx, { persist: false });
			sliders.forEach(function (slider) {
				slider.addEventListener('input', function () {
					syncScroll(function () {
						apply(parseInt(slider.value, 10) || 0);
					});
					if (window.ui && window.ui.sound) {
						var t = parseFloat(slider.value) / (cfg.values.length - 1);
						window.ui.sound('tick', t);
					}
				});
			});
		}

		return apply;
	}

	/* Keep these lists matched to the FOUC script in includes/header.php and
	   the sliders' max in includes/settings/{character,emphasis}-switcher.php.
	   Index 0 is the default (Product = :root, no attribute written). */
	var CHARACTERS      = ['product', 'marketing', 'interface'];
	var CHARACTER_NAMES = ['Product', 'Marketing', 'Interface'];
	var MOODS           = ['expressive', 'technical', 'quiet'];
	var MOOD_NAMES      = ['Expressive', 'Technical', 'Quiet'];

	var applyCharacter = sliderSwitcher({
		kind: 'character',
		attr: 'data-brand-character',
		storageKey: 'character-preference',
		values: CHARACTERS,
		names: CHARACTER_NAMES
	});

	var applyMood = sliderSwitcher({
		kind: 'mood',
		attr: 'data-brand-mood',
		storageKey: 'mood-preference',
		values: MOODS,
		names: MOOD_NAMES
	});

	/* Flavor - the color-range axis on top of mood x character (flavors.css):
	   index 0 is each cell's own default take, named ranges re-pigment only
	   what they state. Value list must stay matched with the FOUC script in
	   header.php and the slider max in includes/settings/flavor-switcher.php. */
	var FLAVORS      = ['default', 'berry'];
	var FLAVOR_NAMES = ['Default', 'Berry'];

	var applyFlavor = sliderSwitcher({
		kind: 'flavor',
		attr: 'data-flavor',
		storageKey: 'flavor-preference',
		values: FLAVORS,
		names: FLAVOR_NAMES
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
	/* All plural (mirror model): the filter renders on every settings surface
	   (panel + band), so every slider, label, and minimap is a dumb mirror
	   that applyFilter reflects together. */
	var filterSliders = document.querySelectorAll('[data-set-filter]');
	var miniMaps      = document.querySelectorAll('.mini-map-bars');
	var filterNames   = document.querySelectorAll('[data-filter-name]');
	var filterCounts  = document.querySelectorAll('[data-filter-count]');
	var filterTotals  = document.querySelectorAll('[data-filter-total]');
	/* Both depths on purpose: in grid view the lane dealer re-parents the
	   items into .timeline-lane wrappers. A bare '.timeline li' would also
	   catch list items INSIDE card content (tag lists, document links). */
	var entries      = document.querySelectorAll('.timeline > li, .timeline > .timeline-lane > li');
	var MAX_WEIGHT   = 6;

	/* Total is the count in this tag lane (what actually rendered), so the
	   label reads e.g. "14 / 35" and tops out at "35 / 35". */
	filterTotals.forEach(function (filterTotal) {
		filterTotal.textContent = String(entries.length);
	});

	if (entries.length) {
		miniMaps.forEach(function (miniMap) {
			entries.forEach(function (li) {
				var article = li.querySelector('[data-weight]');
				var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : MAX_WEIGHT;
				var bar = document.createElement('li');
				bar.setAttribute('data-weight', String(weight));
				miniMap.appendChild(bar);
			});
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
		var inCount = 0;
		entries.forEach(function (li, i) {
			var article = li.querySelector('[data-weight]');
			var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : MAX_WEIGHT;
			var isIn = weight <= tiersShown;
			if (isIn) inCount++;
			li.style.display = isIn ? '' : 'none';
			miniMaps.forEach(function (miniMap) {
				if (miniMap.children[i]) miniMap.children[i].setAttribute('data-state', isIn ? 'in' : 'out');
			});
			if (isIn) ensureCarousels(li);
		});
		filterNames.forEach(function (filterName) {
			filterName.textContent = FILTER_NAMES[tiersShown] || '';
		});
		filterCounts.forEach(function (filterCount) {
			filterCount.textContent = String(inCount);
		});
		/* The visible tier name is decorative (small, hidden on narrow screens),
		   so the slider itself announces the tier for assistive tech. */
		filterSliders.forEach(function (filterSlider) {
			filterSlider.setAttribute('aria-valuetext', (FILTER_NAMES[tiersShown] || String(tiersShown)) + ', ' + inCount + ' entries shown');
		});
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
		filterSliders.forEach(function (filterSlider) {
			filterSlider.value = String(tiersShown);
		});

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

	filterSliders.forEach(function (filterSlider) {
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
	});

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

	   It looks like the SWITCHERS radios above but earns its own wiring: the
	   saved PREFERENCE and the APPLIED state differ - grid only exists from
	   1200px, below that a saved grid choice waits, unapplied, for the next
	   big screen. Keep GRID_MIN matched to the breakpoint in
	   styles/layouts/grid-view.css and the FOUC script in header.php.

	   (The panel stays a POPOVER in both views now - the persistent surface
	   in grid view is the settings BAND, a separate mirror instance, so
	   there's no popover-attribute swapping and nothing to strand.) */
	var viewButtons = document.querySelectorAll('[data-set-view]');
	var GRID_MIN = window.matchMedia('(min-width: 1200px)');
	var currentView = 'list';

	/* The grid invite (tray button, markup in settings-panel.php) pulses
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

		if (applied === 'grid') {
			html.setAttribute('data-view', 'grid');
		} else {
			html.removeAttribute('data-view');
		}

		/* The view swap moves the whole shell (toolbar axis, tray column) -
		   an open panel either re-places against the new layout or, if its
		   trigger just hid (the band took over past 1450), closes. */
		reconcilePanelsToLayout();

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
		viewButtons.forEach(function (button) {
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

				/* Within this surface's radiogroup only - the same mirror-
				   instance rule as the SWITCHERS keydown above. */
				var group = button.closest('[role="radiogroup"]') || button.parentElement;
				var siblings = group.querySelectorAll('[data-set-view]');
				var position = Array.prototype.indexOf.call(siblings, button);
				var next = siblings[(position + step + siblings.length) % siblings.length];
				applyView(next.getAttribute('data-set-view'));
				next.focus();
			});
		});

		/* Crossing 1200px re-resolves the same preference: a saved grid choice
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

	/* Reveal-on-scroll (replaced the corner island in the lab port): when the
	   settings BAND - the persistent mirror instance on the grid's top
	   composition - leaves the viewport, flip data-scrolled on <html> so the
	   tray's reveal members (settings, back-to-top; visibility in
	   grid-view.css) appear. Band-exit IS the condition: it's exactly the
	   moment those jobs are orphaned, no magic scroll depth. This touches
	   only trigger visibility - the band never moves, the popover is a
	   different node - so there's no reflow, no jitter.

	   Off the grid the band is display:none (offsetParent null), where
	   "scrolled past" is meaningless - don't let it leak true there. */
	var band = document.querySelector('.settings-band');
	if (band && 'IntersectionObserver' in window) {
		new IntersectionObserver(function (entriesObserved) {
			var displayed = band.offsetParent !== null;

			html.toggleAttribute('data-scrolled', displayed && !entriesObserved[0].isIntersecting);

			/* Scrolling the band back into view re-hides the reveal members -
			   and a panel may not outlive its trigger. */
			reconcilePanelsToLayout();
		}).observe(band);
	}

	/* Back-to-top - a toolbar member with no panel. */
	document.querySelectorAll('[data-to-top]').forEach(function (button) {
		button.addEventListener('click', function () {
			var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			window.scrollTo({ top: 0, behavior: reduceMotion ? 'auto' : 'smooth' });
		});
	});

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
	var triggers = document.querySelectorAll('.trigger');
	var panels = document.querySelectorAll('.panel');
	var openPanel = null;

	/* One shared dim behind an open panel - but only when that panel is OVER
	   content ([data-over], written by placePanel from real geometry; see
	   .site-shade in modules/settings-panel.css). A panel opening into the
	   tray's own sidebar column covers nothing, so it gets no dim.

	   We track the set of open panels rather than a single flag so a SWITCH
	   reconciles correctly - that's also the whole reason for the rAF: tapping
	   the other menu fires two toggle events (the open one closes, the new one
	   opens). If we set the shade on each event it would blink off then on.
	   Instead each toggle just marks the set and queues one reconcile for the
	   next frame; by then the set holds the settled state, so a switch (still
	   one dimming panel open) leaves the shade on and untouched. Only a real
	   full-close fades it out. (The set, not :popover-open in a selector -
	   that pseudo-class can throw on older Safari.) */
	var shade = document.querySelector('.site-shade');
	var openPanels = new Set();
	var shadeSyncQueued = false;

	function syncShade() {
		shadeSyncQueued = false;
		if (!shade) {
			return;
		}

		var wantDim = false;
		openPanels.forEach(function (panel) {
			if (panel.hasAttribute('data-over')) {
				wantDim = true;
			}
		});

		shade.classList.toggle('is-visible', wantDim);
	}

	function queueShadeSync() {
		if (shadeSyncQueued) {
			return;
		}
		shadeSyncQueued = true;
		requestAnimationFrame(syncShade);
	}

	/* --- Panel placement (placePanel) ---
	   CSS anchor positioning is Chrome/Edge only, so it could never be
	   bulletproof on every browser - in Safari/Firefox it silently no-ops and
	   the popover lands centred, on exactly the phones recruiters use. So WHERE
	   a panel lands is computed here: on open, read the toolbar's rect and set
	   top/left, identically in every browser. The locked rules are the inputs:

	     - the toolbar's AXIS decides below vs beside (read live from its
	       flex-direction - one source of truth, no second place to sync)
	     - BESIDE prefers the free side (right, the tray's margin) and flips
	       left only when there's no room - judged from the shared
	       --layout-panel-max cap, NOT this panel's own width, so every panel
	       on a tray picks the SAME side
	     - the result is CLAMPED inside the viewport unconditionally - that
	       clamp is the "works every time on every browser"

	   Placed once on open; the tray is sticky, so an open panel stays aligned
	   as you scroll. A width resize re-places live (listener below). The
	   numbers come from the --layout-panel-* tokens in default-layout.css -
	   the JS re-encodes nothing. */
	var rootStyle = getComputedStyle(html);
	var PANEL_GAP = parseFloat(rootStyle.getPropertyValue('--layout-panel-gap')) || 8;
	var PANEL_EDGE = parseFloat(rootStyle.getPropertyValue('--layout-panel-edge')) || 8;

	function triggerFor(panel) {
		return document.querySelector('.trigger[popovertarget="' + panel.id + '"]');
	}

	function triggerIsRendered(trigger) {
		return !!trigger && getComputedStyle(trigger).display !== 'none';
	}

	function placePanel(panel, trigger) {
		/* Align to the TOOLBAR's box, not the individual trigger, so every
		   panel shares one clean edge (the corner-most edge of the tray)
		   instead of stepping inboard to whichever glyph opened it. The
		   trigger only tells us which toolbar; its box is what we align to. */
		var toolbar = trigger.closest('.toolbar') || trigger;
		var beside = getComputedStyle(toolbar).flexDirection.indexOf('column') === 0;

		var box = toolbar.getBoundingClientRect();
		var width = panel.offsetWidth;
		var height = panel.offsetHeight;

		/* The VISIBLE box, in the same layout-viewport coordinates the rects
		   and position:fixed use. On desktop this is just the viewport. On
		   iOS the layout viewport is taller than what's on screen (the URL
		   bar overlays it) and can be panned/zoomed - visualViewport is the
		   truth of what the visitor can actually see, so the clamp keeps the
		   panel inside THAT, never half-hidden behind Safari's chrome. */
		var visual = window.visualViewport;
		var viewLeft = visual ? visual.offsetLeft : 0;
		var viewTop = visual ? visual.offsetTop : 0;
		var viewportWidth = visual ? visual.width : document.documentElement.clientWidth;
		var viewportHeight = visual ? visual.height : document.documentElement.clientHeight;

		var top;
		var left;

		if (beside) {
			/* BESIDE: align to the toolbar's top, prefer the free margin
			   to the right of the tray. */
			top = box.top;

			var sideReference = parseFloat(getComputedStyle(panel).maxWidth) || width;

			if (viewLeft + viewportWidth - box.right >= sideReference + PANEL_GAP + PANEL_EDGE) {
				left = box.right + PANEL_GAP;
			} else {
				left = box.left - PANEL_GAP - width;
			}
		} else {
			/* BELOW: drop under the toolbar, right edges aligned. */
			top = box.bottom + PANEL_GAP;
			left = box.right - width;
		}

		/* Bulletproof: never let any edge leave the VISIBLE box, whatever the
		   math above said. */
		left = Math.max(viewLeft + PANEL_EDGE, Math.min(left, viewLeft + viewportWidth - width - PANEL_EDGE));
		top = Math.max(viewTop + PANEL_EDGE, Math.min(top, viewTop + viewportHeight - height - PANEL_EDGE));

		panel.style.left = Math.round(left) + 'px';
		panel.style.top = Math.round(top) + 'px';
		panel.style.right = 'auto';

		/* "Over content" is derived, not declared per situation: the panel
		   covers content exactly when its placed rect overlaps <main>.
		   Dropping into the tray's own sidebar column does NOT overlap main
		   -> not over; the phone top bar dropping onto the page DOES -> over.
		   We only write the boolean; the shade reads it to decide the dim. */
		var mainElement = document.querySelector('main');
		var over = false;

		if (mainElement) {
			var m = mainElement.getBoundingClientRect();
			over = left < m.right && left + width > m.left && top < m.bottom && top + height > m.top;
		}

		panel.toggleAttribute('data-over', over);
	}

	/* One reconcile for every "the layout just moved" door: re-place each
	   open panel against the new geometry, or close it if its trigger is no
	   longer rendered - A PANEL MAY NOT OUTLIVE ITS TRIGGER (closing loses
	   nothing: the mirror model means another surface already shows the same
	   controls with the same state). Callers: the width-resize listener,
	   applyView, and the settings-band observer (data-scrolled re-hides the
	   reveal members).

	   Guarded on openPanels existing because applyView runs once at init,
	   before the panel wiring below has assigned it - nothing can be open
	   that early, so there's nothing to reconcile. */
	function reconcilePanelsToLayout() {
		if (!openPanels || !openPanels.size) {
			return;
		}

		openPanels.forEach(function (panel) {
			var trigger = triggerFor(panel);

			if (!trigger) {
				return;
			}

			if (!triggerIsRendered(trigger)) {
				panel.hidePopover();
				return;
			}

			placePanel(panel, trigger);
		});

		/* A re-place can flip a panel over <-> not-over; the shade re-checks
		   itself (a steady state is a no-op, never a blink). */
		queueShadeSync();
	}

	panels.forEach(function (panel) {
		/* No flash of an unplaced panel: the popover's `toggle` event is
		   queued (async), so a frame could paint between show and placement.
		   `beforetoggle` fires synchronously before it shows, so we hide
		   there and reveal once placed. Done in JS, not CSS, so with no JS
		   the popover still shows - the no-JS floor stays intact. */
		panel.addEventListener('beforetoggle', function (event) {
			if (event.newState === 'open') {
				panel.style.visibility = 'hidden';
			}
		});

		panel.addEventListener('toggle', function (event) {
			var isOpen = event.newState === 'open';

			if (isOpen) {
				openPanel = panel;
				openPanels.add(panel);

				var placeTrigger = triggerFor(panel);

				if (triggerIsRendered(placeTrigger)) {
					placePanel(panel, placeTrigger);

					/* Settle pass: the first placement measured the panel the
					   instant it became renderable, and that first measure can
					   be off (fonts landing, iOS finishing a URL-bar or zoom
					   transition mid-open). One more placement on the next
					   frame reads the settled layout and corrects invisibly -
					   on desktop it lands on the same pixels. */
					requestAnimationFrame(function () {
						if (!openPanels.has(panel)) {
							return;
						}

						var settleTrigger = triggerFor(panel);

						if (triggerIsRendered(settleTrigger)) {
							placePanel(panel, settleTrigger);
						}
					});
				} else {
					/* Safety net only - a panel opens by its trigger, so a
					   hidden trigger shouldn't get here. If it somehow does,
					   clear any stale inline position so the CSS floor (a
					   centred popover) applies instead of last week's spot. */
					panel.style.left = '';
					panel.style.top = '';
					panel.style.right = '';
					panel.removeAttribute('data-over');
				}

				panel.style.visibility = '';
			} else {
				openPanels.delete(panel);
				if (openPanel === panel) {
					openPanel = null;
				}
			}

			queueShadeSync();

			/* Keep the trigger's disclosure state in sync for assistive tech. */
			var trigger = document.querySelector('[popovertarget="' + panel.id + '"]');
			if (trigger) {
				trigger.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
			}
		});
	});

	/* An open panel stays open across a resize and RE-PLACES live - the panel
	   following the layout as the viewport changes is part of the demo (same
	   spirit as the motion policy: the system performing itself is the pitch).
	   If the resize hid the panel's trigger, it closes instead - a panel may
	   not outlive its trigger, and the mirror model means nothing is lost.
	   WIDTH only: phones fire resize when the URL bar collapses on scroll -
	   a height change is no reason to disturb an open panel. */
	var lastViewportWidth = window.innerWidth;

	window.addEventListener('resize', function () {
		if (window.innerWidth === lastViewportWidth) {
			return;
		}

		lastViewportWidth = window.innerWidth;

		reconcilePanelsToLayout();
	});

	/* Re-place on every viewport disturbance (rAF-coalesced, and a fast
	   no-op while nothing is open). On desktop these all land on the same
	   pixels - the tray is sticky, so the toolbar's rect holds still. They
	   exist for iOS Safari, where the VISUAL viewport slides around the
	   layout viewport (the URL bar collapsing/expanding, pinch zoom, the
	   keyboard) - often without firing the window events desktop code
	   listens to - and a panel placed once on open drifts away from its
	   toolbar. Gluing the panel to the toolbar's live rect on every one of
	   these signals is what makes placement hold still on a phone:

	     - window scroll (the sticky toolbar's rect is live-tracked)
	     - visualViewport resize + scroll (the iOS-only movements above) */
	var panelReconcileQueued = false;

	function schedulePanelReconcile() {
		if (panelReconcileQueued || !openPanels.size) {
			return;
		}

		panelReconcileQueued = true;

		requestAnimationFrame(function () {
			panelReconcileQueued = false;
			reconcilePanelsToLayout();
		});
	}

	window.addEventListener('scroll', schedulePanelReconcile, { passive: true });

	if (window.visualViewport) {
		window.visualViewport.addEventListener('resize', schedulePanelReconcile);
		window.visualViewport.addEventListener('scroll', schedulePanelReconcile);
	}

	function tapIsOnTrigger(target) {
		for (var i = 0; i < triggers.length; i++) {
			if (triggers[i].contains(target)) {
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
			var dismissed = openPanel;

			/* Clear the trackers SYNCHRONOUSLY, before hiding. One iOS tap
			   fires this via pointerdown AND touchstart; the toggle handler
			   that normally clears openPanel is queued (async), so without
			   this the second pass saw the stale reference and called
			   hidePopover on an already-hidden popover - an uncaught
			   InvalidStateError on every outside tap (2026-07-20 audit). */
			openPanel = null;
			openPanels.delete(dismissed);

			dismissed.hidePopover();

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

	/* --- Trigger taps are NATIVE - parked cleverness, 2026-07-20 ---
	   The triggers ran three rounds of custom tap choreography (a mid-glide
	   tap fallback so a tap during iOS momentum scrolling opened the menu
	   instead of just stopping the scroll, and a pointerdown panel-to-panel
	   switch with a View Transition morph). All three rounds shipped a
	   variant of the same bug - iOS delivered or withheld the click on its
	   own schedule, our synthesized action collided with it, and the panel
	   toggled open-and-shut in one frame: "the menu button does nothing,"
	   on exactly the surface whose job is proving interface craft. The
	   machinery was inference about event timing we never observed; git has
	   it all (2ebb263, 5747db1, 0b2d492, 9bd4f1d) if it's ever re-earned.

	   The floor won: buttons must work. Native popover behavior only -
	   every tap opens the menu, a mid-glide tap stops the scroll first
	   (what native apps do), a menu switch is an instant swap.

	   REVISIT only via the house method that built the shell: prove the
	   mechanic on a REAL device in the layout lab first (?debug=taps below
	   is the flight recorder), then port. Never again by desktop inference. */
	/* --- QA instrument: tap-event readout (?debug=taps) ---
	   The tap choreography above is iOS-only behavior that no desktop
	   browser can exercise, and every blind fix costs a phone round-trip.
	   With ?debug=taps in the URL, every trigger gesture event and panel
	   toggle is written to a small on-page readout with the ms gap between
	   events - so a phone report can say exactly WHAT fired and WHEN
	   instead of "nothing happened". Renders nothing without the param;
	   inline styles are fine here, it's a labeled instrument, not chrome. */
	if (window.location.search.indexOf('debug=taps') !== -1) {
		var tapLog = document.createElement('ol');
		tapLog.style.cssText = 'position:fixed;left:8px;bottom:8px;z-index:9999;margin:0;padding:6px 8px;list-style:none;font:11px/1.5 Menlo,monospace;background:rgba(0,0,0,0.85);color:#9f9;max-width:70vw;pointer-events:none;';
		document.body.appendChild(tapLog);

		var lastTapEventAt = 0;

		var logTap = function (label) {
			var now = Date.now();
			var gap = lastTapEventAt ? '+' + (now - lastTapEventAt) + 'ms' : '';
			lastTapEventAt = now;

			var line = document.createElement('li');
			line.textContent = label + ' ' + gap;
			tapLog.appendChild(line);

			while (tapLog.children.length > 8) {
				tapLog.removeChild(tapLog.firstChild);
			}
		};

		triggers.forEach(function (trigger) {
			var name = trigger.getAttribute('aria-label') || 'trigger';

			['pointerdown', 'touchstart', 'touchend', 'click'].forEach(function (type) {
				trigger.addEventListener(type, function () {
					logTap(name + ': ' + type);
				});
			});
		});

		panels.forEach(function (panel) {
			panel.addEventListener('toggle', function (event) {
				logTap(panel.id + ': ' + event.newState);
			});
		});
	}

	/* --- Guided-tour control surface (spike, see scripts/tour.js) ---
	   The tour drives the real UI with {persist:false}, then calls restore()
	   to snap the view back to the visitor's saved prefs. Because the tour
	   never wrote localStorage, restore is just a re-read of what was already
	   there - any real choice the visitor made mid-tour DID persist, so it
	   survives; only the tour's own persist:false changes get undone. */
	function restore() {
		var savedCharacter = null;
		var savedMood = null;
		var savedFilter = null;
		try { savedCharacter = localStorage.getItem('character-preference'); } catch (error) {}
		try { savedMood = localStorage.getItem('mood-preference'); } catch (error) {}
		try { savedFilter = localStorage.getItem('filter-preference'); } catch (error) {}

		var characterIdx = savedCharacter ? CHARACTERS.indexOf(savedCharacter) : 0;
		if (characterIdx < 0) characterIdx = 0;
		applyCharacter(characterIdx, { persist: false });

		var moodIdx = savedMood ? MOODS.indexOf(savedMood) : 0;
		if (moodIdx < 0) moodIdx = 0;
		applyMood(moodIdx, { persist: false });

		var savedFlavor = null;
		try { savedFlavor = localStorage.getItem('flavor-preference'); } catch (error) {}
		var flavorIdx = savedFlavor ? FLAVORS.indexOf(savedFlavor) : 0;
		if (flavorIdx < 0) flavorIdx = 0;
		applyFlavor(flavorIdx, { persist: false });

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
		applyCharacter: applyCharacter,
		applyMood: applyMood,
		applyFlavor: applyFlavor,
		applyFilter: applyFilter,
		set: function (kind, value, opts) {
			if (applyByKind[kind]) applyByKind[kind](value, opts);
		},
		restore: restore,
		panel: document.getElementById('settings-panel')
	};
})();
