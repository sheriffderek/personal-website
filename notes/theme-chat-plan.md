# Theme chat - plan (2026-08-11)

A visitor describes their visual design ("we're a fintech - navy, sharp corners,
Inter everywhere, lots of air") and the site re-paints itself into their brand
live, then explains every move in design-system terms. The theme system
performing itself IS the pitch (per the motion policy's framing) - this is that
pitch with the visitor's own brand as the input.

Status: planned, not scheduled. Not for Round 1. Related to the two parked AI
toys in CLAUDE.md ("Future fun") - shares their server-endpoint groundwork and
their `backstory` wall, but needs no answer bank: its corpus is the token
system, which already exists as code.

## Why the system is already shaped for this

The MOOD CONTRACT (top of `styles/settings/moods.css`) is, accidentally and
perfectly, an LLM output schema: 10 core tokens, fixed order, full restatement
required, every value a `light-dark()` pair. It was designed so a *human* can
see a dropped token at a glance; the same shape makes a *model's* output
trivially validatable. The model fills a form the system already defines - it
never writes CSS.

Likewise the cascade-layer law (`styles/settings.css`): because authority is
declared, a machine-authored mood block injected into the `moods` layer is safe
to admit - red-light still stomps it, unlayered modules still win where they
should. No new machinery makes this safe; the existing architecture does.

## The pen scope (recommended: level 2)

What the model's pen can touch, three widening levels:

1. **Preset picker.** Map the description onto existing axis values only
   (character x mood x scheme x flavor). Safe but weak - "we matched you to one
   of our three moods" is a menu, not a demonstration.
2. **Custom mood author - the sweet spot.** The model authors the full mood
   contract (10 core tokens + optional selection pair). For structure it
   *picks* the nearest of the five characters rather than authoring one.
   Color is where brands live; structure is where the system's opinion lives -
   that split is honest, and the narration writes itself: "your structure
   matched our Interface character; your palette became a mood we authored on
   the spot."
3. **Custom character too** (scale ratio, corners, font from the loaded
   families). More knobs, more ways to be ugly, fonts capped by what's loaded
   anyway. v2 at most.

## Mechanism

- **Endpoint.** A small PHP proxy holding the API key - the first server-side
  anything on this site (cost already priced into the parked-toys note in
  CLAUDE.md). Rate-limited, hard spend cap.
- **Context handed to the model.** The theme docs (`theme-model.md`, the
  settings.css map comment), the token vocabulary (mood contract, character
  roster, the `--color-*` ramp names from base.css), the loaded-font list, and
  a few worked examples (below). Small; no RAG (same reasoning as the parked
  toys). **`backstory` and milestone content never enter this context** - the
  model is styling the page, not discussing the career.
- **Response shape** (structured output, roughly):
  `{ narration, character, scheme, mood: { tokens }, moves: [{ token, value, why }] }`
  - every token assignment carries its why.
- **Validation - structural, never trust.** PHP checks: known token names only,
  full contract present, values matched against the ramp vocabulary (or a
  strict color grammar - open decision below). No free-form CSS ever reaches
  the page.
- **Application.** JS injects
  `@layer moods { [data-brand-mood="custom"] { ... } }` and sets
  `data-brand-mood="custom"` on `<html>`. Never an inline style on `<html>` -
  inline beats layers and would break the authority ladder. Character/scheme
  land through the existing settings machinery (settings-panel.js reflects
  from `data-*`, so the panel's controls stay truthful for free - the mirror
  model already handles a mood the sliders didn't set).
- **Narration in the chat UI** reads the moves back: "You said whitespace and
  clinical - so paper stayed white and your navy went into ink, not fill. Your
  teal is the `--accent`."

## Saying no is half the feature

The best pedagogical moment is a constraint refused and explained. "Can the
headings be red?" - "Our voices never carry color; color belongs to the scope,
so your red landed in the accent and the strokes." The system prompt should
treat the contract's rules as things to *narrate*, not just silently obey.
A request the system can't honor is not a failure case - it's the design-system
pitch at its purest.

## No trends taxonomy (decided 2026-08-11)

The model's weights already carry design-trend vocabulary (brutalism, Swiss,
glassmorphism, brand registers) far richer than any list we'd maintain - a
hand-built trends array is a stale fork of knowledge that lives elsewhere.
What the model lacks is OUR vocabulary, and that's the context above.

Calibration comes instead from a few **worked examples** in the system prompt:
description in, axis picks + mood tokens + narration out. Two are nearly free -
Stripe's take is already written as comments in the Technical mood block, and
GoFundMe drove the Marketing character. Three to five total; they double as the
eval set when tuning the prompt. If trend-mapping ever needs a real procedure,
it becomes a house skill grown from actual transcripts after the toy runs
(the target-notes path) - never pre-built.

## Guardrails

- Prompt injection is low-stakes by construction: the worst a hostile visitor
  achieves is an ugly palette on their own screen. The validation layer, not
  the prompt, is the security boundary.
- Off-topic chat: the endpoint answers styling requests only; anything else
  gets a one-line redirect. No career questions here (that's Toy 1's job, with
  its own corpus discipline).
- Cost: rate limit per IP + a monthly cap at the provider. A public free-text
  box pointed at a paid API is the actual risk surface, not the CSS.

## Open decisions (Derek's calls, recorded 2026-08-11)

1. **Pen scope** - level 2 (custom mood + nearest character) as recommended,
   or structure knobs in round one?
2. **Values vocabulary** - must the model speak the `--color-*-N` ramps (stays
   in-system, coarser brand matches), or may it emit raw oklch (closer matches,
   but "raw values are a smell" gains a machine exception)? Pure taste call.
3. **Persistence** - does a custom mood survive in localStorage like the other
   preferences? URL-shareable someday (`url-state-plan.md` territory - a
   recruiter sending "the site in our brand" to a colleague is a strong share)?
4. **Where it lives** - a door in the settings apparatus (the band at >=1450
   has room; the popover doesn't), or its own page?
5. **Build order** - CLAUDE.md currently says the coverage grid (Toy 2) is
   probably first. This shares the endpoint groundwork with the toys but
   nothing else; if it jumps the queue, that note should say so.

## Non-goals

- No RAG, ever, at this scale.
- No trends/style taxonomy data structure (see above).
- No bare-CSS output path - the model fills the contract or nothing applies.
- Not Round 1.
