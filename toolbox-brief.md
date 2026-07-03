# Display Settings Toolbox — portable spec

A self-contained brief for implementing a "display settings" toolbox in another project. Written so an agent in another codebase can build it from zero without reading the source project.

The toolbox is a tiny pattern: **one trigger button + one popover panel + a stack of "switcher groups" inside the panel**. Each switcher flips an attribute on `<html>` (`data-scheme`, `data-palette`, `data-typography`, `data-focus`, …) and the CSS does the rest.

This brief reflects the version shipped at Perpetual Education — accessibility tightenings are part of the spec, not deltas to layer on later.

---

## 1. What it does (in one paragraph)

A user opens a kebab-style trigger in the site chrome. A small panel pops out, anchored to the trigger. Inside the panel are short rows of buttons — **Scheme** (System / Light / Dark), and any other display settings the project wants (palette, typography, focus mode, etc.). Clicking a button writes a `data-*` attribute to `<html>` and persists the choice in `localStorage` (except per-page-only settings like Focus). A blocking inline script in `<head>` restores those attributes before first paint so the page never flashes the wrong mode.

---

## 2. Why it's structured this way (load-bearing decisions)

These are the choices that make the pattern small and durable. Keep them.

- **Generic primitive + thin caller.** The `toolbox` component (trigger + popover panel) is reusable for *any* kebab/dropdown UI — settings panels, per-item action menus, etc. The "settings toolbox" is just a caller that fills the slot with switcher rows. Don't fold the settings logic into the primitive.
- **Attributes on `<html>`, not classes on `<body>`.** Anything in CSS that needs to respond to a scheme/palette/typography choice keys off `[data-scheme]`, `[data-palette]`, `[data-typography]`, etc. on the document root. This is what lets all the switchers share one data-driven JS loop and one FOUC script.
- **One data-driven JS loop for all switchers.** Each switcher is a small config object — kind, attribute name, storage key, default value. Adding a switcher is a one-line config addition.
- **Native HTML `popover` + CSS anchor positioning.** No JS to open/close, no focus-trap library, no z-index wars (top-layer). Light-dismiss and Esc come from the browser. CSS `anchor-name` + `position-anchor` keep the panel pinned to the trigger without measuring with JS.
- **Chrome scope via `data-ui='app'` (optional).** If the project has a strong content-palette system that could leak its colors/type into the panel, scope chrome tokens under `[data-ui='app']` and put `data-ui='app'` on the panel. If the project has a single global look, skip this — straight tokens are fine.
- **FOUC prevention is a blocking inline script in `<head>`.** Not a deferred module, not a stylesheet `@media` query. Synchronously read `localStorage`, write attributes, before the parser hits `<body>`.
- **Some settings intentionally don't persist.** Focus mode is per-page. Make this an explicit per-switcher flag, not a special case.
- **`aria-pressed` is set by JS, not in the HTML.** Hardcoding the selected button leads to drift the moment a default changes.

---

## 3. Required platform features (and what to do if missing)

| Feature | Status | If unsupported |
|---|---|---|
| HTML `popover` attribute | Baseline modern (Chromium, Safari 17+, Firefox 125+) | Add the popover polyfill (`@oddbird/popover-polyfill`) once at the entry point. |
| CSS anchor positioning (`anchor-name`, `position-anchor`, `position-area`) | Chrome/Edge today; Safari/Firefox catching up | Provide an `@supports not (position-area: bottom)` fallback that positions the panel absolutely relative to a wrapping container. Don't block the feature on it. |
| `color-mix()` in oklch | Broadly supported | Replace with fixed shades. |
| `localStorage` | Universal | Wrap reads/writes in try/catch — private-mode Safari throws when storage is disabled. The reference code below already does this. |
| `matchMedia` for `prefers-color-scheme` | Universal | n/a |

---

## 4. The contract — HTML shape

```html
<!-- Trigger (sibling of the panel, anchored to it) -->
<button
  type='button'
  popovertarget='toolbox-1'
  class='toolbox-trigger settings-trigger'
  style='anchor-name: --toolbox-1;'
  aria-label='Display settings'
>
  <span aria-hidden='true'>
    <!-- kebab glyph, inline SVG (three rects in a 16x16 viewBox) -->
    <svg class='toolbox-glyph' viewBox='0 0 16 16'>
      <rect class='glyph-line glyph-line-1' />
      <rect class='glyph-line glyph-line-2' />
      <rect class='glyph-line glyph-line-3' />
    </svg>
  </span>
</button>

<!-- Panel (renders in top layer when open) -->
<div
  id='toolbox-1'
  popover
  class='toolbox-panel settings-panel'
  data-ui='app'
  style='position-anchor: --toolbox-1;'
  aria-label='Display settings'
>
  <div class='scheme-switcher' role='group' aria-labelledby='scheme-switcher-label'>
    <p class='app-data-voice' id='scheme-switcher-label'>Scheme:</p>
    <button type='button' data-set-scheme='system'>System</button>
    <button type='button' data-set-scheme='light'>Light</button>
    <button type='button' data-set-scheme='dark'>Dark</button>
  </div>

  <div class='typography-switcher' role='group' aria-labelledby='typography-switcher-label'>
    <p class='app-data-voice' id='typography-switcher-label'>Typography:</p>
    <button type='button' data-set-typography='default'>Default</button>
    <button type='button' data-set-typography='classic'>Classic</button>
  </div>

  <!-- … more switcher groups, same shape … -->
</div>
```

The shape per switcher row is rigid:

1. A wrapper div with `class='{kind}-switcher'`, `role='group'`, and `aria-labelledby='{kind}-switcher-label'`.
2. A `<p>` (or `<span>`) label with `id='{kind}-switcher-label'`.
3. A flat list of `<button type='button' data-set-{kind}='{value}'>` siblings.

The wrapper carries the group semantics. The label `<p>` is referenced by `aria-labelledby`. Buttons are direct children — no extra nesting.

### Attribute contract

| `data-set-*` | Writes to `<html>` | Storage key | Default | Persisted |
|---|---|---|---|---|
| `data-set-scheme` | `data-scheme` (`light` \| `dark`, removed when system) | `scheme-preference` | `system` | yes |
| `data-set-palette` | `data-palette` (e.g. `php`, `js`, removed when default) | `palette-preference` | `default` | yes |
| `data-set-typography` | `data-typography` (`classic`, removed when default) | `typography-preference` | `default` | yes |
| `data-set-focus` | `data-focus` (valueless — empty string when on, removed when off) | — | `off` | **no** (per-page) |

**Default-value rule:** selecting the default value *removes* the attribute (and clears storage). Defaults never carry a positive selector — keeps the cascade clean.

---

## 5. The JS — single data-driven loop

Vanilla JS, no dependencies. Loaded at the end of `<body>` (not in `<head>`).

```js
(function () {
  const SWITCHERS = [
    { kind: 'scheme',     attr: 'data-scheme',     storageKey: 'scheme-preference',     defaultValue: 'system' },
    { kind: 'palette',    attr: 'data-palette',    storageKey: 'palette-preference',    defaultValue: 'default' },
    { kind: 'typography', attr: 'data-typography', storageKey: 'typography-preference', defaultValue: 'default' },
    { kind: 'focus',      attr: 'data-focus',      storageKey: null,                    defaultValue: 'off', valuelessAttr: true },
  ];

  const html = document.documentElement;

  SWITCHERS.forEach(function (cfg) {
    const buttons = document.querySelectorAll('[data-set-' + cfg.kind + ']');
    if (!buttons.length) return;

    const persists = !!cfg.storageKey;
    let saved = null;
    try {
      if (persists) saved = localStorage.getItem(cfg.storageKey);
    } catch (error) {
      // localStorage unavailable (private mode in some browsers throws) — treat as unset.
    }
    const current = saved || cfg.defaultValue;

    buttons.forEach(function (button) {
      button.setAttribute(
        'aria-pressed',
        button.getAttribute('data-set-' + cfg.kind) === current ? 'true' : 'false'
      );
    });

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        const value = button.getAttribute('data-set-' + cfg.kind);

        if (value === cfg.defaultValue) {
          if (persists) {
            try { localStorage.removeItem(cfg.storageKey); } catch (error) {}
          }
          html.removeAttribute(cfg.attr);
        } else {
          if (persists) {
            try { localStorage.setItem(cfg.storageKey, value); } catch (error) {}
          }
          html.setAttribute(cfg.attr, cfg.valuelessAttr ? '' : value);
        }

        buttons.forEach(function (b) { b.setAttribute('aria-pressed', 'false'); });
        button.setAttribute('aria-pressed', 'true');
      });
    });
  });
})();
```

Adding a 5th switcher = one line in `SWITCHERS` plus a new switcher row in the panel HTML. That's the whole extension story.

---

## 6. The FOUC script — must run in `<head>`, before any stylesheet

Inline. Synchronous. No defer/async. No external script load.

```html
<script>
  (function () {
    var html = document.documentElement;
    try {
      var scheme = localStorage.getItem('scheme-preference');
      if (scheme && scheme !== 'system') html.setAttribute('data-scheme', scheme);

      var palette = localStorage.getItem('palette-preference');
      if (palette && palette !== 'default') html.setAttribute('data-palette', palette);

      var typo = localStorage.getItem('typography-preference');
      if (typo && typo !== 'default') html.setAttribute('data-typography', typo);
    } catch (error) {
      // localStorage unavailable (private mode in some browsers throws) — fall through to defaults.
    }
  })();
</script>
```

Per-page-only settings (focus) are intentionally not restored here.

**The keys and attribute names must stay in lockstep** with the `SWITCHERS` config and the switcher buttons' `data-set-*` values. Any drift = wrong-mode flash on load.

---

## 7. The CSS

### Tokens the panel expects

Provide these in the target project's `data-ui='app'` scope (or — if the project has a single global look — at `:root`):

- `--fill-primary` (panel background)
- `--ink-primary` (panel text)
- `--accent` (selected button background, focus ring color)
- `--stroke-secondary` (panel border)
- `--panel-shadow` (optional — falls back to a hardcoded shadow)
- `--app-data-voice-{font-family, font-size, line-height, letter-spacing}` (chrome typography — small, mono, stable)

### Trigger + glyph + panel chrome (shared primitive)

```css
button.toolbox-trigger {
  all: unset;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  vertical-align: middle;
  cursor: pointer;
  padding: 0.25rem 0.5rem;
  font-size: 1.25rem;
  line-height: 1;
  color: var(--ink-primary);

  &:focus-visible {
    outline: 2px solid var(--accent);
    outline-offset: 2px;
    border-radius: 4px;
  }
}

.toolbox-glyph {
  --glyph-size: 1.25rem;
  --glyph-line-width: 10px;
  --glyph-line-height: 1.5px;
  --glyph-line-gap: 5.25px;
  --glyph-line-radius: calc(var(--glyph-line-height) / 2);

  width: var(--glyph-size);
  height: var(--glyph-size);
  display: block;

  .glyph-line {
    fill: currentColor;
    width: var(--glyph-line-width);
    height: var(--glyph-line-height);
    rx: var(--glyph-line-radius);
    x: calc((16px - var(--glyph-line-width)) / 2);
  }
  .glyph-line-1 { y: calc(8px - var(--glyph-line-gap) - var(--glyph-line-height) / 2); }
  .glyph-line-2 { y: calc(8px - var(--glyph-line-height) / 2); }
  .glyph-line-3 { y: calc(8px + var(--glyph-line-gap) - var(--glyph-line-height) / 2); }
}

.toolbox-panel {
  position-area: bottom span-left;
  margin: 0;
  margin-top: 0.5rem;
  padding: 1rem;
  min-width: 220px;
  background: var(--fill-primary);
  color: var(--ink-primary);
  border: 1px solid var(--stroke-secondary);
  border-radius: 8px;
  box-shadow: var(--panel-shadow, 0 4px 12px rgb(0 0 0 / 0.1));

  font-family:    var(--app-data-voice-font-family);
  font-size:      var(--app-data-voice-font-size);
  line-height:    var(--app-data-voice-line-height);
  letter-spacing: var(--app-data-voice-letter-spacing);
}

/* Optional: clean fade on open via @starting-style */
.toolbox-panel {
  opacity: 1;
  transition: opacity 120ms ease, display 120ms allow-discrete;
}
.toolbox-panel:not(:popover-open) { opacity: 0; }
@starting-style {
  .toolbox-panel:popover-open { opacity: 0; }
}

/* Fallback for browsers without anchor positioning */
@supports not (position-area: bottom) {
  .toolbox-panel {
    /* Positioning becomes the caller's responsibility — usually absolute
       relative to a wrapping container that holds both trigger and panel. */
    inset: auto auto auto auto;
  }
}
```

### Settings-panel-specific

```css
.settings-panel > div { margin-bottom: 0.75rem; }

.settings-panel button[aria-pressed='true'] {
  color: var(--fill-primary);
  background: var(--accent);
  background: linear-gradient(
    to bottom,
    color-mix(in oklch, var(--accent) 80%, white) 0%,
    var(--accent) 100%
  );
  border-color: color-mix(in oklch, var(--accent) 70%, black);
}
```

### What each switcher's attribute does (project-specific)

The pattern is always: a top-level selector that keys off the `<html>` attribute and reassigns tokens or styles.

```css
/* Scheme — light is the implicit base; dark is the override */
@media (prefers-color-scheme: dark) {
  :root:not([data-scheme='light']) { /* dark tokens */ }
}
:root[data-scheme='dark'] { /* dark tokens */ }

/* Typography — flip heading vs body family */
[data-typography='classic'] {
  --font-heading: var(--font-serif);
  --font-body: var(--font-sans);
}

/* Palette — content accent shift (optional) */
[data-palette='warm']  { --accent: var(--color-orange-500); }
[data-palette='cool']  { --accent: var(--color-blue-500); }

/* Focus mode — full-viewport slide sections (optional) */
[data-focus] { scroll-snap-type: y proximity; }
html[data-focus] main > section { min-height: 100vh; scroll-snap-align: start; }

/* If using data-ui='app': chrome stays neutral inside colored content scopes */
[data-palette='warm'] [data-ui='app'] { --accent: var(--accent-chrome); }
```

---

## 8. Accessibility checklist (all of these are required, not optional)

What the pattern ships with — verify each is in your implementation before calling the work done:

- [x] Trigger is a real `<button type='button'>` with a meaningful `aria-label`.
- [x] Trigger glyph is decorative (`aria-hidden='true'` on the wrapping `<span>`).
- [x] Native popover panel gives Esc-to-close and light-dismiss for free.
- [x] Popover panel has its own `aria-label` (currently re-uses the trigger's label — fine).
- [x] Each switcher row is a programmatic group: `role='group'` + `aria-labelledby` pointing at the row's label `<p>`'s `id`.
- [x] Selected state on switcher buttons uses `aria-pressed` (set by JS on mount + click — not hardcoded in HTML).
- [x] FOUC restoration prevents a flash of wrong scheme/palette.
- [x] System-preference fallback for scheme via `prefers-color-scheme` media query.
- [x] Buttons use `:focus-visible` outlines that respect the theme accent.
- [x] All `localStorage` reads/writes wrapped in `try/catch` (private-mode browsers throw).

Optional polish (worth considering, not required):

- **`aria-pressed` vs. radio semantics.** The current pattern uses `aria-pressed` because each option is conceptually "on/off." That's defensible for toggle groups but slightly misrepresents mutually-exclusive choices. If your project is screen-reader-first, switch to `role='radiogroup'` + `role='radio'` + `aria-checked` and add roving-tabindex arrow-key navigation. Cost: more JS. Most projects should stay with `aria-pressed`.
- **Announce changes.** A visually-hidden `aria-live='polite'` region inside the panel that updates to "Dark scheme applied" when a switcher fires.
- **`aria-expanded` on the trigger.** Native popover doesn't manage this. If you want it, toggle `aria-expanded` from the popover `toggle` event on the panel.
- **Auto-focus the first switcher on open.** Native popover doesn't. Add `autofocus` on the first switcher button if your project expects keyboard-first behavior.
- **Color contrast.** The selected button's gradient must hit 4.5:1 against `--accent`. Verify in both light and dark scheme.

---

## 9. File layout (suggested for the target project)

```
components/ (or templates/components/, src/components/, etc.)
  toolbox.{ext}                            # the generic primitive (trigger + popover panel)
  settings-toolbox.{ext}                   # caller that fills the panel with switcher rows
  scheme-switcher.{ext}                    # one file per switcher row (small, identical shape)
  typography-switcher.{ext}
scripts/
  settings-panel.js                        # the one-loop wiring (deferred, loaded at end of body)
styles/
  components/toolbox.css                   # trigger + glyph + panel chrome (the primitive)
  components/settings-toolbox.css          # row spacing + selected-state
  themes/app.css                           # [data-ui='app'] scope tokens (if using chrome scope)
  themes/scheme.css                        # [data-scheme='light'|'dark'] + @media
  typography.css                           # [data-typography='classic'] block
partials/head.{ext}                        # FOUC inline script lives here
```

---

## 10. Differences worth deciding on before implementing

1. **Which switchers ship.** PE has four (scheme, palette, typography, focus). Most projects need only scheme. Drop the rest unless they have a real job in your design.
2. **Where the trigger lives.** Pick a stable spot in the site chrome — usually next to the user menu or in the header.
3. **Should scheme default to "Light" or "System."** Recommend System (attribute absent, CSS reads `prefers-color-scheme`).
4. **Do you need the `data-ui='app'` chrome scope.** Only if the rest of the site can apply colored palettes that would leak into the panel. For a single-look site, skip it and let the panel use root tokens.
5. **Polyfill or progressive enhancement.** If the target project must support Safari < 17 / Firefox < 125, decide between `@oddbird/popover-polyfill` (full parity) or a graceful degradation where the panel becomes a normal block (no popover, no top-layer).

---

## 11. Quick-start prompt for the implementing agent

> Implement a "display settings" toolbox in this project using the spec in this file. Read all of it before writing code — the contract in §4, the JS shape in §5, the FOUC script in §6, the CSS in §7, and the accessibility checklist in §8 are all load-bearing.
>
> Build the generic `toolbox` primitive first (trigger + popover panel), then the `settings-toolbox` caller that fills the panel with switcher rows. Wire all switchers through the single `SWITCHERS` config in §5 — adding a switcher must be one config line plus one HTML row, nothing else. The FOUC script in §6 must be inline in `<head>`, before any stylesheet. Use the CSS tokens listed in §7; if the project's design system uses different token names, rename them in the toolbox CSS rather than introducing new ones.
>
> Treat §8 as a checklist, not a wish list — every required item must be addressed before the task is done. When in doubt about a deviation, ask before inventing.
