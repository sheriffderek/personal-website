# practice-layers - handoff (from the job-search session, 2026-09-19)

Holding spot. Copied here 2026-09-20 because the originals lived in /tmp and would have been cleaned. **Derek has not yet said where this belongs** - `experiments/` was picked only because the site's other standalone prototypes live here. Nothing in this folder is committed until he says.

**Every caption in `index.html` is a placeholder.** Derek writes the real two lines per layer himself (see "No sentence ships without Derek's OK" in CLAUDE.md).

## How we work on this one (Derek, 2026-09-20)

Derek tests on his phone, from the live site: https://derekthomaswood.com/experiments/practice-layers/ - so **every change he needs to look at gets committed and pushed right away** (this folder only, never his other in-progress files), with the stylesheet's `?v=` stamp bumped if the CSS changed. Then confirm the deploy landed before telling him to look. A change that only exists locally is a change he can't check.

## The idea, in Derek's words

"I don't just 'switch roles' I add new layers."

It started from his question of how to explain "15 years": a flat number invites comparison with a 15-year corporate design ladder, where his path reads weakest. The layers visual replaces the number with a shape. He wants it at the top of the main page of derekthomaswood.com, and as "a component we could drop anywhere."

## Decisions Derek made (2026-09-19)

- **No years anywhere, on purpose:** "the idea here is not to think about years because I might start to date myself." Order is the message. Panel heights are narrative weight, never a countable time scale (there is a WHY comment in the CSS).
- **Every layer must pass one test:** "it's all about what I bring to the business." Band years and high school are out.
- **The base layer is art AND technical education together** (art school, plus Flash, Max/MSP, programming in college). The design/code bridge is in the foundation, not added later.
- **PXL senior dev does NOT get its own layer** after senior designer; it folds into the agency layer. A layer marks when a capability was first added, not a job list.
- **The startup chapter** was missing from his sketch and must be in.
- **AI is the most recent layer.** His words (saved verbatim in `job-search/about-derek/his-own-words/stories.md`, 2026-09-19): "I did a LOT of AI agent workflow exploration on list at ease / and have done a bunch of exploration on how to intetgrate things into product / also just have a lot of experience in how it changes how teams work for good- and mostly bad."
- **Per-layer content template** (from his sketch, his red box): (1) what it is - a thing they can recognize and place, job title or focus; (2) what that gives you - why it matters to the business; (3) sticky text so they scroll a bit and feel it stacking.
- **Closing line, his words:** "This experience - across every part of the thinking and planning -- Half my waking life -- devoted to getting you to your goal." Ends on "What's next?"
- **The phone version is long-scroll and that is fine** ("that's normal scrolling for a phone").

## What changed on 2026-09-20 (Derek reacting to the first prototype)

His bar for the whole thing: **"it has to feel like these experiences are stacking over time -- or it's not worth doing."**

- **One component, two tellings, picked by the room it has** (a container query, not the screen). Derek: big screen is "a different layout / swapped... That explains it well when there's room. Then on the smaller screens - like phones - we're telling the story differently. And it's adding up - almost backwards."
  - **With room: the staircase chart** (his first sketches, `sketches/1.webp`-`2.png`). Base layer is the bottom bar, each new layer stacks on top starting further right, every bar runs to the right edge. A bar shows its label; **click** opens its two lines (hover was tried and was janky - his call). Opening one closes the rest.
  - **Narrow: the scroll story.** Base layer at the top, scroll down through the layers.
- **The old "compact" states (1-3) were cut.** He didn't know what they were for. What they were reaching for turned out to be the finale (below).
- **Stacking at the top, with names.** First try (his five-frame sketch): each passed layer leaves a thin unlabeled band across the top. It stacked, but Derek: "it's not really saying what each section is... we need it to be labeled so they see the name of each." So each layer now has **three things**: a short **tag** (the name that holds its place and stacks at the top), the **what** (recognizable job / role / focus), and the **why** (why that matters to the business). His goal for the stack: it "clearly stacks in a story that says 'woah / look at that journey.'" **Where the name sits (his next sketch, same day):** each section reads Role, then Why it matters, and ENDS on its Name - you arrive at the name, and "stick after scrolling past." (The first build put the name at the top of the section; wrong.) Built as a sticky one-row `.tag` that comes after the story and is a direct child of its layer (a sticky element stays stuck until its parent ends, and a layer runs to the end of the story); each tag sticks one row lower and one stripe further in. The tags in the test page are working names, not his.
- **How the story hands over to the finale (his storyboard, five panels left to right):** (1) the full stack of names with the last section; (2) scroll past it - the names leave off the top as ONE piece while the stripes keep running down the left on their own, "revealing the layers on the left"; (3) just the thin stripes, tops aligned, bottoms stepped; (4) they widen, the frame lands, the closing line arrives; (5) "then as we scroll - we can use those to show it a few ways" - the same layers as a horizontal stack (first layer at the bottom) with a tall frame cutting down through all of them, and a line handing over from the closing words. **All five panels are built.** The names exit together (checked: every name moves by exactly the same amount), each stripe's tail ends exactly where the finale begins, each thin finale column starts at the same x as its stripe, and the line from the closing words ends exactly at the top of the tall frame. An earlier attempt let the names fold away one under another - rejected ("sorta").
- **The bottom** must be straight columns ending in steps, like his mockups - not L-shapes wrapping under. Built by making the stripe the layer's left BORDER instead of its background.
- **The finale** (his four-frame sketch): after the last layer, (1) the stripes as they were, thin at the left; (2) they widen to fill the screen; (3) a frame lands across all of them - one slice of "now" cuts through every layer, "explaining how dense this stack is"; (4) the frame thickens and the closing line arrives. Tops aligned, bottoms stepped, first layer longest. Built end-state-first; the scroll-driven journey is behind `@supports (animation-timeline: view())`, so a browser without it just shows the finished figure. In the staircase layout the finale stands down (the chart already is that shape, on its side).
- **Palettes** (Derek: "let's save the colors - but for now try something with our color theme system colors / maybe simplified every other color or something"). The component only reads `--layer-1..9` plus page and ink slots, so a palette is just a different filling of them, flipped with `data-palette` on `<html>`. **Saved rainbow** = his Figma colors, kept exactly. Three read the site's mood-contract slots and name no color, so they repaint with mood and scheme: **Deepening** (one step from paper toward ink per layer - stacking shown as density; the working favorite), **Every other** (two fills taking turns), **Warming** (the same climb toward `--accent`). The last layer is always full ink with the paper color for its words. The test page now loads the site's real tokens (`/styles/color-scales.css` + `/styles/settings.css` - tokens only, no global element styles) and has palette + mood switches; mood uses the site's own `mood-preference` key. Checked live: every palette resolves, and Deepening repaints between Quiet and Expressive.
- **Contrast (Ivy, 2026-09-20: "it needs more contrast").** Deepening's first ramp climbed 6% a layer - neighbors were hard to tell apart, and dark words sat on middle grays. Now: wider steps, and the ramp SKIPS the middle grays (between ~38% and ~58% of the way to ink neither dark nor light words read well) - six light layers with dark words, then three dark layers with paper-colored words. Every other got a real second tone (the theme's own two fills sit too close); Warming climbs further; words on tinted layers use full ink. Whether a layer's words are dark or light is the PALETTE's call now (`--layer-N-ink` slots), not a flag on the layer. Measured live, words against their own layer, all three moods x all four palettes (light scheme): weakest is about 4.3:1 (Warming's deepest layer in Quiet), Deepening's weakest is about 5.4:1, most are 8:1 and up. **Measuring gotcha:** the names fade their color over 0.25s and a mood switch takes longer to settle - measure too soon and you read a mid-fade color and get a false 1.x. Wait a full second after switching. Not judged yet: dark scheme.
- **Section length and breathing room** (Derek): sections are longer (`--step: 14rem`, multiplied by each layer's `weight`) and the text starts further down its section (`--breath: 7rem`), so a section opens as plain color, then the words. Both are single tokens at the top of the component's block.
- **The hold** (Derek: "once they all stack up - they stay there for a little longer while scrolling so they don't all stack up and immediately scroll away - we need to let that sink in a little"). After the LAST layer's name sticks, the finished stack sits through `--hold` (85vh) of empty scroll before everything leaves together. Checked: all nine rows hold still from the start of the hold to its end, then move by exactly the same amount. **Open (his words): "not sure what would be a transition that would be cool"** - the hold is currently just empty space beside the stripes. Nothing has been tried there yet.
- **"What's next?" is a ninth layer like all the others** (Derek: "the last one should behave like all the others - not be its own unique problem"). It was briefly a special black block with its own sticky rules, its own height, and its own bugs (mis-centered text on his phone). Now it is one more entry in the list with `dark: true` (light ink on its black tone): its own stripe, section, and a name that sticks. That also gave the finale's ninth column a real stripe to continue from. The hold is no longer "about" that layer - it is what follows the last layer, whichever it is. Its "why" line currently carries Derek's closing sentence, which the finale also shows - a duplication to settle when he writes the real lines.
- **The finale runs on a small scroll script, not CSS scroll-driven animation.** The CSS version never moved on Derek's iPhone (iOS Safari doesn't have `animation-timeline` yet). Now the script turns the scroll through the finale's runway into three dials from 0 to 1 (`--widen`, `--frame-in`, `--arrive`) written on `.finale`, and the CSS reads them; every dial defaults to 1, so no script = the finished figure. **Pacing (Derek): the widening had to be "5x slower"** - it now takes about 2.8 screens of scrolling inside a ~4.5-screen runway. The runway length (`.finale[data-driven]` in the CSS) and the beat fractions (`FINALE_BEATS` in the script) are one decision.
- **Names are hidden until they stick** (Derek: a name in plain view at the end of a section reads like the NEXT section's heading). The row is always there in its layer's color; only the words wait, and fade in when the script marks the name `.is-stuck`. A pure-CSS version (the name tucked under its story, uncovered by the story's edge) was built and rejected - it showed sliced, half-covered letters. Derek: "not any new positioning tricks."
- **The staircase is not an accordion** (Derek): every bar opens and closes on its own, the black "What's next?" bar starts closed like the rest, each bar wears a `+` at its right edge (`-` when open) and a pointer cursor. $todo for the real build: each bar's name becomes a `<button aria-expanded>`.
- So "the component needs NO JavaScript" is no longer the whole truth: the layout, the stacking, and the exit are pure CSS; the script adds the name fade, the finale's motion, and click-to-open. Everything still reads with the script off.
- This settles the old "open design question" about the compact view's orientation: tops aligned, bottoms stepped.

**Checked in a browser 2026-09-20:** staircase renders and click-to-open works; at phone width the names stick at 0 / 28 / ... one row apart, each only after its own section has scrolled past; the nest ends in stepped columns; the finale reaches its end state. **Not checked:** a real phone, Safari, Firefox's fallback, and how the scroll FEELS - which is the whole question.

## Working layer order (working labels, not his final words)

1. Art and technical education
2. Freelance web design and development
3. Agency front-end (PXL folds in)
4. Startup: the bridge between design and engineering (ShoutQ; the record says "UX engineer before the title existed")
5. Consulting
6. Product design
7. Education: founding and running a school
8. AI
9. What's next?

**Open:** startup-then-consulting is a default order (both arrive in the same stretch); Derek has not confirmed. Sourced from `job-search/about-derek/work-history-full.md`, cross-checked against `resume-exploration/source-materials/dereks-history.md` (dates matched).

## How the prototype works

- One `<practice-layers>` custom element. Each layer is a `<section>` nested inside the previous one; the parent's background showing at the left IS the stripe, so earlier layers persist.
- Story text is `position: sticky`.
- Container queries (not media queries), so it sizes to wherever it is dropped.
- (The `data-view` attribute is gone: the layout follows the container's width.)
- The component needs NO JavaScript. The script on the test page only stands in for a template (the note said "a recursive Vue component"; on this plain-PHP site that would be a recursive PHP partial fed one list).
- Layer colors are token slots (`--layer-1..n`). The `:root` tokens and the two voice classes at the top of `practice-layers.css` are stand-ins - on this site they come from the theme system and typography, and the component should consume the site's semantic slots.
- Verified in headless Chrome at phone width: all four states render, and sticky holds at 1rem after scrolling 250px into a layer (works with `container-type` on the host). **Not verified:** a real phone, and how the scroll feels.

## Gotchas

- **The test page is static HTML, so its stylesheet link carries a hand-bumped `?v=` stamp.** Forget to bump it after a CSS change and phones keep the old stylesheet against the new markup - which looks like the component is broken (unstyled names, nothing sticking). This happened on 2026-09-20.

- Sticky dies silently under any ancestor with `overflow: hidden/auto` (commented in the CSS).
- A container query cannot style its own container, so the roomier-stripes rule is set on the first `.layer`.
- Decided against GSAP/ScrollTrigger: nothing animates, the page just scrolls. If a flourish is wanted later: native CSS scroll-driven animations behind `@supports` (no Firefox support yet), decoration only.

## Still needed from Derek

- His two lines per layer, in his own words (never drafted for him).
- Confirmation of the startup/consulting order.
- A role-based name for the component ("practice-layers" is a working name).
- Where this folder should live.

## Files

- `index.html` - test page: the staircase in a wide box, the scroll story + finale in a phone-width box
- `practice-layers.css` - the component CSS
- `check-small.png` - the other session's last render
- `sketches/` - Derek's six Figma sketches in the order he made them (1 = first horizontal bar chart, 6 = latest nested-stripes idea)
