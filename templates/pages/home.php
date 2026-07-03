<?php
// The home page: the reverse-chronological timeline of milestones.
// The "how much to show" filter lives here and nowhere else.

$all_milestones = load_json('milestones.json');

// Which slice of the timeline? Defaults to job-relevant, weight-3 entries.
$filter_tag = isset($_GET['filter']) ? $_GET['filter'] : 'job';

$milestones = array_filter($all_milestones, function ($m) use ($filter_tag) {
	$tags = isset($m['tags']) ? $m['tags'] : [];
	$weight = isset($m['weight']) ? $m['weight'] : 1;
	return in_array($filter_tag, $tags) && $weight >= 3;
});

// A ?target=companyname loads tailored notes for specific milestones.
$target = null;
if (isset($_GET['target'])) {
	$target_slug = preg_replace('/[^a-z0-9-]/', '', strtolower($_GET['target']));
	if ($target_slug !== '') {
		$candidate = load_json('targets/' . $target_slug . '.json');
		if (!empty($candidate)) $target = $candidate;
	}
}
$target_notes = $target['milestones'] ?? [];
?>

<?php if (!empty($target['hero'])): ?>
	<p class='target-note'>
		<?= $target['hero'] ?>
	</p>
<?php endif; ?>

<ul class='timeline' role='list'>
	<?php foreach ($milestones as $milestone):
		$target_note = $target_notes[$milestone['slug']] ?? null;
	?>
		<li>
			<?php require TEMPLATES_DIR . '/milestone.php'; ?>
		</li>
	<?php endforeach; ?>
</ul>
