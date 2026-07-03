# Case study notes — building the site

A running capture of the small, sharp decisions behind this site's interaction and
theme system. The theme/interaction layer is itself a design-systems-mastery demo,
so the *edge cases* are the point, not a footnote.

Each entry: **the problem** → **the move** → **where it lives**. Entries point at
real code so they can't go stale. Add to this as we hit new ones. Later this can
graduate into a polished case study page (or feed `/how-i-work`).

---

## Architecture & routing

- **Multi-page site, no framework, still portable across servers.** One front
  controller reads the path top-to-down and maps it to a page — no MVC, reads like
  a script a student could follow. Rewrites handle both Apache (local) and nginx
  (prod). → `index.php`, `.htaccess`
- **Per-page slots without branching.** Each page optionally names its own controls
  partial and header; the shell just fills the slot. Adding a page is one entry in
  one list, and it shows up in the menu + footer automatically. → `index.php`
  (`$pages`), `templates/pages/`

## Navigation as a collapsed menu

- **The nav *is* the settings menu.** Site chrome collapses into one popover holding
  page links + theme controls + page-specific controls. Discoverability/SEO is
  covered by an always-visible footer link list + contextual links, so the collapsed
  menu doesn't have to carry it alone. → `includes/settings-panel.php`, footer

## Type system (Fontshare)

- **One knob re-pairs every theme.** Families feed three stacks; theme bundles pair
  them into heading/body. Swapping a family is a one-line token edit. → `styles/font-scales.css`
- **Load the default pair first, everything else async.** Primary pair is render-
  blocking (stable first paint); alternate/audition families load non-blocking
  (`media='print' onload`). Variable fonts (`@1`) where they exist. → `includes/header.php`
- **Display = the left font, body = the right.** Pairing convention written down so
  every theme follows it; heading pulls the display font, body the reading font. A
  safety-net rule makes any bare heading default to the display font. → `settings.css`,
  `typography.css`

## Theming & tokens

- **Scrim is a scheme token, not a theme token.** The mobile menu backdrop dims
  ~15% in light, ~45% in dark — set once at the scheme level, not restated per theme.
  → `styles/settings.css` (`--scrim`)
- **Top-layer `::backdrop` inheritance is unreliable** — so the backdrop repeats the
  light value as a `var()` fallback in case the custom prop doesn't inherit onto it.
  → `styles/modules/settings-panel.css`

## Performance & caching

- **The 1-year immutable cache gotcha.** Hand-named assets were served like
  fingerprinted ones, so devices held stale css/js forever. Root fix is the nginx
  `Cache-Control`; repo-side fix is per-file `?v=<mtime>`.
- **Cache-busting through an `@import` chain.** A query on `index.css` can't bust its
  imported partials (each caches under its own URL). The resolver walks the import
  tree and emits a versioned `<link>` per *leaf*, correctly skipping pure manifests,
  ignoring commented-out imports, and emitting files that mix rules with an import.
  → `includes/render.php` (`stylesheet_paths`, `asset`)
- **A version stamp you can glance at.** Footer shows the live commit hash + time,
  read straight from `.git` (loose ref → packed-refs → detached HEAD), no git binary
  or build step; degrades to deploy time. Turns "is the cache lying?" into a glance.
  → `includes/render.php` (`deployed_version`)

## iOS / mobile polish

- **Sticky-header border, only when actually stuck.** A zero-height sentinel +
  IntersectionObserver toggles `.is-stuck`; the border is CSS-scoped to mobile.
  Because the *paint* is scoped in CSS and the observer runs at every width, resize
  across the breakpoint is free — no matchMedia bookkeeping. (Rule of thumb:
  matchMedia when the *logic* forks, CSS media query when only the *paint* forks.)
  → `scripts/sticky-header.js`, `styles/layouts/default-layout.css`
- **Outside-tap dismiss that actually works on iOS.** Native popover light-dismiss is
  flaky on iOS (support landed in 18.3; a styled `::backdrop` swallows the tap). The
  fallback tracks open state via the `toggle` event, listens to `touchstart` (the raw
  touch event iOS always fires), and compares the tap point to the panel's box rather
  than the event target (a backdrop tap reports the popover itself as target). Plus
  `cursor: pointer` on touch so iOS even delivers taps on the dimmed area.
  → `scripts/settings-panel.js`, `styles/modules/settings-panel.css`
- **Kill the tap flash and the tap delay.** `touch-action: manipulation` removes the
  double-tap-zoom wait (taps fire on press, no accidental zoom); `-webkit-tap-highlight-color: transparent`
  removes the gray flash. → `styles/setup.css`

## Audio feedback

- **Web Audio on iOS drops the first sound.** The AudioContext starts suspended;
  scheduling before `resume()` resolves drops the sound onto a clock that isn't
  advancing. Fix: wait for resume, then play against a live `currentTime`. (Also: the
  hardware mute switch silences Web Audio regardless of prefs — not code-fixable.)
  → `scripts/audio.js`
- **Backdrop dismiss clicks at half volume**, tied to the normal click by a ratio, not
  a magic number — stays half if the base volume is tuned. → `scripts/audio.js` (`click.softScale`)

## Reading experience

- **Keep your place through a reflow.** A theme swap changes the type scale, so every
  card grows/shrinks and the page height shifts under the reader. Instead of
  remembering a scroll number (meaningless after reflow), anchor to the milestone
  nearest screen-center, note its top, apply the change, then nudge scroll by however
  far that same top moved. The card stays under the reader's eye. → `scripts/settings-panel.js`
  (`syncScroll` / `centeredMilestone`)
