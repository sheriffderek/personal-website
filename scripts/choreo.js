/* Choreo - drive page side-effects from a video's clock.

   A small, content-agnostic engine: give it a video plus two kinds of
   instruction and it keeps the page in sync with playback. It knows nothing
   about settings or confetti - the effects are all callbacks you pass in, so
   the same engine drives any video and any whimsy.

   Two primitives, by design:

     channels + spans - STATE that is true over a range. Reconciled every frame,
       so it is idempotent and scrub-proof: at any currentTime the channel holds
       the value of the last span covering it, or its default. Menu open, theme
       serif, a class on an element - anything with an on/off-over-time shape.
       Scrubbing backward or reloading always lands in the right state, because
       we derive from the clock and never depend on having witnessed a moment.

     hits - a TRANSIENT moment. Confetti, a chime, a wobble. Fires forward-only
       during real playback; scrubbing never re-fires it (you don't want ten
       bursts while dragging the scrubber). Whimsy lives in the moment; state is
       always true.

   Lifecycle: start() on the first play (the engine never grabs the page on load),
   stop() on end or when a consumer opts out - stop() calls config.onEnd so the
   consumer can restore whatever it changed. */
window.Choreo = function (video, config) {
	var channels = config.channels || {};
	var spans = config.spans || [];
	var hits = config.hits || [];
	var onEnd = config.onEnd || function () {};

	var active = false;
	var applied = {};
	var lastTime = 0;

	/* The value a channel should hold at time t: the last span covering t wins
	   (so a later span overrides an earlier one); no cover = the channel default. */
	function valueAt(name, t) {
		var value = channels[name].def;
		spans.forEach(function (span) {
			if (span.channel === name && t >= span.from && t < span.to) {
				value = span.value;
			}
		});
		return value;
	}

	/* Bring every channel into agreement with the clock. Idempotent - a channel
	   is only re-applied when its target actually changes - so it is cheap to run
	   every frame and safe to run after any seek. */
	function reconcile() {
		if (!active) return;

		var t = video.currentTime;
		Object.keys(channels).forEach(function (name) {
			var target = valueAt(name, t);
			if (applied[name] !== target) {
				applied[name] = target;
				channels[name].apply(target);
			}
		});
	}

	/* Fire any hit whose moment we just crossed going forward. */
	function fireHits(from, to) {
		hits.forEach(function (hit) {
			if (hit.at > from && hit.at <= to) {
				hit.run();
			}
		});
	}

	/* One forward cursor (lastTime) drives the hits, fed by BOTH the play loop
	   and timeupdate. The play loop is frame-accurate when the tab is visible;
	   timeupdate keeps firing (~4x/sec) even when a backgrounded tab freezes
	   requestAnimationFrame - so whimsy still lands. Feeding one monotonic cursor
	   means whichever caller crosses a moment first consumes it; the other sees
	   now == lastTime and doesn't double-fire. We never fire mid-scrub. */
	function advance() {
		if (!active) return;

		reconcile();

		if (video.seeking) return;

		var now = video.currentTime;
		if (now > lastTime) {
			fireHits(lastTime, now);
			lastTime = now;
		}
	}

	function loop() {
		if (!active) return;
		advance();
		if (!video.paused && !video.ended) requestAnimationFrame(loop);
	}

	/* Only take over once the visitor presses play, never on load - so the
	   choreography can't stomp the state the page was already in. */
	function start() {
		if (active) return;
		active = true;
		applied = {};
		lastTime = video.currentTime;
		requestAnimationFrame(loop);
	}

	function stop() {
		if (!active) return;
		active = false;
		onEnd();
	}

	video.addEventListener('play', start);

	/* A seek re-derives state and moves the hit cursor to the new spot WITHOUT
	   firing the hits in between - so jumping the scrubber past a confetti moment
	   stays quiet. */
	video.addEventListener('seeked', function () {
		lastTime = video.currentTime;
		reconcile();
	});

	video.addEventListener('timeupdate', advance);
	video.addEventListener('ended', stop);

	return {
		start: start,
		stop: stop,
		isActive: function () { return active; },
		video: video
	};
};
