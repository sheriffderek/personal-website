<?php
$brief = [
	'goal' => 'Use the opportunity to show how Derek\'s experience adds up - for people who might not see this history as intertwined and layered - vs hopping from role to role. '
		. 'Set that up / give them options to dig deeper. '
		. 'Show them the three links to specific resumes.',
];

/* This page stands on its own (obviously / why would you ever not think of it that way...) (Derek, 2026-09-24): it links INTO the core
   resume pages (/resume/<lane>, their cover letters, the PDF exports) but
   reads nothing they're built from - not content/resume.json, not
   styles/modules/resume.css. Its words live right here; its styles live in
   styles/modules/resume-index.css. Editing this page can't touch those. */
$lanes = [
	[
		'slug' => 'product-designer',
		'label' => 'Product Designer',
		'roles' => 'Product Designer, Senior/Staff Product Designer, Technical Product Designer, Founding Designer, UX Designer, someone who can understand the whole system and figure out what to do next - at any scope - for any industry.',
	],
	[
		'slug' => 'advocate',
		'label' => 'Advocate & Educator',
		'roles' => 'Developer Advocate, Design Advocate, Developer Experience (DX), Design Educator, Learning Experience Designer, Curriculum/Education Designer at a product company.',
	],
	[
		'slug' => 'design-engineer',
		'label' => 'Design Engineer',
		'roles' => 'Developer with taste and attention to detail, Design Technologist, UX Engineer, Design Systems Engineer, bridging the gap between design and dev.',
	],
];
?>

<text-content class='styled'>

	<h1 class='loud-voice'>Resume</h1>

	<p>I know the generic wisdom is to "pick a lane" and "pick a niche" (and that's what I have my students do - because that's where they're at). My fancy recruiter friends would rewrite everything about me to fit a <em>very specific</em> role. But the truth is, there is more than one role where I can give 100% - with experience and passion.</p>

	<p>Sometimes people ask me: "What's your stack?" This is it. Depending on your goals, the size of the org/project, the shape of the team - and other factors: how I can bring the most value changes. There needs to be a fit beyond "role" and salary for me.</p>

</text-content>

<?php /* The career as layers that stack (Derek's sketch, 2026-09-24) -
	the reason there are three resumes. Placed by resume-index.css. */ ?>
<?= partial('layered-experience-chart', ['layers' => load_json('layered-experience.json')]) ?>

<text-content class='styled'>

	<nav class='resume-lanes' aria-label='Resume versions'>

		<ul>

			<?php foreach ($lanes as $lane): ?>
				<li class='strong-voice'>

					<h2 class='strong-voice'>
						<a class='link' href='/resume/<?= $lane['slug'] ?><?= $target_query ?>'><?= $lane['label'] ?></a>
					</h2>

					<p>For roles like: <?= $lane['roles'] ?></p>

					<?php /* Each lane travels with a letter. "Generic" says it
						out loud: it wasn't written for the reader's company. */ ?>
					<p class='quiet-voice'>
						<a class='link' href='/resume/<?= $lane['slug'] ?>/cover-letter<?= $target_query ?>'>Generic cover letter</a>
					</p>

				</li>
			<?php endforeach; ?>

		</ul>

	</nav>

</text-content>
