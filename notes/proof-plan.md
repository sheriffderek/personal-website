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

### Derek's pass over every card (2026-09-22, dictated - sorted, not yet prioritized)

**Main case studies - the serious ones**
- *Now interviewing* - treat it as a case study of the whole job-hunt
  workflow; show cool things about him (the layered storyline); he has Figma
  files and could make a video of how this site works, how it shows exactly
  what they need to know, and why it's designed this way. (The layered
  experience chart's natural home.)
- *List at Ease* - "a little bit scared to make, but I have a lot of data
  there." One of the main key case studies.
- *BetterLife* - "should be a pretty serious case study. I'm just avoiding it
  because it's kind of a mess" - but so much to talk about; no reason it can't
  be very impressive.
- *Designing a digital product design school* - could be a story/overview of
  why he wanted to design the school, with the case study being built broken
  into the smaller PE parts. Thoughts still forming.

**Already exists - just link out**
- *PE code editor* - a mostly finished, "pretty official" case study on the PE
  site; link to it - after "some more work before it can be read - but not
  too much." Covers the plan's #5 (case-studies-plan.md) once linked.
- *Turing Outside Insights*, *skill-gap podcast*, *bootcamp podcast* - link
  to the video.
- *PSSST* - stands alone, leads to the GitHub.
- *CSS-Tricks* - leads to the article; fine as is.

**Low-hanging fruit**
- *Themeable frame system* - done recently, pretty simple: in any system you
  might need to style and theme your images; practical choices that are also
  visually fun.

**Figma-rich explorations**
- *MIDI / DAW* - lots of Figma of the initial design, plus a Figma Make thing
  made at the Figma event; the storyline could land with the physical-hardware
  companies he's interested in.
- *Figma collection research for multiplatform brands* - the new milestone
  (above).

**Could do - for fun, practice, or the record; not top priority**
- *PE self-paced* - quick overview of how it used to work vs going forward,
  some sketches; not much exciting UI yet, but he wants to build nice UI for it
  while waiting for a job.
- *Text-editor R&D* - not actively working on it; more of an exploration he
  could map out and explain.
- *PE syntax highlighting* - a good chance for a UI-centered, theming,
  design-system thought process; unsure how important.
- *Accessibility + SVG consulting* - lots in CodePens etc. to show; probably
  not top five.
- *Exploring the edges of ecommerce* - could be a case study (CodePens,
  Shopify's problems and limits); not a big priority.
- *LA agencies* - a video, journal-style, showing the wide variety of work.

**Smaller PE features - same boat, keep on the record**
- *PE calendar*, *early study hall*, *initial page modules* - smaller parts of
  a bigger storyline.

**Numbers only**
- *Open office hours* - no case study; maybe numbers (how many people have
  come, the range of who they are).

**Leave as they are**
- *AICAD talk* - never formalized into a video; it's there to show legitimate
  education people want him.
- *PXL* - could talk about building DreamWorks, but "I don't really know what
  the point is" - long ago.
- *Niagen* - hard to gather material; some interesting stories, but maybe
  better kept a little vague and important-sounding.
- *School Loop* - interesting stories behind it, unsure of its value.
- *ShoutQ* - nothing to show; no access to any of it anymore.
- *CCA* - one day, find some of the art and show it.
- *Fresh thoughts on the portfolio* - maybe doesn't need to be here; could
  stay as a milestone in thinking that just links to his journal entries about
  portfolios.

### Next

Prioritize - biggest gap filled for the least new work first.

## Parked elsewhere this session

- The layered experience chart is a site component (includes/layered-experience-chart.php)
  but mounted nowhere - headed for a case-study page about this site.
- Open question from Ivy: should grid view be the DEFAULT on large screens?
- CodeRabbit: this repo runs on the free review allowance - it needs connecting
  to the paid org in the dashboard (see the coderabbit skill).
