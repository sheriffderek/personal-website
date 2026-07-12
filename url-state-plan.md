# URL state plan (deferred - not built)

Share a link that opens the site in a curated look:
`/?target=gofundme&brand=product&emphasis=immersive&scheme=dark&view=grid&filter=2`

The theming is the demo; a link that lands in a specific configuration is part
of the pitch. Planned 2026-07-11, parked until wanted.

## Shape: flat named params, one per axis

- `brand` · `emphasis` · `scheme` · `view` · `filter` - each optional, each
  independent, values are exactly the slugs the system already validates
  (`BRANDS` / `EMPHASES` etc.). No JSON blob: the whole state is five small
  enums, and flat params read as English and survive hand-editing.
- `sound` deliberately excluded - audio the visitor didn't choose is hostile.
- Unknown values are ignored (same posture as the FOUC script's allowlists).

## Locked decisions

1. **URL wins for the visit, but does not persist.** A shared link must not
   overwrite the visitor's own saved preferences - same call as the grid's
   title-click navigation (`persist: false`). The moment they move a slider
   themselves, normal persistence resumes.
2. **View param obeys the same gates as the view preference** - `view=grid`
   applies only where the grid exists (>= 1600px, home page). A param is a
   request, not an override of the layout rules.

## Wiring (all hooks already exist)

- **FOUC script** (`includes/header.php`): URLSearchParams becomes the FIRST
  read, falling back to localStorage - same attribute-stamping, pre-paint.
- **`scripts/settings-panel.js` init**: read the same params so the controls
  (sliders, pills) reflect the link state; pass `persist: false` on these
  initial applies.
- **Do the CLAUDE.md queued item first**: the value lists already live in
  three places that must agree (FOUC script, JS arrays, slider `max`), and
  this adds a fourth consumer. Pull the lists into one PHP-side config both
  scripts read, THEN add the params - don't hand-sync a fourth copy.

## Open questions

- Does `filter` belong in the URL? Harmless, but it's content-scope rather
  than look. Decide when building.
- Should a curated link also suppress the grid invite pulse? (Probably yes if
  the link already opens in grid view - the door was used.)
