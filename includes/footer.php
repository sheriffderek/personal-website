	</main>

	<footer class='site-footer'>
		<nav class='footer-menu' aria-label='Footer'>
			<?php foreach ($pages as $page_slug => $page): ?>
				<?php if (empty($page['menu'])) { continue; } ?>

				<a href='<?= $page_slug === 'home' ? '/' : '/' . $page_slug ?>'><?= $page['menu'] ?></a>
			<?php endforeach; ?>
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
			console.log('carousel init: found', document.querySelectorAll('.carousel').length);
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

			document.querySelectorAll('.carousel').forEach(el => {
				const flkty = Flickity.data(el);
				console.log('flickity instance?', !!flkty, el);
				if (!flkty) return;

				const pauseAll = () => {
					el.querySelectorAll('video').forEach(v => { if (!v.paused) v.pause(); });
					current = null;
				};

				// Settle = animation finished. Old slide fully out, new slide fully in.
				// Pause everything, then autoplay if the arriving slide is a loop.
				flkty.on('settle', i => {
					pauseAll();
					const arriving = flkty.cells[i].element;
					console.log('settled on', i, arriving.dataset.type);
					if (arriving.dataset.type === 'loop') {
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

				// Click on play slides — user invokes native controls; pause anything else.
				el.querySelectorAll('.slide[data-type="play"] video').forEach(video => {
					video.addEventListener('play', () => {
						if (current && current !== video) current.pause();
						current = video;
					});
					video.addEventListener('pause', () => {
						if (current === video) current = null;
					});
				});

				// Scroll trigger:
				//   - start: figure's top crosses above 50% of viewport
				//   - stop:  figure is fully off-screen (top or bottom)
				// Once started, keeps playing as long as any part is visible.
				const check = () => {
					const r = el.getBoundingClientRect();
					const vh = window.innerHeight;
					const fullyOff = r.bottom <= 0 || r.top >= vh;
					const pastStartLine = r.top < vh * 0.5;
					const selected = flkty.selectedElement;
					const video = selected && selected.dataset.type === 'loop'
						? selected.querySelector('video')
						: null;
					if (fullyOff) {
						pauseAll();
					} else if (pastStartLine && video && video.paused) {
						play(video);
					}
				};

				let raf = null;
				window.addEventListener('scroll', () => {
					if (raf) return;
					raf = requestAnimationFrame(() => { raf = null; check(); });
				}, { passive: true });
				check();
			});
		});
	</script>

</body>
</html>
