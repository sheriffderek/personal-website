<?php

define('SITE_ROOT', dirname(__DIR__));
define('CONTENT_DIR', SITE_ROOT . '/content');
define('TEMPLATES_DIR', SITE_ROOT . '/templates');
define('INCLUDES_DIR', SITE_ROOT . '/includes');

define('SITE_TITLE', 'Derek Thomas Wood');
define('SITE_DESCRIPTION', 'Designer, developer, educator, musician.');

/* Canonical production origin. Share-card (Open Graph / Twitter) images and
   URLs must be absolute, so they point here regardless of how the page was
   fetched (local dev, staging). Update if the domain ever moves. */
define('SITE_URL', 'https://derekthomaswood.com');
define('SITE_SHARE_IMAGE', '/default-meta.jpg');
