	</main>

	<footer class='site-footer'>
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

		<?php $version = deployed_version(); ?>
		<p class='quiet-voice'>
			&copy; <?= SITE_TITLE ?>

			<span class='data-voice'>
				<?php if ($version['hash']): ?>
					<?= $version['hash'] ?> ·
				<?php endif; ?>
				<?= date('Y-m-d H:i', $version['time']) ?>
			</span>
		</p>
	</footer>

</div>

	<script>
		window.addEventListener('load', () => {
			let current = null;

			function play(video) {
				if (current && current !== video) current.pause();
				current = video;
				video.play();
			}

			function pause(video) {
				video.pause();
				if (current === video) current = null;
			}

			// Which cut to load. <source media> is ignored inside <video>, so we
			// choose the file ourselves - wide normally, square on phones - and
			// re-choose if the viewport later crosses the breakpoint.
			const phone = window.matchMedia('(max-width: 600px)');

			function pickSource(video) {
				const wanted = phone.matches ? video.dataset.srcSquare : video.dataset.srcWide;
				if (!wanted) return;

				const loaded = video.currentSrc || video.src;
				if (loaded.indexOf(wanted) !== -1) return;

				const resumeAt = video.currentTime;
				const wasPlaying = !video.paused;

				video.src = wanted;
				video.load();

				video.addEventListener('loadedmetadata', function restore() {
					video.removeEventListener('loadedmetadata', restore);
					try { video.currentTime = resumeAt; } catch (e) {}
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

			// Autoplay for 'loop' videos respects reduced-motion: those users get
			// the still first frame, never an auto-playing clip.
			const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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

				const pauseAll = () => {
					el.querySelectorAll('video').forEach(v => {
						if (!v.paused) v.pause();
						if (current === v) current = null;
					});
				};

				// Settle = animation finished. Pause everything, then autoplay the
				// arriving slide if it's a loop.
				flkty.on('settle', i => {
					pauseAll();
					const arriving = flkty.cells[i].element;
					if (!reduceMotion && arriving.dataset.type === 'loop') {
						play(arriving.querySelector('video'));
					}
				});

				// Hover on loop slides — desktop only by virtue of mouseenter/leave.
				el.querySelectorAll('.slide[data-type="loop"]').forEach(slide => {
					const video = slide.querySelector('video');
					slide.addEventListener('mouseenter', () => play(video));
					slide.addEventListener('mouseleave', () => {
						if (flkty.selectedElement !== slide) pause(video);
					});
				});

				const check = () => {
					const view = visibility(el);
					const selected = flkty.selectedElement;
					const video = selected && selected.dataset.type === 'loop'
						? selected.querySelector('video')
						: null;
					if (view.fullyOff) {
						pauseAll();
					} else if (!reduceMotion && view.pastStartLine && video && video.paused) {
						play(video);
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
