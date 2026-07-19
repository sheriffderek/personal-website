# Shell: apply the lab's layout + structure to the real site

Read the code, then make your own plan. This file is just what to look at and
what'll bite you.

## What to look at

**The real shell (how it works today):**
- `includes/settings-panel.php` — the triggers, the panel, the corner island
- `includes/settings/*.php` — one partial per settings row
- `includes/header.php` — the rail, the FOUC restore script
- `styles/layouts/default-layout.css` — `.page-rail` (the trigger host)
- `styles/layouts/grid-view.css` — the grid/wall
- `styles/modules/settings-panel.css` — panel, triggers, scrim
- `scripts/settings-panel.js` — all the chrome behaviour
- `scripts/grid-masonry.js` — the lane dealer (the real wall)
- `scripts/sticky-header.js`, `scripts/tour.js` — other things that touch the rail
- `includes/config.php` — feature flags

**The lab (how we decided it should work):**
- `experiments/shell.html` — a proven standalone shell, QA'd in Chrome and Safari
- `layout-lab-notes.md` — the **"✅ CANONICAL SPEC"** section at the top. This is the
  real reference: the vocabulary, the axes, and the locked rules (what decides where a
  panel opens, which side, what dims, what closes). Read it before the HTML.

## The job

Apply the lab's **layout and structural logic** to the real site.

**Not** what's inside the panels — leave the settings rows, the axes
(character/mood/scheme/red-light), the form markup, and the storage keys alone.

## Traps

- **Storage keys are `*-preference`.** Renaming one silently wipes every visitor's
  saved settings.
- **CSS anchor positioning must be deleted, not overridden.** Inline JS styles win the
  cascade, but a leftover `right:` stays applied next to a JS `left:` and stretches the panel.
- **The corner island adds/removes the `popover` attribute on the live panel node.**
  `.toolbox-panel:not([popover])` vs `[popover]` is the whole inline-vs-popover switch in
  grid view — don't break that predicate.
- **`.page-wrapper > .page-rail` is a direct-child selector.** Wrapping the rail breaks
  the ≥1024 layout.
- `settings-panel.js` captures `.toolbox-trigger` / `.toolbox-panel` **once at init** —
  later elements aren't tracked.
- `grid-view.css` loads via its own `<link>` after the bundle, so it wins ties on purpose.

## Already done (uncommitted — don't redo)

The trigger glyphs are wrapped in `<div class='toolbar'>` (`includes/settings-panel.php`
+ `styles/modules/settings-panel.css`). Check `git status`.

## Working rules

- Derek commits, in Tower. Never `git commit`/`push`/`reset`/`rebase` — `status`/`log`/`diff` only.
- Never start a dev server. MAMP serves `http://derek:8888/`; the settings panel renders
  at `/design-system` — verify there.
- One change at a time; leave the site working. Stop after each for review.
- Say what you verified yourself vs. what needs Derek's eyes. Don't claim browser testing
  you didn't do.
- CSS is tab-indented, single quotes, comments explain why. No BEM.
- If the code contradicts this file, trust the code and say so.
