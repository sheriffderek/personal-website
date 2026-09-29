/* Case-study videos (includes/study-video.php) - the graphic is the face,
   the Vimeo player sits underneath and takes the tap itself (the partial
   says why).

   Each <study-video> walks through a few states, written to its data-state
   so the CSS (and any animation later) can key off one attribute:

     idle    - the graphic and the play cue, over a paused player.
     loading - tapped; Vimeo is starting. The cue goes, the graphic holds.
     playing - the video is really playing; the graphic has faded away and
               Vimeo's own controls take over.
     ended   - finished; the graphic comes back. A tap starts it over.

   Only one study video talks at a time: starting one pauses the other.

   Needs Vimeo's player.js (loaded in header.php). Without it the players
   still work - the graphic just never steps aside. */
(function () {
	var videos = document.querySelectorAll('study-video');
	if (!videos.length || !window.Vimeo) return;

	var talking = null;

	videos.forEach(function (element) {
		var player = new Vimeo.Player(element.querySelector('iframe'));

		function setState(state) {
			element.dataset.state = state;
		}

		player.on('play', function () {
			if (talking && talking !== player) {
				talking.pause();
			}

			talking = player;
			setState('loading');
		});

		player.on('playing', function () {
			setState('playing');
		});

		player.on('ended', function () {
			if (talking === player) {
				talking = null;
			}

			setState('ended');
		});
	});
})();
