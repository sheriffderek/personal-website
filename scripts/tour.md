# Welcome video + guided tour

An in-progress experiment, currently **off** via `TOUR_ENABLED` in `../includes/config.php`. Flip that one flag to `true` to bring back the welcome video and the tour together — the files stay in place while off, nothing loads (no scripts, no header render).

## What it is

A standalone talking-head welcome video at the top of home that, when played, drives the real page UI in sync with the narration (the settings menu opens, the theme changes, the filter widens, confetti fires), then restores the visitor's own settings when it ends. It is deliberately **its own component, NOT the milestone media/carousel** — different job.

## The files

- `../includes/welcome-video.php` + `../styles/components/welcome-video.css` + `welcome-video.js` — the component. Resting state is a still image (the video's poster); may become a muted loop later.
- `choreo.js` — the content-agnostic engine. The video's `currentTime` is the clock; the page is a pure function of it. Two primitives:
  - `channels` + `spans` — reconciled **state**, scrub-proof. A value is held over `[from, to)`; outside any span the channel sits at its default. Scrubbing backward, seeking, reloading all land correctly because state is derived from the clock, never from having witnessed a moment.
  - `hits` — one-shot **whimsy** (confetti, a chime). Fire forward-only during real playback; silent on scrub.
- `tour.js` — the site's choreography (which real controls, which whimsy). Drives settings via the `window.settings` surface in `settings-panel.js` with `{persist:false}` so it never overwrites saved prefs; `settings.restore()` puts them back on end / opt-out.
- `../includes/header.php` loads the three scripts (behind `TOUR_ENABLED`); `../includes/config.php` holds the flag.

## Requirements still to build when we resume (Derek's notes, 2026-07-10)

1. **No scroll jump on theme change.** The video must never shift scroll position when the theme changes — really jarring. The tour changes theme mid-play; the `syncScroll` / `anchorPoint` reflow-anchor in `settings-panel.js` already pins the header's first `<p>`, but confirm the *video itself* holds still.
2. **Play button opts into sound.** Audio triggers are opt-in; pressing play is the opt-in gesture — turn `data-sound` on when the visitor presses play.
3. **Scrubbing is expected — via a standalone scrubber component.** People will want to scrub and investigate how the magic works, so the welcome video needs a visible scrubber (it currently has only a play button; the choreo engine already reconciles state correctly on scrub). The scrubber already exists inside the milestone custom player, so **extract it into one standalone reusable component both consume** rather than duplicating it. Today it's spread across three places, all scoped to `.slide[data-type="play"]`:
   - markup — `../includes/posters/media-item.php` (the `.controls` block: `.play-pause` + `.scrubber`)
   - CSS — `../styles/modules/milestone.css` (the `.scrubber` + `::-webkit-slider-*` / `::-moz-range-*` rules, currently nested under `.milestone`)
   - JS — `../includes/footer.php` (the custom-player block: `paint`/`follow` rAF fill, `scrubber` input/change wiring)
   Pull those into a component (e.g. `styles/components/scrubber.css` + a partial + its own script), then have both the milestone player and the welcome video use it. Note the CSS is currently milestone-scoped, so extracting means lifting it out of `.milestone` (same shape as the `.media` extraction we decided against for the welcome video — here it's warranted because the scrubber is genuinely shared).
4. **Scroll away = magic off.** If the visitor scrolls the video out of view, stop the choreography (stop driving the page). Related to the deferred now-playing pill idea in CLAUDE.md's video section.

## Also still open

- Real cue / span timings against the finished cut.
- Start-model: a run/rerun button vs autoplay — note a narrated video can't browser-autoplay *with sound*.
- Still-image vs muted-loop resting state.
- GSAP: only if the choreography needs tweened motion, not discrete switches.
- Move the video asset out of `../content/milestones/2026-job-search/` to a site-level home.
- Whether to drop the intro clip from the "Now interviewing" card (only the tour flag moved up).
