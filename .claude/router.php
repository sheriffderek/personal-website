<?php
// Dev-only router for `php -S` so clean URLs route through the front
// controller while real files (css/js/media) still serve statically.
// Not used in production (Apache/nginx handle this via rewrite rules).

$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Mirror the .htaccess block: data files under content/ are server-side only and
// carry private fields (per-milestone `backstory`), so they must never be served
// raw - not even on the dev server. Media beside them is not json and is fine.
if (preg_match('#^/content/.*\.json$#', $path)) {
	http_response_code(404);
	return true;
}

if ($path !== '/' && is_file($root . $path)) {
	return false; // let the built-in server serve the static file
}

require $root . '/index.php';
