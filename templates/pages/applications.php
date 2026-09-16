<?php
	$brief = [
		'goal' => 'A private log of every job applied to, when, and why - '
			. 'derived live from job-search/pipeline.md (the canonical list). '
			. 'The route is walled off from production in index.php; this '
			. 'file never edits the source, only reads it.',
	];

	/* One source of truth: the shared job-search repo. If the file is not
	   there (fresh checkout on another machine, or the repo lives elsewhere),
	   the page says so honestly instead of pretending to have zero data. */
	$pipeline_path = dirname(SITE_ROOT) . '/job-search/pipeline.md';
	$pipeline_exists = is_file($pipeline_path);

	/* Parse the markdown into { section => [ { company, role, date, how, notes } ] }.
	   The format is locked by job-search/pipeline.md's own header:
	     - **Company — Role** | applied YYYY-MM-DD | <how> | <notes>
	   A line that doesn't match the shape is skipped (a stray comment or a
	   short-form entry), and we count how many we skipped so the page shows
	   it - honesty over silent loss. */
	$sections = [];
	$total = 0;
	$skipped = 0;

	if ($pipeline_exists) {
		$md = file_get_contents($pipeline_path);
		$lines = preg_split('/\r?\n/', $md);
		$current_section = null;

		foreach ($lines as $line) {
			if (preg_match('/^##\s+(.+?)\s*$/', $line, $m)) {
				$current_section = trim($m[1]);
				if (!isset($sections[$current_section])) {
					$sections[$current_section] = [];
				}
				continue;
			}

			if ($current_section === null) {
				continue;
			}

			if (strpos(ltrim($line), '- **') !== 0) {
				continue;
			}

			/* Split on the pipe delimiters the pipeline format uses. */
			$parts = array_map('trim', explode('|', $line));

			if (count($parts) < 2) {
				$skipped++;
				continue;
			}

			/* First part: `- **Company — Role**` (em dash OR hyphen - the file
			   uses both across entries, so accept either). */
			if (!preg_match('/^-\s+\*\*(.+?)\s*(?:—|-)\s*(.+?)\*\*\s*$/u', $parts[0], $head)) {
				$skipped++;
				continue;
			}

			$company = $head[1];
			$role = $head[2];

			$date_part = $parts[1] ?? '';
			$date = '';
			if (preg_match('/applied\s+(\d{4}-\d{2}-\d{2}|pre-\d{4}-\d{2}|[^|]+)/i', $date_part, $dm)) {
				$date = trim($dm[1]);
			}

			$how = $parts[2] ?? '';
			$notes = trim(implode(' | ', array_slice($parts, 3)));

			$sections[$current_section][] = [
				'company' => $company,
				'role' => $role,
				'date' => $date,
				'how' => $how,
				'notes' => $notes,
			];
			$total++;
		}
	}

	/* A friendly order for the sections - live activity first, then the log,
	   then the graveyard. Unknown sections (a future addition to the file)
	   render at the end so nothing goes missing. */
	$section_order = ['In conversation', 'Applied, waiting', 'Offers', 'Dead'];
	$ordered = [];
	foreach ($section_order as $key) {
		if (isset($sections[$key])) {
			$ordered[$key] = $sections[$key];
		}
	}
	foreach ($sections as $key => $rows) {
		if (!isset($ordered[$key])) {
			$ordered[$key] = $rows;
		}
	}
?>

<text-content class='styled site-index'>

	<h1 class='loud-voice'>Applications</h1>

	<p class='quiet-voice'>
		Read live from <code>../job-search/pipeline.md</code> - that file is the
		canonical list; this page never writes to it. Local-only, walled off
		from production in the router.
	</p>

	<?php if (!$pipeline_exists): ?>
		<p>
			No <code>pipeline.md</code> found at <code><?= htmlspecialchars($pipeline_path) ?></code>.
			The job-search repo may live elsewhere on this machine, or not be
			checked out. Nothing to render.
		</p>
	<?php else: ?>

		<p>
			<strong><?= $total ?></strong> application<?= $total === 1 ? '' : 's' ?> logged
			across <?= count($ordered) ?> section<?= count($ordered) === 1 ? '' : 's' ?>.
			<?php if ($skipped > 0): ?>
				<span class='quiet-voice'>(<?= $skipped ?> line<?= $skipped === 1 ? '' : 's' ?> skipped - didn't match the format)</span>
			<?php endif; ?>
		</p>

		<?php foreach ($ordered as $section => $rows): ?>
			<section class='ds-section'>
				<h2 class='attention-voice'>
					<?= htmlspecialchars($section) ?>
					<span class='quiet-voice'>(<?= count($rows) ?>)</span>
				</h2>

				<?php if (!$rows): ?>
					<p class='quiet-voice'>Empty.</p>
				<?php else: ?>
					<ul>
						<?php foreach ($rows as $row): ?>
							<li>
								<p>
									<strong><?= htmlspecialchars($row['company']) ?></strong> - <?= htmlspecialchars($row['role']) ?>
								</p>

								<p class='quiet-voice'>
									<?php if ($row['date']): ?>
										<?= htmlspecialchars($row['date']) ?>
									<?php endif; ?>

									<?php if ($row['how']): ?>
										· <?= htmlspecialchars($row['how']) ?>
									<?php endif; ?>
								</p>

								<?php if ($row['notes']): ?>
									<p><?= htmlspecialchars($row['notes']) ?></p>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</section>
		<?php endforeach; ?>

	<?php endif; ?>

</text-content>
