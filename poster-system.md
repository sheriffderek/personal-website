# Poster system

Reference for building and theming milestone posters. Keep this pointing at the
real files - don't copy token values here, they live in the stylesheets below.

**Source of truth:**

- Token slots + per-theme/flavor color values: [styles/settings.css](styles/settings.css)
- Poster geometry + how slots map onto `.poster-art`: [styles/modules/milestone.css](styles/modules/milestone.css)
- The reference poster SVG: [includes/posters/poster-shapes.php](includes/posters/poster-shapes.php)

## What gets themed (scope)

Deliberately narrow:

- **The main poster** (the SVG collage) gets the full color repaint.
- **Embedded media** (the framed photo/video) gets **geometry only** - corner radius and border. No tint, no duotone, no blend, no filter.

No `mix-blend-mode`, no `filter`, no SVG `<feColorMatrix>` anywhere. Every themeable thing is a plain token repaint. The one non-native mechanism is the gradient background (a CSS `background`, see below).

## Token slots

### Poster color - repainted per flavor

Same vocabulary as the site (`--fill-*` / `--ink-*`), scoped to the poster.
Surfaces are `fill`, marks drawn on them are `ink`. There is no "shape" or "line"
slot - a solid shape and a stroke are both `ink`; a quieter box is `fill-secondary`.

| Token | What it is | Mechanism |
| --- | --- | --- |
| `--poster-fill` | background surface | SVG `fill` (transparent when a gradient is set) |
| `--poster-fill-secondary` | a second surface (the lighter box) | SVG `fill` |
| `--poster-ink` | everything drawn on the surface - solid shapes, outlines, arrow | SVG `fill` / `stroke` |
| `--poster-ink-secondary` | quieter marks (the texture hatch) | SVG `stroke` (or a `<pattern>`) |
| `--poster-gradient-from` | first gradient stop; unset = flat | CSS `background` (composed in milestone.css) |
| `--poster-gradient-to` | second gradient stop; unset = flat | CSS `background` |
| `--poster-gradient-angle` | gradient direction; has a default | CSS `background` |

A pop color, if ever wanted, is the site's existing `--accent` - not a new slot.

**How it wires:** a flavor sets `--poster-fill` / `--poster-ink`. Inside
`.poster-art`, milestone.css maps those onto the universal `--fill-primary` /
`--ink-primary` and derives the `-secondary` pair, so the SVG just reads the
same `--fill-*` / `--ink-*` names the whole site uses.

### Geometry - varied per theme (applies to poster AND media frame)

| Token | Controls |
| --- | --- |
| `--corners` | corner radius (sharp vs rounded per theme) |
| `--border-width` | media-frame border thickness |
| `--line-width-primary` / `--line-width-secondary` | SVG stroke widths, in the 1600x900 coordinate space |

## Axis split (theme vs flavor)

Two knobs, different jobs - don't mix them:

- **Flavor owns the palette** - which colors fill *this* poster (`warm`, `cool`, `night`, ...). Gradient stops live here. Per-poster, set via `data-flavor` on the article.
- **Theme owns the shape language** - sharp vs round corners, thin vs thick lines, gradient on vs flat. Set via `data-theme` on `<html>`.

So a gradient is entirely a flavor call: define `--poster-gradient-from` / `-to`
to turn one on, leave them out to stay flat (they fall back to `--poster-fill`).
Theme still owns the shape language - corners, line widths. Invert (dark surface,
light marks) is just a flavor that sets `--poster-fill` dark and `--poster-ink`
light - never `filter: invert()`.

## One composition per milestone (Derek, 2026-07-28)

A mood restyles a poster; it never redraws one. Each milestone has exactly ONE
authored composition (`includes/posters/art/<slug>.php`), and every mood paints
that same geometry through the tokens. The one sanctioned variation: a mood may
HIDE or REVEAL a named optional layer - an extra mark drawn in the same file,
wearing a class (e.g. `class='mood-extra'`), toggled with `display` from a mood
block. The layer lives in the poster's own SVG so the composition stays one
file, one truth; a mood block toggles visibility only - it never injects or
repositions art. (Decided when the three-moods sheet showed per-mood example
posters with different compositions - that variety, if wanted, comes from
revealed layers, never from forked artwork.)

## Gradient background

Use a CSS `background` on the poster element, not SVG `<stop>` elements (SVG stops
can't fall back to a flat color cleanly). The gradient is composed once in
milestone.css from stop tokens a flavor fills in:

```css
background: linear-gradient(
	var(--poster-gradient-angle, 135deg),
	var(--poster-gradient-from, var(--poster-fill)),
	var(--poster-gradient-to, var(--poster-fill))
);
```

A flavor opts into a gradient just by defining `--poster-gradient-from` /
`--poster-gradient-to`. Leave them out and both stops fall back to `--poster-fill`
- two identical stops render flat - so **"unset = flat" needs no separate on/off
flag.** `--poster-gradient-angle` defaults to 135deg; a flavor can override the
rake. When a gradient is showing, the SVG's base `<rect>` goes transparent so the
CSS background shows through.

## SVG authoring checklist

Draw in the tool, then link to tokens by hand (find-replace literal colors ->
`var()`). These rules make that swap trivial and keep the SVG safe as one of many
inline on the page.

**Frame**

- [ ] Author at exactly **16:9** (`1600x900`). Other ratios get `slice`-cropped.
- [ ] Export with **`viewBox` only** - no `width`/`height` attributes.
- [ ] Don't set stroke widths in the tool - they're overridden by `--line-width-*`.

**Color for the swap**

- [ ] Use a **tiny, distinct placeholder palette** - one unmistakable color per slot (pure magenta = fill, pure cyan = ink, a third for fill-secondary...). Makes the find-replace to `var()` unambiguous.
- [ ] Surfaces use a `fill` token, marks use `ink`. A filled shape with an outline is `ink` fill + `ink` stroke (or `fill-secondary` fill + `ink` stroke for the lighter box).

**Layer structure**

- [ ] **Group by token role, not visual position** (like the current file's `.outlines` and `.texture` groups). One color per group = set the token once on the group.
- [ ] **Name layers by slot** so the export groups cleanly.

**The ID gotcha (this one breaks silently)**

- [ ] **Prefix every `id` with the poster's slug.** `<marker>`, `<clipPath>`, gradients all use `id`s; multiple posters render on one page. Duplicate ids mean the second poster grabs the first's def.

**Cleanup / export**

- [ ] Export as **presentation attributes** (`fill=`, `stroke=` on the element), not an internal `<style>` block with `.st0 {}` classes.
- [ ] **No tool effects** - drop shadows, blur, group opacity, blend modes. They export as `<filter>` and won't theme.
- [ ] Reduce coordinate precision (~2 decimals). If you run SVGO, configure it to **keep** `id`s and inline `style`/`var()` - a default run strips them.
- [ ] If a texture repeats, author it as **one `<pattern>` tile**, not hundreds of literal paths (the current hatch is the file-size cautionary tale).

**Techniques to reuse**

- [ ] `fill='context-stroke'` on marker defs (arrowheads) so they inherit the arrow's `--ink-primary` automatically.
- [ ] Mark the whole SVG decorative: `role='img'` + `aria-hidden='true'`.

## Parked: conditional poster layers (2026-08-23)

Derek: posters "might even add additional areas to the graphics that get hid
in other cases." The mechanism when we want it is the invertible pattern
reused: the poster AUTHORS an extra group (e.g. `<g class='flourish'>` -
bonus marks, denser texture), hidden by default, and a mood x character cell
ACTIVATES it (the Figma-vibes default cell being the obvious first customer;
quiet cells never show it). Candidacy in the art, activation in the takes -
no new machinery. Not built; parked until a real poster wants its first
flourish layer.

Sharpened by Derek's Artboard22 mock (same day): the layers are translucent
FIELDS, not just extra marks - panels and rings that OVERLAP the base
composition at partial opacity, so the intersections make third tones (the
dome splitting where a panel crosses it). Opacity is the mechanism: a layer
composes with whatever ground the active flavor painted, so one authored
layer works under every palette. And the layers could ANIMATE ON HOVER
(Derek, same breath) - there's precedent: the poster already breathes its
stroke-width on hover, so layer motion would ride the same gesture (hover
is visitor-initiated, so no reduced-motion gate per the motion policy).
The compositional constraint: the fields sit on a 10x8 GRID behind the
scenes (Derek - visible as the checkering in his mock; on the 1600x900
canvas that's 160 x 112.5 per cell), so layers snap to shared geometry
instead of floating free - one grid, every poster, and the panels read as
deliberate zones rather than loose washes. (His mock is one way it could
look, not the spec.) And further out: a CLICKABLE AREA on each poster
that starts an animation sequence (Derek - "GSAP or something"). Cautions
already known for that day: a whole-poster tap handler fires on carousel
swipes too (the exact reason the play BUTTON, not the video body, toggles
playback - footer.php), so the trigger is a small dedicated hit area; and
GSAP is a new dependency on a site that ships almost none - weigh CSS
animation first. Click-started motion is visitor-initiated either way, so
no reduced-motion gate (motion policy). Still parked; still
activation-by-cell.

## Parked: texture / grain finish (2026-09-11)

Sparked by a grainy-gradient graphic Derek liked (blurred color blobs under
a uniform film-grain veil). Everything here is parked ideas, not decisions -
and note up front: adopting ANY of it revises this file's own scope rule
("no mix-blend-mode, no filter anywhere"). That rule was about theming
staying a plain token repaint; a texture layer that is palette-free alpha
doesn't break the repaint contract, but relaxing the rule is a deliberate
call for that day, not a drift.

**The constraint that shapes all of it:** texture must be palette-free
alpha, so the tokens keep painting the color underneath and every mood x
flavor x scheme re-tints it for free. Baked pigment is disqualified.

**The deck model (the session's useful frame).** The poster figure is a
three-deck sandwich, each deck in its own coordinate space:

- CSS ground - fills/gradients, pixel-space, token-painted (already exists;
  it's what fills the phone viewBox overflow).
- SVG - the shape vocabulary, composition-space, token-painted.
- CSS finish - grain, vignette, scanlines, borders: pixel-space, alpha-only,
  painted on the HTML frame around the SVG. Doesn't exist yet.

The deciding rule: composition-relative things (shapes, arcs) live in the
SVG and SHOULD scale; device-pixel-relative things (grain, hairlines) live
in CSS on the frame and hold 1px at every width. Grain especially belongs
in the finish deck: film grain sits on the photograph, not in the scene -
and feTurbulence inside the SVG would resize per phone-viewBox crop (its
baseFrequency is user-units), the same disease as the strokes. Canvas
stays out of the sandwich entirely: it can't read tokens reactively, so it
structurally can't participate in repaint-from-above.

**Grain options, ranked by fit:**

1. A `::after` finish layer with a tiny data-URI noise tile (an SVG that is
   just feTurbulence), low opacity, `mix-blend-mode: soft-light`/`overlay`.
   Cheapest, pixel-true, closest to the reference image.
2. `mask-image` with the same noise tile - texture that eats the shapes
   (risograph/letterpress ink) rather than veiling them.
3. feTurbulence filter inside the SVG - only if the grain must interact
   with individual shapes; pays the viewBox-scaling tax and CPU cost.

**Where it would live:** character-owned, like `--corners` - one finish
token (e.g. `--grain-opacity`) defaulting to 0, consumed by the finish
layer. Serious/chill balance: Product (the default) stays completely
clean; ONE character wears the grain (Marketing the obvious wearer). Same
posters, one panel flip, textured - restraint and range in the same click.
No new axis; sizing-rule-compatible. Terminal could reuse the same slot
for a scanline tile someday.

**1px-honest strokes (related, separate decision):**
`vector-effect: non-scaling-stroke` is CSS-settable, so pixel-locked vs
proportional stroke weight could be a CHARACTER's call (Terminal/Interface
lock to hairlines, Marketing stays proportional) - one rule per character
block, no poster edits. This reframes the "nuclear option" note in
CLAUDE.md's SVG-tradeoff section: nuclear as a global fix, legitimate as a
per-character take. Gotchas: locked strokes read relatively HEAVY on
phones (technical-pen aesthetic - you have to want it), and
stroke-dasharray lengths still scale while the width doesn't, so dashed
lines need a look. Cheap test: one scratch rule
(`.poster-shapes * { vector-effect: non-scaling-stroke; }`), judge at
phone + desktop width.

**Animating texture, ranked by cost (all parked):**

1. Jittered grain - never regenerate the noise; jump the static tile's
   transform in a `steps()` loop (classic film-grain trick, compositor-only,
   nearly free).
2. `@property`-registered custom properties animating the ground's gradient
   angles/positions - the color blobs drifting; "the tokens themselves are
   moving" material.
3. Crawling `mask-position` - weathering that migrates across shapes.
4. Boiling lines - feTurbulence + feDisplacementMap stepping the seed a few
   times a second (squigglevision; charming on line art, CPU-expensive -
   one flagship poster maybe, never the grid wall).

Motion-policy fit: ambient texture motion is decorative -> gated. The
ungated version worth wanting: texture animating ONCE in response to a
theme flip (grain washing in when Marketing lands), which is functional -
the switch demonstrating itself - then settling still.

**The sizing-rule verdict that ended the session:** the impressive thing
is the ratio, not the effect count. The whole shelf above collapses to one
candidate first move: one finish layer + one noise tile + one
character-owned opacity token on Marketing, ~15 lines, judged live before
anything else earns a slot.
