# Case studies plan

Started 2026-09-15 from Derek's braindump; direction updated 2026-09-17.

Rule of thumb Derek noted: you're only really supposed to have 4-5 case studies. Everything else is either a smaller feature study or a journal piece. The top 4-5 also **live on the homepage** as case-study-like conversations (per the "Homepage direction" section in `next-up.md`, 2026-09-17) — simple, sweet, not-much-to-snag-on entries a visitor sees before opting in to the full history grid.

## Page design direction (2026-09-17)

**Goal (Derek, 2026-09-18):** the five have to look official, professional, and in-depth, even at a quick glance over the list. **Check:** a glance at the list alone reads as serious work, before anyone opens one. How they are reached is open - either the home filter starts on just these, or a top-level case-studies page shows only them.

- **Black and white**, process-forward, not visual flourish. Graphics are about process and core design, not high-fidelity UI screens of other people's products.
- **Purpose:** work out thought process over visual style. The point is that the case studies show *how Derek thinks*, not that he can polish someone else's pretty screens.
- **A facts strip at the top of each one** (Derek's note, 2026-09-18, from two reference portfolios): role, timeline, platform, company, team, year - plain labeled facts before any prose. Facts, not sentences.
- **Consistent chrome across every case study** — the visual system's craft carries through the frame, the type, the pace. No project-brand-color blowouts.
- **This changes the build-order criterion:** pick the case studies with the *clearest process story*, not the ones with the prettiest artifacts.

## What the visuals are (working position, 2026-09-21 - still talking about it)

Came up while recording the PE study: the fear of having to show final high-fidelity work, and the pile of options (idealized mockups / low-fi real things / just show the CMS). Where we landed for now:

- **The edge is that it's real.** Most portfolios show idealized finals because a Figma file is all that exists - the thing never ran. Idealized mockups would trade away the one thing those portfolios can't match, and compete on their turf.
- **The sanity rule: every visual is one of two things.** (1) A *thinking diagram* - FigJam boxes, fast and rough, consistent across every study. (2) *The real thing, as it is* - a CMS screen with real fields and real data, a real page from the live product. **Nothing in between**: no redrawn screens, no cleaned-up mockups, no "what it would look like if." That middle zone is where the options (and the weeks) go.
- **Real is the fidelity, and the credibility.** A working admin screen that looks like a working admin screen is evidence; what's being judged is whether the data model makes sense. Showing one record on the page, then its fields in the CMS, then its contextual data is something a mockup cannot do.
- **The public product appears once, at the end, as-is** - the "it's real" beat. Its visual design isn't on trial; the system is the subject.
- **If a real screen is too rough to show:** the honest fix is improving the real thing (the work isn't wasted - it ships). Not worth fixing = not worth faking.
- **Video instead of static graphics is the format on purpose** - what's being sold is the thinking. The static layer (tagline, facts strip, run times, a sentence or two per section) carries "ambitious, owned, real" for the ten-second reader; the videos are the proof for whoever stays.

Open: whether any study ever earns an exception (a study with nothing real left to show - the BetterLife platform is gone).

## The main five (blocked out 2026-09-18)

**What the set has to prove.** The target seat is "in charge of how it all works at the UX level, leading people in their respective scopes" (principal / player-coach, not ops head - see `job-search/read-this-first.md`). So across the five: holds a whole system, leads a team through it, stewards a design system across teams, and does it in someone else's domain, not only his own. Thread 3 (the teacher posture - paying attention, tending conditions) shows through what got *noticed and built around people*, never claimed as an adjective.

Raw material: `job-search/about-derek/his-own-words/stories.md` (verbatim Derek - pull, don't reword), `job-search/about-derek/proof-points.md`, `resume-exploration/positioning-points.md`. `resume-exploration/source-materials/dereks-story-narrative.md` is facts-only: its "learning experience designer" conclusion is Brilliant-era and makes education the whole identity. The current stance (Derek, 2026-09-18) is scope-dependent: he integrates, and education is one of the tools - bringing the person along, leveling them up. If the seat is advocate and a course is the right move, he designs the course. The miscast is only when curriculum is the entire job.

1. [ ] **Designing a digital product design school** - the umbrella. Proves "how it all works." Framed as product design of a learning system (the data model + the observe-and-redesign loops), with the curriculum as one tool inside it, not the identity. The enrollment funnel (application-as-workbook, no-pressure close) folds in as a section. Source: stories.md, the PE funnel entry, Parts 1-4. Open fork Derek named in Part 4: (a) the organic observe-and-iterate story vs (b) the final-state system outline.
   - **Working notes for #1 (2026-09-21, from the recording sessions):**
     - *What this study is:* the design of the LMS - the platform layer ("anyone could teach anything on this"), not the curriculum and not the final website. The curriculum ("billed as a coding bootcamp, secretly a product design school") is another layer of the onion - its own study, a satellite, or the timeline card. Title and slug still open; they should name the platform.
     - *The arc so far:* (1) don't copy the default LMS → (2) who's really involved + the shared school day → (3) what each part of the day is doing, phases at every scale → (4) classroom modes become content types → (5) we had enough to start building - users, roles, permissions, routing for courses / modules / workshops are all common patterns, so that went in quickly (one breath, no stack talk) - and then the real work: using it ourselves as teachers, and testing with other teachers, who all author differently (video as they go / detailed outlines / Drive or markdown workflows they already like / a little at a time). Squares with video 1: the common pattern is fine where the problem really is common - the point was never "reinvent everything," it was knowing which parts deserve the thinking → (6) page section types, fast FigJam map + ONE worked example end to end (a key concept: page → CMS fields → contextual data; the goals module gets a named moment) → (7) stand-alone related content types → (8) how it all interconnects - many views of the same content by progress and filter (the payoff: constraints became capabilities). Then one short closing beat: built it, it ran the school, iterated ever since - with doors to the satellites.
     - *The scope tests:* is it the design of the system? → here. Is it a story about one part, or about the system meeting real people? → its own page, linked from here.
     - *Depth:* as deep as the decision, never the implementation. The data model is design (show the CMS fields); the stack is not (one Platform row in the facts strip; the rest belongs to #5).
     - *Derek's method, worth saying out loud somewhere:* build it and put it in front of users right away, the whole time - continuous discovery as a working habit from 2019, before the book that named it for most teams (Teresa Torres, *Continuous Discovery Habits*, 2021). Careful with the claim: the phrase and her writing predate the book, so it's "before the book," never "before the idea." Natural homes: the closing beat, or section 5.
     - *The "we":* Derek designed it; Ivy, Marco, Karen (not official employees) and the students were a big part of using and testing it. Keep "we" - say who it is once. Their actual roles still need recording in `resume-exploration/source-materials/dereks-history.md`.
     - *Facts strip direction:* no "Team: just me" row - Role leads with the design work (not "founder"), Scale (hundreds of students, concurrent cohorts, four years), Timeline framed as in production 2019-2026 (recent work is on it), Owned, Status.
2. [ ] **QuickBooks for selling your house** (List at Ease) - the leadership proof. Discovery, building the team, teaching it TDD, whiteboard to product. External domain. Needs a Derek telling - no process story in his own words exists yet.
3. [ ] **Product design + real estate mastermind** (BetterLife) - unifying scattered third-party systems, reworking the color system when the brand broke on real screens. The steward making other people's work land. Needs a Derek telling.
4. [ ] **A design-systems study** (new 2026-09-18 - the gap in the first plan). Team of teams, contextual tokens ("fifty, not hundreds"), busywork vs what matters. Proof: School Loop, Disney three-themes, pssst, this site as the live demo. Absorbs the old "themeable frame system" and "pssst CSS" items. Source: stories.md, the LegalZoom and Coinbase entries.
5. [ ] **PE's browser editor** - the design-engineer proof, deliberately last and kept short so it doesn't tip into the code-and-UI story Wesley said is over-told. Needs a Derek telling.

**Open:** ChromaDex was ruled out 2026-09-15 for "nothing to show" - two days before the black-and-white process-forward format, which removes that objection ("the constraint became the product," the wall chart, the doctors). Derek's call whether it challenges #3.

## Feature-sized studies (satellites to the PE umbrella)

Simpler than a full case study - feature studies about *why* the thing was done. **Expect this list to grow as the umbrella gets explored** (Derek, 2026-09-18) - the umbrella is the mine; whatever is too good for a paragraph but too small for a case study lands here.

- [ ] **Study hall experiments** (stories.md Part 3 - the outline-for-conversation idea)
- [ ] **Calendar system** (Part 3 - the magnet, planning around real lives)
- [ ] **Fading lessons** (Part 3 - "almost as a joke")
- [ ] **Building PE's internal page module system** (Parts 3-4; Derek flagged it as its own study)
- [ ] **Key concepts / language features** - filtering the course through many lenses (Part 4)
- [ ] **The application as an educational workbook** (Parts 1-2) - if it outgrows its section in the umbrella
- [ ] **Transitioning PE to self-paced** - demoted from the main list; only if the *decision process* is clear enough to show

## Might be case studies if the filter opens up

- [ ] **Accessibility + SVG animation consulting** - not sure anyone cares, but a nice collection of CodePens could be interesting.
- [ ] **A new take on DAW MIDI sequencing** - could actually be done and be pretty impressive.
- [ ] **A new take on a text editor (R&D)** - less important, further down the list.

## Explicitly NOT case studies

Derek's read on these:

- **World AI Day** — not a ton to show. The existing line about leading conference research strategy does its job.
- **SeniorPixel** — doesn't really need anything.
- **Niagen** — not sure there's anything worth explaining; can't really go back and do anything.

## Journal pieces (not case studies — smaller LinkedIn-shareable things)

Kept here for cross-reference; main planning lives in [notes/journal-ideas.md](./notes/journal-ideas.md) and [notes/journal-plan.md](./notes/journal-plan.md).

- **New direction for this website** — little talk about it.
- **First look at the new portfolio structure** — already has a video.
- **Fresh thoughts on a portfolio** — could link to the journal piece.
- **Officially looking for the long-term role** — status unclear (does this one exist?).
- **How to think about carrying plates and how that has to do with this stuff** — coming.
