<?php

define('SITE_ROOT', dirname(__DIR__));
define('CONTENT_DIR', SITE_ROOT . '/content');
define('TEMPLATES_DIR', SITE_ROOT . '/templates');
define('INCLUDES_DIR', SITE_ROOT . '/includes');

/* Two title lanes, kept separate on purpose:
   SITE_TITLE      - the plain site name. Browser tab, the "- Derek Wood"
                     suffix on subpage titles, and og:site_name.
   SITE_META_TITLE - the share-card headline (og:title / twitter:title). Free
                     to be punchier than the tab title. */
define('SITE_TITLE', 'Derek Wood');
define('SITE_META_TITLE', 'Derek Wood: Designer at large');
define('SITE_DESCRIPTION', 'I help teams do their best work, whether that\'s big-picture vision and strategy, research and user testing, interfaces and code, design systems and cross-team collaboration, or auditing and maintaining what\'s already shipped.');

/* Canonical production origin. Share-card (Open Graph / Twitter) images and
   URLs must be absolute, so they point here regardless of how the page was
   fetched (local dev, staging). Update if the domain ever moves. */
define('SITE_URL', 'https://derekthomaswood.com');
define('SITE_SHARE_IMAGE', '/default-meta.jpg');

/* Feature flags. A bolt-on system can ship dark or be pulled without touching
   its own code - flip the flag off and it stops loading entirely (no scripts,
   no weight), leaving the files in place, unused.

   TOUR_ENABLED - the welcome-video + guided-tour experiment: the standalone
   talking-head at the top of home (includes/welcome-video.php) and the
   choreography that drives the page from it (scripts/welcome-video.js +
   choreo.js + tour.js). Work in progress - off for now, flip to true to resume. */
define('TOUR_ENABLED', false);

/* GRID_VIEW_ENABLED - the List/Grid view axis: on big screens (>= 1600px) a
   Grid option turns the timeline into a multi-column wall of work, with the
   settings panel inline at the top of the page. Off = the toggle never
   renders, the grid stylesheet never loads, and the FOUC script never sets
   data-view - the site is exactly the single-column list. Trying it out -
   flip to false to pull it. */
define('GRID_VIEW_ENABLED', true);

/* SLIDER_HINT_ENABLED - the carousel-affordance nudge: the first carousel to
   scroll into view slides itself a little toward slide 2 and settles back,
   demonstrating the swipe. Once per pageview, retired forever after the
   visitor's first real swipe (localStorage breadcrumb, same pattern as the
   grid invite). Off = the script never loads. Trying it out - flip to false
   to pull it. */
define('SLIDER_HINT_ENABLED', true);
