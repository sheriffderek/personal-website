/* Carousel-affordance nudge (loaded only when SLIDER_HINT_ENABLED).

   A poster+media card looks identical to a poster-only card until you try
   to drag it - the carousel is invisible. So the first carousel to scroll
   into view teaches the gesture by performing it: the slider eases a little
   toward slide 2, holds a beat, and settles back. The real mechanic demos
   itself - no overlay chrome, nothing added to the poster art.

   The lesson only needs teaching once, on two layers:
     - per PAGE LOAD: one nudge, max - the first qualifying carousel does
       it, every card after stays still.
     - across VISITS: it repeats (one nudge per load) until the visitor's
       first real swipe or dot-click, anywhere, ever. That's the "got it"
       signal: it drops the 'slider-hint-seen' breadcrumb and no page ever
       hints again - same retire-forever pattern as the grid invite pulse.

   Stand-down: grid view suppresses it while active (motion stands down on
   the wall) - switching back to list view re-arms it.

   $todo (Derek, 2026-07-12): as tuned, it's way too subtle and firing only
   once per load may be too shy. Revisit the distance/duration (the -48px
   and 1400ms below) and maybe the once-per-load rule. */

window.addEventListener('load', function () {
	var carousels = document.querySelectorAll('.carousel');
	if (!carousels.length) return;

	/* localStorage throws in private mode; the hint just runs every visit
	   there, which is the harmless direction to fail in. */
	function seen() {
		try { return localStorage.getItem('slider-hint-seen') === 'true'; } catch (error) { return false; }
	}

	function retire() {
		try { localStorage.setItem('slider-hint-seen', 'true'); } catch (error) {}
	}

	/* Testing back door (commented out for launch): load the page with
	   ?hint=reset to forget the breadcrumb, so the nudge plays again like a
	   first visit. Uncomment with the footer link in footer.php to tune. */
	// if (new URLSearchParams(window.location.search).get('hint') === 'reset') {
	// 	try { localStorage.removeItem('slider-hint-seen'); } catch (error) {}
	// }

	var nudge = null; /* the running animation, so a swipe can cut it short */

	/* A real swipe (or a dot click) proves the visitor knows, mid-nudge or
	   not - retire the hint everywhere and stop any nudge in flight. Wired
	   even when the breadcrumb is already set: re-setting it is free and
	   keeps this block simple. */
	carousels.forEach(function (el) {
		var flkty = Flickity.data(el);
		if (!flkty) return;

		function learned() {
			retire();
			if (nudge) { nudge.cancel(); nudge = null; }
		}

		flkty.on('dragStart', learned);
		flkty.on('change', learned);
	});

	if (seen()) return;

	/* Watch every carousel; the first one comfortably in view fires the one
	   nudge this pageview gets. */
	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) return;

			/* Grid view is a wall of stills - skip this trigger but keep
			   watching, so returning to list view can still teach. */
			if (document.documentElement.getAttribute('data-view') === 'grid') return;

			if (seen()) { observer.disconnect(); return; }

			var flkty = Flickity.data(entry.target);
			if (!flkty || flkty.slides.length < 2) return;

			observer.disconnect();

			/* Animate the slider element Flickity positions, composited ON TOP
			   of its inline transform (composite: 'add') so we never touch or
			   fight the value Flickity owns. Out, hold a beat, back. */
			nudge = flkty.slider.animate(
				[
					{ transform: 'translateX(0)', easing: 'ease-in-out' },
					{ transform: 'translateX(-48px)', offset: 0.35, easing: 'ease-in-out' },
					{ transform: 'translateX(-48px)', offset: 0.55, easing: 'ease-in-out' },
					{ transform: 'translateX(0)' },
				],
				{ duration: 1400, composite: 'add' }
			);
		});
	}, { threshold: 0.6 });

	carousels.forEach(function (el) { observer.observe(el); });
});
