# PE LMS case study - where things stand (2026-09-22, for the next session)

Private working note. `case-studies-plan.md` (repo root) holds the decisions; this is the
hand-off: what exists, what's uncommitted, what's next. Delete when the study ships.

## The page

- Live: https://derekthomaswood.com/case-studies/pe-design-school - videos 1-4 only (commits 7516f15, b06ea2b).
- Local: `templates/case-studies/pe-design-school.php` has videos 1-7, plus Overview and Goals
  sections and `.working-notes` bullets under every video. All of that is gated `!IS_PRODUCTION`
  so a push can't publish it - the bullets are transcript key points, NOT copy.
- Data: `content/case-studies.json` - title "Putting the system in learning management system",
  teaser (Derek's line, LMS in an abbr), `facts: {}` (renders nothing until filled).
- Wrapper: `templates/pages/case-study.php`. Styles: `styles/modules/case-study.css`.
- Index: `/case-studies` (`templates/pages/case-studies.php`) - built, local only, no menu door.
- Slug still names the school - rename to something like `pe-lms` when the title is final,
  with a redirect from the old URL (Ivy has it).

## Uncommitted right now

index.php (index route) · templates/pages/case-studies.php · templates/pages/case-study.php
(teaser slot) · content/case-studies.json (title + teaser) · styles/modules/case-study.css
(teaser, working-notes, index styles, media wash) · styles/setup.css (`mark` style beside
::selection) · templates/case-studies/pe-design-school.php (videos 5-7, Overview/Goals,
bullets) · case-studies-plan.md (visuals position, working notes for #1) · this note and the
review note. Slice it when Derek says go; the body file can ship alone if Ivy needs 5-7.

## The videos

| # | Heading | Vimeo | Length |
|---|---|---|---|
| 1 | Avoiding blindly following the common pattern | 1228952549 | 1:18 |
| 2 | Exploring a common school day | 1228952586 | 3:18 |
| 3 | Subtle shifts between modes | 1228959941 | 3:09 |
| 4 | Exploring initial content types | 1228980047 | 1:54 |
| 5 | Enough to start building and testing | 1229033043 | 1:38 |
| 6 | Naming matters | 1229034463 | |
| 7 | Breaking things up into a journey of many parts | 1229039339 | |

Planned: (8) it's built - WordPress-as-CMS-with-ACF in one breath (the decision: don't rebuild
the CMS, spend the time on what nobody solved) - the CMS in sections, the same sections on the
front end, data vs style as two layers, aria and landmarks, what teachers and students actually
did and the section/hash-link questions it forced - basic modules only · (9) FigJam of the
interconnected resources, then some CMS · (10) views by progress and filter - FigJam only,
said plainly as design intent (not on the PE site yet; maybe built after, from this outline) ·
closing paragraph: ran the school six years, scale, iterated since, what he'd do differently.

Caption fixes still open: V1 1:16 "system's thinking" → "systems thinking"; V3 0:38 "sitting" →
"setting"; V6 1:53 "you're learned" → "you learn". Derek's words don't get re-recorded.

## Transcripts and review

- Transcripts 1-7 (VTT) and the flattened text are in this session's scratchpad only.
  Re-pull any time: fetch `https://player.vimeo.com/video/<id>` with a browser UA and
  `Referer: https://derekthomaswood.com/`, find the `captions.vimeo.com/...vtt` URL in the
  HTML, curl it. (Plain curl gets a 403.)
- The full storyline review (hiring-manager + editor pass after videos 1-7) is beside this
  note: `notes/case-study-pe-lms-review-2026-09-21.md`. Short version: the thinking is
  senior and confirmed; what's missing is the frame (facts strip, overview, one first-person
  line per video) and the evidence (beat 8). Not a flaw in the videos.
- `wp-based-lms` (perpetual-education on GitHub, Dec 2024-Jul 2025) is a clean WordPress
  rebuild of the model, probably the test area before changing the real one - section/module
  answered in code on day two, hash links, aria labels, goals module, the figure fields.
  Useful as "the model, rebuilt clean in 2024", not as the 2019 original.

## Next, in order

1. Write the frame before recording: facts strip values, the Overview (lead with the third
   paragraph - the LMS/continuous discovery/divergent thinking one; the 2019 bootcamp story
   is the blog post's opener), Goals as bullets. Then look at the page once.
2. Record 8 with the evidence in the footage: named people (with permission), what they did,
   a real CMS screen (the figure module is the one), the feature each observation forced.
3. Ivy, Marco, Karen's actual roles → `resume-exploration/source-materials/dereks-history.md`
   first, then the Team row.

Parked, in this order at the very end: custom thumbnails (needs Vimeo API + own play button -
probably not worth it), a chapter list under the overview.
