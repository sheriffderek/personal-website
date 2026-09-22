<?php
	// The PE umbrella study (#1 in case-studies-plan.md). Each section is the
	// section model in order - heading, video, then Derek's paragraphs. The
	// paragraphs are his to write; a section without them is just not filled yet.
	//
	// The .working-notes lists are NOT copy: they are key points condensed from
	// each video's transcript, raw material for Derek's writing. They render
	// off production only, so a push can never publish them. Each one gets
	// deleted as its section's real paragraphs land.
?>

<?php /* Overview and Goals open the study (the conventional case-study
	front matter, ahead of the videos). Both are gated off production until
	Derek's copy lands - an empty heading is a placeholder in the visitor's
	path. To publish one: write it, delete its working notes, and lift the
	section out of this if-block. */ ?>
<?php if (!IS_PRODUCTION): ?>

	<section id='overview'>

		<h2 class='attention-voice'>Overview</h2>

		<p>In 2019 I set out to design a curriculum for cross-functional product designers.</p>

		<p>Web developers had seemingly divorced themselves from knowing anything about space (literally the difference between 10 and 20px) - and visual designers threw their hands up claiming they could never understand <em>code</em>. I knew - as someone experienced with both - and who had great luck pairing and building things with lean teams - that this was out of ignorance and by choice. People were paying 30k for 3-month boot camps that spit them out the other end with surface-level React.js skills and basically nothing else. Surely - we could do better.</p>

		<p>But that is a story for <em>another time</em>. <mark>This story</mark> is about how we designed a learning management system (LMS), continuous discovery, and how spending the right amount of time in divergent thinking modes will allow you find what you're looking for.</p>
	</section>

	<section id='goals'>
		<h2 class='attention-voice'>Goals</h2>

		<ul class='working-notes'>
			<li>
				<h3>goal name here?</h3>

				<p>Most off-the-shelf course/education platforms offered a small set of options for course... (Description?)</p>
			</li>
		</ul>
	</section>

<?php endif; ?>

<section id='avoiding-the-common-pattern'>

	<h2 class='attention-voice'>Avoiding blindly following the common pattern</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1228952549' title='Avoiding blindly following the common pattern' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>The pull is to copy the known LMS: video on top, a paragraph, a PDF, a progress bar.</li>

			<li>Every discipline wants to start from its own corner - the education gets shoved in later.</li>

			<li>The move: stop and ask who is actually involved.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='a-common-school-day'>

	<h2 class='attention-voice'>Exploring a common school day</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1228952586' title='Exploring a common school day' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>The users are far more than a student clicking "finished": teachers, writers, editors, TAs, legal, whoever inherits it years later - even AI agents reading the data.</li>

			<li>Students take it in a dozen ways: library or home, with a tutor, with a screen reader.</li>

			<li>Everyone has been to school - the school day is a shared reference for research.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='shifts-between-modes'>

	<h2 class='attention-voice'>Subtle shifts between modes</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1228959941' title='Subtle shifts between modes' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>Every block of the day does a second job: welcoming, shifting gears, settling in, resetting.</li>

			<li>Those phases set intensity and build arcs - for the student, and for the teacher planning.</li>

			<li>The same begin / break / end pattern holds at every scale, from K-through-masters down to a paragraph.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='initial-content-types'>

	<h2 class='attention-voice'>Exploring initial content types</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1228980047' title='Exploring initial content types' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>Teachers work from playbooks (state standards, Montessori, Waldorf), often together.</li>

			<li>The modes of a classroom translate into content types: text, image, sequence, interactive, short clip, long lecture with chapters, live vs. asynchronous.</li>

			<li>Start from the accepted pattern and you never discover any of this.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='enough-to-start-building'>

	<h2 class='attention-voice'>Enough to start building and testing</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1229033043' title='Enough to start building and testing' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>The blocks assemble into something like a blog post: heading, a picture to set the feel, a description, long text, an interactive figure.</li>

			<li>Some pieces belong to the page template (the lesson title is always there); the rest are optional areas.</li>

			<li>Each module carries its own conditions - does an image get a caption, alt text, a description, a heading. Solved problems, nothing new.</li>

			<li>So: build it, play with it, get teachers loading their stuff in, and feel all the edges.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='naming-matters'>

	<h2 class='attention-voice'>Naming matters</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1229034463' title='Naming matters' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>An LMS is a CMS built for learning: users, roles, permissions, admin, a database, an ID on every course and workshop. Standard.</li>

			<li>Naming is the opportunity. Started with course > module > lesson because that's how colleges do it - and "module" also names the page modules, so the words ran out.</li>

			<li>Switched to program (a predefined set designed for you to go through) and workshop (self-contained, can live in many programs, has a clear goal you can tell you've reached).</li>

			<li>Every program, every part of a program, every workshop has a goal. As a designer, you can't know a workshop did its job without one.</li>
		</ul>
	<?php endif; ?>

</section>

<section id='breaking-things-up'>

	<h2 class='attention-voice'>Breaking things up into a journey of many parts</h2>

	<figure class='study-figure'>
		<iframe src='https://player.vimeo.com/video/1229039339' title='Breaking things up into a journey of many parts' allow='fullscreen; picture-in-picture' loading='lazy'></iframe>
	</figure>

	<?php if (!IS_PRODUCTION): ?>
		<ul class='working-notes'>
			<li>Famous courses started as a page of ideas and a lecture, then got iterated for years - so the system has to encourage adding, removing, moving, and adjusting goals.</li>

			<li>Page modules can group into phases with a different feel: a welcome area, then a deep-study area. Books have this built in; the web scrolls forever.</li>

			<li>Treat the scroll as a linear journey: an entry point that sets the feel, the goals, the big picture, what's coming.</li>

			<li>Sectioning elements plus page modules let the workshop designer build a journey that does its job.</li>
		</ul>
	<?php endif; ?>

</section>
