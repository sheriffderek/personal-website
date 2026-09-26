# App UI - working state

"Let's work on the app UI / the chrome / the menus" = start here. This file is the map and the queue; the rules and reasons live in the code, so read those, don't re-derive them.

## Where the truth lives

- **The contract** - the comment block at the top of `[data-ui="app"]` in `styles/modules/settings-panel.css`: the three laws (the box never changes; the `--app-*` open bank is the only door; everything else sealed), the phone posture facts, and CHROME TAKES - the pattern vocabulary (recognition test, families, Ring/Ghost, the five states, placements with the phone-test results, Hint, the menu notes, underlines/shortcuts by family, families-follow-characters, Interface, scheme continuity).
- **The slots** - the OPEN BANK in the same file (`--app-pill-*`, `--app-menu-*`, `--app-trigger-*` incl. hover/press, `--range-*` knobs, `--range-track`). Every slot defaults to the pre-slot look.
- **The families** - `styles/modules/app-ui.css` (MENU FAMILIES, FAMILIES: Interface + Interface x Sweet = Slack). Token values only.
- **The playground** - `/design-system/app-ui` (`templates/pages/app-ui.php`): Families row, Menu row, Today column, Context grid (ruled-out pairings labeled), try links. `/design-system` lists the try links first, for phones.
- **The live preview switches** - `index.php` near the top: `?try=<take>-<placement>` and `?family=<name>`, one page view, nothing saved. Best current look: `/?try=ghost-float&family=interface` (set Flavor to Sweet + Dark for Slack).

## Where we are (2026-09-25)

- The live default is unchanged for visitors: Ring triggers, Straddle placement - except the deliberate baseline pass on the settings panel (one left edge, 8px label-to-control, 24px rows, equal and aligned option widths, 12px option text), the menu's row box, and the menu icons (Phosphor regular: HouseLine, Compass, FileText, Alarm, Notebook, At).
- Interface is the first family carried down the whole panel (Claude Code light, Linear dark, flat ring halved to 3px, soft track under options, hover/press ladders), previewable, NOT wired to the Interface character yet.
- Derek's verdict: "a big difference in how pro it looks."

## Queue (Derek decides order)

1. **Review billing** - the review CLI began reporting usage-based billing (~$0.25/file); reviews are paused and every commit from `56849ed` onward says so. Derek decides, then run the review over that range.
2. **Wire Interface to its character** (the `?family=` switch becomes the real thing when Character = Interface), with the placement it needs on phones (Float or Contain - Straddle can't carry Ghost).
3. **A second family** to prove it's a system - Terminal or Windows 95 contrast most.
4. **Specimen page** `/design-system/specimen` - real parts and approved copy only, every link type, so a theme can be judged on one screen (`/now` is text-only and hides most changes).
5. **Open calls** - menu links in page font or chrome mono; a whisper of a current-page mark in Interface; the shortcut keymap (needed before any family shows keys); Slack's PAGE side (charcoal + blue links) - let a flavor set a few page tokens, or keep flavor to posters and chrome; the dim over the page they're watching; the Contain bar/card color seam; "a little tight / tangential" on the floating card.
6. **Resume wall** - lane pages get their own short head (only what the sheet uses) + a last-line-position test; narrow the hook; decide the lane pages' way home (plain link vs tray).
7. **Public doors** - link `/design-system` from the settings panel ("See the whole system"), after a polish pass on it.
