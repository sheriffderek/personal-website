# Theme walk notes (2026-08-25, pre-show)

## NEXT SESSION - start here ("let's work on the themes")

0. **Parked, not a problem (Derek, 2026-09-27): what House means.** House
   denotes something personal - a little splash of Derek's own color - and
   that's worth coming back to. As it stands there's not much a visitor
   could complain about, so it waits.

1. **Chrome overlays (decided 2026-08-25, unbuilt).** Each theme DECIDES if
   it dresses the chrome - chosen, never derived. Neutral baseline stays
   default; an opting theme adds one block repainting the --app-* slots.
   All overlay blocks live in styles/modules/settings-panel.css (it's
   unlayered - nothing else can win those slots; the red-light take there
   is the first example and the pattern). Motivating case: the settings
   BAND sits bare on colored page grounds (fixed grays clash - see the
   green-room complaint). First candidates: Quiet, Technical.
2. **Un-walked cells**: Editorial everywhere, Terminal light, Marketing x
   Technical/Quiet, Interface x Expressive.
3. **Saved for last**: Expressive-light poster/graphics colors (refs
   digested in theme-model.md, Product x Expressive entry).
4. **Post-show**: Interface's code-sample identity; marketing card
   backgrounds (parked under its evocation below); Product rename question.
5. **PARKED IDEA (Derek, 2026-09-13): name what each combo emulates.**
   When a cell settles, give it a little sentence/string citing the
   real-world reference it's channeling - "Slack", "OpenAI", "Linear",
   that register. Extends the per-character evocations below and the
   per-mood chrome-take refs (settings-panel.css) down to the combo
   level. Only for SETTLED cells - the string is a record of a verdict,
   never an aim written in advance. Someday-maybe: surface it in the
   panel as pitch copy ("this look channels Linear"). Note only.
   THE SHELF so far (Derek, 2026-09-13 - raw riff, none settled):
   Discord = dark; Linear = dark; Slack = purple-dark; DOS / old Macs =
   green text on black; Photoshop = working gray; Reddit = light;
   Claude = a little organic / wheat; editorial could go COLOR TEXT
   (the Fuzzco all-red-type careers page); Chrome and Apple do pill
   "islands" of circular controls (which the tray's trigger cluster
   already is); Netflix = dark + red; Chrome dark (2026-09-26) = WORKING
   GRAYS, not black - mid-gray layers where a higher surface is a LIGHTER
   gray (dropdown lighter than toolbar, toolbar lighter than tab strip),
   no borders, no shadows, rows as big-radius pills a step lighter on
   hover, white text with light-gray secondary. The trick worth naming:
   elevation by lightness. Loose axis gravity: DOS/green ->
   Terminal, Photoshop-gray/Linear -> Interface, wheat/organic ->
   a warm Quiet or Technical take, Fuzzco red-type -> Editorial,
   Netflix dark+red -> practically red-light's cousin. Vercel and
   shadcn = strict black-and-white, hairline, flat -> Quiet (the
   chrome-takes comment in settings-panel.css already cites shadcn
   for Quiet's chrome; Vercel is the same citation at page scale).
6. **Journal pages barely theme (Derek, 2026-09-10 - noted for the missing
   "emphasis" layer, not scheduled).** On an entry page the axes almost
   don't show: it's all calm-voice prose, no posters, no variant-carrying
   components - switching character/mood/flavor moves only type and inks,
   subtly. The timeline demonstrates the system; the journal under-
   demonstrates it. If/when an emphasis layer gets designed, the journal's
   small surfaces are the candidates: entry-figure frames, blockquote
   strokes, the date stamps, code panels - places a mood/flavor could
   speak without touching the prose. Note only - the fix is a design
   session, not a patch.


## The contrast bar - every cell (Derek, 2026-09-29)

Every character x mood x flavor x scheme cell meets **WCAG 2.2 AA**: 4.5:1 for body text, 3:1 for large text and for UI parts (borders, knobs, option edges). Under `@media (prefers-contrast: more)` the bar is **AAA** (7:1 text) - a visitor who asks for more gets way more. Measure every cell, don't eyeball it: a hue that looks fine in one mood can fail in another, and the ink/fill pairs in `settings/moods.css` are where it's won or lost. APCA (the WCAG 3 method) is still a draft - a useful second opinion, not the bar. First worked high-contrast example: the layered experience chart (`styles/modules/layered-experience-chart.css`). Open: how each mood answers `prefers-contrast: more` site-wide (per-mood AAA ink/fill pairs, or one shared high-contrast pass).

## What each character EVOKES (Derek, 2026-08-25 - the measuring stick)

**Naming, open (Derek, 2026-09-27): "Character" is increasingly "Platform."** Since the characters became the surfaces of one company (front door, pages one level in, the app, the blog, the developer surface) and started carrying the chrome's grammar (Interface = app chrome, Marketing = roundy, Terminal = TUI), the axis names *where you are* more than *a personality*. A note, not a rename - "Character" is wired through the attribute (`data-brand-character`), the storage key, the panel label, and the docs, so any rename is its own deliberate slice.

- **Product** - the pages one level in: a company's marketing site past the
  front door, explaining one thing (Derek, 2026-09-26: "like a sub page in
  Stripe"). Settles the old "a little confusing / three things at once"
  question: the characters are the surfaces of one company - the mapping
  lives in the header of styles/settings/characters.css. So Product's chrome
  is a marketing site's nav, and app chrome belongs to Interface.
  The DEFAULT look is a separate call (Derek, 2026-09-26): whichever combo
  reads as the safest good starter becomes the default - Product is not the
  default by definition. (The default character is structural today -
  :root in settings/base.css IS Product - so a different default character
  would need its own knob, like DEFAULT_MOOD.) The rename question is parked
  here; with the surface framing the name may simply stand.
- **Marketing** - warm confident INVITATION: bigger display fonts, a
  landing-page / brochure feel. "We're successful and you're welcome."
  PARKED IDEA (2026-08-25): marketing brands often give their CARDS their
  own background fills - lean into that in some places (card grounds as a
  marketing spending rule; the bands story). Not built.
- **Interface** - the AI-app / docs / code-editor surface: ChatGPT, Claude,
  CodePen, documentation with code examples. The mood dial maps onto real
  refs: expressive = Glitch (colorful), Claude = some color opinions (or
  quiet?), quiet = ChatGPT (just grays and neutral).
- **Editorial** - a blog / fancy writing TAKING AN ANGLE: fancy serif
  display fonts, the patience of print, opinionated.
- **Terminal** - literally a monospace terminal: code, raw. Charming
  because it refuses to dress up.

Judge every combo as: "does this still evoke the character's feeling, under
this mood's policy, in this flavor's family?"

**Red light (2026-08-25): APPROVED both schemes** - chrome joins the room
(the first sanctioned chrome take), gradient-token leak sealed.

**Sweep note (2026-08-25): flavors blanket-approved "fine for now"** - the
Earth/Cool/Sweet rows stand as built unless a specific note below says
otherwise. Remaining walk: the un-judged characters (Editorial everywhere,
Terminal light, Marketing x Technical/Quiet, Interface x Expressive), and
the saved-for-last item: Expressive-light poster/graphics colors.

Shorthand notes as `character/mood/flavor light|dark: note` - logged here,
fixed inline. Each section states WHAT THE COMBO IS TRYING TO BE (from the
mood briefs, the flavor definitions, and theme-model.md's register sheet -
references are internal shorthand, never public labels). Character rows are
flavor-free spending briefs; the flavor line above them supplies the hues.

## Expressive - color at full voice - many hues, high presence; warm paper light / velvet room dark, pigments glow, never dim

### Expressive x House
*Aim: YOUR rainbow ("hey Figma - I'm fun"): white lifted cards, per-card link colors, wild accent set; dark = colored ink streams per card on the black room*
- [x] Product: Figma - playful canvas; accents on chips/posters, never on body type. Wrong when: page ground takes pigment or headings go colorful | VERDICT: type approved. 08-25: dark room approved ('dark colors are fun'); LIGHT poster/graphics colors still uninspiring - SAVED FOR LAST, the closing polish item
- [x] Marketing: GoFundMe bands - full-bleed sections at full voice, display type may wear pigment. Wrong when: bands go timid or all land one hue | VERDICT: approved - structure makes its point; pill + band architecture open | 09-27 (light + dark): on the right track, a way to go - it should look like Figma, with well-chosen colors (the saved-for-last Expressive color pass)
- [ ] Interface: Anthropic - warm humane chrome, gentle washes on feature surfaces. Wrong when: chrome takes pigment or an accent hits alarm level
- [ ] Editorial: WIRED - pigment as the headline act at display scale. Wrong when: color retreats to decoration
- [x] Terminal: the colored-streams console (dark is home); readouts each their own hue | VERDICT 08-25: "this one makes sense" - colored-readout model confirmed (Option 1); panel-variants keep their panel under terminal (the ghost-text fix) | 09-27 LIGHT: "not very cool or inspired" - dark is home; the light take needs its own idea | 09-27 DARK: "kinda fun" - confirmed; only the light take needs the new idea (printout / greenbar, or a recognizable light terminal theme)

### Expressive x Earth
*Aim: the rainbow anchored in greens/browns: amber secondary fills, green accent*
- [ ] Product: Figma - playful canvas; accents on chips/posters, never on body type. Wrong when: page ground takes pigment or headings go colorful
- [x] Marketing: GoFundMe bands - full-bleed sections at full voice, display type may wear pigment. Wrong when: bands go timid or all land one hue | VERDICT 09-27: good enough for now, light and dark (the brown date pill, roundy chrome) - come back and dial it in
- [ ] Interface: Anthropic - warm humane chrome, gentle washes on feature surfaces. Wrong when: chrome takes pigment or an accent hits alarm level
- [ ] Editorial: WIRED - pigment as the headline act at display scale. Wrong when: color retreats to decoration
- [x] Terminal: the colored-streams console (dark is home); readouts each their own hue | VERDICT 08-25: "this one makes sense" - colored-readout model confirmed (Option 1); panel-variants keep their panel under terminal (the ghost-text fix)

### Expressive x Cool
*Aim: the rainbow with a cool cast: sky secondary fills, teal accent*
- [ ] Product: Figma - playful canvas; accents on chips/posters, never on body type. Wrong when: page ground takes pigment or headings go colorful
- [ ] Marketing: GoFundMe bands - full-bleed sections at full voice, display type may wear pigment. Wrong when: bands go timid or all land one hue
- [ ] Interface: Anthropic - warm humane chrome, gentle washes on feature surfaces. Wrong when: chrome takes pigment or an accent hits alarm level
- [ ] Editorial: WIRED - pigment as the headline act at display scale. Wrong when: color retreats to decoration
- [x] Terminal: the colored-streams console (dark is home); readouts each their own hue | VERDICT 08-25: "this one makes sense" - colored-readout model confirmed (Option 1); panel-variants keep their panel under terminal (the ghost-text fix)

### Expressive x Sweet
*Aim: the rainbow warmed into candy: rose secondary fills, fuchsia accent*
- [ ] Product: Figma - playful canvas; accents on chips/posters, never on body type. Wrong when: page ground takes pigment or headings go colorful
- [ ] Marketing: GoFundMe bands - full-bleed sections at full voice, display type may wear pigment. Wrong when: bands go timid or all land one hue
- [ ] Interface: Anthropic - warm humane chrome, gentle washes on feature surfaces. Wrong when: chrome takes pigment or an accent hits alarm level
- [ ] Editorial: WIRED - pigment as the headline act at display scale. Wrong when: color retreats to decoration
- [x] Terminal: the colored-streams console (dark is home); readouts each their own hue | VERDICT 08-25: "this one makes sense" - colored-readout model confirmed (Option 1); panel-variants keep their panel under terminal (the ghost-text fix)

## Technical - color controlled - one family, spent with discipline; white lab light / deep room dark, hue holds

### Technical x House
*Aim: the GitHub-utilitarian read: b&w room (white / slate-950), rainbow SLICES on the masses are the only color, blurple accent as the one signature*
- [x] Product: Stripe-register structure; color on accents + one gradient flourish. Wrong when: a second unrelated hue, or family spreads into body ink | VERDICT 08-25: ALL FLAVORS LOOK GOOD (House b&w+slices, Earth, Cool, Sweet) - the technical row on Product is signed off
- [ ] Marketing: same bands, monochromatic confidence. Wrong when: a neighboring hue sneaks into a band
- [x] Interface: GitHub/Stack Overflow - color is DATA (tags, statuses). Wrong when: color appears where it encodes nothing | was navy, not loved (08-25) - fixed to neutral slate rooms; RE-JUDGE
- [ ] Editorial: The Verge - one family used HARD, duotone-leaning. Wrong when: the family loosens
- [x] Terminal: the analogous console (Solarized/Nord territory) | VERDICT 09-27: pretty good, light and dark (all four flavors); what the chrome could do better here is still open

### Technical x Earth
*Aim: climate-fintech: green family analogous, emerald accent, lime->emerald ramp; dark room #243028 (hand-picked)*
- [x] Product: Stripe-register structure; color on accents + one gradient flourish. Wrong when: a second unrelated hue, or family spreads into body ink | VERDICT: approved ("going great")
- [ ] Marketing: same bands, monochromatic confidence. Wrong when: a neighboring hue sneaks into a band
- [ ] Interface: GitHub/Stack Overflow - color is DATA (tags, statuses). Wrong when: color appears where it encodes nothing
- [ ] Editorial: The Verge - one family used HARD, duotone-leaning. Wrong when: the family loosens
- [x] Terminal: the analogous console (Solarized/Nord territory) | VERDICT 09-27: pretty good, light and dark (all four flavors); what the chrome could do better here is still open

### Technical x Cool
*Aim: THE STRIPE READ light / LINEAR dark - Cool OWNS this identity. Richened 08-25 ("miles ahead"): near-black navy room #0a102e, blurple accent full-strength both schemes, indigo->fuchsia glow ramp in dark.*
- [x] Product: Stripe-register structure; color on accents + one gradient flourish. Wrong when: a second unrelated hue, or family spreads into body ink | VERDICT: approved
- [ ] Marketing: same bands, monochromatic confidence. Wrong when: a neighboring hue sneaks into a band
- [ ] Interface: GitHub/Stack Overflow - color is DATA (tags, statuses). Wrong when: color appears where it encodes nothing
- [ ] Editorial: The Verge - one family used HARD, duotone-leaning. Wrong when: the family loosens
- [x] Terminal: the analogous console (Solarized/Nord territory) | VERDICT 09-27: pretty good, light and dark (all four flavors); what the chrome could do better here is still open

### Technical x Sweet
*Aim: the Lemonaid read: pink family analogous, rose->fuchsia ramp; dark room #2b000c (hand-picked)*
- [x] Product: Stripe-register structure; color on accents + one gradient flourish. Wrong when: a second unrelated hue, or family spreads into body ink | VERDICT: approved
- [ ] Marketing: same bands, monochromatic confidence. Wrong when: a neighboring hue sneaks into a band
- [ ] Interface: GitHub/Stack Overflow - color is DATA (tags, statuses). Wrong when: color appears where it encodes nothing
- [ ] Editorial: The Verge - one family used HARD, duotone-leaning. Wrong when: the family loosens
- [x] Terminal: the analogous console (Solarized/Nord territory) | VERDICT 09-27: pretty good, light and dark (all four flavors); what the chrome could do better here is still open

## Quiet - color nearly absent - grays pulled toward the middle, ONE murmured hint; nothing shouts, nothing pure black/white

### Quiet x House
*Aim: gray room + SAGE hint (the House green at whisper volume) - APPROVED reference state in dark*
- [x] Product: Notion/Muji - gray on white, generous space. Wrong when: anything demands attention by color | VERDICT: LOVED in dark - the reference state. 09-27: the whole default approved with its chrome - light, dark, and red light over it ("theming is going great")
- [x] Marketing: Lemonaid whisper-brochure - line art, white, one hint. Wrong when: a full-bleed pigment band appears | VERDICT 09-26: approved with its chrome ("also pretty great"), roundy since
- [x] Interface: OpenAI/Linear - near-monochrome; accent only marks the active thing. Wrong when: two things marked at once | VERDICT: approved as-is, light AND dark (08-25)
- [ ] Editorial: Kinfolk - newsprint + one deliberate mark per view. Wrong when: a second mark shows up
- [x] Terminal: Plan 9/acme - pale paper, barely-there marks. Wrong when (all Terminal): hierarchy arrives by size instead of weight | VERDICT 09-27: pretty good, light and dark (all four flavors)

### Quiet x Earth
*Aim: forest-green hint on the grays*
- [x] Product: Notion/Muji - gray on white, generous space. Wrong when: anything demands attention by color | VERDICT: approved
- [ ] Marketing: Lemonaid whisper-brochure - line art, white, one hint. Wrong when: a full-bleed pigment band appears
- [ ] Interface: OpenAI/Linear - near-monochrome; accent only marks the active thing. Wrong when: two things marked at once
- [ ] Editorial: Kinfolk - newsprint + one deliberate mark per view. Wrong when: a second mark shows up
- [x] Terminal: Plan 9/acme - pale paper, barely-there marks. Wrong when (all Terminal): hierarchy arrives by size instead of weight | VERDICT 09-27: pretty good, light and dark (all four flavors)

### Quiet x Cool
*Aim: sky hint on the grays*
- [x] Product: Notion/Muji - gray on white, generous space. Wrong when: anything demands attention by color | VERDICT: approved
- [ ] Marketing: Lemonaid whisper-brochure - line art, white, one hint. Wrong when: a full-bleed pigment band appears
- [ ] Interface: OpenAI/Linear - near-monochrome; accent only marks the active thing. Wrong when: two things marked at once
- [ ] Editorial: Kinfolk - newsprint + one deliberate mark per view. Wrong when: a second mark shows up
- [x] Terminal: Plan 9/acme - pale paper, barely-there marks. Wrong when (all Terminal): hierarchy arrives by size instead of weight | VERDICT 09-27: pretty good, light and dark (all four flavors)

### Quiet x Sweet
*Aim: rose hint on the grays*
- [x] Product: Notion/Muji - gray on white, generous space. Wrong when: anything demands attention by color | VERDICT: approved
- [ ] Marketing: Lemonaid whisper-brochure - line art, white, one hint. Wrong when: a full-bleed pigment band appears
- [ ] Interface: OpenAI/Linear - near-monochrome; accent only marks the active thing. Wrong when: two things marked at once
- [ ] Editorial: Kinfolk - newsprint + one deliberate mark per view. Wrong when: a second mark shows up
- [x] Terminal: Plan 9/acme - pale paper, barely-there marks. Wrong when (all Terminal): hierarchy arrives by size instead of weight | VERDICT 09-27: pretty good, light and dark (all four flavors)
