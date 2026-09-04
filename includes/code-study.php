<?php
	/* CodeStudy embed - a live code editor (the same system the Perpetual
	   Education lessons use). The library arrives as a prebuilt bundle vendored
	   at scripts/code-study.iife.js + styles/code-study.css - source of truth
	   is the separate code-study repo (~/projects/code-study); rebuild there
	   and re-copy, never edit the vendored files.

	   The whole recipe is three pieces:
	   - this include (mount markup + per-embed config as JSON)
	   - scripts/code-study-loader.js (lazy-fetches the bundle near-viewport)
	   - styles/components/code-study-bridge.css (maps site tokens onto the
	     editor's token slots - the theming)

	   Expected via partial('code-study', [...]):
	   - files: array of ['name' => ..., 'content' => ...]
	   - zones: which pane goes where - ['left' => [...], 'right' => [...], 'bottom' => [...]]
	   - editable: visitors can type in the editor (default true)
	   - line_numbers: show the gutter (default false)
	*/

	$files = $files ?? [];
	$zones = $zones ?? ['left' => [], 'right' => ['output'], 'bottom' => []];
	$editable = $editable ?? true;
	$line_numbers = $line_numbers ?? false;

	if (empty($files)) {
		return;
	}

	$config = [
		'files' => $files,
		'zones' => $zones,
		'editable' => $editable,
		'lineNumbers' => $line_numbers,
	];
?>

<?php if (!defined('CODE_STUDY_ASSETS_LOADED')): define('CODE_STUDY_ASSETS_LOADED', true); ?>
	<link rel='stylesheet' href='<?= asset('/styles/components/code-study-bridge.css') ?>'>

	<script
		src='<?= asset('/scripts/code-study-loader.js') ?>'
		data-stylesheet='<?= asset('/styles/code-study.css') ?>'
		data-bundle='<?= asset('/scripts/code-study.iife.js') ?>'
		defer
	></script>
<?php endif; ?>

<code-study-embed>
	<div class='code-study-mount'></div>

	<?php /* JSON_HEX_TAG keeps any '</script' inside example code from ending
		this block early - '<' ships as \u003C. */ ?>
	<script type='application/json'><?= json_encode($config, JSON_HEX_TAG | JSON_HEX_AMP) ?></script>

	<noscript>
		<p class='quiet-voice'>This live code example needs JavaScript.</p>
	</noscript>
</code-study-embed>
