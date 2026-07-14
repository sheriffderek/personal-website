# Theme model (proposed, 2026-07-14)

**Status: proposal.** Nothing here is built. The current system is documented in the Theme system section of `CLAUDE.md`; this is the model that would replace it. Read it whole, then decide - including the "is a simpler version better?" section at the end, which is the real question.

---

## The story a visitor gets

This is the spine. Every axis below exists to serve one beat of it - if an axis doesn't earn a beat, it gets cut.

1. They slide to **Marketing** - *"hey, that's like ours."* (Shape changed: type got big and loud, corners softened, dates became chips.)
2. They slide through **Theme** - land on the green one - *"oh! that's almost GoFundMe."* Then purple. Different feel entirely - **and nothing but color moved.** Still Marketing.
3. They slide **Emphasis** - and see how each *section* stays on-brand while differentiating. The page becomes a stack of bands, not one flat mood.
4. They poke **Flavor** - the subtle override that keeps things lively without leaving the band.
5. At **any** stage they can flip **dark mode** - and the whole stack repaints coherently.
6. **Red light** - a boolean that forces all color, basically `forced-colors`. The finale.
7. And the **UI chrome itself** (the toolbox) follows along - the tool they're holding is made of the same system.

The point being made: *one design system, serving a real company's real surfaces.* Not "here are some skins."

---

## The axes

| Axis | Attribute | Lives on | Owns | Never touches |
|---|---|---|---|---|
| **Brand** = **brand-character** | `data-brand` | `<html>` | **Character / shape**: type pair, corners, padding, rhythm, scale. Plus the **color application policy** - *where* color is allowed to land (see below). | naming a hue. Ever. |
| **Theme** = **brand-color** | `data-theme` | `<html>` | **Color identity** - the arm of the company. The pigments themselves. | type, shape, and *where* color lands |

The naming is deliberate: `brand-character` and `brand-color` are two **facets of one brand**, not two unrelated dials. Character holds while color swaps - that is the whole green-to-purple demo in two words.
| **Emphasis** | `data-emphasis` | **a section** or a **milestone** (optional) | **Intensity** - which band of the theme's palette this thing wears | hue, type, shape |
| **Flavor** | `data-flavor` | same element as emphasis (optional) | **Hue** - the variation *within* an emphasis | intensity, type, shape |
| **Scheme** | `data-scheme` | `<html>` | light / dark / system. Repaints every band. | everything else |
| **Red light** | `data-red-light` (boolean) | `<html>` | Overrides **all** color. On no dial. | type, shape |

### The matrix (2026-07-14)

Rows are **brand-character**, columns are **brand-color**.

| | **Happy** *(rainbow)* | **Techy** *(analogous)* | **Muted** *(desaturated)* | **Dark** *(where applicable)* |
|---|---|---|---|---|
| **Product** | *stripe-ish* | | | |
| **Marketing** | *gofundme-ish* | | | |
| **Interface** | *anthropic-ish* | | | |

**On the real-company names.** The cells were sketched against real products (Claude, Stripe, Linear, OpenAI, Glitch, Lemonaid). Those are **internal shorthand for a direction only** - they are NOT targets to replicate, NOT things the palettes must match, and NOT public labels. Public copy uses the registers (Happy / Techy / Muted). A portfolio that labels a theme with another company's name reads badly to anyone who works there, and the resemblance lands harder when the visitor arrives at it themselves.

**Dark mode "where applicable"** is the answer to *"what if a theme has no dark colors?"* - not every cell needs one. Under `light-dark()` that costs nothing: a cell with no dark design simply does not call the function and stays itself.

---

## Themes are color-HARMONY rules (this is what makes it affordable)

The breakthrough: **Happy = rainbow. Techy = analogous. Muted = desaturated.** These are not palettes - they are **harmony rules**, and harmony rules are *generative*. That collapses the whole thing into oklch math instead of hand-authored cells.

| Theme | Rule | In oklch |
|---|---|---|
| **Happy** | rainbow | hues spread wide across the wheel, mid-high chroma |
| **Techy** | analogous | hues clustered within ~40 degrees of a base hue, high chroma (gradient-friendly) |
| **Muted** | - | the SAME hue set, chroma pushed toward zero |

Which slots straight into the three channels:

- **Theme** -> the **hue set + chroma level** (the harmony)
- **Flavor** -> **which hue** from that set
- **Emphasis** -> **lightness** (the band)

```css
--poster-fill: oklch(var(--band-l) var(--theme-c) var(--theme-hue-warm));
```

**Muted is nearly free** - it is not a palette anybody designs, it is the same hues with chroma turned down. One value. A whole column of the matrix costs almost nothing.

### This puts FLAVOR back in (reversing the earlier "cut it" call)

Flavor was cut on the assumption that flavors were hand-authored cells. Under a harmony model they are not - they are **hue tokens per theme**, which is cheap. And decisively:

**Flavor is the mechanism that makes harmony visible.** You cannot demonstrate a *rainbow* with one hue. The rainbow IS the flavors. Cutting flavor does not simplify the system - it deletes the thing the Happy theme is made *of*. Same for analogous: the entire point is several neighbouring hues sitting together.

So flavor is **load-bearing, not decorative.**

### The Happy theme is already built

The current poster flavors - `warm / cool / stone / moss / rose`, described in their own comment as a **"70s muted rainbow"** - are exactly a rainbow harmony at mid chroma. That is `Happy`, shipped. This is a generalisation of something that already works, not a start from zero.

### What it costs now

- **3 brands** - application maps (*where* color lands)
- **3 themes** - a hue set + a chroma level
- **4 emphases** - a lightness value each
- **flavors** - hue names that resolve per theme

Everything else is composition; `light-dark()` covers scheme in the same declaration. **The full grid becomes affordable** - so "less is more" and "build the whole matrix" stop being in conflict, and the earlier "build the cross, not the grid" hedge is no longer needed.

### The honest crack

**Equal chroma at different hues is not perceptually equal.** Yellow physically cannot reach the chroma blue can, so a naive `oklch(L C H)` sweep gives a weak yellow and a screaming blue. Expect a **per-hue chroma ceiling** as a correction table. That is a handful of values, not a re-authoring - but it is real, and it is where the fiddly time actually goes.

### The two structural moves

Everything new comes from two changes to what exists today:

1. **`data-emphasis` moves from `<html>` down to the section / milestone.** Today it's global, so a page can only ever be *one* mood. Once it's per-section, Default / Alternate / Focused / Immersive can **stack on one page** - which is the entire GoFundMe scroll. This single relocation is the unlock.

2. **A new `data-theme` takes over the global color job.** Brand goes back to being purely shape. Theme is "which arm of the company" - and it's the axis that produces the *"that's almost GoFundMe... now purple"* beat.

### Emphasis is intensity; Flavor is hue

They are two dimensions of one color space. They were collapsed before only because emphasis had nowhere to live.

| Emphasis (intensity) | What it is |
|---|---|
| **Default** | the page's own paper - no band |
| **Alternate** | a soft tint (the light grey / cream section) |
| **Focused** | bold, saturated (the yellow band) |
| **Immersive** | deep, inverted (the dark green band) |

Flavor then picks the **hue** inside that band: `warm`, `cool`, `moss`, `rose`, `stone`. So a poster is `data-emphasis='focused' data-flavor='warm'` (bold harvest gold) or `data-emphasis='immersive' data-flavor='moss'` (deep avocado).

**Proof the axis was missing:** the current flavor list is `warm / cool / stone / night / moss / rose`. Five are **hues** at one mid intensity. **`night` is not a hue - it's a darkness.** Its own comment admits the problem:

> *"deliberately a MID tone, not espresso: a near-black card in a light wall of muted pastels reads as a hole."*

`night` was doing emphasis's job from inside the flavor list, and had to be watered down to survive. Under this model it stops being a flavor and becomes `emphasis='immersive'` - free to be as dark as it likes, because the whole band goes with it. It also explains why the wall feels tonally flat today: six flavors, one intensity, zero range.

---

## Theme supplies the pigments; Brand decides where they go

The snag that forced this (2026-07-14): *"Marketing green has green headings and a white background. But Product doesn't want to use color that way."*

Both are true, and the palette alone cannot express the difference - because that difference is not a palette. It is **how much color a brand allows, and which roles it spends it on.** That is a discipline, and discipline is character - so it belongs to **brand**.

The token set today is a **hierarchy ladder** (`--ink-primary` / `-secondary` / `-auxiliary`). It can say "the most prominent ink"; it cannot say "headings are green but body copy is black." So brands need a small set of **optional role slots** they may or may not declare.

**A brand is not a diff off a default.** Product is not "Marketing minus a token." It may well reach for the same green - as a tinted surface, a hairline stroke, a state color, something in a chart - while its type stays neutral. That is not *fewer slots declared*; it is **a different, deliberate way of spending the same pigments.** So each brand **states its whole application map** - the same doctrine already running in `settings.css` (*"every emphasis block restates the FULL set - no chaining"*), applied one level up.

```css
/* THEME - binds pigment ROLES to actual colors. Says nothing about where they land. */
[data-theme="green"] {
	--pigment:        oklch(… green);
	--pigment-deep:   oklch(… dark green);
	--pigment-tint:   oklch(… pale green);
	--neutral-ink:    near-black;
	--paper:          white;
}

/* MARKETING - color is loud, and it lands on the type. */
[data-brand="marketing"] {
	--ink-heading:  var(--pigment-deep);
	--ink-primary:  var(--neutral-ink);
	--fill-primary: var(--paper);
	--accent:       var(--pigment);
}

/* PRODUCT - a full, different composition. Still green. Nothing like Marketing. */
[data-brand="product"] {
	--ink-heading:    var(--neutral-ink);   /* type stays quiet */
	--ink-primary:    var(--neutral-ink);
	--fill-primary:   var(--paper);
	--fill-secondary: var(--pigment-tint);  /* green shows up as SURFACE */
	--stroke-primary: var(--pigment);       /* and as line */
	--accent:         var(--pigment-deep);
}
```

The type layer reads the role slot:

```css
h1, h2, h3 {
	color: var(--ink-heading);
}
```

### Why this stays additive, not multiplicative

The indirection is the whole trick:

- **Brand** maps semantic roles -> **pigment roles** (`--pigment`, `--pigment-deep`, `--neutral-ink`)
- **Theme** binds pigment roles -> **actual colors**

Any theme's pigments flow into any brand's map with nobody authoring the pair. It is **brands + themes**, never brands x themes. Three brand blocks and three theme blocks give nine coherent looks - all designed, none a cross-product accident.

**Risk to verify, not assume:** that is a `var()`-of-`var()` chain, the same shape as the `--t-*` layer that failed in top-layer popovers. But the shipped `--app-*` work is also that shape (`--app-stroke: var(--stroke-primary)` -> `light-dark(…)`) and it **verified clean inside a top-layer popover** during the light-dark pilot. So there is evidence one level resolves. Make the popover the acceptance test the moment the first brand block lands, exactly as we did for `light-dark()`.

### The rule this produces

> **Brand may say WHERE color goes. Only Theme may say WHAT color it is.**

Brand never names a hue - it says *"headings take the brand color,"* never *"headings are green."* So it stays honest to the shape/color separation, and it survives every theme.

**This is also what makes the wow beat work.** Naively, sliding green -> purple would only swap an accent and the page would barely move. Here, Marketing has declared *"headings take the brand color"* - so switching the theme repaints **the headings too**. Green headings become purple headings, automatically. Product, standing beside it, doesn't budge except for its one accent. Same components, one token changed, and the visitor sees exactly how much color each arm is *allowed to spend*. That is an argument no palette swap can make.

### Consequence: a small role layer is needed

Today's tokens are hierarchy-only (`--ink-primary` / `-secondary` / `-auxiliary`) - they can say *"the most prominent ink"* but not *"headings are green while body copy is black."* This model needs a handful of **role** slots (`--ink-heading` first) layered *beside* the hierarchy ladder, never replacing it.

Keep the list ruthlessly short. Every slot added is a slot **every brand must now have an opinion about** - that is the real cost, and it is paid in design time, not code. Add a slot only when a brand actually needs to say something new with it, never in advance.

Note this brushes the pssst rule *"color belongs to context, not to type patterns."* It stays compatible: the color is assigned at the **type layer** reading a **scope token**, not inside a voice class and not inside a component. A voice still carries no color.

---

## Keeping the color math tractable (this is the part that decides whether it's buildable)

Naively, color cells = themes x emphases x flavors x schemes. That is a cross-product in the hundreds and it is not hand-authorable. **It must not be hand-authored.**

The escape is that the palette is already **oklch**, where intensity and hue are literally separate channels:

- **Emphasis** sets **lightness + chroma** (`L`, `C`)
- **Flavor** sets **hue** (`H`)
- The token composes them: `oklch(var(--band-l) var(--band-c) var(--flavor-h))`

So 4 emphases + 6 flavors = **10 authored values**, not 24 hand-tuned blocks. Multiply by themes and it stays linear instead of exploding. `light-dark()` (already piloted on `immersive`) covers the scheme axis in the same single declaration, so dark costs nothing extra.

**Known caveat:** equal L/C at different hues is not perceptually equal (a saturated yellow and a saturated blue do not read as the same "loudness"). Expect per-hue chroma nudges as escape hatches. That is a handful of overrides, not a re-authoring - but it is real, and it is where the fiddly time will go.

---

## The UI scope (the toolbox) - unchanged by any of this

The settings chrome stays a **sealed scope** (`[data-ui='app']`, already built): it reads only `--app-*` tokens, so a theme can **repaint** it but structurally **cannot resize** it. Themes may change fill, ink, stroke *color*, shadow, and corners; they may never change padding, size, gap, or border-*width* - a control that moves out from under the cursor is broken, no matter how good it looks.

That contract is orthogonal to everything above and needs no revision. The chrome just gets its colors from whatever theme is active.

---

## What this replaces, and why the current model failed

Today: `brand` (shape) x `emphasis` (whole-page color: default / muted / immersive / red-light), fully orthogonal.

It fails for one reason: **full orthogonality promises every combination, and nobody designed most of them.** Marketing's type was never checked against red-light's palette. "Some colors don't look great with some type" is not a bug in the code - it is the cross-product itself. The system was advertising states no one had ever looked at.

The fix is not to abandon separating shape from color - that separation is right and it stays. The fix is that **color needed two levels** (a theme identity, and a band within it), and **the band belongs to a section, not the page.**

Casualties, all of them good:
- `muted` and `immersive` as *page* moods dissolve. Immersive survives as a **band**.
- `red-light` **leaves the dial** and becomes a boolean override - which is what it always was. It has nothing to harmonize with, so "Marketing in red-light" stops being a question.
- `night` leaves the flavor list (see above).

---

## Open question: do Brand and Theme travel together?

The four current "brands" (Personal / Marketing / Product / Documentation) differ in **both** shape and color - so they are really **arms**. Two options:

- **One switch, two axes.** The UI offers "arm," which moves brand + theme together. The CSS keeps them separate (so the system can *prove* it could split them) but the switcher never invites garbage. **Recommended.**
- **Two sliders.** Expressive, and immediately re-opens the unvetted cross-product.

---

## Is a simpler version better?

The honest scorecard. The risk here is **not** the number of axes - it is the number of **authored cells** and the amount of design time each demands.

### Cut candidates, ranked

| Candidate | Saves | Costs | Call |
|---|---|---|---|
| **Flavor** | ~~The most~~ **Nothing** | ~~The "subtle liveliness" beat~~ | **REVERSED (see the harmony section).** Flavor was going to be cut on the assumption it was hand-authored cells. Under the harmony model it is hue tokens - cheap - and it is **the mechanism that makes harmony visible**. You cannot show a rainbow with one hue. **Keep it. It is load-bearing.** |
| **Themes per brand** (2-3 -> 1) | A lot | Beat #2 - *"that's almost GoFundMe... now purple"*. | **Do not cut.** This is the wow moment and the clearest proof that color is a system, not a paint job. |
| **Brands** (4 -> 2) | Real design time | Breadth. But two *maximally different* brands (Marketing + Interface/Claude) prove more than four mediocre ones. | **Cut to 2-3.** Keep Personal (it is the site) + Marketing + Interface. Documentation is nearly free (mono, hard corners, no color drama) - keep if it costs nothing. |
| **Emphasis** (4 -> 3) | A little | Not much. Alternate and Focused are close. | Optional. Try 4, collapse if two read the same. |
| **Red light** | Almost nothing - it is a boolean | A memorable finale, and it is already built | **Keep.** Cheapest beat on the board. |
| **The per-section playground page** | Medium | The place a visitor can actually *drive* emphasis + flavor per section - on a single-column timeline there is otherwise nowhere to see bands stack | **Keep** - without it, emphasis is invisible on this site. |

### Recommended shape (the simpler version worth shipping)

- **Brands: 3** - Personal (default), Marketing, Interface/Claude. Documentation if free.
- **Themes: 2 per brand** - enough to prove color is its own system.
- **Emphasis: 4 bands**, per section / per milestone.
- **Flavor: deferred.** The oklch hue channel stays in the token design as a seam, unused at launch.
- **Scheme:** `light-dark()`, already piloted.
- **Red light:** boolean override.
- **A playground page** where a visitor sets emphasis + flavor per section.

That keeps **every beat of the story except #4**, and #4 is the one Derek himself called "subtle."

### The thing to be honest about

The real cost here is **not code - it is design time.** Every theme x emphasis band is a palette someone has to actually look at and approve. The oklch composition makes them *cheap to express*; it does not make them *automatically good*. Budget the eye time, not the keystrokes - that is what makes or breaks this.
