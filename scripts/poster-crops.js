/* Poster phone crops - each poster picks its own window (Derek, 2026-07-28).

   On phones every poster frame relaxes to the 3-wide ratio (milestone.css:
   large 3:3, medium 3:2, small 3:1), which means the 1600x900 art gets
   windowed. Center-slice suits a centered figure but wrecks anything
   anchored off-center - so each poster may author its own crop anchor as
   the --crop-x / --crop-y custom properties on its svg (in the art's own
   1600x900 user units, in the svg's style attribute next to the geometry
   it belongs to - a real token, visible and scrubbable in devtools; being
   CSS, a theme could even override it from above). This script reads the
   computed values and turns them into a real viewBox window at phone
   widths, restoring the full frame above them. No anchor = centered,
   exactly like the old behavior. viewBox itself is not a CSS property -
   that's the only reason this script exists.

   The windows (derived from the 3-wide ratios, clamped inside the frame):
     large  -> 900x900,  slides on x (anchor data-crop-x)
     medium -> 1350x900, slides on x (data-crop-x)
     small  -> 1600x533, slides on y (data-crop-y)

   The 600px condition must stay matched to the phone media query in
   milestone.css - the ratio change and the window change are one design
   decision. */

(function () {
	var FULL_FRAME = '0 0 1600 900';

	var phone = window.matchMedia('(max-width: 600px)');

	function clamp(value, min, max) {
		return Math.min(Math.max(value, min), max);
	}

	/* One axis of the window: center it on the anchor. While the window fits
	   inside the 1600x900 canvas it stays clamped inside; once --crop-scale
	   zooms it PAST the canvas, the overflow centers evenly and the CSS
	   ground paints the extra (the ground is the frame's background, not the
	   art - milestone.css - so there is no void to show). */
	function position(anchor, window, canvas) {
		if (window <= canvas) {
			return Math.round(clamp(anchor - window / 2, 0, canvas - window));
		}

		return Math.round((canvas - window) / 2);
	}

	function windowFor(svg) {
		var article = svg.closest('.milestone');
		var size = article ? article.getAttribute('data-poster-size') : null;
		var style = getComputedStyle(svg);
		var anchorX = parseFloat(style.getPropertyValue('--crop-x')) || 800;
		var anchorY = parseFloat(style.getPropertyValue('--crop-y')) || 450;
		var scale = parseFloat(style.getPropertyValue('--crop-scale')) || 1;

		/* The base 3-wide windows; --crop-scale renders the art smaller
		   (scale 0.8 = the window grows to 1/0.8, so the composition reads
		   at 80%) or bigger (scale above 1 tightens the window). */
		var baseWidth = size === 'small' ? 1600 : (size === 'medium' ? 1350 : 900);
		var baseHeight = size === 'small' ? Math.round(1600 / 3) : 900;
		var width = Math.round(baseWidth / scale);
		var height = Math.round(baseHeight / scale);

		return position(anchorX, width, 1600) + ' ' + position(anchorY, height, 900) + ' ' + width + ' ' + height;
	}

	function apply() {
		document.querySelectorAll('svg.poster-art').forEach(function (svg) {
			svg.setAttribute('viewBox', phone.matches ? windowFor(svg) : FULL_FRAME);
		});
	}

	phone.addEventListener('change', apply);

	/* Hidden tabs defer media-query change events (Chromium page lifecycle),
	   so a breakpoint crossed while backgrounded can leave stale windows -
	   re-derive on resize too. Re-setting an unchanged viewBox is a no-op. */
	window.addEventListener('resize', apply);

	/* Scrubbing --crop-x/y in devtools edits the svg's style attribute -
	   re-derive on that mutation so the anchor tunes live, which is the
	   whole authoring workflow for placing 36 anchors. Each svg is observed
	   individually (never the page: Flickity writes style per frame during
	   swipes, and a subtree observer would thrash). apply() writes the
	   viewBox attribute, not style, so this can't loop. */
	var observer = new MutationObserver(apply);

	document.querySelectorAll('svg.poster-art').forEach(function (svg) {
		observer.observe(svg, { attributes: true, attributeFilter: ['style'] });
	});

	apply();
})();
