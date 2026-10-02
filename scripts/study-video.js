/* Case-study videos (includes/study-video.php) - the graphic is the face,
   a plain <video> waits behind it.

   Each <study-video> walks through a few states, written to its data-state
   so the CSS (and any animation later) can key off one attribute:

     idle    - the graphic and the play button. The video's controls are
               off, so nothing of the player shows.
     loading - pressed; the video is starting. The button goes, the graphic
               holds until the first frame is really playing.
     playing - the graphic has faded away and the browser's own controls
               take over.
     ended   - finished; the graphic and the button come back. Pressing
               play again starts it over.

   Only one study video talks at a time: starting one pauses the other.

   Without this script there's no data-state, so the CSS leaves the graphic
   and button hidden and the video keeps the controls the markup gave it. */
(function () {
	var videos = document.querySelectorAll('study-video');

	videos.forEach(function (element) {
		var video = element.querySelector('video');
		var button = element.querySelector('.play');
		if (!video || !button) return;

		function setState(state) {
			element.dataset.state = state;
		}

		setState('idle');
		video.controls = false;

		// The play() happens right here in the click, on our own page - which
		// is the whole reason this is a <video> and not Vimeo's player: the
		// browser counts the tap, so the sound comes with it.
		button.addEventListener('click', function () {
			setState('loading');

			// If it can't start (a network error, the browser says no), put
			// the button back so the visitor can try again.
			video.play().catch(function () {
				setState('idle');
			});
		});

		// Stopped before it ever got going - another video started first.
		// Same thing: the button comes back.
		video.addEventListener('pause', function () {
			if (element.dataset.state === 'loading') {
				setState('idle');
			}
		});

		video.addEventListener('play', function () {
			videos.forEach(function (other) {
				var otherVideo = other.querySelector('video');

				if (otherVideo && otherVideo !== video) {
					otherVideo.pause();
				}
			});
		});

		video.addEventListener('playing', function () {
			video.controls = true;
			setState('playing');

			// The button just hid under the video; keep a keyboard visitor's
			// place by handing focus to the player itself.
			if (document.activeElement === button) {
				video.focus();
			}
		});

		video.addEventListener('ended', function () {
			video.controls = false;
			setState('ended');
		});
	});
})();
