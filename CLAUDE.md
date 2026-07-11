# Derek Thomas Wood - Personal Website

## What this is

derekthomaswood.com is Derek's personal "hire me" site for full-time/salary roles. It's a reverse-chronological timeline of his work, visual-first, with short video walkthroughs instead of formal case studies.

## Related projects

- **`resume-exploration/`** - master work history, job-specific application materials, source-of-truth dates. The canonical history lives at `resume-exploration/source-materials/dereks-history.md`.
- **`sheriffderek-consulting-website/`** - the consulting/fractional "hire my services" site. Different audience, different framing.
- **`command-center/`** - brand strategy, project status, career context. Brand split documented in `command-center/brand-strategy.md`.

The long-term source of truth would be in the resume-exploration project (or in it's own history) - so, anytime we're confirming something - we can look there -- and also, if we unearth any new info - we can save it there too. Got it?

## Content model

Three layers, top to bottom:

1. **`resume-exploration/source-materials/dereks-history.md`** - raw truth, real dates, internal notes. Never public.
2. **`timeline-content-plan.md`** (this repo) - which entries surface, how they're framed, format choices, per-job angles (e.g. `?target=gofundme`).
3. **The public site** - polished, dates may be adjusted for presentation, only what serves the visitor.

Always update layer 1 first. Layers 2 and 3 pull from it.

**Private `backstory` field (working layer).** Each milestone in `content/milestones.json` may carry a `backstory` key - loose, unpolished "what really happened" notes synthesized from the resume-exploration source docs, used for *our* thinking (especially the target-notes win-gate). It is **never client-facing**: the template ignores unknown keys, AND `content/*.json` is walled off from direct HTTP fetch in both `.htaccess` and `.claude/router.php` - **do not remove that block; it is the privacy guarantee.** The field is derived and lossy - resume-exploration stays canonical. Where the source is thin or conflicts with the public card, the backstory says so (the gap is the useful signal). Rolled out on a few milestones first (`list-at-ease`, `better-life`, `aicad-2024`) as a pilot.

The per-company `?target=` notes (`content/targets/<company>/target.json`) have their own skill: `.claude/skills/target-notes/`. It auto-triggers when you're matching a posting to milestones; it's the source of truth for voice, the growth-mindset spine, and the coverage-grid process. (`target-notes-recipe.md` in the root is its historical origin, superseded by the skill.)

## Current status

Round 1 target: GoFundMe Senior Product Designer application. Five entries needed. See `timeline-content-plan.md` for the full plan and `SESSION-HANDOFF.md` for latest state.

## Design notes

- 16:9 poster cards for each entry
- Entries can be: static image, slider (3-5 slides), or short local video (under 1 min)
- `?target=companyname` inserts tailored paragraphs connecting work to that company's needs, and auto-links any application PDFs dropped in `content/targets/<company>/` (`cover-letter.pdf` / `resume.pdf` / `questions.pdf` - fixed names, presence = rendered; details in the target-notes skill)
- Weighted timeline (filter slider reveals more/fewer entries) and theme switcher (type patterns, font pairs, color) - both built; see the Timeline weights and Theme system sections below
- Videos are local files, not YouTube/Vimeo embeds
- Video behavior: muted, playsinline, autoplay on scroll into view (IntersectionObserver), loop. Always have a poster frame fallback. Use preload="none" on off-screen videos.

## Naming conventions

Two casing rules coexist by design — they mark the boundary between layers:

- **JSON keys** (content data): `snake_case` or single-word — e.g. `link_label`, `weight`, `tags`. This is the data layer. Editors work in the JSON, so keys are typed and read as data fields.
- **HTML attributes, CSS classes, file names, folder names**: `kebab-case` — e.g. `data-set-sound`, `.mode-switcher`, `settings/mode-switcher.php`. This is the DOM/asset layer.
- **PHP functions**: `snake_case` (`load_json`, `partial`, `square_variant`). Matches PHP-native style.
- **JS variables/functions**: `camelCase` (`ensureContext`, `applyFilter`, `hookSettleSound`). Standard JS.
- **Milestone slugs**: `kebab-case`, sometimes year-prefixed (`2026-job-search`, `pe-founded`, `nouveau-studios`). Used as JSON keys AND folder names AND URL params — kebab wins because the DOM/URL context dominates.

Rule of thumb: if it's DATA, snake or single word. If it's DOM or FILESYSTEM, kebab.

## Code voice

**The goal is never golf.** Optimize for the most readable, clear story for people of all skill levels — a PE student should be able to read this codebase end to end. Brevity is only good when it makes the story clearer.

Code should tell a real story. Pick constructs for a reason, not because they're current idiom — no arrow functions "just because," no clever one-liners that hide the plot. If something has a nameable purpose, name it (`quote_safe`, `square_variant`, `calm-voice`). Same doctrine as the output policy: every choice should be explainable at the place it's used.

This cuts against fashion in both directions. `var` is fine and sometimes *better* — it honestly says "a variable," while `const` on a mutating array tells a false story ("constant" on a thing that changes). `rel=` is storytelling itself — it names the relationship between documents. Don't let linter dogma overrule the narrative.

For CSS-specific conventions (no BEM, no underscores/double-dashes, nesting scoped to parent class), see `../CLAUDE.md` at the projects root.

## Writing rules

**Goal.** A recruiter scrolling the timeline thinks "wow — breadth + depth + 15 years of doing this, let's talk." Each card is a recruiter scan; the "Read more" is the dig-deeper. Derek is targeting **Head of Design** and **Senior Product Designer** roles where he'll lead and educate teams.

### Voice
- No em dashes. A single spaced hyphen (` - `) for pacing IS Derek's voice and is allowed. Otherwise use periods, commas, or restructure.
- Parentheses for mid-sentence asides.
- High-level, conversational. Not formal case study language.

### The bar

The home page-header intro (`templates/pages/home.php`, `.page-header`) is the signed-off calibration example. Hold every other section to its standard:
- Every list item carries a claim. Pairs like "design systems and cross-team collaboration" encode how things connect. They are not padding.
- Breadth framed as method, not menu. "That's how I attack problems," never "I can do anything."
- Each paragraph does one job (what / where and how long). Three jobs in a paragraph is the ceiling.
- Don't repeat what the timeline already tells. The header owns certain phrases ("do their best work," "folding it back into the curriculum") - entries must not reuse them.

### Entry titles

The title needs to help a recruiter envision the project and what Derek did. Short, but enough to show seniority and breadth. The test: does someone read this and immediately picture the work?

`[Senior Title] for [product type]` is a good default, but break it when a more specific framing does the job better. The energy and clarity matter more than the formula.

- **Good defaults**: "Senior Front-End Developer for animation studios," "Founding Product Designer for a longevity science startup"
- **Good breaks**: "Product design: real estate mastermind social network + habit tracking R&D," "Ready to join my next team"
- **Bad**: company name as the title, "things I did at X," vague labels like "Early freelancing" or "Mentoring as a job"

### Description (the intro)

Assume they never hit "Read more." The description has to say everything important on its own. It's the whole pitch for this entry.

Tell a small story, not a resume bullet list. "Did X. Built Y. Mentored Z." reads like Tarzan-speak. Instead:

1. **What was the situation?** Set the context. What was the company trying to do, what was hard about it?
2. **What did I own?** Make Derek's role clear and distinct.
3. **What made this interesting or hard?** Lean into the specifics. Translating science. Building an app for the first time. Managing volunteers with no budget.

Lead with the problem or situation, not industry context or ecosystem news. Don't frame early work as "I was learning." Frame it as what you delivered.

For projects that didn't ship or platforms that are gone: state it plainly, don't be defensive. "The platform is gone now, but the Figma files are a mile deep." "Still in progress." Move on.

### Details (the "Read more")

They asked for it, so give them the story. This can be longer. More human, more specific. The laundromat. The Home Depot colors. The co-chair who didn't show up (framed gracefully). These are what make someone want to meet you.

**Goal of details**: after reading, a recruiter thinks "I want to hear this person talk about this in an interview."

Can include a tag list at the end for specific buzzwords if it helps (design systems, user testing, Vue, accessibility, etc.), but the narrative comes first.

### Boxes to tick

Work these in naturally across the full set of entries, don't sprinkle them into every one:
- triad collaboration (designer + PM + engineering)
- end-to-end ownership (vision through strategy through execution)
- consumer-facing scale, web + native
- design systems stewardship
- cross-functional alignment (research, marketing, customer care)
- mentorship + raising craft bar
- AI tools fluency
- shipping in fast-paced / ambiguous environments

Buzzwords are fine when earned. Concrete > generic. "Hired my replacement before leaving" beats "demonstrated leadership."

## Video / carousel behavior (locked-in rules)

Code lives in `includes/footer.php`, `templates/milestone.php`, and the shared per-item renderer `includes/posters/media-item.php`.

**Three media shapes, all built on the themable poster-shapes cover** (there is no `format` field; it was retired). The poster-shapes is ALWAYS the cover — slides and videos are *additional*, never a replacement for it:
- **no poster** → text-only card.
- **poster only** → the poster-shapes alone (a card wants a visual but has no slides/videos yet). Opt in with `"poster": true`.
- **poster + media** → the poster-shapes cover slide first, then every real slide/video, in a carousel.

Shape is derived, never hand-set: a card with any `real_media_items()` (drops `/content/placeholder/`, see `render.php`) is **poster + media**; else `"poster": true` gives **poster only**; else text-only. So a single slide/video is still **poster + media** — the poster, then that one item (never the item by itself). One partial (`posters/media-item`) renders every item (`photo`/`loop`/`play`/`vimeo`). Placeholder entries (`["/content/placeholder/…"]`) are the "no real media yet" marker and stay text-only until real media replaces them.

**Locked — do not add a "bare media" shape.** There is no path that renders a slide or video without the poster-shapes cover in front of it. This was built the wrong way once (single media rendered as the item alone); if you find yourself "simplifying" a single-media card to skip the poster, stop — poster-first is the whole rule. The one knob is `"poster": true` to give a *media-less* card the poster (shape 2); media never removes the poster.

**Item types** (`type` on each media object; becomes `data-type` on the slide):
- `photo` — still image (`<picture>`, responsive)
- `loop` — muted video, no controls, ambient motion; autoplays on scroll into view (and on carousel settle/hover)
- `play` — video with our own custom controls (below), user-initiated; never autoplays
- `vimeo` — responsive `<iframe>` embed (`src` is the Vimeo id)

**Media file convention.** One folder per milestone (`content/milestones/<slug>/`), files named `<order>-<type>-<size>[-<slug>].<ext>` — the folder listing IS the storyboard, top to bottom, and every file states what it is.
- `<order>` — storyboard position (`01`, `02`, `03`). Load-bearing and must be visible. Don't split assets into per-type subfolders — that scatters the sequence and hides what plays first.
- `<type>` — `photo` | `loop` | `play`. This is the "what does it do" token: `loop` autoplays muted on scroll, `play` waits for a click and carries audio + controls. It must agree with the item's `"type"` in the JSON, but the JSON is the source of truth (the template keys off `data-type`, not the path).
- `<size>` — `wide` | `square`. **Both variants carry it**, so neither is an implicit "default" you have to remember (that ambiguity is exactly how a set of files ended up contradicting itself). The JSON always points at the **`-wide`** file; `square_variant($src)` (`includes/render.php`) swaps the size token and falls back to the wide when no square cut exists, so a clip without a phone crop just serves at every width.
- `<slug>` — optional, only when it earns its place (`01-play-wide-intro.mp4`); omit it when the type already says everything (`03-loop-wide.mp4`). Never a milestone prefix — the folder already scopes them.
- `2026-job-search/` is the reference: `01-play-wide-intro.mp4` + `01-play-square-intro.mp4`, `02-photo-wide.jpg` + `02-photo-square.jpg`, `03-loop-wide.mp4` + `03-loop-square.mp4`.
- **Freeze-frame poster (optional, videos only):** drop an image beside the video with the *same basename* — `01-play-wide-intro.mp4` → `01-play-wide-intro.jpg` (`jpg`/`jpeg`/`png`/`webp`). `poster_variant($src)` finds it, so each cut gets its matching still and the poster rides the wide/square swap for free. No still = no `poster` attribute, no fallback needed. Only add one where frame zero doesn't do the job — a `play` whose opening frame doesn't say "press me" (a `play` never autoplays, so that frame is the whole invitation), or a `loop` with a bad first frame (that frame is what a `prefers-reduced-motion` visitor sees, since they never get the motion). Otherwise skip it; the browser paints frame zero on its own. Grab one with `ffmpeg -ss 00:00:03 -i in.mp4 -frames:v 1 -q:v 2 out.jpg`.
- **Every video MUST be fast-start (`moov` atom at the front) or Safari/iOS shows a blank frame** (Chrome tolerates moov-at-end, Safari does not). In **HandBrake, tick "Web Optimized"** (Summary tab) — that IS the fast-start flag, and it's off by default. To fix an existing file losslessly: `ffmpeg -i in.mp4 -c copy -movflags +faststart out.mp4`. Verify `moov` precedes `mdat`. A non-fast-start video looks fine locally in Chrome and silently fails on the phones recruiters use. Fast-start also lets a long `play` video *stream* on press (with `preload="metadata"`) instead of downloading first.
- Media URLs are cache-busted by mtime via `asset()` in `includes/posters/media-item.php` (same as CSS/JS), so re-encoding/replacing a file forces a fresh fetch. Without it a browser can keep serving a stale copy (e.g. a pre-fast-start video Safari already failed on).

**Playback rules:**
- Only one video plays across the entire page at a time (a shared `current`).
- **A pressed `play` outranks every loop.** A `loop` is ambient motion nobody asked for, a `play` is something the visitor chose. Loops autoplay through `autoplay()`, which stands down while a `play` is running; the custom player's button calls `play()` directly and always wins. Starting a second `play` still stops the first (shared `current`).
- On carousel `settle` (slide animation finishes): pause everything in that carousel, `play` included - swiping away is a direct gesture at that video. If the arriving slide is a `loop`, autoplay it.
- **Scrolling is not a gesture at the video.** A figure going fully off-screen pauses that carousel's `loop`s only (`pauseLoops()`). A `play` keeps talking, so a visitor can scroll the timeline while listening - they stop it, scroll back to it, or press a different one.

**Now-playing pill (designed 2026-07-09, deferred - build it if orphaned audio actually annoys someone).** The shape, so it isn't re-derived: two circles the same size as the `.toolbox-trigger` glyphs, fused into one pill, sitting to the left of them. Left circle is the playing video's `poster_variant()` still (says *which* video is talking, and clicking it scrolls the milestone back into view); right circle pauses. Unlike its neighbors it acts directly - no popover.
- **It only exists when the audio is orphaned**: a `play` video is running AND its figure is fully out of view. Fades in on that, fades out when either stops holding - so scrolling back to the video dismisses it, no separate close.
- It gets a reserved (transparent) slot in the toolbox row, never pushing the settings button sideways. Chrome that jumps mid-scroll is worse than the problem it solves.
- Hover on a `loop` slide plays it (desktop); mouseleave pauses unless it's the selected slide.
- `loop` autoplay respects `prefers-reduced-motion`: those users get the still frame, no autoplay.
- `play` never autoplays — the user presses play.

**Scroll trigger for `loop` autoplay:**
- Start: figure's top scrolls above 50% of the viewport
- Stop: figure is fully off-screen (either fully above or fully below)
- Once playing, keeps playing while any pixel of the figure is visible — never re-stops mid-scroll
- One shared scroll listener drives every carousel's check; at load each checks itself and starts if it already qualifies.

**Custom player (on `play` items):** native controls are all-or-nothing on iOS (`controlslist` is ignored there), so `play` ships none — instead a bottom `.controls` bar: a play/pause button (icon swaps on `.is-playing`) and a seek `.scrubber` (rAF-driven fill via `--progress`, auto-sync frozen while dragging). Wiring keys off `.slide[data-type='play']` (any play slide). **Only the button toggles — a tap on the video does not**, because a video-tap handler also fires on a Flickity swipe and would start the clip mid-drag; the video body stays free to swipe the carousel.

**Read more:**
Uses `<details>` for inline unfold. Both `description` and `details` render raw HTML — links, `<em>`, etc. work in either.

**Output policy (site-wide):** the call site names the threat, and no threat means no call.
- Just echoing our own hand-authored content → bare `<?= $thing ?>`. This is the default everywhere; the render layer doesn't defend against its own author.
- Free-form prose inside a quoted attribute (quotes could end the attribute early) → `quote_safe()` (the meta description in `includes/header.php` is the one current case).
- User-generated or otherwise untrusted content → doesn't exist on this site today. If it ever does, add a helper named for that threat at that time (don't pre-build it), and convert at output, per context.

Locked-in markup pattern (same in the home page-header `templates/pages/home.php` and `templates/milestone.php`, keep them matching):
- `<summary class='read-more'>` wraps `<span class='calm-voice link'>Read more</span> →` — underline on the span only, arrow outside it.
- Body is `<text-content class='styled more-body'>` (paragraph rhythm comes from `text-content.styled` in typography.css).
- Body opens with `<p>→</p>` — the arrow persists as an anchor after `[open] summary` hides.

## Theme system (locked-in rules)

The theming behavior is a load-bearing artifact of this site — it's part of the design-system-mastery demo, not just a nicety. Decisions below are pinned; don't re-litigate without a reason.

**The model (decided 2026-07-11): two orthogonal axes replace the old bundled themes.** Brand carries type + corners + rhythm ("who this surface is"); emphasis carries intensity ("how loud it speaks"), expressed through the color tokens. Any brand works under any emphasis under either scheme, because components only ever read the semantic slots. The four brands are *surfaces of one organization* — Personal (this site as itself, the default), Marketing (brochure-facing), Product (the app), Documentation (technical) — which is the pitch: one design system serving a company's real surfaces. The old `data-theme` bundles (`serif | mono | display`) are retired; their palettes live on as emphases (warm/cool/neutral).

**Attribute axes (each lives at a specific level, do not confuse):**
- `data-brand` on `<html>` — structure: type pair, `--corners`, scale ratio, voice weights (`personal | marketing | product | documentation`). Absent = personal (`:root` IS the personal brand).
- `data-emphasis` on `<html>` — intensity, not hue (`default | muted | focused | immersive`): a dial from soft to theatrical. Each level is still *expressed* through the color tokens only (that's the mechanism), but the semantic is loudness — muted eases contrast off, focused sharpens it, immersive lets the paint go rich. Absent = default. Current recipes are placeholders to make the dial real.
- `data-scheme` on `<html>` — `system | light | dark`. Absent = system (reads `prefers-color-scheme`).
- `data-view` on `<html>` — `grid` when the grid view is applied; absent = list. See the Grid view section below.
- `data-sound` on `<html>` — audio-feedback toggle.
- `data-flavor` on `<article class='milestone'>` — per-poster color variant (`warm | cool | stone | night | moss | rose`).
- `data-ui='app'` on the settings panel container — chrome-stays-stable scope.

**A brand block never touches color; an emphasis block never touches type or shape.** That one-way split is what makes the axes composable — a `color:` in a brand block or a `--font-*` in an emphasis block is wrong by definition.

**Font pairings are placeholders** until the Figma audition locks them (Personal = Quicksand/Spline Sans is the current live look; Marketing = Poppins/General Sans, Product = Switzer/Switzer, Documentation = Menlo are working stand-ins). Swap families in the brand blocks in `styles/settings.css`; the render-blocking primary pair `<link>` in `includes/header.php` must stay matched to the Personal pair. After locking, trim the unused audition families from the async font `<link>`.

**Corners.** `--corners` is brand-owned (declared per brand in settings.css) and consumed by the milestone media frame (`milestone.css`, `var(--corners, 0)`). The settings panel keeps literal radii on purpose, so brand-switching never morphs the panel.

**Where tokens live.** `styles/settings.css` — every emphasis/scheme block declares the FULL color token set directly (`--fill-primary`, `--ink-primary`, `--stroke-primary`, `--accent`). No `--t-*` indirection layer. No derived `--ui-*` color-mix. Direct assignment per block.

**Why direct restatement.** We tried the `--t-*-light` / `--t-*-dark` intermediate layer. It caused inheritance mysteries in top-layer popovers and a "not defined" tooltip on `--stroke-primary` that took hours to unwind. Simpler wins. The tradeoff (adding a new color token = editing every emphasis×scheme block) is accepted.

**Panel chrome invariance (`[data-ui='app']` contract).** The panel must NOT restyle while the user is changing brands/emphases. `--font-body` and `--font-heading` are pinned to sans inside the scope; radii are literal (above). If brands ever carry shadow/depth tokens, add matching `--app-*` invariants first.

**Flavors are global baselines** (top level in settings.css, done 2026-07-11 — this was queued item "move flavors to :root"). A brand or emphasis block may override a flavor if its palette wants a different take.

**FOUC pattern.** Inline `<script>` in `<head>` at [includes/header.php](includes/header.php) reads localStorage keys (`brand-preference`, `emphasis-preference`, `scheme-preference`, `sound-preference`, `view-preference` when the grid flag is on) and sets `<html>` attrs BEFORE stylesheets load. Adding a new persisted axis = adding a line there AND its wiring in [scripts/settings-panel.js](scripts/settings-panel.js) (the `BRANDS`/`EMPHASES` arrays, a `SWITCHERS` entry, or the view section) — the lists can drift silently. The brand/emphasis value lists live in three places that must agree: the FOUC script, the JS arrays, and the sliders' `max` in `includes/settings/{brand,emphasis}-switcher.php`.

**Queued structural improvements** (in priority order — none urgent):
1. Split settings.css into orthogonal token layer files (`settings/brands.css`, `settings/emphasis.css`, …) now that the axes exist — file split only, no indirection.
2. Pull the FOUC restoration list into one PHP-side config that both the inline script and the JS read from.

## Timeline weights (locked-in rules)

Every milestone carries a `weight` (1-6) in `content/milestones.json` - **that file is the source of truth; never duplicate the per-entry assignments anywhere else.** **Weight 1 is the TOP tier** (like "priority 1"), counting down in importance to 6. The slider value = the deepest weight shown, so its default (leftmost, value 1) shows weight 1 only and each notch adds the next tier. (The scale was flipped 2026-07-10 - it briefly ran 6-high. If old notes say "weight 6 = flagship," they predate the flip.)

**The ladder (what earns each weight):**
- **1 - Flagship / the pitch.** End-to-end product ownership, founding roles, and the major credentials (design systems at scale, accessibility depth, published authority, leadership). This tier IS the default view, so it also carries the **gap rule** below. The "Now interviewing" opener always rides here.
- **2 - Major supporting credentials.** Substantive product/design work a notch below the flagship.
- **3 - Solid supporting work.** Real product/design/dev projects that show range and depth.
- **4 - Range & methodology.** Breadth pieces, positioning moves, early product/startup work, R&D.
- **5 - Craft detail.** Internal tooling and feature-level work (mostly the PE LMS internals).
- **6 - Texture & color.** Podcasts and talks, music, food, art school - the human layer.

**The gap rule.** Weight 1 doubles as the timeline's spine: it must cover the years with no visible multi-year gaps, so the default view reads as a continuous career, not a highlight reel with holes. When two entries tie on merit, the one that fills a year-gap takes the higher (numerically lower) weight. This is why weight 1 is a larger bucket (~14) than the tiers below it.

**The balance rule (Shape A - pitch-floored).** Weight 1 is the fixed ~14-entry pitch; the rest split into evenly-sized tiers below it so each slider notch reveals a comparable chunk - never a big jump then a trickle. Current tier sizes are **14 / 4 / 4 / 4 / 5 / 6** (= 37) for weights 1→6, so the slider reveals cumulatively **14 → 18 → 22 → 26 → 31 → 37**. (What the visitor actually sees is per-lane: the default `job` lane's tag filter drops two un-tagged weight-6 entries - Holloys, Pizzaiolo - so its slider tops out at **35**, not 37. The label's total reflects the live lane count, not the full 37.) When adding entries, keep the lower tiers within a couple of each other. (If the slider ever needs to *narrow below* the pitch to a smaller flagship view, that's "Shape B" - a different default-position design we chose against; revisit deliberately, don't drift into it.)

**Wiring.** Three places must agree: the slider's `max` in `includes/settings/filter-control.php`, and `MAX_WEIGHT` + `FILTER_NAMES` in `scripts/settings-panel.js`. Adding or removing a tier means touching all three.

## Filter / minimap (locked-in rules)

**The minimap is load-bearing - do not arbitrarily remove it.** It has been removed by accident more than once; treat it like the video and theme rules above. If a change seems to require dropping it, stop and ask.

The timeline filter (`includes/settings/filter-control.php`) is a slider plus a **minimap that is a scaled schematic of the page itself** - not a generic bar chart. It mirrors the real responsive layout:

- **Phones** - one column of bars (the milestone list), `max-width` so it stays a small diagram, never full-bleed. It should read like the list of milestones.
- **Large screens** - it gains a **fake settings panel** to the right and becomes **two columns**, because the real layout gains the rail there. The fake panel is the diagram's way of showing "the page is two-column now."
- It flips at the **same 1024px breakpoint as the page grid** (`styles/layouts/default-layout.css`). Keep the two breakpoints in sync - the minimap's whole job is to match the layout it depicts.

Structure: `.mini-map` wraps `.mini-map-bars` (an `<ol>`, one `<li>` per milestone, filled by `scripts/settings-panel.js`) and `.mini-map-panel` (the fake panel, `display: none` until 1024px). Bars: `data-state='in'` = dark (surfaced by filter), else faint.

The label above the slider reads `Filter: <count> / <total>` (`data-filter-count` / `data-filter-total`, the total set from the live lane count) and is always shown. The tier's descriptive name (`data-filter-name`, from `FILTER_NAMES` in the JS) sits *below* the slider as `.filter-level-name` - deliberately small and faded (subtle support, not real info), and shown only from the 1024px breakpoint where there's room; hidden on the narrow popover where it would wrap.

The failure mode to avoid: it renders as full-width, blown-out bars instead of a small faithful diagram. If you see that, the `max-width` / two-column rules got lost - restore them, don't delete the map.

In **grid view** the minimap keeps its contract by changing shape with the page: the fake panel becomes a thin strip on TOP (the inline settings bar) and the bars become a grid of 16:9 cells matching the real column count — both read `--grid-columns`, so they can't drift.

## Grid view (built 2026-07-11, behind a kill switch)

List view is the argument (a readable spine); grid view is the evidence (the wall of work at a glance). It's an experiment — **`GRID_VIEW_ENABLED` in `includes/config.php` is the one-line kill switch**: off = the toggle never renders, `styles/layouts/grid-view.css` never loads, the FOUC script never sets `data-view`, and the site is exactly the single-column list (same "no weight when off" contract as the tour).

**The rules:**
- Grid exists only at **≥ 1600px**. Below that the toggle hides and everyone gets the list — a phone gridding into one column would just be a worse list. A saved grid preference persists but applies only where the grid exists (`data-view='grid'` on `<html>` reflects the APPLIED state, not the preference; the media-query gate lives in the FOUC script, `GRID_MIN` in settings-panel.js, and grid-view.css — keep the three matched).
- **Columns**: 2 from 1600px, 3 from 1900px. The wall's geometry is three shared tokens in grid-view.css — `--grid-columns` (count), `--wall-column` (the designed lane measure; cards never stretch past it), `--wall-gap` (gutter). The wall's max-width derives from count × measure, so a bigger window adds a lane rather than inflating cards; the intro constrains to `--wall-column` and reads as lane one; the minimap reads `--grid-columns`.
- **Cells keep the full card** (date, title, media, description, read-more) at column width; only motion stands down.
- **Lanes, not aligned rows** (2026-07-11): cards vary in height, so the wall packs via multi-column (`columns: var(--grid-columns)`) — tight vertical packing everywhere today, with the accepted tradeoff that order flows *down each lane* (newspaper-style) rather than across rows. An `@supports (grid-template-rows: masonry)` block upgrades to native masonry (row-major order AND tight packing) wherever it ships — no stable browser yet, treat as progressive enhancement only.
- **The grid invite**: a small circular toggle beside the settings trigger (markup in settings-panel.php, flag-gated) that exists only in list view at ≥1600px and pulses an accent halo until the visitor enters grid view once by any door — then a `grid-invite-seen` localStorage breadcrumb retires the pulse forever (same pattern as the passkey button). Respects `prefers-reduced-motion`.
- **Per-view interaction decisions key off `data-view`** — the one switch. First case: `syncScroll` (the keep-your-place anchor correction) stands down entirely in grid view, because the lanes re-pack wholesale and there's no stable card to hold. New "should this behave differently in the grid?" questions get answered the same way: check the attribute, don't invent a second flag.
- **Motion stands down in the grid**: `autoplay()` in `includes/footer.php` returns early under `data-view='grid'`, so loops never autoplay there (scroll, settle, and hover all route through it). A pressed `play` still works — that's a visitor's choice.
- **The settings panel leaves its popover** and sits inline at the top of the page, at regular scroll (no sticky — at these widths the controls and the first grid rows share the viewport, so the playground moment needs no following chrome). Mechanism: the JS removes/restores the `popover` attribute; all visual rules live in grid-view.css. The trigger button hides.
- **A milestone title click in the grid lands in LIST view**, scrolled to that milestone — as a navigation aid, not a preference change (`persist: false`, so a saved grid choice survives for the next visit). One reading surface; the grid is for surveying.

**Wiring:** toggle partial `includes/settings/view-switcher.php` (flag-gated in settings-panel.php) · view section + `applyView` in `scripts/settings-panel.js` · layout in `styles/layouts/grid-view.css` (flag-gated `<link>` in header.php) · storage key `view-preference`.

Type comes from [Fontshare](https://www.fontshare.com) via its CSS API. The audition stacks live in `styles/font-scales.css` (`--font-sans` / `--font-serif` / `--font-mono`); the brand blocks in `settings.css` pair families into `--font-heading` / `--font-body` (mostly by direct name now). So swapping a family is a one-line token edit and the brand re-pairs. **Fontshare has no monospace** — `--font-mono` stays system (`Menlo`).

**Where the real code lives** (don't duplicate it — edit it):
- Loading: `includes/header.php` `<head>` — preconnect + the two `<link>`s + the full family URL.
- Family → token mapping + the list of every loaded family: `styles/font-scales.css` (top comment).

**API syntax** (`api.fontshare.com/v2/css?f[]=<slug>@<weights>&display=swap`):
- `@1` = the **variable** font (one `@font-face`, `font-weight: 100 900`). Prefer this.
- `@400` / `@400,700` = discrete static weights (one `@font-face` each).
- CSS is served from `api.fontshare.com`; the actual woff2 files come from `cdn.fontshare.com` — that's the host that matters for preconnect (`crossorigin`, since font fetches are CORS).
- Not every family has a variable cut: currently `gambarino` and `bebas-neue` are static `@400` only. Check by fetching `?f[]=<slug>@1` and seeing whether `font-weight` is a range.

**Loading strategy** (decided 2026-07-06): the **primary pair** loads as a normal render-blocking `<link>` (stable first paint); **every other family** loads non-blocking via `media='print' onload="this.media='all'"` with a `<noscript>` fallback, so alternate-theme/audition fonts don't hold up render. To add a family: append `&f[]=<slug>@1` to the async `<link>` **and** its `<noscript>` copy, then reference `'Family Name'` in a token.

**Open decisions / deferred:**
- **Brand pairings are being auditioned in Figma** (2026-07-11); the blocks in settings.css carry working placeholders until then. **Pair convention: heading = the display font (the left name in a Fontshare pair), body = the reading font (right).** Once locked, trim the unused families from the async `<link>` (and its `<noscript>` copy).
- `font-size-adjust: from-font` (anti-CLS on font swap) is **deferred** until the pairings are locked — it rescales metrics, so not worth churning mid-audition.
- **Self-host the woff2 before final launch** — the CDN `<link>` is fine for auditioning, but there's no reason to ship a personal site with a third-party font dependency.

## Future: journal page

Eventually the site should have a journal section. Blog-style entries, dated, casual. The point is the *register*, not the topic — Derek talking about how he's feeling about a given moment, reminiscing about something, working an idea out loud. Adjacent to the timeline but not part of it. Lower polish than the timeline cards, higher signal about who Derek is as a person and a thinker. Not for Round 1.
