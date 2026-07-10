---
name: target-notes
description: >-
  Write or rebuild the per-company "target notes" for Derek's job sites - the
  short annotations in content/targets/<company>.json that render under the
  timeline when a visitor hits ?target=<company>. Use this whenever the task is
  matching a specific job posting to Derek's career milestones: "write the
  GoFundMe notes", "connect my work to this role", "rebuild the target notes for
  <company>", "here's the posting, tailor the timeline", or editing/critiquing
  any note in a targets/*.json file. Also use when a note reads like generic AI
  cover-letter filler and needs to be brought back to Derek's voice. Trigger even
  if the words "target note" aren't said - if there's a posting and milestones to
  connect, this is the skill.
---

# Target notes: matching a job posting to Derek's milestones

## What these are

Derek's personal site is a reverse-chronological timeline of his work. A
`?target=<company>` URL loads a per-company JSON file that injects two things: a
**hero** note at the top of the header, and a short **per-milestone note** under
some (not all) of the timeline cards. The notes are the "I read your posting and
thought about why *this* work is relevant to *you*" layer.

- File: `content/targets/<company>.json`
- Keys under `"milestones"` are milestone **slugs** (must match `content/milestones.json`)
- Values are the note text, rendered as **raw HTML** (`<strong>`, `<a>`, `<em>` all work)
- A slug with **no** entry gets **no** note - blanks are a feature, see rule 6
- The milestone card above the note already describes the project. The note does
  NOT re-describe it - it connects it.

The source of truth for what actually happened in each milestone is
`resume-exploration/source-materials/dereks-history.md` (and the descriptions in
`content/milestones.json`). Never invent a detail to make a match land. If the
proof isn't real, pick a different milestone or leave it blank.

## The one job of a note

Do the connective work **for the reader**. A busy hiring manager should not have
to figure out why Derek's experience is relevant - the note hands them the
connection in one breath, in Derek's voice, so it reads like a person who
actually thought about their team.

- Aim for: *"Huh - they really looked at what we do and thought about how their experience fits."*
- Avoid: the transactional "You want X, and I have X" template, and the polished-tragic
  cadence of AI marketing copy.

## Voice doctrine (this is most of the skill)

**1. Lead with Derek's thinking, not their requirement.** The note is "here's how
what I learned here connects," never "you want X, I have X." Foreground the
project or the insight and let the match surface. The requirement is the thing
the note quietly lands on, not the thing it opens by reciting.

**2. Growth mindset is the spine, not a bullet.** The posture is never "I'm the
expert on everything you need." It's "here's real, relevant experience, *and*
I'm clear I don't know *your* domain yet, and going deep on a new one at real
scale is exactly what excites me." Every job is about learning the specific
domain. A senior person who's still hungry to learn is safe to hire; one who acts
finished reads as a threat. Across the set, experience and appetite both have to
be present. If the whole column reads "I've already got all of this," it's wrong
even when every individual claim is true. This is the thread that most often goes
missing - check for it last, on the whole set, not note by note.

**3. Quote their language, but weave it in.** Borrowing a short verbatim fragment
(a few of their exact words) proves Derek read the posting and lets them
recognize their own requirement. But it's a seasoning, not a scaffold - drop it
mid- or end-sentence inside Derek's own thought. Never a `From the posting: "..."`
prefix, never air-quotes around a whole clause. Some notes carry no quote at all
and are better for it (see the belief variant).

**4. Move the company name around.** Only the first note or two may open with the
bold **Company** name. Everywhere else, weave it in mid- or end-sentence, so the
column doesn't drum "**Company** ... **Company** ... **Company** ..." down the
page. The name appears **once per note** (it's the reminder the note is aimed at
them) - just not always in front. Only the company name is bold; nothing else.

**5. Specificity is the proof.** Names, constraints, the hard part. "Redirected
the color system when the rebrand made everything feel like Home Depot on real
screens" beats "worked on branding." The concrete, un-fakeable detail is what
makes a recruiter believe it and want to hear the rest in an interview.

**6. Derek's voice, not AI voice.** Crisp, confident, conversational, no hedging.
Watch for the AI tells that make copy sound generic:
   - **No em dashes.** A single spaced hyphen ` - ` is Derek's pacing and is correct.
   - No too-neat triplets or rising-tricolon rhythm ("it sent help, it caught my
     friends, it lifted them up").
   - No writerly flourishes doing emotional work ("caught them mid-fall"), no
     stock closers ("that's why this sits at the top of my list").
   - No noun-list padding. Every clause carries a real claim.
   - Say it the way Derek would say it out loud. Plain and true beats polished.

**7. These are captions, not paragraphs.** One to three sentences. If it rambles,
cut it. Ask of each sentence: did this save the reader time or cost them time?

## Selection doctrine

**Match, don't stretch.** Only claim what the milestone genuinely demonstrates.
A forced match is worse than no note.

**Some get nothing.** Deliberate blanks (a pure-dev role, an off-topic entry, a
PE-internal task) make the matched notes hit harder and keep the page honest.
Three thoughtful notes beat fifteen mediocre ones. Note what maps; skip what
doesn't.

**One requirement per note.** Pick the single strongest thing that milestone
proves. One clean hit beats three fuzzy ones.

**Distribute across the posting.** Across the whole set, cover the *range* of
their bullets - don't prove the same one five times. Put the strongest, hardest-
to-fake evidence against their most important / most gatekept requirements.

**Keep one personal anchor personal.** One entry (usually the "why this company"
one) is the human hook - a real, specific reason this company matters to Derek.
Don't flatten it with a requirement match. Don't over-polish it either; that's
the note most likely to slip into AI-marketing cadence (see rule 6).

**A belief variant, sparingly.** One or two notes can skip the quote entirely and
lead with a principle, book, or tool Derek genuinely cares about: "One of my
favorite things to poke at is ...". Adds taste. Keep them rare so the set stays
scannable.

## The process for a new posting

1. **Extract the checklist.** Pull every requirement and responsibility bullet
   from the posting - both the responsibilities ("The Job") list and the
   qualifications ("You" / "What you'll bring") list - into a flat list. Fetch
   the live posting rather than working from memory.

2. **List the milestones.** From `content/milestones.json` (slug + title +
   description), cross-checked against
   `resume-exploration/source-materials/dereks-history.md` for real detail.

3. **Build a coverage grid.** For each milestone worth a note, mark the single
   posting bullet it proves best. This turns a vague "write notes" into a
   matching task and surfaces: which milestones are noteless, which high-value
   bullets aren't covered, which bullets are piling up. Present the grid before
   writing prose - it's the cheapest place to catch a bad plan.

   | Milestone | Best-matched posting bullet | Note type |
   |---|---|---|
   | <slug> | "<their fragment>" | quote / belief / personal / none |

4. **Write each note** in the voice doctrine above. Reflection-forward, quote
   woven, growth mindset present.

5. **Assign the specials:** the personal anchor, and 1-2 belief notes if a real
   principle fits.

6. **Scan the whole set as a column.** Any bullet proved 3+ times? Any gatekeeping
   requirement uncovered? Does the growth-mindset thread actually read across all
   of them, or does the set sound finished and expert-on-everything? Rebalance.

7. **Get Derek's verdict before writing JSON.** Show the grid and the drafts.
   Write only the notes he approves into `content/targets/<company>.json`. He is
   the source of truth on voice - do not batch-commit unapproved copy.

## Per-note quality test

- Does it lead with a thought, not a recited requirement?
- Is the proof a specific, un-fakeable detail from something Derek really did?
- Could a busy recruiter get the connection in about three seconds?
- Is it true, not stretched? (If not: different milestone, or blank.)
- Does the *set* carry both experience and appetite-to-learn, or does it sound finished?
- Any AI tells (em dash, neat triplet, flourish, stock closer)? Cut them.

## Working with Derek on these

- He edits copy in place between turns and prefers refinement over wholesale
  rewrites - tweak the existing draft, don't re-guess a fresh version, unless he
  says it doesn't land at all.
- Fix obvious typos freely; rewording is his call.
- One posting at a time, and let him name which notes are keepers.

## Calibration example

The approved GoFundMe set (once locked) lives in
`content/targets/gofundme.json` and is the reference for tone. Read it before
writing notes for a new company - it's the clearest picture of "right" for this
site. The recipe this skill grew from is `target-notes-recipe.md` in the repo
root (kept as historical origin; this SKILL.md is now canonical).
