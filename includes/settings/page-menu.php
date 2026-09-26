<nav class='page-menu' aria-label='Pages'>
	<ul role='list'>
		<?php foreach ($pages as $page_slug => $page): ?>
			<?php if (empty($page['menu'])) { continue; } ?>

			<li>
				<?php /* Every row carries an icon slot (2026-09-25) - one fixed
					size for every family, so a family swaps the icon set and
					never the layout. The outlined circle holds the slot until a
					family brings its own set (the menu notes in
					styles/modules/settings-panel.css, CHROME TAKES). */ ?>
				<a class='link' href='<?= ($page_slug === 'home' ? '/' : '/' . $page_slug) . ($target_query ?? '') ?>'<?= $page_slug === $slug ? " aria-current='page'" : '' ?>>
					<span
						class='menu-icon'
						aria-hidden='true'
					><?php include INCLUDES_DIR . '/app-ui/glyphs/circle.php'; ?></span>
					<?= $page['menu'] ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
