# Layout lab - working notes

Scratch notes for the shell rework. Page: `/layout-lab` (bare skeleton, no real content).
These are decisions as we hone them - not final CLAUDE.md rules yet.

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
