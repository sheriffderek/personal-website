	</main>

	<footer class='site-footer'>
		<?php /* ---- Footer nav - disabled alongside the Pages menu in settings-panel.php ----
			How I work / Now / Contact are still placeholders, leaving home as the only
			link. Routes and templates untouched - uncomment when those pages are real.

		<nav class='footer-menu' aria-label='Footer'>
			<ul role='list'>
				<?php foreach ($pages as $page_slug => $page): ?>
					<?php if (empty($page['menu'])) { continue; } ?>

					<li>
						<a href='<?= ($page_slug === 'home' ? '/' : '/' . $page_slug) . ($target_query ?? '') ?>'><?= $page['menu'] ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		---- end footer nav ---- */ ?>

		<?php $version = deployed_version(); ?>

		<p class='data-voice'>&copy; <?= SITE_TITLE ?></p>

		<p class='data-voice'>
			<?php if ($version['hash']): ?>
				<?= $version['hash'] ?> ·
			<?php endif; ?>
			<?= date('Y-m-d H:i', $version['time']) ?>
		</p>

		<?php /* Testing knob for the carousel nudge, riding its flag: the link
			reloads with ?hint=reset, which forgets the swiped-once breadcrumb so
			the nudge plays again like a first visit. Commented out for launch -
			uncomment (with the ?hint=reset handler in slider-hint.js) to tune.

		<?php if (SLIDER_HINT_ENABLED): ?>
			<p class='quiet-voice'>
				<a class='link' href='/?hint=reset'>Reset the slider hint</a>
			</p>
		<?php endif; ?>

		*/ ?>
	</footer>

</div>

	<script>
		// ------------------------------------------------------------------
		// THE PLAYBACK RULEBOOK - what a tester should expect, in plain rules.
		// This is the behavioral spec; every handler below implements one line
		// of it. If the code and this list disagree, one of them is a bug.
		//
		// Three video kinds (the 'type' on each media item in milestones.json):
		//   loop  - ambient motion. Always muted, always loops, no controls.
		//   story - a little animation with a quick story behind it. Runs as
		//           silent ambient motion like a loop, but wears a Listen
		//           button; pressing it restarts the clip from the top WITH
		//           sound. When the story ends it goes quiet and loops again.
		//   play  - a talking video. Audio + custom controls. Starts only when
		//           its button is pressed, never by itself.
		//
		// Only muted videos ever autoplay - that is a browser rule, not a
		// choice of ours: no browser will start a video that has a voice
		// without the visitor asking. That is why a story starts silent and
		// why Listen (a click) is the one door into its audio.
		//
		// On page load: every carousel sits on its poster cover slide, so
		// nothing is playing anywhere.
		//
		// Scrolling: if a card's SELECTED slide is ambient (a loop, or a story
		// nobody has pressed) and the card crosses the middle of the screen, it
		// starts. A card leaving the screen entirely pauses its ambient motion -
		// but never a video that is talking, so you can listen while you scroll.
		//
		// Swiping: swiping away from a slide stops everything in that carousel
		// (a talking video included - a swipe is a direct gesture at that
		// video). If the arriving slide is ambient, it starts.
		//
		// Hover (desktop): hovering an ambient slide plays it; leaving pauses it
		// unless it's the selected slide. A talking story ignores both.
		//
		// Pressing Listen or a play button: it talks; whatever else was talking
		// stops (a story that gets silenced keeps running as ambient motion; a
		// play has nothing to fall back to, so it just stops).
		// Tapping the video body does nothing (so a swipe can't start a clip).
		//
		// Grid view: scrolling never starts ambient motion (the wall stays
		// still), but swiping to it or hovering it still plays - those are
		// direct gestures at that card, like pressing a play button.
		//
		// Always: at most one video is audible; autoplay is never audible;
		// reduced-motion visitors get still frames, never motion.
		// ------------------------------------------------------------------
		window.addEventListener('load', () => {
			// The one talking video. A video claims that slot the moment it has a
			// voice, and 'muted' is the honest test for that whatever its type: a
			// 'play' is never muted, a 'loop' always is, and a 'story' is muted
			// until someone presses Listen. So audio exclusivity falls out of one
			// rule for all three - a talking video silences the other talker, and
			// muted motion neither silences anyone nor waits for anyone.
			let current = null;

			function play(video) {
				if (!video.muted) {
					if (current && current !== video) hush(current);
					current = video;
				}
				video.play();
			}

			function pause(video) {
				video.pause();
				if (current === video) current = null;
			}

			function isStory(video) {
				return video.closest('.slide').dataset.type === 'story';
			}

			// Ambient = muted, by the rule above. It's what scroll may start and
			// what scrolling away may stop.
			function isAmbientSlide(slide) {
				return slide.dataset.type === 'loop' || slide.dataset.type === 'story';
			}

			// Pressing Listen. The clip restarts from the top: unmuting it in place
			// would drop the visitor into the middle of a sentence, and the whole
			// point of a story clip is the little story. Looping is dropped for the
			// same reason - ambient motion may repeat forever, a spoken story may not.
			function tell(video) {
				video.muted = false;
				video.loop = false;
				video.currentTime = 0;
				markTalking(video, true);
				play(video);
			}

			// Taking a video's voice away. A story doesn't stop existing when it
			// stops talking - it goes back to being the silent little loop it was
			// before anyone pressed Listen, still moving. A 'play' has no ambient
			// life to return to, so it simply stops.
			function hush(video) {
				if (!isStory(video)) {
					pause(video);
					return;
				}

				video.muted = true;
				video.loop = true;
				markTalking(video, false);

				if (current === video) current = null;
			}

			function markTalking(video, talking) {
				const slide = video.closest('.slide');
				const listen = slide.querySelector('.listen');

				slide.classList.toggle('is-talking', talking);

				if (listen) {
					listen.setAttribute('aria-label', talking ? 'Mute' : 'Play with sound');
				}
			}

			// Ambient motion autoplays through here (scroll, settle, and hover all
			// route through it); Listen and the play/pause button talk directly to
			// tell() and play().
			function autoplay(video) {
				play(video);
			}

			// Grid view is a wall of stills: scrolling never starts a loop there.
			// But a swipe to a loop slide or a hover IS a gesture at that card, so
			// those still play - only the ambient scroll trigger checks this.
			function gridView() {
				return document.documentElement.getAttribute('data-view') === 'grid';
			}

			// Which cut to load. <source media> is ignored inside <video>, so we
			// choose the file ourselves - wide normally, square on phones - and
			// re-choose if the viewport later crosses the breakpoint.
			const phone = window.matchMedia('(max-width: 600px)');

			function pickSource(video) {
				const wanted = phone.matches ? video.dataset.srcSquare : video.dataset.srcWide;
				if (!wanted) return;

				// Keep the freeze-frame poster on the same cut as the source. Done
				// above the early return, so the poster still swaps at the breakpoint
				// even when the source itself doesn't need reloading.
				const wantedPoster = phone.matches ? video.dataset.posterSquare : video.dataset.posterWide;
				if (wantedPoster && video.poster.indexOf(wantedPoster) === -1) {
					video.poster = wantedPoster;
				}

				const loaded = video.currentSrc || video.src;
				if (loaded.indexOf(wanted) !== -1) return;

				const resumeAt = video.currentTime;
				const wasPlaying = !video.paused;

				video.src = wanted;
				video.load();

				video.addEventListener('loadedmetadata', function restore() {
					video.removeEventListener('loadedmetadata', restore);
					// Only seek when there's actually a position to restore. Seeking
					// clears the element's "show poster" flag, so an unconditional
					// currentTime = 0 here would throw the poster away and paint
					// frame zero instead - which is what happened on phones, where
					// this is the only path that reloads the source.
					if (resumeAt > 0) {
						try { video.currentTime = resumeAt; } catch (e) {}
					}

					if (wasPlaying) video.play();
				});
			}

			const sourced = document.querySelectorAll('video[data-src-wide]');
			sourced.forEach(pickSource);

			phone.addEventListener('change', () => sourced.forEach(pickSource));

			// Custom player for 'play' slides - the button toggles play/pause, the
			// scrubber seeks. Replaces the native controls we can't trim on iOS.
			document.querySelectorAll('.slide[data-type="play"]').forEach(slide => {
				const video = slide.querySelector('video');
				const playPause = slide.querySelector('.play-pause');
				const scrubber = slide.querySelector('.scrubber');

				// While the user is dragging, the video's own time updates must not
				// write back to the scrubber - that fight is what makes it jitter.
				let scrubbing = false;

				// Paint the thumb position and the filled (played) portion. --progress
				// drives the track fill in CSS.
				function paint() {
					const percent = video.duration ? (video.currentTime / video.duration) * 100 : 0;
					scrubber.value = percent;
					scrubber.style.setProperty('--progress', percent);
				}

				// timeupdate only fires ~4x/sec, which looks steppy. While playing we
				// repaint every animation frame instead for a smooth playhead.
				let frame = null;
				function follow() {
					if (!scrubbing) paint();
					frame = video.paused ? null : requestAnimationFrame(follow);
				}

				video.addEventListener('loadedmetadata', paint);

				video.addEventListener('play', () => {
					slide.classList.add('is-playing');
					playPause.setAttribute('aria-label', 'Pause');
					if (!frame) follow();
				});

				video.addEventListener('pause', () => {
					slide.classList.remove('is-playing');
					playPause.setAttribute('aria-label', 'Play');
					paint();
				});

				// Only the play/pause button toggles — never a tap on the video.
				// A tap-on-video handler also fires when Flickity treats the press
				// as a swipe, so the clip would start playing mid-drag. The button
				// (with its own pointerdown stopPropagation below) is the only play
				// affordance, leaving the video body free to swipe the carousel.
				playPause.addEventListener('click', () => {
					if (video.paused) play(video); else pause(video);
				});

				// Seek live as the user drags; the video follows the thumb, not vice versa.
				scrubber.addEventListener('input', () => {
					scrubbing = true;
					if (video.duration) video.currentTime = (scrubber.value / 100) * video.duration;
					scrubber.style.setProperty('--progress', scrubber.value);
				});

				// Hand control back once the drag ends.
				function endScrub() { scrubbing = false; }
				scrubber.addEventListener('change', endScrub);
				window.addEventListener('pointerup', endScrub);

				// Keep a control-bar gesture from turning into a carousel swipe.
				// (The video itself stays swipeable - only the controls stop it.)
				[playPause, scrubber].forEach(control => {
					control.addEventListener('pointerdown', event => event.stopPropagation());
				});
			});

			// Ambient autoplay respects reduced-motion: those users get the still
			// first frame, never an auto-playing clip. A story still shows its
			// Listen button, so the story itself is never withheld from them - only
			// the motion they didn't ask for. (Motion policy: gate the decorative,
			// never the requested.)
			const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			// The Listen button on 'story' slides - the one door from silent motion
			// to a spoken story, and it must be a click: see the rulebook above.
			document.querySelectorAll('.slide[data-type="story"]').forEach(slide => {
				const video = slide.querySelector('video');
				const listen = slide.querySelector('.listen');

				listen.addEventListener('click', () => {
					if (video.muted) tell(video); else hush(video);
				});

				// Told all the way through, it goes quiet and picks its silent loop
				// back up (play() on an ended video restarts it from the top).
				video.addEventListener('ended', () => {
					hush(video);
					if (!reduceMotion) video.play();
				});

				// Same guard as the custom player: a press on a control must not
				// also become a carousel swipe.
				listen.addEventListener('pointerdown', event => event.stopPropagation());
			});

			// Shared scroll test:
			//   pastStartLine - the figure's top has crossed above 50% of viewport
			//   fullyOff      - the figure is entirely above or below the viewport
			// Once playing, a loop keeps playing while any part stays visible.
			function visibility(el) {
				const r = el.getBoundingClientRect();
				const vh = window.innerHeight;
				return { fullyOff: r.bottom <= 0 || r.top >= vh, pastStartLine: r.top < vh * 0.5 };
			}

			// One scroll listener drives every registered carousel check, instead
			// of each carousel adding its own listener.
			const checks = [];
			function runChecks() { checks.forEach(fn => fn()); }

			let scrollRaf = null;
			window.addEventListener('scroll', () => {
				if (scrollRaf) return;
				scrollRaf = requestAnimationFrame(() => { scrollRaf = null; runChecks(); });
			}, { passive: true });

			// Carousels (2+ items): Flickity, with the selected slide's loop
			// autoplaying on scroll/settle.
			document.querySelectorAll('.carousel').forEach(el => {
				const flkty = Flickity.data(el);
				if (!flkty) return;

				// Swiping away from a slide is a direct gesture at that video, so
				// settle stops everything here, a talking one included. A story that
				// gets swiped away is hushed first, so it can't sit half-told and
				// resume mid-sentence when the visitor swipes back.
				const pauseAll = () => {
					el.querySelectorAll('video').forEach(video => {
						hush(video);
						pause(video);
					});
				};

				// Scrolling away is not. It only ends the ambient motion - anything
				// with a voice keeps talking until the visitor stops it, scrolls back
				// to it, or presses a different one. Muted IS ambient (see above).
				const pauseAmbient = () => {
					el.querySelectorAll('video').forEach(video => {
						if (video.muted) pause(video);
					});
				};

				// Settle = animation finished. Pause everything, then autoplay the
				// arriving slide if it's ambient.
				flkty.on('settle', i => {
					pauseAll();
					const arriving = flkty.cells[i].element;
					if (!reduceMotion && isAmbientSlide(arriving)) {
						autoplay(arriving.querySelector('video'));
					}
				});

				// Hover on ambient slides — desktop only by virtue of mouseenter/leave.
				// A story that's talking ignores hover entirely: the visitor asked for
				// it, so a stray mouse must not take it away.
				el.querySelectorAll('.slide[data-type="loop"], .slide[data-type="story"]').forEach(slide => {
					const video = slide.querySelector('video');

					slide.addEventListener('mouseenter', () => {
						if (video.muted) autoplay(video);
					});

					slide.addEventListener('mouseleave', () => {
						if (video.muted && flkty.selectedElement !== slide) pause(video);
					});
				});

				const check = () => {
					const view = visibility(el);
					const selected = flkty.selectedElement;
					const video = selected && isAmbientSlide(selected)
						? selected.querySelector('video')
						: null;
					if (view.fullyOff) {
						pauseAmbient();
					} else if (!reduceMotion && !gridView() && view.pastStartLine && video && video.muted && video.paused) {
						autoplay(video);
					}
				};
				checks.push(check);

				// Flickity caches cell geometry once. When the frame ratio and
				// full-bleed flip at the breakpoint it must re-measure, or slides
				// land off-center at the wrong height. Debounced so a window drag
				// doesn't thrash it.
				let resizeTimer = null;
				window.addEventListener('resize', () => {
					if (resizeTimer) clearTimeout(resizeTimer);
					resizeTimer = setTimeout(() => { flkty.resize(); runChecks(); }, 150);
				});

				check();
			});
		});
	</script>

</body>
</html>
