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

## Current status

Round 1 target: GoFundMe Senior Product Designer application. Five entries needed. See `timeline-content-plan.md` for the full plan and `SESSION-HANDOFF.md` for latest state.

## Design notes

- 16:9 poster cards for each entry
- Entries can be: static image, slider (3-5 slides), or short local video (under 1 min)
- `?target=companyname` inserts tailored paragraphs connecting work to that company's needs
- Future: weighted timeline (slider reveals more/fewer entries), theme switcher (type patterns, font pairs, color)
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

Took a few iterations to settle on. Code lives in `includes/footer.php` and `templates/milestone.php`.

**Slide types** (set via `data-type` on each slide):
- `photo` — still image
- `loop` — muted video, no native controls, plays as ambient motion
- `play` — video with native controls, user-initiated

**Playback rules:**
- Only one video plays across the entire page at a time
- On carousel `settle` (slide animation finishes): pause everything in that carousel; if arriving slide is a `loop`, autoplay it
- Hover on a `loop` slide plays it (desktop); mouseleave pauses unless it's the selected slide
- `play` slides never autoplay — user clicks the native play button

**Scroll trigger for `loop` autoplay:**
- Start: figure's top scrolls above 50% of the viewport
- Stop: figure is fully off-screen (either fully above or fully below)
- Once playing, keeps playing while any pixel of the figure is visible — never re-stops mid-scroll
- At load, check every carousel and start any that already qualify

**Native control stripping (on `play` videos):**
`controlslist='nodownload nofullscreen noremoteplayback noplaybackrate'` + `disablepictureinpicture`. Leaves play, scrubber, time, volume. Chrome fully honors; Safari respects `disablepictureinpicture` but ignores `controlslist`.

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

**Attribute axes (each lives at a specific level, do not confuse):**
- `data-theme` on `<html>` — personality bundle (`default | serif | mono | display`). Always set, including `'default'`.
- `data-scheme` on `<html>` — `system | light | dark`. Absent = system (reads `prefers-color-scheme`).
- `data-sound` on `<html>` — audio-feedback toggle.
- `data-flavor` on `<article class='milestone'>` — per-poster color variant (`warm | cool | stone | night | moss | rose`).
- `data-ui='app'` on the settings panel container — chrome-stays-stable scope.

**Where tokens live.** `styles/settings.css` — every theme/scheme block declares the FULL semantic token set directly (`--fill-primary`, `--ink-primary`, `--stroke-primary`, `--accent`, `--font-heading`, `--font-body`, etc.). No `--t-*` indirection layer. No derived `--ui-*` color-mix. Direct assignment per theme.

**Why direct restatement.** We tried the `--t-*-light` / `--t-*-dark` intermediate layer. It caused inheritance mysteries in top-layer popovers and a "not defined" tooltip on `--stroke-primary` that took hours to unwind. Simpler wins. The tradeoff (adding a new token = editing 8 blocks) is accepted for now. When shape/depth/motion tokens land, split into orthogonal layer files (`settings/colors.css`, `settings/shape.css`, etc.) — don't reintroduce indirection.

**Panel chrome invariance (`[data-ui='app']` contract).** The panel must NOT restyle while the user is changing themes. Currently only `--font-body` and `--font-heading` are pinned to sans inside the scope. That's intentional-for-now. The moment themes carry `--radius-md` or `--shadow-md`, add matching `--app-radius` / `--app-shadow` invariants — otherwise the panel will morph mid-interaction.

**Flavors are per-theme, not global.** Only the default theme currently defines flavor variants (nested under `[data-theme='default']` in settings.css). Other themes get their own flavor palette IF a flavor suits them. Missing flavor under a theme = milestone renders with the theme's base fill/ink (`milestone.css` fallback). That silent fallback is a known regression risk (see queued item #2 below).

**FOUC pattern.** Inline `<script>` in `<head>` at [includes/header.php](includes/header.php) reads localStorage keys (`theme-preference`, `scheme-preference`, `sound-preference`) and sets `<html>` attrs BEFORE stylesheets load. Adding a new persisted axis = adding a line here AND a `SWITCHERS` entry in [scripts/settings-panel.js](scripts/settings-panel.js) — the two lists can drift silently.

**Queued structural improvements** (in priority order — none urgent):
1. Add `--app-stroke` / `--app-radius` / `--app-shadow` invariants inside `[data-ui='app']` before themes carry shape/depth. Three lines, pre-emptive.
2. Move flavor baselines to `:root` so themes optionally *override* rather than being the only source. Fixes silent-flatten regression.
3. Split settings.css into orthogonal token layers when the next non-color axis lands.
4. Pull FOUC restoration list into one PHP-side config that both the inline script and JS SWITCHERS read from.

## Fonts (Fontshare)

Type comes from [Fontshare](https://www.fontshare.com) via its CSS API. The families feed the three stacks in `styles/font-scales.css` (`--font-sans` / `--font-serif` / `--font-mono`), which the theme bundles in `settings.css` pair into `--font-heading` / `--font-body`. So swapping a family is a one-line token edit and every theme re-pairs. **Fontshare has no monospace** — `--font-mono` stays system (`Menlo`).

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
- The **default theme is chosen last**, after each theme's CSS pairing is worked out. `Boska` (display/heading) / `General Sans` (body) is a working placeholder in the default slot until then. **Pair convention: heading = the display font (the left name in a Fontshare pair), body = the reading font (right).**
- `font-size-adjust: from-font` (anti-CLS on font swap) is **deferred** until the pairings are locked — it rescales metrics, so not worth churning mid-audition.
- **Self-host the woff2 before final launch** — the CDN `<link>` is fine for auditioning, but there's no reason to ship a personal site with a third-party font dependency.

## Future: journal page

Eventually the site should have a journal section. Blog-style entries, dated, casual. The point is the *register*, not the topic — Derek talking about how he's feeling about a given moment, reminiscing about something, working an idea out loud. Adjacent to the timeline but not part of it. Lower polish than the timeline cards, higher signal about who Derek is as a person and a thinker. Not for Round 1.
