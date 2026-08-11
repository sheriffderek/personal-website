<?php /* The generic ascii twin - the Terminal character's fallback cover for
	any card without its own twin yet (per-milestone twins live in
	includes/posters/art-ascii/<slug>.php; poster_ascii_path in render.php
	picks). A real text readout, not an image: an ascii-framed generic graphic
	(echoing the poster-shapes collage) with a command line under it, built
	from the card's own data ($milestone is in scope at the include site in
	templates/milestone.php). Content sits flush-left because <pre> keeps
	every space. It wears .poster-art so the poster token re-pointing applies
	unchanged; styling lives in milestone.css. */ ?>
<pre class='poster-art poster-ascii'>
+------------------+
|                  |
|  [ ] ( ) --->    |
|                  |
+------------------+
$ open <?= $milestone['slug'] ?>  <span class='meta'># <?= $milestone['date'] ?></span>
</pre>
