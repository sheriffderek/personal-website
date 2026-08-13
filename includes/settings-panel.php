<?php /*
	Two menus, two triggers, two panels (absolute children of the tray, placed
	by CSS per posture; open/close is the manual state machine in
	scripts/settings-panel.js - de-popovered 2026-08-11):

	  Pages (hamburger glyph) — site navigation. The PRIMARY member: every
	    page, every scroll depth, first in the toolbar's DOM so the corner
	    rules land it corner-most. The hamburger glyph is reserved for it -
	    only real navigation may wear it.
	  Settings (sliders glyph) — theme/scheme/sound + per-page contextual
	    controls (the timeline filter rides here on the home page).

	Split into two panels because page links + settings + filter together got
	too tall. Each row inside the settings is its own partial in
	includes/settings/.
*/ ?>

<?php /* Grid view exists only where the timeline does - the page carrying
	the timeline's own controls (home). Everywhere else the invite and the
	Layout row would be doors to nothing. */ ?>
<?php $page_has_grid = GRID_VIEW_ENABLED && ($page_controls ?? null) === 'filter-control'; ?>


<?php /* ---- The toolbar ----
	One flex parent for the trigger glyphs so they arrange as a GROUP (this is
	the "these all need to be in a parent" note that used to live here).

	It earns its keep twice over: it lets the triggers be styled/spaced as a set
	independent of the tray, and its axis (flex-direction) tracks where panels
	open - row means the group sits above the content so panels sit BELOW it;
	column means it sits beside, so panels open in-column or BESIDE. The tray
	is the panels' containing block; the toolbar owns ARRANGEMENT.

	The panels stay OUTSIDE the toolbar (siblings, below) so the toolbar can
	stack ABOVE them - the circles ride clear of the card's top edge. */ ?>
<div class='toolbar'>

<?php /* DOM order is IMPORTANCE order, primary first (the toolbar's
	row-reverse puts the first member in the corner-most slot - see the locked
	rules on .toolbar in styles/modules/settings-panel.css). The rank: pages
	menu (primary) > settings (secondary) > contextual extras (grid invite,
	back-to-top). A new trigger is inserted at the rank it earns, never
	appended to the end. */ ?>

<?php /* ---- Pages menu (PRIMARY) ---- */ ?>
<button
	type='button'
	data-panel='pages-menu'
	aria-controls='pages-menu'
	class='trigger'
	aria-expanded='false'
	aria-label='Pages'
>
	<span aria-hidden='true'>
		<svg class='glyph' viewBox='0 0 24 24' focusable='false'>
			<path d='M21 12C21 12.1989 20.921 12.3897 20.7803 12.5303C20.6397 12.671 20.4489 12.75 20.25 12.75H3.75C3.55109 12.75 3.36032 12.671 3.21967 12.5303C3.07902 12.3897 3 12.1989 3 12C3 11.8011 3.07902 11.6103 3.21967 11.4697C3.36032 11.329 3.55109 11.25 3.75 11.25H20.25C20.4489 11.25 20.6397 11.329 20.7803 11.4697C20.921 11.6103 21 11.8011 21 12ZM3.75 6.75H20.25C20.4489 6.75 20.6397 6.67098 20.7803 6.53033C20.921 6.38968 21 6.19891 21 6C21 5.80109 20.921 5.61032 20.7803 5.46967C20.6397 5.32902 20.4489 5.25 20.25 5.25H3.75C3.55109 5.25 3.36032 5.32902 3.21967 5.46967C3.07902 5.61032 3 5.80109 3 6C3 6.19891 3.07902 6.38968 3.21967 6.53033C3.36032 6.67098 3.55109 6.75 3.75 6.75ZM20.25 17.25H3.75C3.55109 17.25 3.36032 17.329 3.21967 17.4697C3.07902 17.6103 3 17.8011 3 18C3 18.1989 3.07902 18.3897 3.21967 18.5303C3.36032 18.671 3.55109 18.75 3.75 18.75H20.25C20.4489 18.75 20.6397 18.671 20.7803 18.5303C20.921 18.3897 21 18.1989 21 18C21 17.8011 20.921 17.6103 20.7803 17.4697C20.6397 17.329 20.4489 17.25 20.25 17.25Z' />
		</svg>
	</span>
</button>

<?php /* ---- Settings menu ---- */ ?>
<button
	type='button'
	data-panel='settings-panel'
	aria-controls='settings-panel'
	class='trigger'
	aria-expanded='false'
	aria-label='Display settings'
>
	<span aria-hidden='true'>
		<svg class='glyph' viewBox='0 0 24 24' focusable='false'>
			<path d='M3.75 8.25002H6.84375C7.00898 8.89533 7.38428 9.4673 7.91048 9.87575C8.43669 10.2842 9.08387 10.5059 9.75 10.5059C10.4161 10.5059 11.0633 10.2842 11.5895 9.87575C12.1157 9.4673 12.491 8.89533 12.6562 8.25002H20.25C20.4489 8.25002 20.6397 8.17101 20.7803 8.03035C20.921 7.8897 21 7.69894 21 7.50002C21 7.30111 20.921 7.11035 20.7803 6.96969C20.6397 6.82904 20.4489 6.75002 20.25 6.75002H12.6562C12.491 6.10471 12.1157 5.53274 11.5895 5.12429C11.0633 4.71584 10.4161 4.49414 9.75 4.49414C9.08387 4.49414 8.43669 4.71584 7.91048 5.12429C7.38428 5.53274 7.00898 6.10471 6.84375 6.75002H3.75C3.55109 6.75002 3.36032 6.82904 3.21967 6.96969C3.07902 7.11035 3 7.30111 3 7.50002C3 7.69894 3.07902 7.8897 3.21967 8.03035C3.36032 8.17101 3.55109 8.25002 3.75 8.25002ZM9.75 6.00002C10.0467 6.00002 10.3367 6.088 10.5834 6.25282C10.83 6.41764 11.0223 6.65191 11.1358 6.926C11.2494 7.20009 11.2791 7.50169 11.2212 7.79266C11.1633 8.08363 11.0204 8.3509 10.8107 8.56068C10.6009 8.77046 10.3336 8.91332 10.0426 8.9712C9.75166 9.02908 9.45006 8.99937 9.17598 8.88584C8.90189 8.77231 8.66762 8.58005 8.5028 8.33338C8.33797 8.0867 8.25 7.7967 8.25 7.50002C8.25 7.1022 8.40804 6.72067 8.68934 6.43936C8.97064 6.15806 9.35218 6.00002 9.75 6.00002ZM20.25 15.75H18.6562C18.491 15.1047 18.1157 14.5327 17.5895 14.1243C17.0633 13.7158 16.4161 13.4941 15.75 13.4941C15.0839 13.4941 14.4367 13.7158 13.9105 14.1243C13.3843 14.5327 13.009 15.1047 12.8438 15.75H3.75C3.55109 15.75 3.36032 15.829 3.21967 15.9697C3.07902 16.1103 3 16.3011 3 16.5C3 16.6989 3.07902 16.8897 3.21967 17.0304C3.36032 17.171 3.55109 17.25 3.75 17.25H12.8438C13.009 17.8953 13.3843 18.4673 13.9105 18.8758C14.4367 19.2842 15.0839 19.5059 15.75 19.5059C16.4161 19.5059 17.0633 19.2842 17.5895 18.8758C18.1157 18.4673 18.491 17.8953 18.6562 17.25H20.25C20.4489 17.25 20.6397 17.171 20.7803 17.0304C20.921 16.8897 21 16.6989 21 16.5C21 16.3011 20.921 16.1103 20.7803 15.9697C20.6397 15.829 20.4489 15.75 20.25 15.75ZM15.75 18C15.4533 18 15.1633 17.912 14.9166 17.7472C14.67 17.5824 14.4777 17.3481 14.3642 17.074C14.2506 16.8 14.2209 16.4984 14.2788 16.2074C14.3367 15.9164 14.4796 15.6491 14.6893 15.4394C14.8991 15.2296 15.1664 15.0867 15.4574 15.0288C15.7483 14.971 16.0499 15.0007 16.324 15.1142C16.5981 15.2277 16.8324 15.42 16.9972 15.6667C17.162 15.9133 17.25 16.2034 17.25 16.5C17.25 16.8978 17.092 17.2794 16.8107 17.5607C16.5294 17.842 16.1478 18 15.75 18Z' />
		</svg>
	</span>
</button>

<?php /* ---- Grid invite (contextual) ----
	The door INTO grid view - one-way, by design (the way back out is the
	settings panel's Layout row). Only exists where the grid exists and only
	in list view; pulses "touch me" until first used - discovery for the
	magic. Chrome/visibility/pulse live in styles/layouts/grid-view.css;
	wiring in settings-panel.js (view section). */ ?>
<?php if ($page_has_grid): ?>
	<button
		type='button'
		class='trigger grid-invite'
		data-grid-invite
		aria-label='View as grid'
	>
		<span aria-hidden='true'>
			<svg class='glyph' viewBox='0 0 24 24' focusable='false'>
				<path d='M9.75 3.75H5.25C4.85218 3.75 4.47064 3.90804 4.18934 4.18934C3.90804 4.47064 3.75 4.85218 3.75 5.25V9.75C3.75 10.1478 3.90804 10.5294 4.18934 10.8107C4.47064 11.092 4.85218 11.25 5.25 11.25H9.75C10.1478 11.25 10.5294 11.092 10.8107 10.8107C11.092 10.5294 11.25 10.1478 11.25 9.75V5.25C11.25 4.85218 11.092 4.47064 10.8107 4.18934C10.5294 3.90804 10.1478 3.75 9.75 3.75ZM9.75 9.75H5.25V5.25H9.75V9.75ZM18.75 3.75H14.25C13.8522 3.75 13.4706 3.90804 13.1893 4.18934C12.908 4.47064 12.75 4.85218 12.75 5.25V9.75C12.75 10.1478 12.908 10.5294 13.1893 10.8107C13.4706 11.092 13.8522 11.25 14.25 11.25H18.75C19.1478 11.25 19.5294 11.092 19.8107 10.8107C20.092 10.5294 20.25 10.1478 20.25 9.75V5.25C20.25 4.85218 20.092 4.47064 19.8107 4.18934C19.5294 3.90804 19.1478 3.75 18.75 3.75ZM18.75 9.75H14.25V5.25H18.75V9.75ZM9.75 12.75H5.25C4.85218 12.75 4.47064 12.908 4.18934 13.1893C3.90804 13.4706 3.75 13.8522 3.75 14.25V18.75C3.75 19.1478 3.90804 19.5294 4.18934 19.8107C4.47064 20.092 4.85218 20.25 5.25 20.25H9.75C10.1478 20.25 10.5294 20.092 10.8107 19.8107C11.092 19.5294 11.25 19.1478 11.25 18.75V14.25C11.25 13.8522 11.092 13.4706 10.8107 13.1893C10.5294 12.908 10.1478 12.75 9.75 12.75ZM9.75 18.75H5.25V14.25H9.75V18.75ZM18.75 12.75H14.25C13.8522 12.75 13.4706 12.908 13.1893 13.1893C12.908 13.4706 12.75 13.8522 12.75 14.25V18.75C12.75 19.1478 12.908 19.5294 13.1893 19.8107C13.4706 20.092 13.8522 20.25 14.25 20.25H18.75C19.1478 20.25 19.5294 20.092 19.8107 19.8107C20.092 19.5294 20.25 19.1478 20.25 18.75V14.25C20.25 13.8522 20.092 13.4706 19.8107 13.1893C19.5294 12.908 19.1478 12.75 18.75 12.75ZM18.75 18.75H14.25V14.25H18.75V18.75Z' />
			</svg>
		</span>
	</button>
<?php endif; ?>

<?php /* ---- Back to top (contextual, last) ----
	A reveal member: hidden everywhere except grid view >= 1450 after the
	settings band has scrolled away (data-scrolled on <html>) - the moment
	its job exists. Visibility lives in grid-view.css; wiring in
	settings-panel.js. */ ?>
<?php if ($page_has_grid): ?>
	<button
		type='button'
		class='trigger'
		data-to-top
		aria-label='Back to top'
	>
		<span aria-hidden='true'>
			<svg class='glyph' viewBox='0 0 24 24' focusable='false'>
				<path d='M19.281 11.0312C19.2114 11.1009 19.1287 11.1563 19.0376 11.194C18.9466 11.2318 18.849 11.2512 18.7504 11.2512C18.6519 11.2512 18.5543 11.2318 18.4632 11.194C18.3722 11.1563 18.2894 11.1009 18.2198 11.0312L12.7504 5.5609V20.2506C12.7504 20.4495 12.6714 20.6403 12.5307 20.7809C12.3901 20.9216 12.1993 21.0006 12.0004 21.0006C11.8015 21.0006 11.6107 20.9216 11.4701 20.7809C11.3294 20.6403 11.2504 20.4495 11.2504 20.2506V5.5609L5.78104 11.0312C5.64031 11.1719 5.44944 11.251 5.25042 11.251C5.05139 11.251 4.86052 11.1719 4.71979 11.0312C4.57906 10.8905 4.5 10.6996 4.5 10.5006C4.5 10.3016 4.57906 10.1107 4.71979 9.96996L11.4698 3.21996C11.5394 3.15023 11.6222 3.09491 11.7132 3.05717C11.8043 3.01943 11.9019 3 12.0004 3C12.099 3 12.1966 3.01943 12.2876 3.05717C12.3787 3.09491 12.4614 3.15023 12.531 3.21996L19.281 9.96996C19.3508 10.0396 19.4061 10.1223 19.4438 10.2134C19.4816 10.3044 19.501 10.402 19.501 10.5006C19.501 10.5992 19.4816 10.6967 19.4438 10.7878C19.4061 10.8788 19.3508 10.9616 19.281 11.0312Z' />
			</svg>
		</span>
	</button>
<?php endif; ?>

</div><?php /* end .toolbar */ ?>

<?php /* ---- The panels ----
	Absolute children of the tray, siblings of the toolbar - a button opens
	an element just below it, placed per posture by CSS (.panel in
	settings-panel.css), driven by the manual state machine in
	settings-panel.js. Sticky positioning makes the tray the containing
	block, so the browser keeps panel and toolbar together natively -
	no placement JS. Each panel's content sits in a .panel-scroll layer
	(scroll + padding live there; the settings scroller doubles as the
	.settings-panel rows grid, which the band instance wears with no
	scroller - templates/pages/home.php). Closed = display:none via
	.panel:not(.is-open). Without JS they never open - a decision, not a
	gap: the settings are dead switches without JS anyway, and the footer's
	site-map carries the same navigation the pages menu does.

	The pages panel is a plain container - the nav landmark lives in the
	partial. */ ?>

<div
	id='pages-menu'
	class='panel'
	data-ui='app'
>
	<div class='panel-scroll'>
		<h2 class='app-data-voice panel-heading'>Menu</h2>

		<?= partial('settings/page-menu', ['pages' => $pages, 'slug' => $slug, 'target_query' => $target_query ?? '']) ?>
	</div>
</div>

<div
	id='settings-panel'
	class='panel'
	data-ui='app'
	aria-label='Display settings'
>
	<div class='panel-scroll settings-panel'>
		<?= partial('settings-rows', [
			'id_suffix' => '',
			'page_has_grid' => $page_has_grid,
			'page_controls' => $page_controls ?? null,
		]) ?>
	</div>
</div>

<?php /* (The corner island used to float here - retired with the lab port.
	Its jobs moved into the tray itself: the tray is a sticky column that rides
	the page, and settings / back-to-top are REVEAL members that appear via
	data-scrolled once the settings band leaves the viewport. Same conditional-
	cluster idea, no second floating chrome to maintain.) */ ?>
