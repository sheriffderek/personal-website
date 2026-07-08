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
