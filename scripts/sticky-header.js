/* Sticky-rail border. A zero-height sentinel sits just above the rail.
   When it scrolls out of the top of the viewport, the rail has reached
   its stuck position, so we flag it with .is-stuck. The border itself is
   painted in CSS and scoped to small screens - here we only track state. */
(function () {
	var sentinel = document.querySelector('.rail-sentinel');
	var rail = document.querySelector('.page-rail');
	if (!sentinel || !rail) {
		return;
	}

	var observer = new IntersectionObserver(function (entries) {
		/* sentinel no longer visible = rail is pinned to the top. The
		   scrollY guard grounds the observer in reality: the sentinel is
		   zero-height and sits exactly on the viewport's top edge, so a
		   resize reflow can momentarily report it gone while the page is
		   still at the top - which flashed the border in and out (the 1s
		   fade made it linger). You can't be stuck without having scrolled. */
		rail.classList.toggle('is-stuck', !entries[0].isIntersecting && window.scrollY > 0);
	});

	observer.observe(sentinel);
})();
