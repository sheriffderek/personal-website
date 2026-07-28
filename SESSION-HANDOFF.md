# Session Handoff (July 3, 2026, updated July 28)

## Current focus (2026-07-28): THE POSTER LANGUAGE LAB

The milestone poster covers are being designed in `experiments/posters.html`
(view at http://derek.local:8888/experiments/posters.html - MAMP host renamed
to `derek.local`, CLAUDE.md already updated). All 36 milestones have a drafted
poster; several review rounds are done and committed (`7d05750` through
`3b4acb7` on `fixing-grid-and-reworking-theme`).

**The lab file's header comment IS the spec - read it before touching any
poster.** It carries the family law (alignment, padding, equilateral
triangles, optical bump, no tangents), THE SCALE (line weights 5/10/30, the
20-ladder for circles, bar heights, gap semantics: 20 breathing / 40
separation, constant sibling steps), the roundness law (corners axis drives
rect rx AND stroke caps/joins), the 3-wide phone-crop system (large 3:3,
medium 3:2, small 3:1 - milestone.css adopts it at graduation), and the open
question (per-poster dark/inverted variety - recorded, deliberately not acted
on). Every poster's own comment documents its geometry against the ladders.

**The working process that works:** Derek posts a marked-up screenshot or a
one-line note per poster; rework that poster in place (geometry derived, not
eyeballed - document the numbers in its comment); verify in the browser pane;
he says "save" and the batch commits as one slice. Do not batch-redesign
unprompted.

Things to know:
- Poster sizes are AUTHORED in `content/milestones.json` (`poster` key);
  several changed this session - the JSON is already correct.
- The steps/staircase motif is parked for a future PE card (geometry at
  `35f57b2`); pe-founded wears the target now.
- Coverage audited: all 36 JSON milestones have lab posters, 1:1. Two stale
  doc refs found (timeline-content-plan still lists the removed
  crowdfunding-competitor as a must-have; milestone-tuneup-notes still
  reviews the deleted pe-redesign-dashboard) - one-line cleanups, unclaimed.
- Next when Derek says so: remaining misses; the dark/inverted question;
  grain as a FRAME finish (the texture bench lives on /design-system -
  posters themselves stay filter-free per poster-system.md); graduating
  winners into `includes/posters/` per poster-system.md's checklist
  (slug-prefixed ids; site wiring needs the 3-wide crops in milestone.css,
  per-brand corners/caps tokens, and the poster-space corner-unit multiplier
  flagged in the lab).
- `styles/settings/moods.css` has Derek's own uncommitted working diff -
  never stage it.

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
