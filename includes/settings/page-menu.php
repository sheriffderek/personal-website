<nav class='page-menu' aria-label='Pages'>
	<?php foreach ($pages as $page_slug => $page): ?>
		<?php if (empty($page['menu'])) { continue; } ?>

		<a href='<?= $page_slug === 'home' ? '/' : '/' . $page_slug ?>'<?= $page_slug === $slug ? " aria-current='page'" : '' ?>><?= $page['menu'] ?></a>
	<?php endforeach; ?>
</nav>
