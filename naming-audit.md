# Naming Audit — derek-thomas-wood-website

## Current Conventions in Use

### Files
- **kebab-case** throughout. Examples: `settings-panel.php`, `milestone.css`, `audio.js`, `filter-control.php`, `theme-switcher.js`
- PHP files with no suffix patterns (Laravel uses `Model.php`, WordPress uses `class-` prefix). Derek: just `name.php`
- CSS split into semantic layers: `settings.css` (tokens), `modules/milestone.css` (component), `layouts/default-layout.css` (structure)

### Folders
- kebab-case: `includes/settings/`, `styles/modules/`, `styles/layouts/`
- No separation by type (all PHP in includes/; all CSS flat + organized into subfolders only when multiple files per concern)

### PHP Functions
- **snake_case** consistently: `load_json()`, `load_markdown()`, `render()`, `partial()`, `square_variant()`, `e()` (escape)
- Short utilities (2-3 letters) are acceptable: `e()` for htmlspecialchars escape
- All lowercase, no underscores in abbreviations

### PHP Variables & Constants
- **SCREAMING_SNAKE_CASE** for constants: `CONTENT_DIR`, `TEMPLATES_DIR`, `INCLUDES_DIR`, `SITE_ROOT`
- **snake_case** for variables: `$filter_tag`, `$target_notes`, `$all_milestones`, `$square_variant`
- No camelCase seen in PHP

### CSS Classes
- **kebab-case** throughout (per Derek's documented rules: no BEM, no underscores)
- **Semantic naming** over structural: `.milestone`, `.settings-panel`, `.carousel`, `.slide` (role-based, not `__child` patterns)
- **Voice classes** as utilities: `.quiet-voice`, `.calm-voice`, `.label-voice`, `.strong-voice`, `.attention-voice`, `.loud-voice`, `.high-voice`
- Descendant structure visible in CSS nesting, not class names: `.milestone .heading` (not `.milestone__heading`)
- Settings toggles: `.theme-switcher`, `.mode-switcher`, `.sound-switcher`, `.filter-control`

### CSS Custom Properties
- **kebab-case** with semantic prefixes: `--fill-primary`, `--ink-primary`, `--stroke-primary`, `--accent`, `--link-color`, `--font-heading`, `--font-body`
- **Tier-based logic** for token hierarchy: primary / secondary / auxiliary (not light/dark/base/variant)
- **Poster-specific tokens** (flavor variants): `--poster-fill`, `--poster-ink`
- Token sets **restated fully per theme** (no intermediate `--t-*` indirection layer; documented in CLAUDE.md as intentional)

### HTML Data Attributes
- **kebab-case throughout**: `data-theme`, `data-scheme`, `data-flavor`, `data-format`, `data-weight`, `data-sound`, `data-type`, `data-ui`, `data-flickity`
- Consistent with HTML spec conventions

### JavaScript
- **camelCase** for functions and variables: `applyTheme()`, `ensureCarousels()`, `hookSettleSound()`, `playClick()`, `playToggle()`, `isOn()`, `getContext()`
- **SCREAMING_SNAKE_CASE** for config: `CONFIG.master`, `SWITCHERS` (array of switcher configs)
- Lowercase single-letter/short utility functions match the micro-function pattern (e.g., `v()` for volume scaling in audio.js)

### JSON Keys (content/milestones.json)
- **kebab-case** for public-facing metadata: `data-theme`, `data-scheme`, `data-flavor`
- **snake_case** for JSON internal keys: `slug`, `date`, `title`, `weight`, `tags`, `flavor`, `format`, `media`, `description`, `details`, `link`, `link_label`, `vimeo`, `ratio`
- **Inconsistency flagged**: JSON uses `snake_case` keys while HTML uses `kebab-case` data attributes

### Milestone Slugs (content/milestones.json)
- **kebab-case** consistently: `2026-job-search`, `2026-portfolio-site`, `pe-self-paced`, `pe-figure-cms-options`, `pe-code-editor`, `world-ia-day-2026`
- Namespaced with year/project prefix for clarity

---

## Inconsistencies Flagged

### 1. JSON vs. HTML Attribute Naming Mismatch
**File**: `content/milestones.json` and `templates/milestone.php`
**Issue**: JSON uses `snake_case` keys (`data-flavor`, `data-type`) but these become HTML `data-*` attributes which Derek treats as `kebab-case` in CSS/JS selectors.
**Impact**: Low (JSON keys don't clash with DOM, but semantically dissonant)
**Examples**:
```json
{"flavor": "warm", "format": "carousel", "weight": 1}
```
```html
data-flavor='<?= e($milestone['flavor']) ?>'
data-format='<?= e($milestone['format']) ?>'
```

### 2. File Name Inconsistency: "settings-panel" vs. "filter-control"
**File**: `includes/settings-panel.php`, `includes/settings/filter-control.php`
**Issue**: Top-level settings file is `settings-panel.php` but sub-component is `filter-control.php` (not `settings-filter-control.php`). Naming doesn't reflect containment hierarchy.
**Impact**: Minor (clear enough from directory, but inconsistent nesting signal)

### 3. Class Naming: "mode-switcher" vs. "mode-button-group"
**File**: `includes/settings/mode-switcher.php` and `styles/modules/settings-panel.css`
**Issue**: Component file is `mode-switcher.php` but CSS uses both `.mode-switcher` and `.mode-button-group` for the same logical feature.
**Impact**: Low (no actual conflict, but suggests inconsistent component naming)
**Line**: `settings-panel.css` line ~130 (rough)

### 4. PHP Function Naming: Inconsistent Abbreviation Style
**File**: `includes/render.php`
**Issue**: `e()` is a 1-char function name (acceptable for escape utility), but no other single-char functions. Not un-idiomatic, but could be `escape()` for clarity in a portfolio.
**Impact**: Cosmetic (Django, Rails, and many PHP projects use `e()` or `h()`, so it's fine)

### 5. Voice Class Naming: One-Off Outlier
**File**: `styles/modules/settings-panel.css`
**Issue**: `.app-data-voice` uses prefix `app-` while all other voice classes follow pattern `.{type}-voice` (calm, quiet, strong, etc.)
**Impact**: Low (clearly intentional: "app-only" variant of data-voice), but breaks visual pattern
**Context**: Inside `[data-ui='app']` scope per CLAUDE.md pin

---

## Recommendations, Ranked

### Must-Fix (breaks consistency or readability)

**None flagged.** Derek's conventions are internally coherent and intentional per CLAUDE.md. The micro-inconsistencies below are either documented as intentional (voice class outlier, `e()` abbreviation) or harmless (JSON snake_case stays internal).

### Nice-to-Fix (alignment with framework convention)

#### 1. **Clarify JSON key case in CLAUDE.md** (v. low effort, high signal)
**Current**: JSON uses `snake_case` internally (`flavor`, `format`, `weight`, etc.)  
**Recommendation**: Explicitly document that JSON keys stay `snake_case` (data layer), while HTML `data-*` attributes are derived `kebab-case` (DOM layer). No code change needed; just clarify in CLAUDE.md.  
**Why**: Portfolio viewers will ask "why are the JSON keys different from the attributes?" Answer: clear data/DOM boundary. Best practices (Next.js, Eleventy, Nuxt all keep data model case separate from DOM convention).  
**Effort**: One-line note in CLAUDE.md.

#### 2. **Rename `e()` to `escape()` for portfolio clarity** (low effort)
**Current**: `function e($s)` in `includes/render.php`  
**Recommendation**: Rename to `escape()`.  
**Why**: Portfolio viewers (hirers) may not recognize `e()` at a glance; `escape()` is self-documenting. Laravel/Symfony use `e()`, but Derek wants to signal "intentional, clear naming." Easy search-replace (7 occurrences across the codebase).  
**Framework pattern**: Laravel uses `e()` (it's idiomatic), but Next.js / Astro templates are explicit (`sanitize()`, `escapeHtml()`).  
**Effort**: ~5 minutes (grep + sed or manual replace).

#### 3. **Move theme/mode switcher files under a top-level "switchers" folder** (medium effort, structural clarity)
**Current**: 
```
includes/
  settings-panel.php
  settings/
    theme-switcher.php
    mode-switcher.php
    sound-switcher.php
    filter-control.php
```
**Recommendation**: Nest more consistently:
```
includes/
  settings-panel.php
  settings/
    switchers/
      theme-switcher.php
      mode-switcher.php
      sound-switcher.php
    filter-control.php
```
Or flatten all to:
```
includes/
  settings-panel.php
  theme-switcher.php
  mode-switcher.php
  sound-switcher.php
  filter-control.php
```
**Why**: Rails and Laravel nest "similar things" under a common folder; Next.js components live flat under a components/ folder. Derek's current structure mixes both patterns.  
**Impact**: CSS imports, PHP partials would need path updates (~10 lines total).  
**Framework pattern**: Rails uses `app/views/settings/_theme_switcher.html.erb` (underscore prefix for partials); Laravel mirrors this.  
**Effort**: Medium (file moves, import updates).

### Cosmetic (subjective, leave alone unless Derek wants)

- **Voice class `.app-data-voice` prefix inconsistency**: Intentional per CLAUDE.md (`[data-ui='app']` scope invariant). Leave as-is.
- **Short function `v()` in audio.js**: Acceptable micro-utility pattern. Consistent with the codebase style.
- **"settings-panel" vs. "filter-control" naming**: Clear from directory. No action needed.

---

## What to Leave Alone

1. **No BEM, no underscores in CSS** — Derek explicitly documented this (CLAUDE.md). His descendant-selector pattern is intentional and clean.
2. **Voice classes utility structure** — Semantic, well-scoped, standard modern CSS practice.
3. **Custom properties without intermediate `--t-*` layer** — Documented as intentional in CLAUDE.md. Tried indirection, caused inheritance mysteries. Current choice is correct.
4. **PHP function naming (snake_case)** — Standard across WordPress, modern PHP. No change needed.
5. **Data attributes on components** — Semantic, not "divitis." Clean.
6. **Slug naming (kebab-case with year/project prefix)** — Excellent (SEO-friendly, future-proof).

---

## Summary

Derek's naming is **coherent, intentional, and framework-informed**. The three inconsistencies flagged are micro-level and either:
- **Harmless** (JSON snake_case stays internal)
- **Intentional per CLAUDE.md** (voice class outlier, `e()` abbreviation)  
- **Structural but non-urgent** (folder nesting could be clearer, but current state is readable)

**For portfolio impact**: The code reads as intentional and mature. A hirer won't dock points on naming. The one thing that might trigger questions is the JSON/HTML case mismatch — a quick CLAUDE.md note clarifies it's a data/DOM boundary choice, which is actually a signal of good design thinking.

**Best action**: If Derek wants to tighten it, rename `e()` → `escape()` (clearest lift) and optionally clarify JSON case in CLAUDE.md. Folder restructuring is nice-to-have, not essential.
