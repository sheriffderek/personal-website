/* CodeStudy lazy loader - the editor bundle is heavy (~360KB over the wire),
   so nothing downloads until an embed is about to scroll into view. Each
   <code-study-embed> carries its own mount config as a JSON block; this file
   watches the embeds, fetches the stylesheet + bundle once, and mounts every
   embed when the bundle arrives. The asset URLs (already cache-busted by
   asset()) ride in on this script tag's data attributes - see
   includes/code-study.php, which is the only thing that emits this script. */
(function () {
	var loaderTag = document.currentScript;
	var stylesheetUrl = loaderTag.getAttribute('data-stylesheet');
	var bundleUrl = loaderTag.getAttribute('data-bundle');
	var requested = false;

	var embeds = document.querySelectorAll('code-study-embed');

	if (!embeds.length) {
		return;
	}

	function fetchEditor() {
		if (requested) {
			return;
		}

		requested = true;

		var stylesheet = document.createElement('link');
		stylesheet.rel = 'stylesheet';
		stylesheet.href = stylesheetUrl;
		document.head.append(stylesheet);

		var bundle = document.createElement('script');
		bundle.src = bundleUrl;
		bundle.onload = mountAllEmbeds;
		document.head.append(bundle);
	}

	/* The lazy part is the download, not the mounting - once the bundle is
	   here, mounting the whole page's embeds at once is cheap. */
	function mountAllEmbeds() {
		embeds.forEach(mountEmbed);
	}

	function mountEmbed(embed) {
		var config = JSON.parse(embed.querySelector("script[type='application/json']").textContent);
		var app = CodeStudy.Vue.createApp(CodeStudy.StudyFrame, config);

		app.mount(embed.querySelector('.code-study-mount'));
	}

	var watcher = new IntersectionObserver(function (entries) {
		var approaching = entries.some(function (entry) {
			return entry.isIntersecting;
		});

		if (approaching) {
			watcher.disconnect();
			fetchEditor();
		}
	}, { rootMargin: '600px 0px' });

	embeds.forEach(function (embed) {
		watcher.observe(embed);
	});
})();
