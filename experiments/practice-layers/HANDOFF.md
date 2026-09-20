# practice-layers - handoff (from the job-search session, 2026-09-19)

Holding spot. Copied here 2026-09-20 because the originals lived in /tmp and would have been cleaned. **Derek has not yet said where this belongs** - `experiments/` was picked only because the site's other standalone prototypes live here. Nothing in this folder is committed until he says.

**Every caption in `index.html` is a placeholder.** Derek writes the real two lines per layer himself (see "No sentence ships without Derek's OK" in CLAUDE.md).

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
- **Stacking at the top** (his sketch, five viewport frames): once a layer has passed, it leaves a thin band across the TOP, the same thickness as its stripe at the left - so a frame grows out of the top-left corner, one layer at a time. Built as a sticky `.band` that is a direct child of its layer (a sticky element stays stuck until its parent ends, and a layer runs to the end of the story).
- **The bottom** must be straight columns ending in steps, like his mockups - not L-shapes wrapping under. Built by making the stripe the layer's left BORDER instead of its background.
- **The finale** (his four-frame sketch): after the last layer, (1) the stripes as they were, thin at the left; (2) they widen to fill the screen; (3) a frame lands across all of them - one slice of "now" cuts through every layer, "explaining how dense this stack is"; (4) the frame thickens and the closing line arrives. Tops aligned, bottoms stepped, first layer longest. Built end-state-first; the scroll-driven journey is behind `@supports (animation-timeline: view())`, so a browser without it just shows the finished figure. In the staircase layout the finale stands down (the chart already is that shape, on its side).
- This settles the old "open design question" about the compact view's orientation: tops aligned, bottoms stepped.

**Checked in a browser 2026-09-20:** staircase renders and click-to-open works; at phone width the bands stick at 0 / 8 / 16 / 24px as layers pass; the nest ends in stepped columns; the finale reaches its end state. **Not checked:** a real phone, Safari, Firefox's fallback, and how the scroll FEELS - which is the whole question.

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
