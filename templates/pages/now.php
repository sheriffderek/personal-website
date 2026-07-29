<?php
// The "now" page: what Derek's focused on at the moment.
// A ?target=companyname loads a tailored note for that company, same as the
// timeline's target notes on the home page.
$target = isset($_GET['target']) ? load_target($_GET['target']) : null;
?>

<text-content class='styled'>
	<h1 class='loud-voice'>Now</h1>

	<p>Hello! Is it summer already!?</p>

	<p>For the last five years, I've been designing curriculum and teaching digital product design at Perpetual Education. We've refined the program so thoroughly that students can now move through it autonomously.</p>

	<p>I couldn't exactly have a full-time role while running a school ;), but I had bandwidth for interesting contracts - accessibility consulting, full product design/builds, research and development. I folded those experiences back into the curriculum to show students how it works in practice.</p>

	<p>Now I'm designing the next phase of my career. You could say I've been doing "web dev" since Flash in 2000 - but I really started getting serious in 2011. While I love programming, I'm most valuable as an iterative product designer. I'm interested in collaborative product work, particularly in education and learning.</p>

	<?php if (!empty($target['now'])): ?>
		<p class='target-note'>
			<?= $target['now'] ?>
		</p>
	<?php endif; ?>
</text-content>
