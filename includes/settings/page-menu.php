<nav class='page-menu' aria-label='Pages'>
	<ul role='list'>
		<?php foreach ($pages as $page_slug => $page): ?>
			<?php if (empty($page['menu'])) { continue; } ?>

			<li>
				<a href='<?= $page_slug === 'home' ? '/' : '/' . $page_slug ?>'<?= $page_slug === $slug ? " aria-current='page'" : '' ?>><?= $page['menu'] ?></a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>
