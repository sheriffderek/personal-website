# Theme texture - paper, material, depth (exploring, 2026-09-30)

Thinking notes, nothing decided. Started from paper.design's landing page as a reference.

## What the reference does

- The page ground is a scanned paper tile (cold-press tooth, irregular, fibrous) - real stock, not generated noise. That's why it reads as material.
- A second tinted sheet, same tooth, is cut on a diagonal and carries dashed drafting lines + ruler ticks - pencil on paper.
- The footer is a different stock (woven/linen) and the page casts a soft shadow onto it. Surfaces are stacked sheets; texture does the layering work that borders and cards usually do.

## Two materials, not one winner

- **Generated noise** (feTurbulence) = screen materials: film grain, CRT, frosted panels.
- **Scanned tiles** = physical stock: paper, kraft, linen, construction paper.

## Where things could live (the agent's map, challenge freely)

- **Stock is not a fill.** Putting texture inside `--fill-*` breaks the mood contract (every mood restates the same 10 color tokens; `color-mix`/`light-dark()` read fill as color). Instead: a separate grayscale texture slot (e.g. `--stock`) owned by the character, like `--corners`. The mood still tints underneath.
- **Hand-made is structure** (cut edges, offset-shadow layers, fibrous stock, a human type pair) - so a character, with brightness coming from the mood. The test: does it look intentional under every mood? Expressive = construction paper, Quiet = kraft/newsprint, Technical = blueprint collage. If it only works bright, it's a flavor pretending to be a character.
- **Cheapest home for hand-made is the posters** - adds no states. The collage specimen on the design-system page is already this idea at poster scale, minus stock.
- **Drafting marks** - the parked poster flourish layer (`poster-system.md`), or a character's line quality. Open.
- **Bright construction-paper color** - Expressive already, or a flavor (+30 states, has to earn it).

## What already exists

- Canonical `--grain`: `styles/settings/base.css` (unused outside the tester).
- The grain bench + its findings (fine × mottle = paper; multiply muds on dark; soft-light survives schemes but vanishes on white): the top and finish-class comments in `styles/modules/design-system.css`.
- The parked finish deck (ground → SVG → CSS finish layer, character-owned, default off, "~15 lines, judged live"): `notes/poster-system.md`.
- Chrome depth: `--app-shadow` and the CHROME TAKES list in `styles/modules/settings-panel.css`. The page has no depth token.
- Dark-mode depth precedent ("elevation by lightness"): `notes/walk-notes.md`.

## Where it fights existing contracts

- **Chrome** - any new global token pierces `[data-ui='app']` until pinned there.
- **Resume** - the theme-proof pin in `resume.css` must restate any new token; the fit-check will catch a leak.
- **Contrast** - measure ink against the tile's darkest pixel; stock off under `prefers-contrast: more`.
- **Dark** - a light scan on a dark ground reads as dirt. Needs a dark tile, or depth by lightness instead.
- **Grid** - stock on every card fights the wall's variety; one textured ground sheet works with it.
- **Poster SVG** - texture inside the SVG gets rescaled by the phone viewBox crops (same tax as strokes). Keep it in CSS on the frame.
- **Performance** - blend modes on viewport-sized or fixed backgrounds repaint on scroll on iOS Safari. `background-blend-mode` on a tile is cheaper.

## Product - what does it own?

On the page, mostly the defaults wearing a name: `:root` in `base.css`, a font pair, `--corners: 0`, a 1.25 ratio. Versus Interface the difference is a font pair, 6px of corner, 0.05 of ratio. Its description says "friendly, soft" but the corners are square. Its real identity is in the chrome (the Brutalist family, the baseline others have to beat - `app-ui-walk.md`).

Replacing it with a material/hand-made register keeps the state count flat. Costs: the default look changes (what recruiters see first), every approved Product cell gets re-walked, the Brutalist family needs a new owner, and either `:root` gets rewritten or a `DEFAULT_CHARACTER` knob appears. The resume stays pinned regardless.

## Experiments, cheapest first

1. **The parked poster finish** - `::after` on the frame, grain × mottle soft-light, behind an opacity token at 0, on for one character. Judge on the grid wall across moods and schemes.
2. **Scanned vs generated** on the design-system page - one grayscale cold-press tile beside the layered grain, light and dark, worst-pixel contrast.
3. **One sheet test** - page over footer as two stocks with a cast shadow, depth-by-lightness in dark. On the tester page only, no new axis.

## Open questions (Derek's calls)

1. Is texture part of the default look, or a reward found by flipping settings?
2. Does the page get depth at all, or does depth stay the chrome's craft?
3. Product: keep as the neutral baseline, sharpen it, or hand its slot to a material register? Should "default" stop being a character (a `DEFAULT_CHARACTER` knob)?
4. The named reference for hand-made: paper.design, Are.na, a riso zine, something else?
5. Physical stock vs screen material: one character each, or one stock slot carrying both (paper on Editorial, scanlines on Terminal)?
6. Dark mode paper: a dark stock, elevation by lightness, or off?
7. Construction-paper brightness: Expressive as-is, or a flavor that earns +30 states?
8. Drafting marks: a mood turning on a layer, or a character's line quality?
