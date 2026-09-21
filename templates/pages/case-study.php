<?php
	// The shell every case study wears: title, the facts strip, then the
	// study's own body file. The direction it answers to (consistent chrome,
	// plain labeled facts before any prose) lives in case-studies-plan.md.
?>

<article class='styled case-study'>

	<header class='study-header'>
		<h1 class='loud-voice'><?= $study['title'] ?></h1>

		<?php /* One line under the title ('tagline' in case-studies.json).
			Derek writes it; until he does, nothing renders. */ ?>
		<?php if (!empty($study['tagline'])): ?>
			<p class='tagline'><?= $study['tagline'] ?></p>
		<?php endif; ?>

		<?php /* The facts strip - role, timeline, platform, team. Facts, not
			sentences. Filled per study in content/case-studies.json ('facts',
			label => value); an empty map renders nothing. */ ?>
		<?php if (!empty($study['facts'])): ?>
			<dl class='study-facts'>
				<?php foreach ($study['facts'] as $fact_label => $fact_value): ?>
					<div>
						<dt class='label-voice'><?= $fact_label ?></dt>

						<dd><?= $fact_value ?></dd>
					</div>
				<?php endforeach; ?>
			</dl>
		<?php endif; ?>
	</header>

	<?php require TEMPLATES_DIR . '/case-studies/' . $study['slug'] . '.php'; ?>

</article>
