<?php /* The generic ascii twin - the Terminal character's fallback cover for
	any card without its own twin yet (per-milestone twins live in
	includes/posters/art-ascii/<slug>.php; poster_ascii_path in render.php
	picks). A real text readout, not an image: an ascii-framed graphic with a
	command line built from the card's own data ($milestone is in scope at
	the include site in templates/milestone.php).

	THE GLYPH PALETTE (shared by every twin - keep new twins on it):
	  ●  the accent mark (always wrapped in <span class='accent'>)
	  ▓█ solid ink masses     ░▒ secondary/quiet masses
	  □○ open shapes          ─│╱╲ strokes    ┌┐└┘╭╮╰╯ outlines
	All chosen single-width in the mono stacks, so rows stay on the grid.
	Content sits flush-left because <pre> keeps every space; interior rows
	are exactly 18 characters between the pipes. */ ?>
<pre class='poster-art poster-ascii'>
+------------------+
|                  |
|  □  ○  ───>  ▓▓  |
|                  |
+------------------+
$ open <?= $milestone['slug'] ?>  <span class='meta'># <?= $milestone['date'] ?></span>
</pre>
