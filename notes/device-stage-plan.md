# Device stage - emulate a phone/tablet on a big screen

Status: **idea, parked** (Derek, 2026-09-19). Not pressing. This file keeps what we learned so it isn't re-derived when the idea comes back up. Nothing here is built.

## The idea

On large screens, a control that puts the site into a phone- or tablet-width **stage**, so a desktop visitor sees the small-screen design without resizing their window - and **without losing their place** (scroll position, open read-mores, a playing video).

It is a viewer, not a theme axis - it doesn't multiply the theme states. It belongs with the other band-side ideas in `next-up.md` (Reset / Random / Theme chat).

## Decisions so far

- **Iframe: ruled out (Derek).** A framed copy is a fresh page load - the visitor loses their spot.
- **So the route is container queries.** The layout today is viewport `@media` queries, which don't respond to a narrowed box. Moving the width-driven layout to `@container` against one stage element is what makes a same-document stage possible.
- **The stage does NOT get its own scrollbar** (both reviews agree, see below). The page keeps scrolling; the stage is just narrow.

## How this was looked at

An independent read-only agent audited the codebase (2026-09-19), then a second pass spot-checked its load-bearing claims against the code (the `:root` wall tokens, the lane dealer's token read, the `<picture>` source, the four viewport-width reads, `scrollbar-gutter`, the existing `container-type` on `inner-column`). All held. Line numbers are left out on purpose - grep for the names.

## What it would take

**CSS - small and mostly mechanical.** About 22 size queries in 8 files, a handful of breakpoints, nearly all in `styles/layouts/default-layout.css`, `styles/layouts/grid-view.css`, and `styles/modules/settings-panel.css`. Container queries are already in use here (`inner-column` has `container-type: inline-size`). The ones that are NOT a find-and-replace:

- **The wall tokens are set on `:root` inside `@media`** (`--layout-wall-*`, `--layout-tray-*` in grid-view.css). A container rule can never reach `:root`, and a container can't be styled by its own query. The tokens move down to a child of the stage (e.g. `.page-wrapper`). CSS readers still inherit them; the one JS reader does not - `laneCount()` in `scripts/grid-masonry.js` reads the token from `<html>`.
- **`.page-wrapper` can't be the stage** - its own grid is the thing being queried. **`<body>` is the natural stage**: `container-type`, a `max-width`, `margin-inline: auto`, zero markup change.
- **`<source media='(max-width: 600px)'>` in `includes/posters/media-item.php`** only ever tests the viewport. Staged photos would keep the wide cut (center-cropped) unless JS swaps them. A decision, not a blocker.
- `4vw` wall inset becomes `4cqi` (only matters at 1200+, never inside a phone stage). The `svh`/`dvh` caps and the one `height >= 800px` query stay as they are while the window is the scroller.
- Non-size queries (`prefers-reduced-motion`, `hover`, print, the rotate notice) correctly stay `@media`.

**JS - the real work.** Four places decide things from the viewport WIDTH and would read the stage instead: `GRID_MIN` in `scripts/settings-panel.js`, the phone-source pick in the playback script (`includes/footer.php`), the phone frames in `scripts/poster-crops.js`, and the `innerWidth` guard in front of `reconcilePanelsToLayout`. The honest shape is one small helper - "the layout width is the stage's width", fed by a `ResizeObserver` on the stage - that replaces those reads and also fires the five things that today wait for a window `resize` (applyView, the lane re-deal, poster crops, the source pick, Flickity). There is no `matchContainer` API; JS keeps its own copies of 600 / 1200 as it does now. The head FOUC script can only ever use `matchMedia` - fine, as long as the stage is never persisted across loads (it shouldn't be).

**Keep-your-place is already built.** Wrapping the stage toggle in `syncScroll()` pins the centered card through the reflow - the same machinery the theme sliders use. It works here because list and phone-list share a card order (the list-to-grid switch gives up and scrolls to top precisely because they don't).

## The hazards worth remembering

1. **It changes the production phone experience to serve a desktop demo.** Containment on `<body>`, above the iOS sticky tray, is a new variable for every real phone visitor - and iOS sticky is where this repo's scars are. This is the gating risk. Nothing about it can be known without a real iPhone.
2. **Grid view to stage.** If `data-view='grid'` stays on while the CSS falls back to list, the lane dealer still thinks it's in the grid: cards stay dealt into `.timeline-lane` wrappers that now stack as plain blocks, and the chronology scrambles (all of lane 1, then all of lane 2). `data-view` has to keep meaning APPLIED, keyed off the stage width.
3. **A talking `play` video hiccups** on entering the stage, because the source pick reloads `src`. Likely answer: skip the swap while the video has a voice.
4. **Platform facts are recalled, not tested.** Whether `container-type` makes an element the containing block for `position: fixed` descendants changed in the spec (layout containment was dropped from query containers around 2024; Chromium shipped it; Safari/Firefox status unknown to us). Only `.site-shade` cares. Sticky should be unaffected. Test, don't trust.
5. **A rule needs a deliberate amendment**: "only two things ever decide the view: the viewport and the visitor's explicit choice" (CLAUDE.md, Grid view). The stage is the visitor's choice overriding the viewport gate. Also: resume, journal breakout, and the design-system page each need an in-or-out call.
6. A side effect that exists even with the stage OFF: `@media` width includes the scrollbar gutter (`scrollbar-gutter: stable` in `styles/viewport.css`), container width doesn't - so breakpoints shift ~15px. If JS reads the stage width through the helper, CSS and JS agree again; if it keeps `matchMedia`, there's a 15px band where they disagree.

## Why not a device box with its own scroll

Everything that owns scroll assumes the window: `syncScroll`'s `scrollBy`, back-to-top, the view switch's `scrollTo`, the loop-autoplay scroll listener (scroll events don't bubble from an element scroller, so it would simply never fire), `innerHeight` math in two places, the `scrollY` guard in `scripts/sticky-header.js`, the `svh` panel caps. It would fork the exact code that has cost the most hours here, for a desktop-only feature - plus dead wheel outside the box and two scrollbars.

**The middle option (the good find):** the narrow stage, plus a `position: fixed; pointer-events: none` device bezel drawn over the viewport. The page scrolls under it - device silhouette, no second scroller. Only knock-on: the tray's sticky `top` becomes a token so it pins under the bezel's top edge.

## Design questions still open

- **The tray goes INSIDE the stage** (agent's call, and it seems right): the sticky top bar and the panel hanging from the circles ARE the phone design, and the panel postures convert for free since they're tray-relative. But then the exit control has to exist in the phone tray.
- **The control is probably a settings row**, not a toolbar glyph (two glyph attempts for layout switching already failed - see The tray's reveal members in CLAUDE.md). Its existence is gated by a true viewport `@media` - that one is honestly about the real window.
- Does a plain narrow column deliver the "wow", or does it need the bezel to read as a device?

## A cheaper alternative, noted and not chosen

`window.open()` at 390x844 on the nearest milestone's hash: zero refactor, perfectly faithful, theme carries over through localStorage. But it loses open read-mores and playing video - the same loss that ruled out the iframe - and a popup window is a rough note inside a design-system pitch.

## Size, and the first step when it's time

Medium - three or four slices (query conversion + token move; the stage-width helper and its hooks; the control + syncScroll wrap; the `<picture>` decision).

**First, a one-hour throwaway:** copy `experiments/shell.html` (it already has a sticky tray, fixed elements, a handful of media queries), make `<body>` the container, convert its queries, add a button that toggles `max-width: 390px`. Check in Chrome, Safari, Firefox - **and on a real iPhone with the stage off**: is the tray still sticky with no flicker, where does the fixed shade land, does the panel re-place, does a scroll-anchored toggle hold your place. If iOS sticky regresses there, the idea is dead for this codebase and nothing real was touched.
