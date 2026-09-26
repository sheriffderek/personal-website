<?php /* One phone-context frame for the /app-ui playground: a phone-sized
	frame over a stand-in page, with the real bar triggers and the real card
	(live settings rows) placed one way, in one button style. Dumb: all of
	it arrives as props. Placement and take are pure CSS
	(styles/modules/app-ui.css) keyed off the two data attributes.

	Callers pass (via partial()):
	  placement  straddle | below | contain | float - where the card meets the bar
	  take       ring | ghost - the trigger style
	  caption    what the frame is labeled
	  id_suffix  keeps the settings rows' label ids unique per frame */ ?>
<figure
	class='phone-context'
	data-ui='app'
	data-placement='<?= $placement ?>'
	data-take='<?= $take ?>'
>
	<div
		class='stand-in-page'
		aria-hidden='true'
	></div>

	<div class='context-bar'>
		<div
			class='toolbar'
			data-ui='app'
			inert
		>
			<?= partial('app-ui/trigger', ['label' => 'Pages', 'glyph' => 'pages']) ?>

			<?= partial('app-ui/trigger', ['label' => 'Display settings', 'glyph' => 'settings', 'expanded' => true]) ?>
		</div>
	</div>

	<div
		class='app-card context-card'
		data-ui='app'
	>
		<div class='panel-scroll settings-panel'>
			<?= partial('settings-rows', ['id_suffix' => $id_suffix, 'page_has_grid' => false, 'page_controls' => null]) ?>
		</div>
	</div>

	<figcaption class='quiet-voice'><?= $caption ?></figcaption>
</figure>
