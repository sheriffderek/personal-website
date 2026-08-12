<?php /* MONTH GRID, as a readout. Mirrors art/pe-calendar-milestones.php: a
	month of days, one of them the milestone. */ ?>
<pre class='poster-art poster-ascii'>
+------------------+
|  •  •  •  •  •   |
|  •  •  •  <span class='accent'>●</span>  •   |
|  •  •  •  •  •   |
+------------------+
$ open <?= $milestone['slug'] ?>  <span class='meta'># <?= $milestone['date'] ?></span>
</pre>
