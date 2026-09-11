# Journal plan (2026-09-10)

The story problem: Derek has written for fifteen years - CSS-Tricks,
Substack, and a mountain of Perpetual Education material - but never on one
consistent site of his own. The journal starts now, and its first moves must
read as a seasoned writer consolidating, never a newcomer starting out.

## The settled arc (live, in date order)

Dates are full ISO days in content/journal.json, list sorts newest-first:

1. **A new direction for this website** (Sep 2) - what the site is now.
2. **A first look at my new portfolio structure** (Sep 9) - the video tour.
3. **Officially looking for a new long-term role** (Sep 10, top) - the
   announcement lands AFTER the work is shown, on purpose.

## Phase 1 - the foundation (blocked on Derek's link list)

- **The past-writing shelf on /journal itself** (Derek's sketch,
  2026-09-10): a section below the entry list - a row of cards linking out
  to the earlier writing (CSS-Tricks article, chosen PE pieces, Substack).
  The index page carries the receipts directly, so every visitor sees the
  history without opening anything. Design TBD with Derek (his sketch:
  three outlined cards; also note the entry-list itself has no graphics -
  see the emphasis-layer note in walk-notes.md).
- **"Where my writing has lived" entry** (dated August, sits at the
  bottom as the origin): the story version of the same material - "many
  websites, never one consistent home; the writing went elsewhere." May
  shrink or fold into the shelf once that exists - decide when both are
  drafted, don't build the overlap.
- **Date stagger** - done (2026-09-10).
- **The metadata gate** (also in command-center/next-up.md): share image
  (meta.jpg per entry - the by-presence contract exists, unused),
  descriptions, how URLs unfurl. Test by pasting entry URLs into a real
  message. Belongs to Phase 1 because links travel the moment Derek
  announces.

**Needs from Derek:** the link list - CSS-Tricks URL, which PE pieces make
the cut, whether Substack is named or just linked.

## Phase 2 - the cadence (one entry a week, video-first)

Ranked by job-hunt value and by raw material already in the repo:

1. **"What a machine actually sees in your resume PDF"** -
   pdf-text-layer-forensics.md is the draft. Screen-record the copy/paste
   failures. Recruiter-adjacent, shareable.
2. **"The theme system performing itself"** - video of character/mood/
   flavor. Follows naturally from the portfolio-structure entry.
3. **"My cover letters are a route on my website"** - letters.json, the
   print pipeline, the one-page assert.
4. **"Tokenizing spacing from a marked-up print"** - the pill-sizes ->
   dials story (resume-system-notes.md addendum has the material).
5. **The AI essays** - ai-thoughts.md seeds two: friction is load-bearing,
   and TDD with AI.

Per-entry checklist: journal.json meta (ISO date) + body file - share
image - `follows` link where it threads (wiring not built yet; see below) -
unlisted until ready.

## Open / deferred

- **`follows` connections** (designed 2026-09-10, unbuilt): one directional
  key in an entry's JSON naming the earlier entry; renders "Follows: ..."
  up top and "Continued in: ..." on the earlier entry by reverse lookup.
  Explicit authored connection - no tags/categories at this scale.
- **Emphasis layer for entry pages** - the theme axes barely show on prose
  pages; noted in walk-notes.md, needs its own design session.
