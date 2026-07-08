/* Display settings toolbox — single data-driven loop for switcher rows,
   plus the timeline filter slider (its own shape, not a switcher).
   Native <popover> handles open/close, Esc, and light-dismiss. */
(function () {
	var html = document.documentElement;

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

	function syncScroll(applyChange, willSurvive) {
		var anchor = centeredSection(willSurvive);
		var topBefore = anchor ? anchor.getBoundingClientRect().top : 0;

		applyChange();

		if (!anchor) return;

		/* Wait one frame so the browser has recalculated layout with the new
		   type scale before we measure where the anchor landed. */
		requestAnimationFrame(function () {
			var topAfter = anchor.getBoundingClientRect().top;
			var shift = topAfter - topBefore;

			if (shift) window.scrollBy(0, shift);
		});
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

		function choose(button, moveFocus) {
			var value = valueOf(button);

			if (value === cfg.defaultValue) {
				if (persists) {
					try { localStorage.removeItem(cfg.storageKey); } catch (error) {}
				}
				html.removeAttribute(cfg.attr);
			} else {
				if (persists) {
					try { localStorage.setItem(cfg.storageKey, value); } catch (error) {}
				}
				html.setAttribute(cfg.attr, cfg.valuelessAttr ? '' : value);
			}

			reflect(value);
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

	function applyTheme(idx) {
		var clamped = Math.max(0, Math.min(THEMES.length - 1, idx));
		var theme = THEMES[clamped];
		html.setAttribute('data-theme', theme);
		try {
			if (theme === 'default') {
				localStorage.removeItem('theme-preference');
			} else {
				localStorage.setItem('theme-preference', theme);
			}
		} catch (error) {}
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

	/* Timeline filter — slider sets number of weight tiers shown.
	   1 = top tier only, 3 = all. */
	var FILTER_NAMES = { 1: 'Top', 2: 'More', 3: 'All' };
	var filterSlider = document.querySelector('[data-set-filter]');
	var miniMap      = document.querySelector('.mini-map-bars');
	var filterName   = document.querySelector('[data-filter-name]');
	var filterCount  = document.querySelector('[data-filter-count]');
	var entries      = document.querySelectorAll('.timeline > li');
	var MAX_WEIGHT   = 3;

	if (miniMap && entries.length) {
		entries.forEach(function (li) {
			var article = li.querySelector('[data-weight]');
			var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : 1;
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
				if (window.ui && window.ui.sound) window.ui.sound('settle');
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

	function applyFilter(tiersShown) {
		var minWeight = MAX_WEIGHT - tiersShown + 1;
		var bars = miniMap ? miniMap.children : [];
		var inCount = 0;
		entries.forEach(function (li, i) {
			var article = li.querySelector('[data-weight]');
			var weight = article ? parseInt(article.getAttribute('data-weight'), 10) : 1;
			var isIn = weight >= minWeight;
			if (isIn) inCount++;
			li.style.display = isIn ? '' : 'none';
			if (bars[i]) bars[i].setAttribute('data-state', isIn ? 'in' : 'out');
			if (isIn) ensureCarousels(li);
		});
		if (filterName) filterName.textContent = FILTER_NAMES[tiersShown] || '';
		if (filterCount) filterCount.textContent = String(inCount);
		try {
			if (tiersShown === FILTER_DEFAULT) {
				localStorage.removeItem('filter-preference');
			} else {
				localStorage.setItem('filter-preference', String(tiersShown));
			}
		} catch (error) {}
		if (filterSlider) filterSlider.value = String(tiersShown);
	}

	var savedFilter = null;
	try { savedFilter = localStorage.getItem('filter-preference'); } catch (error) {}
	var initialFilter = savedFilter ? parseInt(savedFilter, 10) : FILTER_DEFAULT;
	if (isNaN(initialFilter) || initialFilter < 1 || initialFilter > MAX_WEIGHT) initialFilter = FILTER_DEFAULT;

	function cardWeight(card) {
		var article = card.matches('[data-weight]') ? card : card.querySelector('[data-weight]');
		return article ? parseInt(article.getAttribute('data-weight'), 10) : 1;
	}

	if (filterSlider) {
		filterSlider.addEventListener('input', function () {
			var tiersShown = parseInt(filterSlider.value, 10);
			var minWeight = MAX_WEIGHT - tiersShown + 1;

			syncScroll(
				function () {
					applyFilter(tiersShown);
				},
				function willSurvive(card) {
					return cardWeight(card) >= minWeight;
				}
			);

			if (window.ui && window.ui.sound) {
				var t = (parseFloat(filterSlider.value) - 1) / (MAX_WEIGHT - 1);
				window.ui.sound('tick', t);
			}
		});
	}

	if (entries.length) applyFilter(initialFilter);

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

	toolboxPanels.forEach(function (panel) {
		panel.addEventListener('toggle', function (event) {
			var isOpen = event.newState === 'open';

			if (isOpen) {
				openPanel = panel;
			} else if (openPanel === panel) {
				openPanel = null;
			}

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
})();
