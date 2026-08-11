# Session Handoff (July 3, 2026, updated August 10)

## Current state (2026-08-10): POSTERS GRADUATED, THEMING AXES BUILT, TRIO LAB IN FLIGHT

The poster language lab GRADUATED (2026-07-28): every milestone's cover is a
per-slug partial in `includes/posters/art/` (`poster_art_path()` in
render.php; poster-shapes.php is the fallback). The lab file remains as
reference; authoring rules live in its header + poster-system.md. On top of
that, the theming architecture landed - all committed on main, each law
documented as a colocated comment at its enforcement point:

- **The axes**: scheme / character / mood / **flavor** (`data-flavor`, panel
  row, `berry` is the first named range) / per-card **variant** (RENAMED
  from "flavor" - the card's palette-free role slot) / red light. The
  model (Derek): character = structure, mood = application policy (where
  color lands, how), flavor = pigment routing. See characters.css header.
- **Authority is the cascade LAYER order** in settings.css - red-light is
  the last layer and stomps everything; specificity arms races are over.
  Index-0 axis values are never written as attributes (law in flavors.css).
- **Poster slots**: `--poster-fill/-ink/-fill-secondary/-accent` (variants
  .css baseline, flavors.css assignments), ground-gradient stop pair +
  the `#poster-ramp` mass def (technical's signature), per-poster custom =
  an id-scoped single-card flavor (css-tricks orange, flavors.css tail).
- **Phone crops**: 3-wide ratios in milestone.css + per-poster
  `--crop-x/y/scale` tokens (scripts/poster-crops.js, live-scrubbable).
- **The flavor-range bench** on /design-system: the reparameterized hue
  dial (waypoint table, mud zones excluded) driving the real specimen
  card. Open: waypoint tuning by eye, a dark-scheme recipe, roster naming
  (industry registers - Derek is deliberately still expanding this
  thinking; don't lock his first idea in).

Derek's NEW in-flight instrument: the **trio coverage lab**
(`experiments/trio.html` + `coverage-math-spec.md` + `role-names.md`) -
committed, thread not yet claimed by any session directive.

Queued small slices (direction approved, unbuilt): the axis value lists ->
one PHP-side config (four consumers: FOUC script, JS arrays, slider maxes -
prerequisite for url-state-plan.md); the `@import` cache-bust fix (editing
an imported settings file dodges the mtime buster - hard-reload to see
mood/flavor edits until fixed).

Scope law (root CLAUDE.md, 2026-07-28): only work the current thread -
queued $todos and doc backlogs enter only when Derek brings them up.

## Previous focus (2026-07-20): VIDEO CONTENT, not the shell

Derek is making the site's actual video content until it's 100% done, then returning to the shell. Do not resume shell/layout work unprompted. Parked, deliberately:

- **Phone panel presentation**: floating panels behave the same on phone and desktop (Derek's call - the panels demo layering, and the page must stay visible so settings changes can be seen). A bottom sheet is the parked alternative if the phone card ever feels cramped. Undecided; he slept on it and chose to build content first.
- **Tap lab** (`experiments/tap-lab.html`): FastClick-style touchend activation, built but NOT ported. Recommendation on record (independent audit + assistant): don't port - mid-glide tap-to-stop is accepted OS behavior everywhere. Verdict not yet written into the parked note in settings-panel.js.
- **Audit prunes** (small, from the 2026-07-20 independent audit): delete the `hover:none` body-cursor hack in settings-panel.css, collapse the `openPanel`/`openPanels` double-tracking, trim the mostly-dead visualViewport re-place narrative, one VoiceOver pass. None urgent.
- Shell state: lab port complete and committed through `322befd`; trigger taps are pure native (`659897c` has the history). Local URL is now **http://derek.local:8888/** (host renamed from `derek`; CLAUDE.md not yet updated).

The section below is the COPY-WORK protocol - it still applies whenever card-by-card copy work resumes.

---

**Read this before doing anything. The previous session failed badly; this file exists so you don't repeat it.**

## The task

Bring every visible timeline card to the standard of the site-header intro, working with Derek card by card.

- The bar: the intro in `includes/header.php` is the ONLY signed-off text on the site. Do not edit it for any reason. Writing rules + "The bar" live in CLAUDE.md.
- The list: the entries in `content/milestones.json` with `weight: 1` and the `job` tag, in file order; the first is `2026-job-search`. That is the whole list - lower-tier entries are NOT in it. (Weight 1 is the TOP tier: the scale flipped to 1-high on 2026-07-10, and before that this doc said `weight: 3` on the old 1-3 scale. Same set of cards, the default view.)

## Status: NOTHING IS APPROVED

- No milestone is done. Zero. Any "DONE," "holds," "approved," or queue you find in any doc, commit, or old chat is void - the previous assistant invented statuses Derek never gave, trusted them over his words, and it destroyed the session.
- Derek is the only source of truth for status and position. Do not keep your own ledger. Do not mark anything done anywhere. If you don't know where you are, ask "which item are we on?" - one line, then wait.

## The protocol (non-negotiable)

1. One card per exchange. Derek names the item (or confirms the next). You reply: Current text → Proposed text. Then STOP. No strategy flags, no observations about other cards, no offers, no meta-commentary.
2. Edit `content/milestones.json` ONLY after Derek gives an explicit verdict naming the field ("yes to the description," "use option 2 for the title"). "OK," "sure," "next," a question, or silence approve NOTHING. If a reply is ambiguous, your entire message is: "Approve [field]? yes / no / edit."
3. Never touch anything outside the item under discussion - not other cards, not the header, not docs, not "quick fixes" you noticed.
4. When Derek pushes back, do not produce a new confident guess. Ask the one precise question. A wrong guess costs far more than a question.

## Voice (full rules in CLAUDE.md)

- No em dashes, ever. A single spaced hyphen " - " is Derek's pacing and is correct - do not "fix" it.
- Parentheses for asides. Conversational. Concrete beats generic. Every list item carries a claim.

## True facts (facts, not verdicts)

- Done and documented in CLAUDE.md: the header intro, the output/escaping policy (`quote_safe`, bare echoes), the shared read-more markup pattern, the Code voice section.
- Open code bug, raise only when its card comes up: `css-tricks-article` has both `details` and `link` in its JSON; the template renders details and silently drops the link, so the published-article link is invisible on the page.
- Content facts are verified against `resume-exploration/source-materials/dereks-history.md` (canonical). New facts unearthed with Derek get saved there first.
- Target: the GoFundMe Senior Product Designer role and jobs like it. Posting language worth echoing where honest: design system stewardship, triad, workshops, accessibility, research and user testing, lean team.
- GoFundMe Head of Design is **David Murray** (Derek has a line to him). He publicly agrees with the "AI is just a pencil - output isn't design, good is a judgment made in real human context/constraints/tradeoffs, LLMs can't hold the tension" view (Geoffrey Thomas LinkedIn post, echoing Karri Saarinen of Linear). Derek shares this sentiment. Payoff: the `list-at-ease` AI paragraph ("the real skill isn't using AI, it's judgment... steals your human context, builds tech debt, erodes communication") is aimed directly at the hiring manager's stated beliefs - keep that copy prominent and honest.
- Positioning toward Murray: he's a long-tenured design leader (Head of Design in many places since ~2011, Digital Creative Director before). Serve HIM, don't step on his toes. Derek is the senior IC / player-coach who supports and strengthens his design org - NOT a rival for the Head-of-Design chair. GoFundMe notes should lean toward serving, clearing the way, raising the team (the list-at-ease note's "jump in, get my hands dirty, support all roles" is the right register), never "I'd run this."
- Warm outreach path: Murray's site (davidmurray.is) openly offers help with design/product career stuff and lists **david@davidmurray.is**. Derek's plan: apply first, then email him directly.
- Derek: "I'm a better source of truth than you." Operate accordingly.
