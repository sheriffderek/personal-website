/* Poster phone viewBox — each poster defines its own phone frame.

   On phones (<=600px) the poster's aspect ratio relaxes (milestone.css),
   so each poster can define a custom viewBox for that size. The lookup
   below maps each milestone slug to its phone viewBox string — origin
   and dimensions in the svg's own coordinate space. No clamping, no
   bounds — the viewBox can extend past the 1600x900 canvas in any
   direction; the CSS ground fills the overflow.

   Posters not in the table get the full 0 0 1600 900 frame (no crop).

   The 600px condition must stay matched to the phone media query in
   milestone.css. */

(function () {
	var FULL_FRAME = '0 0 1600 900';

	var PHONE_FRAMES = {
		'2026-job-search': '200 -550 1200 1600',
		'equivalent-design': '100 -250 1400 1400',
		'world-ia-day-2026': '250 -100 1100 1100',
		'list-at-ease': '250 -100 1100 1100',
		'better-life': '100 -250 1400 1400',
		'pxl': '150 -200 1300 1300',
		'agency-work': '150 -220 1300 1300',
		'freelancing-2019': '200 -150 1200 1200',
		'smart-text-editor': '150 -200 1300 1300',
		'midi-sequencing': '150 -182 1300 1300',
		'open-office-hours': '200 -150 1200 1200',
	};

	var phone = window.matchMedia('(max-width: 600px)');

	function apply() {
		document.querySelectorAll('svg.poster-art').forEach(function (svg) {
			if (!phone.matches) {
				svg.setAttribute('viewBox', FULL_FRAME);
				return;
			}

			var article = svg.closest('.milestone');
			var slug = article ? article.id : null;
			svg.setAttribute('viewBox', PHONE_FRAMES[slug] || FULL_FRAME);
		});
	}

	phone.addEventListener('change', apply);
	window.addEventListener('resize', apply);
	apply();
})();
