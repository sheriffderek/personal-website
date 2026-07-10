/* Welcome video - its own small player. The milestone custom player is scoped
   to .slide[data-type='play']; this component is deliberately separate, so it
   owns its own play affordance.

   The big trigger over the still covers the whole frame and toggles
   play/pause. It stays in place (and in the tab order) while playing - only
   its icon hides - so keyboard users can pause too; a tap anywhere on the
   frame lands on the same button. Starting the video also drives the guided
   tour - tour.js listens to this same element (data-tour-video) - so there's
   nothing to wire between them here; playing is the shared signal.

   No .welcome-video on the page = inert. */
(function () {
	var figure = document.querySelector('.welcome-video');
	if (!figure) return;

	var video = figure.querySelector('video');
	var trigger = figure.querySelector('.play-trigger');
	if (!video || !trigger) return;

	trigger.addEventListener('click', function () {
		if (video.paused) {
			video.play();
		} else {
			video.pause();
		}
	});

	function showPlaying() {
		figure.classList.add('is-playing');
		trigger.setAttribute('aria-label', 'Pause the intro');
	}

	function showResting() {
		figure.classList.remove('is-playing');
		trigger.setAttribute('aria-label', 'Play the intro');
	}

	video.addEventListener('play', showPlaying);
	video.addEventListener('pause', showResting);
	video.addEventListener('ended', showResting);
})();
