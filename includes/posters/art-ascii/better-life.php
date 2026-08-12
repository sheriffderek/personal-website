<?php /* HABIT CHECKS, as a readout. Mirrors art/better-life.php: the tracker
	row - done, done, today (the accent), ahead, ahead. */ ?>
<pre class='poster-art poster-ascii'>
+------------------+
|                  |
|  ▓  ▓  <span class='accent'>●</span>  □  □   |
|                  |
+------------------+
$ open <?= $milestone['slug'] ?>  <span class='meta'># <?= $milestone['date'] ?></span>
</pre>
