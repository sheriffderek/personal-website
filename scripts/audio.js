/* Tiny synth for UI feedback. Gated by data-sound='on' on <html>.
   AudioContext is created lazily on first user interaction so
   browser autoplay policies don't block us.

   Tune sounds in CONFIG at the top. Live-tune from devtools via
   window.ui.audio.config (e.g. window.ui.audio.config.master = 0.5).
*/
(function () {
	'use strict';

	/* ============== CONFIG ==============
	   All knobs in one place. Frequencies are Hz, durations are seconds,
	   volumes are 0..1 (each sound is also multiplied by `master`). */
	var CONFIG = {
		master: 0.5,

		click: {
			duration: 0.025,
			volume: 0.1,
			softScale: 0.5   // 'click-soft' (backdrop dismiss) plays at this fraction of the normal click
		},

		toggleOn: {
			freqFrom: 600,
			freqTo: 900,
			volume: 0.1,
			rampDuration: 0.1,
			totalDuration: 0.12
		},

		toggleOff: {
			freqFrom: 900,
			freqTo: 600,
			volume: 0.1,
			rampDuration: 0.1,
			totalDuration: 0.12
		},

		tick: {
			freqLow: 400,      // freq at slider position 0
			freqHigh: 1600,    // freq at slider position 1
			volLow: 0.03,
			volHigh: 0.11,
			duration: 0.03
		},

		settle: {
			waveform: 'triangle',
			frequency: 320,
			volume: 0.05,
			duration: 0.12
		}
	};
	/* ==================================== */

	/* Toggle sounds bypass the on/off gate — they ARE the feedback for
	   turning sound on or off, so they need to play in both directions. */
	var FORCE_TYPES = ['toggle-on', 'toggle-off'];

	var audioCtx = null;

	function isOn() {
		return document.documentElement.getAttribute('data-sound') === 'on';
	}

	function getContext() {
		if (!audioCtx) {
			audioCtx = new (window.AudioContext || window.webkitAudioContext)();
		}
		if (audioCtx.state === 'suspended') {
			audioCtx.resume();
		}
		return audioCtx;
	}

	function ensureContext() {
		if (audioCtx) return;
		try { getContext(); } catch (error) { /* no audio support — skip */ }
	}

	function v(volume) { return volume * CONFIG.master; }

	/* ============ SOUND PLAYBACK ============ */

	function playClick(ctx, now, scale) {
		var c = CONFIG.click;
		var bufSize = Math.max(1, Math.floor(c.duration * ctx.sampleRate));
		var buf = ctx.createBuffer(1, bufSize, ctx.sampleRate);
		var arr = buf.getChannelData(0);
		for (var i = 0; i < bufSize; i++) {
			var env = Math.pow(1 - i / bufSize, 3);
			arr[i] = (Math.random() * 2 - 1) * env;
		}
		var src = ctx.createBufferSource();
		src.buffer = buf;
		var gain = ctx.createGain();
		gain.gain.value = v(c.volume) * (scale || 1);
		src.connect(gain);
		gain.connect(ctx.destination);
		src.start(now);
	}

	function playToggle(ctx, now, direction) {
		var c = direction === 'on' ? CONFIG.toggleOn : CONFIG.toggleOff;
		var osc = ctx.createOscillator();
		var gain = ctx.createGain();
		osc.connect(gain);
		gain.connect(ctx.destination);
		osc.frequency.value = c.freqFrom;
		osc.frequency.linearRampToValueAtTime(c.freqTo, now + c.rampDuration);
		gain.gain.setValueAtTime(v(c.volume), now);
		gain.gain.exponentialRampToValueAtTime(0.001, now + c.totalDuration);
		osc.start(now);
		osc.stop(now + c.totalDuration);
	}

	function playTick(ctx, now, t) {
		var c = CONFIG.tick;
		var pos = (typeof t === 'number') ? t : 0.5;
		var freq = c.freqLow + pos * (c.freqHigh - c.freqLow);
		var vol  = c.volLow  + pos * (c.volHigh  - c.volLow);
		var osc = ctx.createOscillator();
		var gain = ctx.createGain();
		osc.connect(gain);
		gain.connect(ctx.destination);
		osc.frequency.value = freq;
		gain.gain.setValueAtTime(v(vol), now);
		gain.gain.exponentialRampToValueAtTime(0.001, now + c.duration);
		osc.start(now);
		osc.stop(now + c.duration);
	}

	function playSettle(ctx, now) {
		var c = CONFIG.settle;
		var osc = ctx.createOscillator();
		var gain = ctx.createGain();
		osc.connect(gain);
		gain.connect(ctx.destination);
		osc.type = c.waveform;
		osc.frequency.value = c.frequency;
		gain.gain.setValueAtTime(v(c.volume), now);
		gain.gain.exponentialRampToValueAtTime(0.001, now + c.duration);
		osc.start(now);
		osc.stop(now + c.duration);
	}

	/* ============ PUBLIC API ============ */

	window.ui = window.ui || {};
	window.ui.audio = window.ui.audio || {};
	window.ui.audio.config = CONFIG;

	function emit(ctx, type, t) {
		var now = ctx.currentTime;

		if (type === 'click')      return playClick(ctx, now, 1);
		if (type === 'click-soft') return playClick(ctx, now, CONFIG.click.softScale);
		if (type === 'toggle-on')  return playToggle(ctx, now, 'on');
		if (type === 'toggle-off') return playToggle(ctx, now, 'off');
		if (type === 'tick')       return playTick(ctx, now, t);
		if (type === 'settle')     return playSettle(ctx, now);
	}

	window.ui.sound = function (type, t) {
		var forced = FORCE_TYPES.indexOf(type) !== -1;
		if (!isOn() && !forced) return;
		var ctx;
		try { ctx = getContext(); } catch (error) { return; }

		/* If the context is still waking (suspended), scheduling now would drop
		   the sound onto a clock that isn't advancing yet. Wait for resume to
		   resolve, then play against a live currentTime. */
		if (ctx.state === 'suspended') {
			ctx.resume().then(function () {
				emit(ctx, type, t);
			}).catch(function () { /* no gesture yet — can't start; skip */ });
			return;
		}

		emit(ctx, type, t);
	};

	/* ============ EVENT DELEGATION ============ */

	/* Audio unlock — the first user interaction with the menu or a Read more
	   warms the AudioContext (browsers require a user gesture). After that,
	   any click warms it too, but only when sound is on. */
	document.addEventListener('click', function (e) {
		if (e.target.closest('.toolbox-trigger, .read-more')) { ensureContext(); return; }
		if (isOn()) ensureContext();
	});
	document.addEventListener('input', function () { if (isOn()) ensureContext(); });

	/* Generic click for any <button> or "Read more" disclosure.
	   Sound switcher buttons play their toggle sound from settings-panel.js,
	   so we exclude them here to avoid doubling up. */
	document.addEventListener('click', function (e) {
		var hit = e.target.closest('button, .read-more');
		if (!hit) return;
		if (hit.matches('[data-set-sound]')) return;
		window.ui.sound('click');
	});
})();
