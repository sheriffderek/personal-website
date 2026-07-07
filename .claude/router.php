<?php
// Dev-only router for `php -S` so clean URLs route through the front
// controller while real files (css/js/media) still serve statically.
// Not used in production (Apache/nginx handle this via rewrite rules).

$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($path !== '/' && is_file($root . $path)) {
	return false; // let the built-in server serve the static file
}

require $root . '/index.php';
