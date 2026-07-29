/* Poster phone crops - each poster picks its own window (Derek, 2026-07-28).

   On phones every poster frame relaxes to the 3-wide ratio (milestone.css:
   large 3:3, medium 3:2, small 3:1), which means the 1600x900 art gets
   windowed. Center-slice suits a centered figure but wrecks anything
   anchored off-center - so each poster may author its own crop anchor as
   data-crop-x / data-crop-y on its svg (in the art's own 1600x900 user
   units, next to the geometry it belongs to). This script turns the anchor
   into a real viewBox window at phone widths and restores the full frame
   above them. No anchor = centered, exactly like the old behavior.

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

	function windowFor(svg) {
		var article = svg.closest('.milestone');
		var size = article ? article.getAttribute('data-poster-size') : null;
		var anchorX = Number(svg.getAttribute('data-crop-x')) || 800;
		var anchorY = Number(svg.getAttribute('data-crop-y')) || 450;

		if (size === 'small') {
			var bandHeight = Math.round(1600 / 3);
			var bandY = Math.round(clamp(anchorY - bandHeight / 2, 0, 900 - bandHeight));

			return '0 ' + bandY + ' 1600 ' + bandHeight;
		}

		if (size === 'medium') {
			var mediumX = Math.round(clamp(anchorX - 675, 0, 1600 - 1350));

			return mediumX + ' 0 1350 900';
		}

		var largeX = Math.round(clamp(anchorX - 450, 0, 1600 - 900));

		return largeX + ' 0 900 900';
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

	apply();
})();
