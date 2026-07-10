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
			/* The header pins its intro paragraph today. When the welcome video is
			   live (<figure class='welcome-video'>, gated by TOUR_ENABLED in
			   config.php - see templates/pages/home.php), the anchor should pin
			   THAT instead: it sits above the intro and, being a fixed-ratio video,
			   is theme-stable like a milestone's media. So prefer it here -
			   `section.querySelector('.welcome-video, p')` - and it wins whenever
			   it's rendered, falling through to the paragraph when the tour is off. */
			return section.querySelector('p') || section;
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

	/* Theme — slider (not buttons). Index maps to theme slug.
	   data-theme is ALWAYS set on <html>, including data-theme='default',
	   so that selectors like [data-theme='default'] [data-flavor='X']
	   can target the right combinations. We still skip writing the storage
	   key for the default so first-load defaults stay clean. */
	var THEMES      = ['default', 'serif', 'mono', 'display'];
	var THEME_NAMES = ['Default', 'Serif',  'Mono', 'Display'];
	var themeSlider = document.querySelector('[data-set-theme-slider]');
	var themeName   = document.querySelector('[data-theme-name]');

	function applyTheme(idx, opts) {
		var clamped = Math.max(0, Math.min(THEMES.length - 1, idx));
		var theme = THEMES[clamped];
		html.setAttribute('data-theme', theme);
		if (shouldPersist(opts)) {
			try {
				if (theme === 'default') {
					localStorage.removeItem('theme-preference');
				} else {
					localStorage.setItem('theme-preference', theme);
				}
			} catch (error) {}
		}
		if (themeName) themeName.textContent = THEME_NAMES[clamped];
		if (themeSlider) themeSlider.value = String(clamped);
	}

	if (themeSlider) {
		var saved = null;
		try { saved = localStorage.getItem('theme-preference'); } catch (error) {}
		var initialIdx = saved ? THEMES.indexOf(saved) : 0;
		if (initialIdx < 0) initialIdx = 0;
		applyTheme(initialIdx);
		themeSlider.addEventListener('input', function () {
			syncScroll(function () {
				applyTheme(parseInt(themeSlider.value, 10) || 0);
			});
			if (window.ui && window.ui.sound) {
				var t = parseFloat(themeSlider.value) / (THEMES.length - 1);
				window.ui.sound('tick', t);
			}
		});
	}

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
	var entries      = document.querySelectorAll('.timeline > li');
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
		}
		if (filterSlider) filterSlider.value = String(tiersShown);
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

	if (entries.length) applyFilter(initialFilter);

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
		var savedTheme = null;
		var savedFilter = null;
		try { savedTheme = localStorage.getItem('theme-preference'); } catch (error) {}
		try { savedFilter = localStorage.getItem('filter-preference'); } catch (error) {}

		var themeIdx = savedTheme ? THEMES.indexOf(savedTheme) : 0;
		if (themeIdx < 0) themeIdx = 0;
		applyTheme(themeIdx, { persist: false });

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
	}

	window.settings = {
		applyTheme: applyTheme,
		applyFilter: applyFilter,
		set: function (kind, value, opts) {
			if (applyByKind[kind]) applyByKind[kind](value, opts);
		},
		restore: restore,
		panel: document.getElementById('menu-settings')
	};
})();
