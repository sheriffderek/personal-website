/* Case-study videos (includes/study-video.php) - the graphic is the face,
   the Vimeo player waits behind it.

   Each <study-video> walks through a few states, written to its data-state
   so the CSS (and any animation later) can key off one attribute:

     idle    - the graphic and the play button. Nothing from Vimeo loaded.
     loading - pressed; the player is being built behind the graphic.
     playing - the video is really playing; the graphic has faded away and
               Vimeo's own controls take over.
     ready   - the browser refused to start it for us (iOS can refuse sound
               in a freshly made player), so the graphic steps aside and
               Vimeo's own play button is one more tap away.
     ended   - finished; the graphic comes back, and pressing play again
               starts it over.

   Only one study video talks at a time: starting one pauses the other.

   Needs Vimeo's player.js (loaded in header.php). Without it the play
   button stays a plain link to the video on Vimeo. */
(function () {
	var videos = document.querySelectorAll('study-video');
	if (!videos.length || !window.Vimeo) return;

	var talking = null;

	videos.forEach(function (element) {
		var button = element.querySelector('.play');
		var player = null;

		// The link is for no-JS visitors. With JS it acts as a button.
		button.setAttribute('role', 'button');

		function setState(state) {
			element.dataset.state = state;
		}

		function build() {
			var holder = document.createElement('div');
			holder.className = 'player';
			element.prepend(holder);

			player = new Vimeo.Player(holder, {
				id: element.dataset.vimeo,
				dnt: true,
			});

			player.ready().then(function () {
				player.element.title = element.dataset.title;
			});

			player.on('playing', function () {
				if (talking && talking !== player) {
					talking.pause();
				}

				talking = player;

				// The button is about to hide under the video; keep a keyboard
				// visitor's place by handing focus to the player itself.
				if (document.activeElement === button) {
					player.element.focus();
				}

				setState('playing');
			});

			player.on('ended', function () {
				if (talking === player) {
					talking = null;
				}

				setState('ended');
			});
		}

		button.addEventListener('click', function (event) {
			event.preventDefault();

			if (!player) {
				build();
			}

			setState('loading');

			player.play().catch(function () {
				setState('ready');
			});
		});
	});
})();
