# URL state plan (deferred - not built)

Share a link that opens the site in a curated look:
`/?target=gofundme&character=marketing&mood=quiet&scheme=dark&view=grid&filter=2`

The theming is the demo; a link that lands in a specific configuration is part
of the pitch. Planned 2026-07-11; design settled 2026-08-14 (fresh-eyes agent
review + Derek, this section down through "Slice order"); parked until wanted.
(Param names updated to the settled axis vocabulary - the old `brand` /
`emphasis` sketch predates the character/mood/flavor model.)

## The settled model (2026-08-14): dress the visit, not the default

The question that forced the design: "if the default ever meant marketing,
shouldn't the default be programmatic?" The answer, after a full review:
**no - the default stays absence, and what becomes programmatic is the
VISIT.** The index-0 law (flavors.css) survives; it was written anticipating
exactly this ("anything that sets those attributes must normalize to removal
instead"), and it was right.

Why absence-as-default stays (the reasoning, so it isn't re-litigated):

- **One DOM state per logical state.** Before the law, JS sometimes stamped
  `product` and sometimes removed the attribute - every default-cell selector
  needed two spellings (~25 doubled halves deleted in commit `abe1ea3`).
- **The zero state is the resilience story.** No-JS, private-mode storage
  throw, stale stored slug - every failure degrades to the site-as-itself.
  Always-stamping invents a new failure mode (stamp missing) with no floor.
- **The floor must be a real, shown design.** Characters state only their
  DIFFERENCES from the default; everything unstated falls through to `:root`
  (Editorial is "Product with a serif hat on" - its quiet voice IS Product's).
  That fall-through only works because the bottom layer is a complete design
  that is on screen every day, judged constantly. A "neutral stub" floor that
  never shows would still leak through every gap in every character block -
  an invisible design nobody reviews. Never build that.
  (Moods restate all 10 tokens instead of falling through - deliberate per-
  axis philosophy: an inherited font-weight degrades gracefully, an inherited
  color token renders as wrong contrast. Same principle, different material.)
- **Two roles, kept separate**: the FLOOR (what deltas fall through to) is
  `:root`/Product, structural, never a knob. The INITIALIZATION (what renders
  when nobody chose anything) is the `house_look` config slot below - empty
  by default, so behavior is byte-identical to today until it's ever set.

## Precedence (settled)

    URL param  >  target look  >  stored preference  >  house_look  >  absence
    └── server-side, per-visit, never persists ──┘      └ config ┘

- **URL beats target**: more specific wins - `?target=gofundme&character=
  terminal` is someone deliberately overriding the preset. Both resolve in
  one place (PHP), so the ordering is one array merge.
- **Target beats stored** (needs Derek's final ratification when built): the
  link is the pitch - the site dressed for the interview - and the visitor is
  one slider-touch from their own preferences resuming. Same law as URL-wins.
- **`house_look` loses to stored** - it's the site's own initialization, not
  a per-visit curation. PHP can't see localStorage, so PHP stamps it and the
  FOUC script overrides when a stored value exists (one `if` - the opposite
  of the pinned-axes rule that protects target/URL stamps from stored).

## Which axes a link or target may curate

`character` · `mood` · `flavor` ONLY. Machine-checked via a `targetable`
flag per axis in the config, never remembered:

- **Never `scheme`** - light/dark is the visitor's environment and comfort,
  not Derek's pitch (same reasoning that pinned the three-state toggle).
- **Never `sound`** - audio the visitor didn't choose is hostile.
- **Never red-light** - a stunt, not a company look.
- `view`/`filter` stay in the client-side URL lane, behind their existing
  layout gates (a param is a request, not an override of the layout rules).
- **Flavors are archetypes**: a target carries `"flavor": "earth"`, never
  `"flavor": "gofundme"` - already law in flavors.css; the config's legal-
  values list enforces it structurally.

## Where it lives

One config file - the already-queued "axis lists -> one PHP config" slice,
grown one field per axis:

    /* includes/theme-config.php - THE axis registry. Every consumer reads
       this: FOUC allowlists, settings-panel.js arrays, slider maxes,
       target-look validation, URL params. Index 0 = the default = absence. */
    const THEME_AXES = [
        'character' => [
            'attr'        => 'data-brand-character',
            'storage_key' => 'character-preference',
            'values'      => ['product', 'marketing', 'interface', 'editorial', 'terminal'],
            'names'       => ['Product', 'Marketing', 'Interface', 'Editorial', 'Terminal'],
            'targetable'  => true,
        ],
        // mood, flavor: targetable true · scheme: targetable false
        // sound, view: targetable false (view keeps its media-query gate in JS)
    ];
    // plus: 'house_look' => []   - the someday "initialize visitors into X"
    // door; empty = absence = today, one array key until ever used.

Per-target, in the existing `content/targets/<slug>/target.json` (one folder
per company is the target contract - no parallel presets table):

    { "look": { "character": "marketing", "flavor": "earth" } }

Unknown axes/values ignored (same posture as the FOUC allowlists).

## Wiring: server-side stamping (upgraded 2026-08-14 from the FOUC-script idea)

`?target=` and query params are known to PHP, so the look stamps into the
`<html>` tag literal in `includes/header.php`:

    <html lang='en'<?= theme_visit_attributes() ?>>

`theme_visit_attributes()` merges URL > target look, validates against the
config, normalizes index-0 values to nothing (the law), and emits the
attributes. This dominates the old FOUC-script-first wiring on every axis:
no-JS visitors get the curated look, the pre-paint script grows by nothing,
and there is no flash at all - the attributes are in the parsed HTML.
Stamped axes get emitted into the FOUC script as a pinned list so stored
preferences stand down on them; `settings-panel.js` init reads the resulting
`<html>` attributes (not localStorage) so the controls reflect the visit.

## Slice order (each committable, no hand-synced copies at any step)

1. **Config slice** (already approved in CLAUDE.md queue): `THEME_AXES`;
   FOUC allowlists + JS arrays + slider `max` all read it. Pure refactor.
2. **Init reflects DOM**: settings-panel.js derives control positions from
   `html.getAttribute(...)` (absence -> index 0) instead of re-reading
   localStorage. Removes the second read that could disagree; prerequisite
   for any server stamping.
3. **Target looks**: `look` key + `theme_visit_attributes()` + pinned-axes
   list. GoFundMe can land dressed as marketing the day this ships.
4. **URL params**: this plan's params, same server-side merge, params beat
   target. `view`/`filter` stay client-side per their gates.

## Locked decisions (original, still in force)

1. **URL wins for the visit, but does not persist.** A shared link must not
   overwrite the visitor's own saved preferences - same call as the grid's
   title-click navigation (`persist: false`). The moment they move a slider
   themselves, normal persistence resumes.
2. **View param obeys the same gates as the view preference** - `view=grid`
   applies only where the grid exists (home page, at the grid's breakpoint).
   A param is a request, not an override of the layout rules.

## Open questions (for when this is built)

- **Ratify target-beats-stored** (argued above; Derek hasn't signed it).
- **May a target pin an axis to its default?** (Stamp nothing but pin the
  axis, so a Terminal-storing visitor still gets Product.) Mechanism is free;
  are the semantics wanted, or is "unstated axis = visitor's normal view" law?
- **What is each target's actual look?** (GoFundMe = marketing × which
  flavor?) First real target look probably exposes a tuning pass on the cell.
- Does `filter` belong in the URL? Harmless, but it's content-scope rather
  than look. Decide when building.
- Should a curated link also suppress the grid invite pulse? (Probably yes if
  the link already opens in grid view - the door was used.)
