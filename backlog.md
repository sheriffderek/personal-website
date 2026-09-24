# derekthomaswood.com - backlog

Future ideas and parked work - after version 1 (`v1.md`). Each item keeps the reasoning that got it here, so picking one up doesn't mean re-deriving it. Nothing here is scheduled; an item moves to `v1.md` only when Derek brings it up.

## Timeline filter: below 16 shows only case studies (Derek, 2026-09-24 - an idea)

Replaces the 2026-09-17 "homepage as 4-5 case-study conversations" direction, which is **dropped** - no new page. The idea instead: the filter sits at 16 (the weight-1 pitch) by default, and moving it further left narrows to only the case studies. This is the "Shape B" the Timeline weights section of CLAUDE.md says was chosen against (narrowing below the pitch), so it's a deliberate revisit of that call, not a drift into it. Needs case studies to exist first.

## Per-target URL plumbing (the seam that unlocks tailored intros)

Discussion 2026-09-15. The `?target=<company>` view works when a recruiter clicks a link, but the resume PDF is what actually leaves the site - and a naked domain typed after seeing the PDF loses the target. Fixes, in order:

- [ ] **Pretty URLs per target** - `derekthomaswood.com/gofundme` maps to the same handler as `?target=gofundme`. The target *is* the URL - clean in the cover letter, sayable out loud, fits at the bottom of the resume PDF. One route rule in `index.php`; the query string stays as the implementation detail.
- [ ] **Per-target resume PDF export.** Export already goes per lane (product / engineer / advocate); add a target dimension so the portfolio link's `href` becomes the tailored URL. Filename convention exists (role-type-name); extend with company. Note dropped in `styles/modules/resume.css` print block (2026-09-15).
- [ ] **Hidden `href` pattern for the portfolio link** - visible label reads `derekthomaswood.com` or `Portfolio →`, the `href` carries the full `/gofundme`. The print stylesheet must NOT append `[href]` in parens for this link. A QR in the corner is possible for the printed-and-retyped case, but probably not worth it - tech hiring reads PDFs, it doesn't type URLs.
- [ ] **Then** the per-target hello video slots in (below) - plumbing done, recording is the only remaining cost.

Order matters: pretty URLs first (unlocks everything, low effort), then the PDF export (locks the seam), then videos as the payoff. Only worth doing per-target where the target notes already exist and matter - general sends stay general.

## Hello video

- [ ] **Derek saying hello, on camera.** The tradeoff Derek named (2026-09-15): in some ways it's giving them too much information - but if they don't like him right away, that's a big hurdle to get over, and he wants people who want somebody enthusiastic and outgoing. So the video *is* the filter, and better upfront than after three interview rounds.
  - **Probably a `?target=` thing, not the general site** (2026-09-15). The general site stays text-first; the tailored view gets a per-target hello ("Hi GoFundMe team..."). Personal, screens hard, only made for applications that matter. Depends on the plumbing above.

## Case studies

- [ ] **Case-study page design.** Unlike journal pages, case-study pages probably need to be white and NOT follow the theme - theme colors would blow out project colors we don't control. The design direction from 2026-09-17 still stands: **black and white, process-forward, not visual flourish** - graphics about process and core design, not high-fidelity UI screens. Thought process over visual style, consistent with the "invisible design" positioning and Wesley's bridge-story feedback (iterative friction, not finished-looking prototypes). A reviewer looking for visual craft sees restraint used on purpose; the craft carries through the frame, the type, the pace.
- [ ] **Decide which case studies to build, and in what order** - weight toward the clearest process story, not the prettiest artifacts. Open call, no rush: ChromaDex vs the real-estate mastermind as #3 (`case-studies-plan.md`).
- [ ] **PE umbrella case study** (`case-studies-plan.md` #1): Derek works out the grew-over-time story live on camera and sends notes; they get kept in order, in his words, in `pe-case-study-notes.md`. The How I work page gets built from these walkthroughs.

## Journal

- [ ] **Journal categories** - probably needed eventually.

## The sheriffderek circle (Derek, 2026-09-18 - thinking about, not building)

Derek's insignia (the little two-tone pink circle) as the site's way home: above his name at rest, reappearing as a floating circle on scroll-up, maybe a big corner version on wide screens. The v1 part is just "a visible way home" (see `v1.md`). Full write-up, sketches, where the real SVG lives, and the open questions: `notes/home-circle-idea.md`.

## Settings band extras

Three ways to drive the same system, all in the settings band (grid view, >= 1450 - the one place a visitor sees the whole wall repaint): go home / surprise me / make it mine.

- [ ] **"Reset to default" control** (2026-09-18 - talked through, not built). The way home from a rough combo. Resets the LOOK only (scheme, character, mood, flavor, red light - not interface sounds, layout, or filter); always present and disabled at default rather than appearing/disappearing (app-ui form law); a quiet text-level control under the rows, not another button row. Nearly free mechanically - each slider's `apply(defaultIdx)` already clears its own key. Label is Derek's to write.
- [ ] **Random - parked until the colors land** (2026-09-18). Band-only (precedent: `.filter-level-name`). Leaves scheme and red light alone. Parked because random showcases every cell, including unfinished ones - the same reason the default went Quiet. Open, unproven: draw only from combos with a passing verdict in `notes/walk-notes.md`?
- [ ] **Theme chat** (2026-09-18). A visitor describes their brand and the site re-paints into it. Same band-only reasoning as Random. Full plan: `notes/theme-chat-plan.md`.

## Viewers

- [ ] **Device stage - parked, not pressing** (2026-09-19). On large screens, show the site in a phone/tablet-width stage so a desktop visitor sees the small-screen design without resizing or losing their place. Iframe ruled out; the route is container queries. Everything learned, the hazards, and the one-hour experiment to run first: `notes/device-stage-plan.md`.
