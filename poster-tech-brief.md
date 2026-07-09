# Poster cover-art spec

Everything you need to author a milestone **poster cover** (the generated SVG art). This is ONLY about the cover graphic and how it's colored - not media, carousels, or files.

Reference implementation: `includes/posters/poster-shapes.php` (the SVG) + `styles/modules/milestone.css` (`.milestone`, `.poster-art`) + `styles/settings.css` (the flavor palettes).

## The frame

- One SVG, `viewBox="0 0 1600 900"` (16:9), `preserveAspectRatio="xMidYMid slice"` - it's cropped to fill the card frame, so keep anything important away from the extreme edges.
- Author to that exact 1600×900 space.

## Hard rule: NO hardcoded colors or widths

The whole point is that one graphic **recolors itself per theme + per flavor**. So the art may only reference these CSS custom properties - never literal hex/rgb, never literal stroke widths:

**Color tokens**
- `--fill-primary` - the background fill.
- `--ink-primary` - the main stroke color (outlines).
- `--fill-secondary` - a filled-shape accent. Derived: `color-mix(in oklch, var(--poster-fill) 70%, var(--poster-ink))`.
- `--ink-secondary` - the secondary/texture stroke color. Derived: `color-mix(in oklch, var(--poster-ink) 55%, var(--poster-fill))`.

(`--fill-primary`/`--ink-primary` are re-pointed to `--poster-fill`/`--poster-ink` inside `.poster-art`, and the two `-secondary` tokens are the color-mixes above. You just consume them.)

**Line-quality tokens**
- `--line-width-primary` (default **10**) - the bold outlines.
- `--line-width-secondary` (default **5**) - the fine texture strokes. On card **hover** this animates to **3** over 300ms (`transition: stroke-width 300ms`) - so secondary strokes are the "breathing" layer; primary stays put.

Set widths via `style="stroke-width: var(--line-width-primary)"` on a group.

## Stroke / fill treatment (the "line quality")

From the reference art:
- Outlined shapes: `fill: none; stroke: var(--ink-primary)` at `--line-width-primary`, `stroke-linejoin: round`.
- One or two shapes use `fill: var(--fill-secondary)` for a solid accent block.
- The fine "texture" field: `fill: none; stroke: var(--ink-secondary)` at `--line-width-secondary`, `stroke-linecap: round`, clipped to a rectangle (a dense field of tiny hand-drawn-feeling marks).
- Arrows: an SVG `marker` whose tip uses `fill="context-stroke"` so the arrowhead inherits the line's stroke color automatically.
- Root SVG carries `fill-rule: evenodd; clip-rule: evenodd; stroke-linejoin: round`.

Net look: bold token-colored outlines (boxes / circle / arrow) + a filled secondary block + a clipped field of fine round-capped texture strokes. Composition can change per card; the *treatment* (tokens + round joins/caps + two line weights) should stay consistent so all posters read as one family.

## Flavors (the color moods)

`data-flavor` on the `.milestone` swaps the palette by setting **`--poster-fill` + `--poster-ink`** (a two-tone pair). Current set (in `settings.css`, currently flat two-tones):

| flavor | `--poster-fill` | `--poster-ink` |
|---|---|---|
| warm | amber-100 | orange-900 |
| cool | sky-100 | blue-900 |
| stone | `#c9c9be` | stone-900 |
| night | stone-900 | amber-200 *(inverted - dark bg, light ink)* |
| moss | green-100 | green-900 |
| rose | red-100 | red-900 |

Because everything derives from that pair (background = fill, outlines = ink, plus the two color-mixes), a new flavor is just a new fill/ink pair. `night` proves it works inverted.

## Gradients

Not implemented yet - the current covers are **flat** token fills. If you want gradient fills (e.g. the fill sweeping fill→ink):
- Define an SVG `<linearGradient>`/`<radialGradient>` in the SVG's `<defs>` whose stops reference the tokens (`stop-color: var(--fill-primary)` → `var(--fill-secondary)` or `var(--ink-primary)`), then `fill="url(#thatGradient)"` on the background rect.
- Keep the stops on tokens (never literal colors) so gradients still recolor per flavor.
- This is an open extension - propose it before shipping, since it changes the whole family's look.

## TL;DR for the artist

Draw to 1600×900. Use only the tokens: `--fill-primary`/`--ink-primary` (+ the two derived `-secondary`), and `--line-width-primary` (10, bold) / `--line-width-secondary` (5, fine, thins to 3 on hover). Round joins and caps. No literal colors, no literal widths. It'll then recolor across all six flavors + light/dark for free.
