<?php /* CHECKLIST, as a readout. Mirrors art/list-at-ease.php: rows of
	checkbox + bar - done, done, you are here (the accent), still ahead. */ ?>
<pre class='poster-art poster-ascii'>
+------------------+
|  [x] ======      |
|  [x] ======      |
|  [<span class='accent'>o</span>] ======      |
|  [ ] ======      |
+------------------+
$ open <?= $milestone['slug'] ?>  <span class='meta'># <?= $milestone['date'] ?></span>
</pre>
