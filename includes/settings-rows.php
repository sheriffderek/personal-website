<?php /* The settings rows - ONE list, shared by every settings surface: the
	panel popover in the tray, and the band on the grid's top composition
	(templates/pages/home.php). The mirror model: each surface renders its own
	copy of these rows; none owns the state - settings-panel.js writes state to
	data-* on <html> + localStorage and reflects EVERY control from it, so N
	copies stay in step for free.

	Callers pass (via partial()):
	  id_suffix      keeps label ids unique per surface (aria-labelledby) -
	                 '' for the panel, '-band' for the band
	  page_has_grid  whether the Layout row renders (timeline page only)
	  page_controls  the page's own controls partial (the timeline filter) */ ?>
<?php $id_suffix = $id_suffix ?? ''; ?>
<?= partial('settings/mode-switcher', ['id_suffix' => $id_suffix]) ?>
<?= partial('settings/character-switcher', ['id_suffix' => $id_suffix]) ?>
<?= partial('settings/mood-switcher', ['id_suffix' => $id_suffix]) ?>

<?= partial('settings/flavor-switcher', ['id_suffix' => $id_suffix]) ?>
<?php if (!empty($page_has_grid)): ?>
	<?= partial('settings/view-switcher', ['id_suffix' => $id_suffix]) ?>
<?php endif; ?>
<?= partial('settings/sound-switcher', ['id_suffix' => $id_suffix]) ?>
<?= partial('settings/red-light-switcher', ['id_suffix' => $id_suffix]) ?>
<?php /* The page's own controls row (the timeline filter) sits behind
	FILTER_ENABLED - v1 ships without the slider; config.php has the story. */ ?>
<?php if (!empty($page_controls) && FILTER_ENABLED): ?>
	<?= partial('settings/' . $page_controls, ['id_suffix' => $id_suffix]) ?>
<?php endif; ?>
