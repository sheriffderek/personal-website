# Resume system - raw notes for a journal entry

Working notes from the build session (2026-09-06). Not public, not polished -
the journal entry gets written FROM this, in Derek's voice, later. The angle
that makes it an article: **the resume is a web page, and the PDF is just
that page printed.** One source of truth, three role-angled tellings, and a
verified one-page export - no Figma-export treadmill, no copy drift.

## The shape

- One data file (`content/resume.json`) holds the facts once: header,
  experience entries, speaking, education. Three "lanes" (Product Designer /
  Design Engineer / Advocate & Educator) each carry ONLY what differs: a
  role line, an intro, a skills order. Fix a fact once, all three tellings
  and all three PDFs inherit it. (This mirrors the private lane spec in the
  job-search repo - the site renders it, never forks it.)
- Routes: `/resume` (index) + `/resume/<lane>`. Same template, lane-fed.
- The page IS the artifact. `derekthomaswood.com/resume/design-engineer` is
  the thing a recruiter reads AND the file they get - printed straight from
  the route with headless Chrome.

## Layout ideas worth telling

- **The parallel timeline.** Full-time roles down the left (3/5), the
  contract work that ran alongside them down the right (2/5), a time axis
  rising between them. A resume that shows *when work overlapped* instead of
  flattening it into one list.
- **Left-column-first thinking.** The left column stacks naturally - its
  rhythm is never disturbed by the right. The right pieces are
  absolutely-positioned grid items: a positioned grid child keeps its GRID
  AREA as its containing block but stops sizing the track. So "contracts,
  centered on the Perpetual Education row" is literally `grid-row: 2 / 3;
  top: 50%; translateY(-50%)` - pure constraints, zero JS, and it re-centers
  itself when the font, character, or viewport changes.
  - Gotcha recorded in the CSS: on absolutely-positioned grid items an
    *auto* end line means "to the edge of the grid," not "span 1" - the
    explicit `/ 3` end lines are load-bearing.
- **Sections are the grid pieces.** Entries are plain `<li>`s stacking
  inside semantic lists; only whole sections get placed. Simple to reason
  about, and the markup stays honest.
- **The theme system rides along.** All type via the site's voices
  (stamp-voice eyebrows, strong-voice titles), all color via tokens (the
  intro speaks in `--accent` - whatever the current mood/flavor makes that).
  Switch the character and the resume re-typesets; the centering math never
  breaks because none of it is stored numbers.

## Semantics / ATS (the invisible layer)

- Group headings exist for parsers and screen readers, hidden visually:
  "Experience", "Contract experience", "Earlier experience" - the exact
  keyword ATS segmenters bucket on. On screen: the standard clip recipe
  (`.reader-only`). In print: swapped to in-flow, zero-height, transparent
  ink - because the 1px clip box never makes it into Chrome's PDF text
  layer at all.
- Source order = narrow reading order = parse order: current role,
  contracts, earlier roles, speaking/education. No `order:` hacks - the DOM
  is just right, and screen-reader order matches visual order everywhere.
- Contracts are marked "· contract" in the eyebrow, so they file as work
  even when a parser reads them out of visual position.

## The PDF pipeline (the hard-won part)

Ground truth command (not the browser's print preview):

    chrome --headless --print-to-pdf=out.pdf --no-pdf-header-footer <route>

Measured facts (all verified empirically, recorded in resume.css):

- Print media queries evaluate at ~741px viewport width regardless of
  @page size - so screen breakpoints are useless in print; the print layout
  is fully declared inside `@media print` with fixed dimensions.
- Letter is 816x1056 CSS px; at 0.4in @page margins the content box is
  ~739px.
- Chrome never auto-shrinks: overflow = extra pages, so **page count is the
  fit signal** (`pdfinfo`).
- **Variable fonts embed as Type 3 glyph programs** with custom encoding -
  that's what breaks copy/paste ("te a ch") and raw extraction
  ("Iteachproductdesign"). Fix: static cuts of the same faces, self-hosted,
  registered under distinct "* Print" family names, with `@media print`
  re-pointing the font tokens. Static cuts embed as CID TrueType and
  extract clean. (Diagnosed via `pdffonts` - zero Type 3 = pass.)
- Token gotcha: the font-token override must land at `:root` - a var()
  inside a custom property resolves on the element that DEFINES it, so a
  lower-scope override never reaches the voice tokens.
- **Paint order is extraction order.** Positioned elements paint after
  normal flow, which shoved the absolute contracts block to the end of the
  PDF text stream (breaking copy/paste order). Fix: every sibling section
  goes `position: relative` (no offsets, zero visual change) so paint order
  = DOM order again.
- The honest probe for copy/paste order is PDFKit (Preview's engine), not
  pdftotext - geometric extractors mask paint-order problems. One-liner
  osascript check: landmark offsets must be strictly increasing.
- Fit tuning, in order of what it bought: kill the site's main padding in
  print (the 80px page-top was riding onto the sheet), print line-height
  1.4 (screen reading air is a web convention; paper sets solid), tighter
  paper rhythm (1.25em), 13px root (~9.75pt body - the readable floor,
  never below).
- The out-of-flow contracts can't push the speaking block down naturally,
  so the section break below them is a deliberate margin bias (a centered
  box shifts by half its top margin).

## Verification loop (per lane, after any print change)

1. `pdfinfo` -> exactly 1 page at 612x792.
2. `pdffonts` -> zero Type 3.
3. PDFKit landmark offsets strictly increasing (work sections before
   Speaking & teaching).
4. `pdftotext` -> contact present, no build hash, "Experience" before
   PERPETUAL EDUCATION.
5. Eyeball the render (the text checks miss visual overlaps).

## Companion evidence

`pdf-text-layer-forensics.md` (repo root) holds the before/after receipts
for the article: the four real extraction specimens (Figma interleaved,
Figma fused-words, variable-font shattered, fixed pipeline), the six
transferable rules, the full verification loop, and the ATS research
summary with sources. The journal entry's "here's what the old PDF looked
like to a machine" section quotes from there.

## Open / undecided

- ~~The accent intro ("green like that")~~ - RESOLVED (2026-09-08):
  screen stays theme-alive (the accent is the live mood/flavor's), print
  pins its own dial (--resume-print-accent, vivid royal blue #2a4abf) -
  chosen by the grayscale test: dark enough that B&W printing degrades
  the intro to near-body dark instead of the faintest text on the sheet. A
  per-target brand color = re-dialing that one token (target dressing,
  url-state-plan.md).
- ~~Advocate lane's `speaking_first` flag~~ - RESOLVED by removal
  (2026-09-07): Derek saw a built speaking-first variant and reverted it.
  The rule now lives on the article element: one layout for all lanes,
  no per-lane section reordering, ever. Bonus discovery from the detour:
  Chrome does NOT honor CSS anchor positioning in print output (the
  @supports check passes; the positions silently fall back) - recorded
  in the print block.
- Print fonts double as the start of the self-host-before-launch plan that
  was already queued for the whole site.
- ~~Cover letter PDFs~~ - BUILT (2026-09-08): /resume/<lane>/cover-letter,
  one per lane - same identity header (extracted to
  includes/resume-header.php), same print pipeline, spine + arrow in the
  same spot, letter prose in the left column. Content is
  content/letters.json (placeholder bodies seeded from
  job-search/briefing/cover-letter-*.md; Derek's approved revisions land
  letter by letter). Bespoke per-target letters ride ?target= via the
  targets map (structure only, none authored). Exports to
  job-search/briefing/ deliberately NOT wired yet - waits for approved
  copy; the fit-check script grows letter routes then.
- Review flag (2026-09-08, unresolved): the print block declares
  `--resume-space-section: 22px`, which nothing consumes - it looks like
  the section->lead token rename missed the print block. Left as-is
  because the approved print checks were made with `--resume-space-lead`
  falling through to its screen value; re-valuing lead to 22px would
  change the sheet. Needs Derek's eye, then either rename or delete.

## Addendum (2026-09-07) - the system as it settled

Several details above evolved during the content-finalization day; the
code is the truth, but for the article's sake:

- **firm-voice got minted** (body family, text size, weight 600) - entry
  titles were too big in strong-voice's display font. "A calm voice,
  just bolder" became a real voice in the token system.
- **The spacing system became five dials + two rhythm tokens**: entry
  (teal, between jobs - every column), lead (purple, section leads),
  break (the closing group's larger space), all rem on screen / px in
  print; cluster + eyebrow stay em and ride the type. Derek's marked-up
  print with colored pills per space size WAS the spec - decode the
  pills, name the tokens.
- **No container gaps at the page level** - each piece states its own
  space above; text rhythm is em sibling margins (the house model);
  only entry lists use gap. And spacing went through gap-everything and
  back - the lesson: mechanisms follow the house doctrine, tokens carry
  the design.
- **The break is COMPUTED, not magic**: the closing group anchors to the
  contracts block's real bottom edge via CSS anchor positioning
  (anchor-name / anchor()), one break below - correct per lane whatever
  their columns' heights. Centering-plus-bias remains as the @supports
  fallback; headless Chrome (the whole print pipeline) always takes the
  anchored path.
- **Hidden ATS headings, final form**: a real 12px in-flow band at each
  section's top, its height paid for by the space above - the overlay
  and transform tricks both corrupted extraction (interleaving and
  paint-order respectively; the transform one because a transform
  creates a stacking context, which paints late - stream order again).
- **Lane-variant entries exist now** (entry_overrides per lane, whole
  fields only): PE's body differs per lane - the product lane sells
  outcomes, the DE lane sells the platform build, the advocate lane
  leans on its intro instead.
- **The one-page guarantee is a hook**: every edit to resume.json
  auto-exports all three lanes, asserts one page (pdfinfo), and
  publishes the verified PDFs to ~/Desktop/resume-exports on green -
  checking and exporting are one gesture, and a failing lane can't
  overwrite its last good export. bin/resume-fit-check.sh +
  .claude/settings.json PostToolUse hook.
- **Estimates lie; measure**: the last fit crisis was "1-2 lines over"
  by line-counting and actually 81pt (7-8 lines) by pdf word-coordinate
  measurement (pdftotext -bbox). The whole day's motto.
- The full application kit (all three lane PDFs in job-search/briefing/)
  is site-generated now - the Figma-export era is over.
