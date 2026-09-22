<?php
	// The index of case studies - every row in content/case-studies.json that
	// also has its body file (the same both-must-exist rule as the entry route).
	// Bare on purpose: title and teaser, in the JSON's authored order (the
	// order IS the ranking - these aren't dated). No menu door yet.
	$case_studies = load_json('case-studies.json');

	$listed = array_filter($case_studies, function ($study_slug) {
		return is_file(TEMPLATES_DIR . '/case-studies/' . $study_slug . '.php');
	}, ARRAY_FILTER_USE_KEY);
?>

<text-content class='styled case-study-index'>

	<h1 class='loud-voice'>Case studies</h1>

	<ol class='study-list'>
		<?php foreach ($listed as $study_slug => $study): ?>
			<li>
				<a class='link strong-voice' href='/case-studies/<?= $study_slug ?><?= $target_query ?>'><?= $study['title'] ?></a>

				<?php if (!empty($study['teaser'])): ?>
					<p><?= $study['teaser'] ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>

</text-content>
