/* Guided intro tour - the site's choreography, played through the Choreo engine
   (scripts/choreo.js). This file is the CONTENT: it says which real controls the
   video drives (channels + spans) and which whimsy fires at which moment (hits).
   The engine knows none of it.

   Every settings apply runs with {persist:false}, so the demo mutates the view
   without touching the visitor's saved preferences. When the tour ends - finishes,
   the visitor opts out, or they skip - settings.restore() puts their prefs back.

   Opt-in: gated on the 'tour-preference' setting so a visitor can turn the whole
   thing off and leave it off (or back on). Reduced-motion visitors never get it.

   No [data-tour-video] on the page, engine missing, or tour disabled = inert. */
(function () {
	var video = document.querySelector('[data-tour-video]');
	if (!video || !window.settings || !window.Choreo) return;

	if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

	/* Opt-out is sticky: 'off' means the visitor turned the tour off and it stays
	   off until they turn it back on. Absent = on (the wow is the default). */
	var enabled = true;
	try { enabled = localStorage.getItem('tour-preference') !== 'off'; } catch (error) {}
	if (!enabled) return;

	var settings = window.settings;
	var panel = settings.panel;

	/* --- Whimsy (example hits - swap freely) ------------------------------- */

	/* A quick confetti pop, self-contained: DOM spans flung with the Web
	   Animations API, each removing itself when it lands. No dependency, no CSS
	   file, no leftover nodes. Inline styles are fine here - this is a throwaway
	   effect that owns its own pixels, not a themed component. */
	function confetti() {
		var pieces = ['🎉', '✨', '🎊', '⭐️'];

		for (var i = 0; i < 18; i++) {
			var el = document.createElement('span');
			el.textContent = pieces[i % pieces.length];
			el.style.cssText = 'position:fixed;left:50%;top:38%;z-index:9999;font-size:1.6rem;pointer-events:none;will-change:transform,opacity;';
			document.body.appendChild(el);

			var dx = (Math.random() - 0.5) * 520;
			var dy = -240 - Math.random() * 220;
			var spin = Math.random() * 720 - 360;

			var animation = el.animate(
				[
					{ transform: 'translate(-50%, -50%) rotate(0deg)', opacity: 1 },
					{ transform: 'translate(calc(-50% + ' + dx + 'px), calc(-50% + ' + (dy + 520) + 'px)) rotate(' + spin + 'deg)', opacity: 0 }
				],
				{ duration: 1300 + Math.random() * 700, easing: 'cubic-bezier(0.2, 0.6, 0.3, 1)' }
			);

			animation.onfinish = function (event) {
				event.target.effect.target.remove();
			};
		}
	}

	/* Reuse the site's own sound system (audio.js). Gated by data-sound like every
	   other UI sound, so a muted visitor hears nothing. */
	function chime() {
		if (window.ui && window.ui.sound) window.ui.sound('toggle-on');
	}

	/* --- Channels: reconciled state the video drives ----------------------- */

	var channels = {
		menu: {
			def: 'closed',
			apply: function (value) {
				if (!panel) return;
				if (value === 'open') {
					try { if (panel.showPopover) panel.showPopover(); } catch (error) {}
				} else {
					if (panel.hidePopover) panel.hidePopover();
				}
			}
		},

		brand: {
			def: 0,
			apply: function (value) {
				settings.applyBrand(value, { persist: false });
			}
		},

		emphasis: {
			def: 0,
			apply: function (value) {
				settings.applyEmphasis(value, { persist: false });
			}
		},

		filter: {
			def: 1,
			apply: function (value) {
				settings.applyFilter(value, { persist: false });
			}
		},

		scheme: {
			def: 'system',
			apply: function (value) {
				settings.set('scheme', value, { persist: false });
			}
		}
	};

	/* --- Author these against the finished video. Placeholder timings. -----
	   brand/emphasis values are slider indices (brand: 0 personal, 1 marketing,
	   2 product, 3 documentation; emphasis: 0 default, 1 warm, 2 cool,
	   3 neutral); filter values are tiers shown (1 Top ... 6 All); times are
	   seconds. */
	var spans = [
		{ channel: 'menu',   value: 'open', from: 3,  to: 15 },
		{ channel: 'brand',  value: 1,      from: 5,  to: 10 },
		{ channel: 'filter', value: 4,      from: 8,  to: 13 },
		{ channel: 'scheme', value: 'dark', from: 11, to: 15 }
	];

	var hits = [
		{ at: 6.0,  run: confetti },
		{ at: 12.0, run: chime }
	];

	var choreo = window.Choreo(video, {
		channels: channels,
		spans: spans,
		hits: hits,
		onEnd: function () {
			settings.restore();
		}
	});

	/* Opt-out: the first real interaction with a control hands the page back. We
	   pause the narration (so it isn't talking over them) and stop the engine,
	   which restores. Any choice they just made persisted through the real handler,
	   so restore keeps it - it only undoes the tour's persist:false changes.
	   Capture phase so this runs alongside the control's own handler. */
	function userTookOver() {
		if (!choreo.isActive()) return;
		if (!video.paused) video.pause();
		choreo.stop();
	}

	var optOutTargets = [panel].concat(
		Array.prototype.slice.call(document.querySelectorAll('.toolbox-trigger'))
	);

	optOutTargets.forEach(function (el) {
		if (!el) return;
		el.addEventListener('pointerdown', userTookOver);
		el.addEventListener('keydown', userTookOver);
	});

	/* A visible skip control (if the page provides one) ends the tour cleanly. */
	var skip = document.querySelector('[data-tour-skip]');
	if (skip) skip.addEventListener('click', function () { choreo.stop(); });
})();
