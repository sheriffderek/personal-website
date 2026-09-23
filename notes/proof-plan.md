# Proof plan (started 2026-09-22)

Working notes from the audit session where the timeline got its skill tags and
we started listing what proof to make next. Raw - Derek dumps his thoughts
first, then we decide what matters and prioritize. Delete this file once its
decisions live where they belong.

## The purpose (Derek's reminder - everything below serves it)

Recruiters and hiring managers are **scanning**. They need to see clear lanes
of what they're hiring for, and that there's proof of the experience AND the
thought process. Give them what they want; don't build machinery for it (a
lane-grouped tag system was proposed and dropped as too complex).

## The ladder a visitor walks

1. The card: date, title, skill tags, description, "Read more +"
2. Read more: the setup and the thought process - the real proof
3. The door out, at the END of Read more, once the context has landed

Default: an outside link lives at the end of Read more, after Derek's setup
(the CSS-Tricks card is the working example - Read more frames it, then "Read
the article"). Exception: a card that's only a pointer (a podcast, "it's on
YouTube") may put its link in the first layer.

## Door grammar - one meaning per mark

Already the site's rule: the "Link marks" comment in styles/setup.css defines
`+` (opens in place) / `→` (another page here) / `↗` (leaves this page), and
the selectors apply `→` and `↗` so content never types them. Today's
realization was that this grammar is also the answer to "buttons vs links".

Kinds of doors: an **outside article** (published once elsewhere), a
**long-lived collection** ("View the collection ↗"), and a **stand-in** - the
best thing that exists until the real destination ships.

Stand-in, open: the PE code editor card's Read more should end on the PE story
(https://perpetual.education/stories/building-the-codestudy-sandbox/), label
not settled ("Read the story" proposed). When its case study ships, the door
swaps to "Read the case study →". The journal entry "Taking our code editor to
a second site" is a second candidate door (proves the library is reused).

## Skill tags (built 2026-09-22, uncommitted at time of writing)

- What a project was HEAVY on, not everything that happened (Derek: "I do all
  of these things on every project"). Posting language, max 4 per card,
  heaviest first.
- Stored as slugs in each card's `skills` (content/milestones.json); the words
  live once in content/skills.json - so a later filter can use the slugs.
  The old `tags` field stays the private lane switch (job / music / life).
- "Writing" was dropped: all of Derek's work involves writing, so it says
  nothing about a card.
- **Open: the look.** They render as tinted chips (styles/modules/milestone.css),
  which read as buttons. Rule we landed on: looks like a button only if it
  acts, a link only if it goes somewhere, otherwise plain text. Derek's
  mockup: quiet comma-separated text above the first paragraph; link color
  only once a filter makes them clickable.

## Proof ledger

What each piece proves, what already exists, how deep, what gap it fills.

### World IA Day LA 2026 - journal entry (recommended over a case study)

- Why journal: photos of the day and team, what went well, what he learned,
  what he'd do differently - that's the journal's register; case studies are
  capped at 4-5 and reserved for the target-seat proof (case-studies-plan.md).
- Proves: leadership, stakeholder management, community building, information
  architecture, people leadership (volunteers, coaching speakers).
- Exists: photos, the three-pillar theme, call for speakers, branding/graphics.
- Depth: medium - photos + Derek's writing, no video.
- The card's Read more ends on "Read the story →".
- "What I'd do differently" deserves real space: it's the senior-interview
  question, answered in writing.
- **Settle first:** the history says co-chair, Nov 2025 - Mar 2026 (~4 months);
  the card says "chair" and "over 6 months" (see the card's backstory field).

### Figma collection research for multiplatform brands - new milestone (Derek's title)

- The collection: https://perpetual.education/resources/figma-variable-collection-composition/
  (about ten Figma files; last updated 2026-08-24).
- Proves: design systems + theming in Figma - the only Figma-native
  design-systems proof so far; the design-file twin of this site's theme
  system.
- Suggested: tags Design systems / Theming / Typography / UI design; weight 2
  (or 1); door "View the collection ↗" at the end of Read more.
- **Needs from Derek:** dates (the Sam's workshop file came first - when, and
  who is Sam), the description in his words. Record it in
  resume-exploration/source-materials/dereks-history.md first (it only lists
  "Figma Variables and Modes" as a skill bullet today). No draft state exists
  for cards, so the card waits for its words.

### Next

Derek lists the rest of the proof he can make and at what depth; then we
prioritize - biggest gap filled for the least new work first.

## Parked elsewhere this session

- The layered experience chart is a site component (includes/layered-experience-chart.php)
  but mounted nowhere - headed for a case-study page about this site.
- Open question from Ivy: should grid view be the DEFAULT on large screens?
- CodeRabbit: this repo runs on the free review allowance - it needs connecting
  to the paid org in the dashboard (see the coderabbit skill).
