# Layout lab - working notes

Scratch notes for the shell rework. These are decisions as we hone them - not final CLAUDE.md rules yet.
The live lab is now **`layout-sandbox.html`** (static file, http://derek:8888/layout-sandbox.html) - NOT the
old `/layout-lab` PHP page. It will port into the real shell once proven.

---

# ✅ CANONICAL SPEC (consolidated 2026-07-16) - read this first

The single clean statement of the shell. Everything below this section is the dated *history* (the
reasoning record); when they disagree, this section wins. This is what ports to the real site.

## Vocabulary + naming (dashboard/app-shell terms, on purpose)

The site is a personal portfolio used as a **playground to demonstrate application-grade UI**, so the
names are the industry-standard ones a design-systems person recognises. Prefixes mark the layer:

| prefix | layer | members |
|---|---|---|
| `shell-` | persistent app chrome (same every page) | `shell-tray` (the morphing control strip: top bar ↔ side strip), `shell-footer`, `shell-shade` (the dim layer we render ourselves) |
| `page-` | per-page content | `page-wrapper`, `page-header` |
| *(none)* | reusable components that slot in | `toolbar` (the group of icon buttons), `trigger` (one icon button), `panel` (a popover surface), `settings` (the display-settings form), `settings-band` (the inline exhibit instance of that form) |

- **`panel`** is the popover surface (both menus share it). Roles by id: `#pages-menu` (a **menu** = a
  simple nav list) and `#settings-panel` (the settings **form/panel**). "menu" = nav only; the settings are
  a panel, never a "menu". "cluster"/"toolbox"/"dimmer"/"rail" are RETIRED words.
- Names are **claims we then back**: a thing called `toolbar` should eventually carry `role="toolbar"` +
  roving focus; `menu` its semantics; `shell-shade` a focus-trap. Name now, make-the-name-true as a
  named later layer (not yet wired).

## Variables: `--layout-<system>-<measure>` = "this is a structure knob"

Any layout/structure-critical value carries the `--layout-` prefix so the structural surface reads as one
block you can scan/grep - and know you're moving layout, not painting. Paint/theme tokens stay OUT of it
(`--page-bg` now; `--fill-*`/`--ink-*` later) - that mirrors the theme system's structure-vs-color split.

| system | tokens |
|---|---|
| `--layout-wall-*` | `-columns` (count 2→3), `-lane` (one lane), `-gap`, `-inset`, `-max` |
| `--layout-list-*` | `-column` (reading col) |
| `--layout-tray-*` | `-width` (glyph strip), `-reserve` (grid margin col), `-sidebar` (list sidebar col) |
| `--layout-panel-*` | `-gap`, `-edge`, `-min`, `-max` — **read by placePanel via getComputedStyle**, so the JS re-encodes NO numbers. CSS is the single source for every layout constant. |

## The axes (inputs → everything derives)

**Independent inputs:** viewport width · `data-view` (list/grid, grid gated ≥1200) · `data-page`
(timeline/plain, plain forces list) · open panel (none/pages/settings). Orthogonal: `data-scheme`
(color only) · reduced-motion (shade fade only).

**Derived (nothing re-encodes a breakpoint - the JS reads rendered geometry):**
- **situation** ← width×view×page (base `list-small-over` · ≥1024 `list-dedicated-sidebar` · ≥1200
  grid · ≥1450 grid+band · ≥1600 grid-3). Collapses for orientation to: **list→below, grid→beside.**
- **menu open-direction** ← the toolbar's `flex-direction` (row→below, column→beside) + geometry (side).
- **side** ← room vs the shared `--layout-panel-max` cap (all panels on a tray agree).
- **align** ← the toolbar's box (panels share one corner edge, not the individual trigger).
- **over/dim** ← does the placed rect overlap `<main>` (`data-over`) - pure geometry, not situation.

## Locked rules (distilled)

1. **Mirror model** - state on `<html>` + localStorage; N dumb form instances; one delegated `change`
   + reflect-all. Nothing moves anything else.
2. **shell-tray** = ONE sticky strip, positioned per situation; the glyph pins to the top (no magic
   number). Primary (menu) takes the corner-most slot (row→`row-reverse` right; column→top).
3. **Menu placement is JS** (`placePanel`), not CSS anchor positioning (Chrome-only) - cross-browser,
   clamped inside the viewport always. Placed on open; re-placed live on width-resize; stays put on scroll.
4. **A panel may not outlive its trigger** (state change hides trigger → close). **Grid toggle = door
   IN only** (list-view only; way out is the Layout row).
5. **shell-shade** = our own dim element (not native `::backdrop`, which blinks on menu-switch). One
   rAF reconcile coalesces a switch's close+open: `want&&!shown`→in · `want&&shown`→hold · else→out.
   z-index below the tray so triggers stay clickable over the dim.
6. **Sidebar constraint** - in `list-dedicated-sidebar`, panels cap to `--layout-tray-sidebar` so both
   fit the dedicated column consistently (list only; grid keeps the 360 cap).

## Status
Swept to this vocabulary 2026-07-16 (JS parses, zero stray old identifiers, braces balance). **Fable
audit done** - cross-reference sound (no broken selector/id/token; declarative chain wired right);
punch-list cleared (prose sweep rail→tray / cluster→toolbar, placeMenu→placePanel, grid-area
filters→settings, filter DEFAULTS gap). **Browser-QA'd by Derek 2026-07-16 - all working, Safari
included** (agent tooling couldn't verify; Derek confirmed by hand). The cross-browser claim is
CONFIRMED - the JS-placement rework does what it was built for: the menu places correctly in Safari
where CSS anchor positioning would have failed. Zero verification debt.

**Bare-min standalone EXTRACTED 2026-07-16 → `experiments/shell.html`** - the sandbox minus the debug
aids (situation badge, page-type toggle, violet/green region tints). JS parses, braces balance. Plain-
page CSS + content kept (real feature; set `data-page='plain'` to exercise - no toggle). The shell-tray's
subtle top-bar bg (`#000 4%`) kept (reads as real chrome, not a debug tint). This is the artifact that
ports; `layout-sandbox.html` stays as the working lab.

Next: commit, then port to the real PHP shell (re-fit to the lane-dealer, not a CSS grid; FOUC + state
wiring).

---

## ⏭ SESSION HANDOFF / NEXT UP (last worked 2026-07-14)

> **SUPERSEDED (2026-07-16) — read the ✅ CANONICAL SPEC at the top instead.** This block predates the
> naming sweep, the JS-placement rework, the shade, and the browser QA. Everything it lists as "open"
> (menu-button placement, popover overflow, the dim question) is RESOLVED and captured in the spec.
> Kept below as history only. Current state: swept to the new vocabulary, Safari-verified, standalone
> extracted to `experiments/shell.html`. Next up = **port to the real PHP shell.**

**WHERE WE ARE.** This session we REBUILT the sandbox on the **mirror model** after the old
"one moving node" approach caused a cascade of bugs (anchor-jump, jitter, width-reflow,
vanishing filters - all the same root cause: one panel node trying to be band + popover + rail
at once). The rebuild's three principles (also at the top of the file's `<style>`):

1. **Settings = ONE semantic form** (`fieldset`/`legend`/`radio`/`range`). Markup carries NO
   layout - CSS arranges it per situation. (This is the view-source flex too.)
2. **MIRROR MODEL** - the persistent band and the toolbox popover are SEPARATE instances of that
   form. Neither owns state; both are dumb mirrors of `data-*` on `<html>` + localStorage.
   Nothing moves or redraws anything else, so scroll/resize/open-state can't fight.
3. **CSS by NAMED SITUATION** - base first, one complete block per situation, ascending width.
   Kills the source-order surprises we kept hitting.

**DEBUG AIDS in the sandbox** (remove before ship): a live situation-name badge in the intro, a
"Switch page type (timeline / plain)" button, and region tints.

**WORKING NOW:** all situations render (badge confirms live); mirror sync proven (change scheme
anywhere, all copies reflect, page bg reacts); grid toggle appears >=1200 and flips both ways;
nav island always present in grid with settings/grid-toggle/arrow revealing on scroll; plain-page
mode (no band/wall/grid, simple list shell + text); exhibit moved to start at **1450**; first
pass at the airy "time to breathe" exhibit.

**THE NAMED SITUATIONS (the shared vocabulary - use these words):**

| width | situation | menu behaviour |
|---|---|---|
| base | `list-small-over` | menu only goes over content (dim) |
| >=1024 | `list-dedicated-sidebar` | menu in the rail's own space (no dim) |
| >=1200 grid | `grid-2-rail-over` | thin trigger rail, menu over wall |
| >=1450 grid | `grid-2-persistent-settings` | airy band; sticky menu-rail, scrolled -> settings rejoin the rail |
| >=1600 grid | `grid-3-...` | 3 columns (moved 1900->1600, 2026-07-16) |

(`>=2400 grid-3-...-dedicated-sidebar` was retired 2026-07-16 - the menu now prefers the free side
at every width and flips only when it must, so the breakpoint had no job left.)

**RESOLVED (2026-07-15): the MENU BUTTON lives in a sticky MENU-RAIL.** We tried all three
placements live in the sandbox (debug cycle, since removed) and Derek picked the rail by feel -
the sticky travel is what won it:
- **rail (A) - CHOSEN.** The shell-nav is a real column in the reserved right lane
  (`grid-column: 3`), `position: sticky; top: 1rem`. It rides the page in flow and **pins to the
  top as you scroll** - so it's always reachable, reads as intentional (owns a lane, aligns with
  the top composition), and has **no magic number**. The two things the island couldn't do at once.
- ~~island (`top: 13rem`)~~ REMOVED - the hardcoded "below the filters" guess, the fragility.
- ~~corner (fixed, floats top-right)~~ - works but floats over the wall's edge; rail owns space.
- **Works on plain pages too (confirmed):** at wide widths a plain page is already in
  `list-dedicated-sidebar` (the rail column exists there independent of any band/filter), so the
  same sticky menu rail carries over with no filter present. "Works on all pages" test PASSED.
- **Below the rail widths** the honest fallback is the base sticky top bar (phones can't spare a
  rail column) - unchanged.
- Code: the `>=1450` block's shell-nav is now `sticky/top:1rem/grid-column:3`; the debug cycle
  (button + JS + `data-menu` CSS) is gone; the reserved-column tokens renamed
  `--island-*` -> `--menu-rail-*` to match the decision.

**RAIL HORIZONTAL PLACEMENT (2026-07-16): hug the wall, not the viewport edge.**
First rail cut pinned the glyph to the far viewport-right - Derek: "way out there, people
won't see it." Fixed at >=1450: the wall column is sized to content (`minmax(0, var(--wall-max))`)
with a trailing `1fr` soaking leftover width, and `shell-nav` gets `justify-content: flex-start`
so the toolbox sits at the rail's INNER edge, right beside the cards. Derek: "working well."
STILL TODO: propagate the same hug to the 1200-1450 (`grid-2-rail-over`) block - it still has the
old `1fr max-content` far-right template.

**LOCKED RULE (2026-07-16): THE MENU OPENS INTO THE FREE SPACE - WHICH SIDE IS NOT A BREAKPOINT.**

Rule 1 says the axis picks *below vs beside*. This says which side of *beside*:
**prefer the empty side, cover content only as the fallback.** The rail hugs the wall, so the free
margin is to its RIGHT -> the grid menu prefers RIGHT at EVERY width and falls back to
left-over-the-wall only where there's no room right (the 1200-1450 template puts the rail hard
against the viewport edge; it flips there). (Mechanism note: this WAS CSS `left: anchor(right)` +
`position-try-fallbacks: flip-inline`; since 2026-07-16 it's `placePanel()` in JS - the rule is
unchanged, only who computes it. See the anchor-removal note below.)

**Retired: the `>= 2400` situation.** Its whole job was "now there's room on the right, so open
right" - a width standing in for a room measurement we can answer exactly. Gone from the CSS, the
situations table, and the debug badge. Don't re-add a 2400 situation for a reason that is "how much
room is there".

**RESOLVED (2026-07-16): dropped CSS anchor positioning entirely; the menu is placed by JS.**
Derek's requirement: "work 1000% of the time - exactly as we've decided - on all browsers." CSS
anchor positioning is Chrome/Edge only (NOT Baseline - I'd wrongly claimed it was), so it could
never meet that; in Safari/Firefox it no-ops and the popover lands centred. Polyfill
(`@oddbird/css-anchor-positioning`) is partial + a runtime dep against house "least machinery" -
rejected. So: the native `popover` stays (top layer, light-dismiss, focus - all Baseline), but
WHERE it lands is now `placePanel()` in the script.
- **The locked rules are its inputs, not CSS:** reads the cluster's `flex-direction` (below vs
  beside - one source of truth), prefers the free side (right) and flips left only with no room,
  and **clamps into the viewport unconditionally** - that clamp IS the "1000%".
- **Placed on open** (`toggle`), hidden via `beforetoggle` until placed so it never flashes at an
  unplaced spot (JS-only, so the no-JS floor is still a centred popover). Positions once - the
  trigger is sticky, so an open menu stays aligned on scroll (Derek's choice).
- **This deleted a whole pile of anchor workarounds:** the `position-try` flip, the flip-inline/
  flip-block agonising, the resize hide+show reopen. On resize we now just re-run `placePanel` -
  LIVE re-placement, no flash, no debounce (the demo Derek wanted, for free).
- **Bugs it also fixed:** each menu now lands by ITS OWN trigger (anchor-name on the cluster made
  both open at the cluster top); and it works in Safari/iOS - the recruiters' browsers.
- **UNVERIFIED end-to-end:** browser CDP kept hanging (2 calls then 300s timeout). Confirmed only
  that the pages trigger is visible + menu opens in grid. Derek to eyeball: open each menu at each
  width, resize with one open (should follow live), check Safari.

**Sidebar constraint (2026-07-16, Derek flagged): menus are capped to the rail column there.**
`list-dedicated-sidebar` (>=1024, list) has a DEDICATED column (`--rail-width` 320). The JS-placement
refactor had deleted the old `.menu { max-width: var(--rail-width) }`, so the wide Settings menu
(360) spilled past the content edge and dimmed while narrow Pages (280) fit - the rail behaving two
ways. Restored, scoped `html:not([data-view='grid']) .menu { max-width: var(--rail-width) }` (grid
still wants the 360 cap beside the wall). Measured at 1280: main ends 811, cluster.right 1139; capped
Settings lands at 819 -> clears 811 -> both menus fit the sidebar, neither dims. Consistent.

**Side consistency (2026-07-16, Derek flagged): the SIDE is decided per-RAIL, not per-menu.**
Edge case: at a wide window the Pages menu (280px) opened right into the margin while the wider
Settings menu (360px) flipped left over content - same rail, two behaviours. Cause: the room check
used each panel's OWN width, so they split whenever the right margin fell between the two widths
(~280-360px). Fix: decide the side from a SHARED reference - `parseFloat(getComputedStyle(panel)
.maxWidth)`, the menu's max-width CAP (360), same for every menu - so a rail commits to one side and
all its menus follow. Each panel's real width still drives exact left/top + the viewport clamp.
Verified: Pages at 1999px -> LEFT (roomRight 96 < 360); Settings runs the identical check -> LEFT
too. (The cap is read from computed style, so it auto-tracks the CSS - no hardcoded 360 in JS.)

**Alignment (2026-07-16, Derek flagged): menus align to the CLUSTER, not the trigger.** `placePanel`
now positions against the toolbox's bounding box, so the pages menu and the settings menu share one
clean edge (the corner-most edge of the rail) instead of stepping inboard to whichever glyph opened
them. Trade-off accepted: the menu no longer points at the exact glyph clicked - fine, because the
panels are mirrors and the cluster is tiny. (UNVERIFIED - Derek to eyeball; if he wanted per-glyph
tethering instead, revert to `trigger.getBoundingClientRect()`.)

**Mistakes not to repeat (all mine, all from the anchor era now deleted):**
1. Claimed anchor positioning was Baseline. It is Chrome/Edge only. Check support before "safe".
2. The preference was the bug, not the overflow - "left until 2400" was written for the old
   far-edge rail; adding a flip just made a wrong preference flip sometimes.
3. Shipped `flip-block` blind (browser was hung) - it caused Derek's "intermittent" report. Overlay
   positioning is exactly the thing not to ship without eyes on it.

**The DIM SIGNAL is derived, declared off `[data-over]`.** `placePanel` tests the placed panel's
rect against `<main>` and sets `data-over` (over content -> dim; own space -> no dim). Why geometric
overlap-with-`main` and not a per-branch flag: the "below" branch is over in the base bar but NOT
over in the list sidebar (drops into the empty rail column), so side/axis alone can't tell - the
rect test is the one signal right everywhere. **This still decides WHETHER to dim; what changed is
how the dim is RENDERED (below).**

**RESOLVED (2026-07-16): we render the dim with our OWN element, not the native `::backdrop`.**
Derek's reason: the native `::backdrop` belongs to EACH popover, so switching menu A -> B destroys
A's backdrop and builds B's -> it BLINKS. To animate the dim AND hold it steady across a switch, we
need one persistent element we own (`.dimmer`, a fixed div; native `::backdrop` kept transparent).
- **Three cases, and a single toggle can't tell them apart** (a switch fires a close AND an open):
  initialising (closed -> a dimming menu opens) = fade IN · switching (dimming menu -> another) =
  HOLD, no re-animate · closing (last -> none) = fade OUT. So we don't react per-event: every toggle
  (and resize) SCHEDULES one `requestAnimationFrame` reconcile, which coalesces the switch's
  close+open into ONE look at the settled state. Then: `want && !shown` -> in · `!want && shown` ->
  out · `want && shown` -> the switch, a deliberate no-op (never blinks). `want` = any
  `.menu[data-over]:popover-open`.
- **z-index 4 (BELOW the shell-nav's 5):** content dims but the triggers stay bright and clickable
  ON TOP of the dim, so you can switch straight from one menu to the next (that one-click switch is
  the whole thing that must not blink). The open menu is a top-layer popover, always above both.
- Policy still one place: reconcile ignores `data-over` = always dim; never add `is-shown` = never.
  Fade is `opacity` transition, dropped under `prefers-reduced-motion` (dim stays, animation goes).
- **UNVERIFIED** (CDP hung): needs Derek to eyeball the no-blink on an A->B switch, and that the
  triggers stay clickable over the dim.

**OTHER OPEN THREADS (taste-led, Derek's calls):**
- **Airy exhibit:** match the real page's spacious, un-boxed apparatus + big negative space
  ("space, time to breathe"). First pass in (row-gap 6rem, quiet labels, un-boxed); needs Derek's
  eye to push toward the ambient-decoration feel of the original screenshot.
- **Intro breakout:** on the timeline page the intro deliberately does NOT align to the wall
  grid (aligning reads lazy; breakout reads intentional).
- **FRAGILITY TO FIX (important):** the top composition currently leans on `main` being one CSS
  grid shared with the wall + magic numbers. The real timeline is the **lane-dealer** (flex
  lanes, NOT a CSS grid), so top<->wall alignment WON'T PORT. Robust direction: **decouple the
  top from the wall entirely** - the top is its own composition, sizes from its own content, and
  never references the wall's columns. Real-wall alignment becomes a BY-EYE port pass.
- **Plain-page header:** the same page-header must read well WITH the band and ALONE. Resolved:
  timeline page = breakout; plain page = a heading over text; "share the wall's grid?" = no
  (deliberate), and it's a per-page-type choice.
- **Filter is page-specific** (home/style-guide only) - on plain pages it shouldn't appear even
  in the toolbox popover (conditional row; not yet handled).

**LOCKED RULE (2026-07-16): THE CLUSTER'S AXIS DECIDES WHERE THE MENU OPENS.**

| toolbox axis | menu opens | why |
|---|---|---|
| `row` | **BELOW** | a row sits ABOVE the content, so the menu drops down |
| `column` | **BESIDE** | a column sits BESIDE the content, so it opens sideways |

The axis already encodes where the content is - so the open-direction is **derived** from it,
never picked per breakpoint. Same move as the menu-rail itself: **derive, don't guess.**
- **The pairing is the contract:** a block that sets the cluster's `flex-direction` has thereby
  ALREADY decided the menu's direction - set both in that same block so they can't drift.
- `position-try-fallbacks` on `.menu` is only the safety net for "this side has no room." It is
  NOT how the side gets chosen. Don't let a fallback become the de-facto placement.
- **Audit + fix applied:** base (row/below) OK · >=1024 (row/below) OK · **>=1200 grid was the one
  violation** (row cluster but opened beside) -> the cluster is now a `column` there, which is also
  what it visually is (a thin rail beside the wall) · >=1450 + >=2400 (column/beside) OK.

**LOCKED RULE (2026-07-16): THE PRIMARY TAKES THE CORNER-MOST SLOT + THE RANK.**

The cluster's *order* derives from the axis exactly like its open-direction does:

| toolbox axis | primary sits | mechanism |
|---|---|---|
| `row` | at the **RIGHT** end | `row-reverse` |
| `column` | at the **TOP** | plain `column` (free) |

Both land the primary nearest the **top-right corner** - where the eye and hand already go for
nav. Derive, don't place by hand.

**The rank (Derek, 2026-07-16):**
1. **MENU (pages) - PRIMARY.** The most important trigger, and on **all pages**. The one member
   that never has to earn its slot (everything else answers the orphaned-job membership test).
2. **Filters / settings - secondary.** "For fun," and page-specific (timeline only).
3. **Contextual extras - LAST.** Grid-mode toggle, back-to-top arrow, etc.

- **DOM order IS importance order, primary first** (menu, settings, grid, back-to-top) - so the
  keyboard reaches the primary first. `row-reverse` puts it in the corner WITHOUT reordering the
  source. A new trigger is inserted at the rank it earns, never appended to the end.
- **Was violated:** the DOM had settings, grid, **pages(3rd)**, back-to-top. Reordered.
- a11y note: in the row, visual order runs opposite tab order. Acceptable here - independent icon
  buttons, no meaning rides on their sequence, and source order keeps the PRIMARY first.

**LOCKED RULE (2026-07-16): A PANEL MAY NOT OUTLIVE ITS TRIGGER.**
Derek caught it: open the settings popover in `grid-2-rail-over`, widen past 1450, and the panel
would be stranded on screen - the settings trigger hides (it becomes a reveal member once the
band takes over), but a popover lives in the TOP LAYER, so hiding its trigger does NOT close it.
Result: an orphaned panel over the wall, duplicating the band that just appeared.
- **The condition is not a breakpoint - it's "is my trigger rendered?"** (`getComputedStyle(trigger)
  .display === 'none'` -> `panel.hidePopover()`). Derive, don't guess: ask the trigger, and every
  future toolbox member is covered for free.
- **Closing loses nothing** - the mirror model means the band is already showing the same controls
  with the same state. The two surfaces make the transition graceful instead of lossy.
- Covered doors: resize (breakpoint crossing) · `applyState` (e.g. view -> grid) · the band
  observer (scrolling the band back into view re-hides the reveal members) · page-type switch.
- `closeOrphanedPanels()` in the sandbox script. **UNVERIFIED** (browser CDP still hung).

**LOCKED RULE (2026-07-16): THE GRID BUTTON IS THE DOOR *IN*, NOT A TWO-WAY SWITCH.**
It renders only where grid exists (`>= 1200`) **AND only in LIST view** - once you're in grid it's
gone. Why: the settings panel already carries the Layout row, so a grid glyph in the rail would be
a second control for a job already covered, and the rank says contextual extras only hold a slot
while they *have* a job. (Independently confirms the real site's 2026-07-11 finding: every glyph
tried for a layout switch just read as "menu" - hence NO layout member on the corner island.)
- Selector: `html:not([data-view='grid']) .trigger[data-grid-toggle]`. The plain-page rule sits
  later in source at equal specificity, so plain pages still hide it - **don't reorder those two.**
- Dropped from the `>= 1450` reveal lists - nothing to reveal if it never exists in grid.
- Knock-ons (the button must stop *claiming* to be a toggle): `aria-label` is now **"View as grid"**,
  not "Toggle grid layout"; the JS handler just does `applyState('view', 'grid')` instead of reading
  the current view to pick a direction - a branch that can't happen is a false story.
- **The way OUT of grid is the settings panel's Layout row.** That's the only door, by design.

**LOCKED RULE (2026-07-16): AN OPEN PANEL STAYS OPEN ACROSS A RESIZE AND RE-PLACES LIVE.**
The menu re-placing itself as the viewport changes IS the demo (Derek: closing it is a valid fix
but "not as nice for demonstrating it" - same spirit as the motion policy, the system performing
itself is the pitch). Since the JS placement rewrite (see the anchor-removal note above) this is
trivial: on each width-changing resize, re-run `placePanel` on every open panel - LIVE re-placement,
no flash, no debounce, no hide/show. If a resize hides the trigger (settings -> band past 1450) the
panel closes instead (a panel may not outlive its trigger).
- **WIDTH only:** mobile fires resize when the URL bar collapses on scroll - height, not a reason
  to disturb an open menu.
- (History, so it isn't reintroduced: this went close-on-resize -> debounced hide/show reopen ->
  this. The first two were workarounds for `position-try` not reverting while open; owning
  placement in JS deleted the need. Don't add a debounce or a reopen back.)

**STILL OPEN - THE BIG ONE: anchor positioning is Chrome/Edge only, NOT Baseline.**
(I had claimed it was Baseline - **that was wrong**, corrected 2026-07-16 via modern-web-guidance.)
Safari + Firefox ship none of it, so `anchor-name` / `position-anchor` / `anchor()` /
`position-try-*` all no-op there and the popover lands **centered on screen**. Derek's recruiters
are on Safari/iOS - that IS the "visitor sees a broken state" the lab's gate forbids, so this must
be settled before the port. Options:
1. **Polyfill** `@oddbird/css-anchor-positioning` - note it does NOT support `position-area` on
   popovers (must use `anchor()` insets - what we already have) and needs explicit anchor names
   (we have those too). So our current shape is already the polyfill-compatible one.
2. **Position from the layout instead** - the rail is a REAL grid column we own; placing the panel
   with the grid needs no anchor at all. Most consistent with everything else locked this arc
   (derive from what we control). **Agent's lean.**
3. **Accept centered-popover** as the Safari floor (the notes do call the overlay popover the
   no-JS default).

**PRINCIPLES LOCKED THIS ARC:**
- Weird nav is fine on a page or two (the exploration/flagship pages); the MAJORITY are simple +
  clear (the calm list-mode shell).
- The nav is a SEPARATE, minimal, always-present trigger - **never inside the settings art**.
  That's how "spacious artful settings" and "functional nav" coexist without fighting.
- Default/floor = the overlay popover (works with no JS). Every placement is an enhancement on top.

**FILES:** `layout-sandbox.html` (the work) · `layout-lab-notes.md` (this file) · real-page grid
sizing reference `styles/layouts/grid-view.css` (`--wall-column: 36rem`, `--wall-gap: 4rem`,
`--wall-inset: 4vw`, the `.corner-island` anchor math).

**COMMITS:** `9106ad8` = the pre-rebuild sandbox (fallback). The rebuild is committed as of this
handoff.

---

# ⭐ SESSION HANDOFF - START HERE (next session)

## Where we are
Working in **`layout-sandbox.html`** - the design lab for the shell (nav + settings + menu) across every
breakpoint. This session we did a full **REBUILD on the mirror model** (the old "one node that moves"
approach caused a whole class of bugs - anchor jumps, jitter, reflow, vanishing filters - all one root
cause, now abandoned).

## What's built and working
- **Semantic settings FORM**: `<form>` → `<fieldset>`/`<legend>`/`<input radio|range>`. Mounted TWICE
  (persistent band + toolbox popover) = the mirror model. Both are dumb mirrors of `data-*` on `<html>`;
  one delegated `change` listener writes state + reflect-all. Proven: Color scheme → Dark darkens the page
  and both instances reflect. Nothing moves anything else.
- **CSS organized by NAMED SITUATION**, base first, ascending width - kills the source-order bug class.
- **The named situations** (live badge shows the current one in the intro):
  - `list-small-over` (base) - menu overlays content, dim.
  - `list-dedicated-sidebar` (≥1024) - menu in the rail's own space, no dim. Toolbox is a horizontal row.
  - `grid-2-rail-over` (≥1200, grid) - 2-col wall, thin trigger rail, menu over wall.
  - `grid-2-persistent-settings` / `scrolled` (≥1450, grid) - the airy exhibit band + reveal-on-scroll.
  - `grid-3-…` (≥1600) - 3 columns.
  - (`grid-3-…-dedicated-sidebar` ≥2400 was retired 2026-07-16 - menu now prefers the free side at
    every width and flips only when it must.)
- **Grid toggle** glyph (appears ≥1200; the door in/out; mirrors the Layout radio).
- **Plain-page toggle** (debug button in the intro) - flips to a plain page: no band, no wall, no grid
  toggle; just the shell + text, forced to list.
- **Debug badge** (green, in the intro) - live situation name. (Both debug bits: remove before ship.)
- **The exhibit is airy now**: intro breaks out (3fr/2fr top), settings un-boxed + quiet labels,
  generous "time to breathe" gap before the wall.

## 🔑 THE BIG DECISION - RESOLVED (2026-07-15): the sticky MENU-RAIL
**Where the MENU BUTTON lives.** Tried all 3 live in the sandbox; Derek picked the rail by feel -
the **sticky travel** is what sold it (glyph rides the page up and pins to the top).
1. ~~Viewport corner (`top: 13rem`)~~ - REJECTED: magic number, fragile. REMOVED from code.
2. ~~Tied to the settings block~~ - REJECTED: locks the settings' freedom AND no home on plain pages.
3. **A persistent right MENU-RAIL beside the content - CHOSEN + BUILT.**

**What shipped in the sandbox:**
- The shell has ONE shape on wide pages: **`[ content-area | menu-rail ]`**.
- **menu-rail** = the shell-nav as a real reserved right column (`grid-column: 3`), the glyph
  **`position: sticky; top: 1rem`** → rides in flow, pins to the top at every scroll depth.
- **content-area** = the page content: timeline → airy settings zone + wall; plain → heading + text.
  Settings live INSIDE the content-area, free to move (never tied to the rail).
- **Confirmed on plain pages (no filter):** a wide plain page is already `list-dedicated-sidebar`,
  whose rail column exists independent of any band/filter - the same sticky menu rail carries over.
- Why it won: always reachable, independent of settings, works on all pages, no magic number.
- **Next: port the proven rail into the real PHP shell** (`includes/`, `styles/`) - same
  `[ content | rail ]` frame, sticky glyph, list + grid + plain.

## The reconciliation insight we finally connected on
The airy/spacious layout (settings spread as background decoration, "time to breathe") and nav
functionality do NOT conflict - because the **nav is a separate, minimal, always-present glyph, not part
of the settings apparatus**. Settings = displayed art (free to sprawl); nav = one tiny persistent trigger.
**Weird where you're inviting exploration; simple & clear everywhere else** (Derek's rule - the majority
of pages are plain).

## Open items / still to think through
- ✅ DONE: menu-rail decided + built (sticky rail, no magic number; confirmed on plain pages).
- The exhibit's airy composition needs more design love (proportions, "background-art" feel) to match the
  real original's spread apparatus (Derek's screenshots of the live site).
- Page-header treatment: breaks out on timeline, is just a heading on plain - must read well BOTH ways.
- Plain pages should hide the **Filter** control (it's timeline-specific) - page-conditional form rows.
- Alignment with the REAL timeline is a BY-EYE port job: the real wall is the lane-dealer (flex lanes),
  NOT a CSS grid, so the top↔wall alignment won't port as a mechanism. Don't over-engineer grid alignment.
- Two menus kept separate (Settings + Pages) - toolbox holds N panels, each its own popover mirror.
- Eventually port the proven shell into the real PHP (`includes/`, `styles/`).

## Key files
- `layout-sandbox.html` - the lab. Start here.
- `layout-lab-notes.md` - this file.
- Real-page reference: `styles/layouts/grid-view.css` (wall geometry + corner island), and the `$todo` in
  `includes/settings-panel.php` (~lines 184-198) that first diagnosed the overlay-state smear.

## Git checkpoints
- `9106ad8` - pre-rebuild (exhibit/island/3-col on the OLD one-node approach; fallback if the rebuild goes wrong).
- (this session's commit) - the mirror-model rebuild.

---


## Why the lab exists

Isolate the shell - nav + settings apparatus + menu placement across screen sizes - in the
barest HTML, away from real content, so the layout can be reasoned about and made bulletproof.

## Purpose / the gate

- The interface itself is the portfolio exhibit: dashboard-grade overlays handled with zero cracks.
- Full-screen menu is the safe client move - rejected *because* it hides the skill being sold.
  The hard overlay IS the flex.
- Bulletproof = the visitor never sees a broken state. Not code purity.
- Hacks allowed at the platform edge IF labeled + contained. Honest hack = fine; hidden fork = no.

## Three roles, untangled (they're currently conflated)

1. shell-nav - the chrome that hosts triggers (today `<header class='page-rail'>`; drifted, should be `<nav>`).
2. page-header - the content's own top (`.page-header`, lives in `main`, per-page).
3. settings panel - a GUEST, not a permanent resident of the nav.

## Settings apparatus

- Controls live in server-rendered HTML. Progressive enhancement. JS enhances + places, never creates.
- Mirror model: controls are dumb mirrors of state on `<html>` + localStorage. N copies allowed,
  none owns state. Cost: event delegation off `data-set-*`, reflect-all on change, de-ID the partials.
- Trigger / panel split: a stable trigger cluster (holds N items) + transient panels.
  Wiring is the contract; skin is free (edge/border/full-bleed treatments iterate without rewiring).
- Panel overflow scrolls - already working.

## Placement modes (by breakpoint)

- **Phone**: sticky full-width header (the only viable way). Triggers -> panels overlay content.
- **Rail size**: full-width header dissolves, content scrolls freely past the top, a persistent side
  rail hosts the toolbox (a HOST for N triggers, not just settings).
  Sticky-header machinery (`.rail-sentinel` / `.is-stuck` / border-on-scroll) is PHONE-ONLY.
  IMPORTANT (corrected in sandbox): the trigger is ALWAYS present at a known spot; the panel is a
  TOGGLED DROPDOWN off it (closed most of the time, takes no space), opening below the menu into the
  rail's own space - NO dim there. It stays a native popover; the only per-size change is backdrop
  on (narrow, over content) vs off (rail+, opens in the rail's empty space). No JS needed - CSS drops
  the visible `::backdrop` at rail width. The always-DISPLAYED inline panel is a different thing -
  that's only the big-screen exhibit, not the rail.
### Largest breakpoint - controls as exhibit (what we NEED)

- There's abundant room, so the filters/theme controls get DISPLAYED openly - organic, near-seamless,
  almost background-art - spread into the wide right space as a big point of visual interest.
  The apparatus becomes the decor. This is the "TONS OF STUFF, epic but organic" feeling paid off.
- State: shown openly, always present, part of the composition -> "on its own" (nothing covered) ->
  no backdrop, no floating treatment. Different from grid mode's thin-rail-plus-overlay.
- Persistent/displayed filters likely show only on FANCY pages: home + style-guide.
- OPEN QUESTION: how cross-page nav lives here. Not resolved. Constraint to hold while deciding:
  settings/filters may sprawl as art (playful, optional), but NAV is needed on every page + every
  scroll, so it must stay reliably findable - it can't dissolve into decoration. Nav likely stays a
  stable, legible element AMID the art, not art itself.

### The tricky corner: scroll-past + less-fancy pages

PROBLEM: the displayed exhibit is top-anchored and you can scroll PAST it. On fancy pages the controls
then orphan, so access must REVIVE in a compact form (the persistent rail again, or a right-side island
if there's room).

CANDIDATE MODEL (not decided):
- Fancy pages (home, style-guide): exhibit at top -> scroll past -> revive as rail/island.
- Plain pages (leaf, case study): never escalate to the exhibit. They cap at the persistent-rail mode
  from the previous breakpoint - nav + optional settings beside content, nothing to revive.
- The exhibit is a fancy-page ESCALATION; plain pages just don't take it. Fancy pages effectively
  BECOME the plain-page rail again once scrolled past their exhibit.
- Invariant: nav + settings access reachable at every scroll position on every page. The revived form
  is really just "the persistent rail, brought back."
- OPEN: do plain pages carry any flavor of the big treatment, or stay dead-simple rail? TBD.

### Three-column breakpoint - tight margin (what we NEED)

- Three columns: the wall is wider and eats the horizontal space, so the right margin is too tight for
  a full sidebar / persistent exhibit.
- So: a menu toggle that STICKS + FOLLOWS (always reachable while scrolling), and the settings panel
  OVERLAYS on top. Same over-content state as phone/grid (backdrop, floating).
- NEW VARIABLE: open direction. Panel opens LEFT here - the trigger is at the right edge with no room
  to open rightward, so it expands into the content area over the wall. Open-direction adapts to where
  the room is (left/right/down) via anchor-positioning (`position-area`), per breakpoint. Not a new
  mechanism - the over-content state, just aimed where the space is.
- The opened overlay can be WIDE (spans ~2 columns over the wall). When wide, the panel's controls
  arrange HORIZONTALLY (scheme/filter/sound in a row, then themes, List/Grid, minimap) instead of the
  narrow phone vertical stack. So the panel's internal layout REFLOWS to fit how much room it opened
  into - it's not one fixed internal design.

### Grid mode invite (recap)

- **Big screen (>=1600px)**: the grid invite appears. It's THE HOOK, not just a toggle - but it
  INVITES, never forces. A radial pulse animation (or similar) calls attention; the visitor chooses.
  The offer: flip from "reading a website" to "playing with an interface playground" (whoa, all these
  color + type options). This is the big-screen pitch to the $300k decider, and the answer to "list on
  a huge screen feels empty" - the wide margin is where the invite lives and says "more here."
  Bar to clear: enticing enough to earn the click, AND the grid + theme payoff must deliver the woah.

Independent wrappers per mode -> hide/show the one that fits -> this is exactly why the mirror model
earns its cost (phone header wrapper vs rail wrapper are different parents).

### Grid mode (big screen, the wall) - what we NEED

- A wall of cards at its max size (limited by the wall's own width).
- Horizontal space: small left inset, wall pinned to its max, extra width all flows RIGHT into a
  flexible region.
- Reserve a THIN rail in that right region for the menu TRIGGERS only (glyph buttons) - not the panel.
- When the menu opens it is ON TOP of the content -> over-content treatment (backdrop, reads as a
  floating layer). The panel is top-layer, so it needs no layout space of its own; the reserved rail
  only ever has to fit the buttons.

## Over-content vs on-its-own state (the "when overlaying" rule)

THE POINT: the panel must reliably KNOW which state it's in - over content, or standing on its own -
because styles of every kind will differ (backdrop, border, shadow, background opacity, padding...).
Border/backdrop are just examples. The absolute need is a dependable state hook CSS can branch on.

- Candidate hook: the panel's own `popover` state - one switch could drive placement AND skin
  (`[popover]:popover-open` = over content; no `popover` = in flow, e.g. the rail). To be proven in the lab.
- When over content: whatever the skin, it must stay legible over a sheer scrim (see live-preview rule)
  and read as a distinct floating layer.
- When on its own (in flow): free to dress however; nothing underneath to separate from.

## Default = overlay; placements are enhancements (progressive enhancement)

The overlay popover is the DEFAULT and the no-JS floor: HTML + CSS alone give a working menu that
opens and overlays at every size (native popover, nothing to break). Every placed-inline state (panel
in the rail, top strip, the exhibit) is an ENHANCEMENT layered on top only when JS runs AND Room allows.
If JS never loads / a browser chokes / something races -> fall back to the working overlay.

Build rule: overlay is the guarantee; inline placements only ADD on top, never replace it. Each
placement step reads "if JS and <room> -> lift panel into <spot>"; the moment a condition isn't met,
it's back to the working overlay. Bulletproof by construction.

## Named situations (naming them one by one with Derek)

Scheme: `<size>-<placement>` (or similar), one clear name per situation. "orphaned" is retired -
confusing; the scrolled situation gets a real name when we reach it.

1. **`small-over`** - the smallest layout. The menu's ONLY option is OVER content (backdrop on) -
   no room for its own space, so just the one placement.
2. **`dedicated-sidebar`** - the rail layout. There's a dedicated side column for the menu, so the
   panel opens in its OWN space (no backdrop). The menu has a home of its own here.
   (more to come, one at a time...)

## The switchboard (organizing principle - keeps it bulletproof)

The trap: "lots of different JS per situation" = independent code paths that drift and hide edge cases
(that's how the scroll-freeze happened). The plan: everything reduces to a FEW signals; CSS does most
of the reacting. Every "situation" is a COMBINATION of these, never a bespoke branch.

1. Room - the breakpoints. Mostly CSS media queries. JS only for the one gate CSS can't express (grid >=1600).
2. View - list vs grid. One attribute: `data-view`.
3. Panel state - over-content vs on-its-own. One switch: the `popover` attribute. CSS branches ALL styling off it.
4. Open direction - left/right/down. CSS `position-area` per breakpoint. No JS.
5. Scroll-past - is the top exhibit in view? One IntersectionObserver -> toggles one attribute -> revived trigger shows.
6. Page type - fancy (home, style-guide) vs plain. Server-rendered once; decides whether the exhibit exists at all.

JS = a few observers that set attributes; CSS reacts. The lab's real job is to PROVE the switchboard,
not to build N layouts - get the signals clean and the layouts fall out of CSS.

## LIST MODE TERMINATES AT THE RAIL (important scoping)

List mode's full story is only: narrow (panel overlays content, dim on) -> rail (panel drops off the
toolbox into the rail's own space, no dim) -> wider still (NOTHING new; the group keeps its rail layout
and the growing right space is just elegant empty margin, NOT a panel target). List mode is complete
there. The trigger/toolbox is always present; the panel is a toggled dropdown, never always-shown.

Everything below - "is there enough room / open right into own-area", the exhibit, 3-column, the
reveal-on-orphan - is GRID MODE ONLY (opt-in). Do NOT build any of it into list mode.

Shell measures are variables (`--content-max`, `--rail-width`) so grid's thresholds are calculable
from them, never magic numbers.

## GRID-MODE ONLY below: "Is there enough room yet" - Room decides Panel-state

At the big sizes the panel's state is not fixed per breakpoint - it's a ROOM decision (signal 1
choosing signal 3). "Whichever menu" = whichever placement the room allows:
- Enough room in the right 1fr/infinite space to hold the panel -> opens in its OWN area -> no backdrop.
- Not enough -> opens OVER content -> backdrop.
Same panel, same two states, chosen by fit. Not a new state - the edge between own-area and over-content.

LAB DECISION to pin: how is "enough room" answered?
- (recommended) BREAKPOINT / CSS: panel max-width + wall max are known, so the threshold is computable -
  one media query at ~[wall-max + panel-width + gaps]. Stays pure CSS, on the Room signal, no runtime
  measuring, nothing to race or drift. Prove this first.
- (fallback) MEASURED / JS: compare available right-space to panel width at runtime, toggle the attr.
  Exact + fluid but a live measurement branch - a labeled-hack candidate only if the breakpoint feels coarse.

## Biggest breakpoint - reveal-on-orphan

- Top inline exhibit is the primary control surface. The far-right 1fr/infinite space stays calm/empty
  (or art) - NOT a permanent second control panel.
- A compact menu button appears in the right rail ONLY once the top filters scroll out of view
  (IntersectionObserver on the exhibit leaving). Slot may be reserved; only the button reveals on orphan.
  Tap -> overlay panel (over-content state).
- Why not park full settings permanently on the right: (1) redundant with the top exhibit - two control
  surfaces reads as "which one?"; (2) too far from the content it repaints - weakens the live-preview
  "whoa it's changing" moment; (3) calm empty space is itself part of the composed look.

## KEY FINDING - the existing code already diagnosed our problem

`styles/modules/settings-panel.css` (the `.menu-scrim` `$todo`, ~lines 184-198) says exactly what
we've been circling: the scrim's `.is-visible` "is impersonating a state it doesn't own." The real
condition isn't "a menu is open" - it's "a menu is open AND presented as an OVERLAY." That truth is
currently SMEARED across three spots that only coincidentally agree (the scrim `.is-visible`, a
`@media >=1024 display:none`, and the JS that adds/removes the `popover` attr for grid). That smear is
almost certainly where the scroll-freeze hid.

Its prescribed fix == our switchboard signal #3: name overlay ONCE as a flag on `<html>`
(`data-menu='overlay'`, same shelf as data-view/data-brand), set where the rail-vs-inline-vs-popover
decision is already made, and make the scrim (+ any future scroll-lock / inert / blur) a CONSUMER of
it instead of each re-deriving "are we overlaying?". This is the plan.

Gotcha it also explains: the real page can't use a native `::backdrop` because there are TWO menus
(Settings + Pages) and switching hands one popover's backdrop to the other -> flash. That's why they
hand-rolled the shared scrim div (which then needs the 3-way state tracking). With ONE panel (sandbox),
native `::backdrop` is clean and auto-correct: it exists only while the panel is an open popover, so
backdrop-presence IS the overlay state for free. Decision to make: keep two separate menus (needs the
flag+scrim-consumer) vs unify (native `::backdrop` stays clean).

## Alignment is a by-eye port job (the timeline is unique)

The real milestone timeline is NOT a plain CSS grid - it's the lane-dealer (cards dealt into
`.timeline-lane` flex columns, varied heights). So the wall's "columns" are lane wrappers, not grid
tracks, and the clean "shared parent grid so the top row auto-aligns with the wall" that works in the
sandbox WON'T port. Aligning the intro/exhibit band to the wall columns is a BY-EYE tuning pass against
the real timeline, not a mechanism to over-engineer in the sandbox.

- The sandbox proves STRUCTURE + BEHAVIOR (switchboard, placements, reveal-on-orphan, room-decides-side),
  not pixel-perfect alignment.
- The wall-2 "break the grid a bit" (intro spills wider / interleaves at the 2-col stage) is part of that
  by-eye pass too - tune it visually at port, don't build a precise mechanism for it here.

## Parked bugs (JS, not layout)

- Scroll-freeze: page scroll gets stopped (this is what shut the settings panel off site-wide -
  the "mobile scroll-freeze"). Assumed JS, not layout - likely a scroll-lock added on popover-open
  that isn't cleanly removed, or an overflow toggle on the wrong element. Hunt when wiring JS back in.

## Rail-size menu state

Red (open menu) at rail size is NOT over content - it opens in the rail's own space. So it's the
"on its own" state: no backdrop, styles free. No third "over-rail" case to name.

## Live-preview requirement

The settings panel is a live preview controller. The page must stay visible + legible behind an open
panel so brand/emphasis/scheme changes are seen AS they happen. So: sheer scrim, and the panel is
never full-bleed - even on phone a band of live page stays in view.
