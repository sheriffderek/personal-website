/* Case-study videos (includes/study-video.php) - the graphic is the face,
   a plain <video> waits behind it.

   Two builds, chosen per device (Derek, 2026-10-02):

   PHONE - the tap opens the phone's own full-screen player. One video,
   nothing else, landscape if they want it: the focus IS the point, and a
   FigJam screen recording is unreadable in a phone-width box anyway. Our
   inline frame never shows the video at all - closing the player lands
   them back on the graphic. (Tablets have the room, so they're inline.)

   DESKTOP - inline. The graphic fades away once the video is really
   playing and the browser's own controls take over (they hide themselves
   while the mouse is idle).

   Each <study-video> walks through a few states, written to its data-state
   so the CSS (and any animation later) can key off one attribute:

     idle     - the graphic and the play button. The video's controls are
                off, so nothing of the player shows.
     loading  - pressed; the video is starting. The button goes, the graphic
                holds until the first frame is really playing. (On a phone
                it holds the whole time the full-screen player is up.)
     playing  - desktop only: the graphic has faded away and the browser's
                own controls take over.
     ended    - finished; the graphic and the button come back. Pressing
                play again starts it over.

   Only one study video talks at a time: starting one pauses the other.

   Without this script there's no data-state, so the CSS leaves the graphic
   and button hidden and the video keeps the controls the markup gave it. */
(function () {
	var videos = document.querySelectorAll('study-video');

	// A phone: touch, no hover, and narrow. The width is what keeps tablets
	// on the inline build.
	var phoneScreen = matchMedia('(hover: none) and (pointer: coarse) and (max-width: 48em)').matches;

	videos.forEach(function (element) {
		var video = element.querySelector('video');
		var button = element.querySelector('.play');
		if (!video || !button) return;

		// The phone build needs a full-screen call to make: iPhone Safari has
		// only webkitEnterFullscreen, Android Chrome only the standard one. A
		// phone browser with neither gets the inline build, never a video
		// playing invisibly under the graphic.
		var phone = phoneScreen && !!(video.webkitEnterFullscreen || video.requestFullscreen);

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

			if (phone) {
				openFullScreen();
			}
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
			if (phone) return;

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

		/* PHONE: the full-screen player. Two calls, two rules: Android's
		   standard requestFullscreen must be asked inside the tap itself;
		   iPhone's webkitEnterFullscreen refuses until the video knows its
		   size, so when the tap comes before that, it waits for the
		   metadata - which the play() above is already fetching. */
		function openFullScreen() {
			if (!video.webkitEnterFullscreen) {
				video.requestFullscreen().catch(closePlayer);
			} else if (video.readyState >= 1) {
				enterWebKitFullScreen();
			} else {
				video.addEventListener('loadedmetadata', function () {
					// Only if they're still waiting on this one - a second tap
					// elsewhere may have stopped it in the meantime.
					if (element.dataset.state === 'loading') {
						enterWebKitFullScreen();
					}
				}, { once: true });
			}
		}

		// Either call can refuse (the browser didn't count the tap, or the
		// video can't go full screen). Then don't leave the video talking
		// under the graphic - stop it, button back.
		function enterWebKitFullScreen() {
			try {
				video.webkitEnterFullscreen();
			} catch (refused) {
				closePlayer();
			}
		}

		// Closing the player: stop the video (with playsinline it would keep
		// going, invisibly, under the graphic) and show the button again.
		function closePlayer() {
			video.pause();
			setState(video.ended ? 'ended' : 'idle');
		}

		video.addEventListener('webkitendfullscreen', closePlayer);

		document.addEventListener('fullscreenchange', function () {
			if (!document.fullscreenElement && element.dataset.state === 'loading') {
				closePlayer();
			}
		});
	});
})();
