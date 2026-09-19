# derekthomaswood.com — next up

Started 2026-09-15 from Derek's braindump. The site is going pretty good; this is the list of what still needs to happen for it to be A+ as a candidate-review surface. Existing planning files (`notes/journal-plan.md`, `notes/journal-ideas.md`, `notes/theme-chat-plan.md`, `notes/timeline-content-plan.md`) stay as their topic-specific homes; this file is the current work list.

## Tomorrow (2026-09-19) - picked up from the 2026-09-18 session

One at a time, commit after each. The sentence pass works like this: name the thing, the file, and its goal - Derek types in his editor - spelling gets fixed, punctuation is his.

- [ ] **Record the "Officially looking for a new long-term role" video** (Derek). Send the Vimeo id; it embeds in `templates/journal/officially-looking.php` the same way as the other two entries. The entry is title + summary until then.
- [ ] **Sentence pass, in order:** Now page (`templates/pages/now.php` - is it current? does the last line box the search into education?) -> Contact (`templates/pages/contact.php`, likely a quick OK) -> home intro (`templates/pages/home.php`) -> the "Now interviewing" card -> the 16 weight-1 timeline cards in `content/milestones.json`, a few at a time.
- [ ] **Resume index** (`content/resume.json`): Derek writes the `index.intro` and the three `when_to_pick` lines. The page's goal: choose a path and feel good about it.
- [ ] **Past-writing shelf** (`templates/pages/journal.php`): titles and links only right now. Derek writes the blurbs in one sitting, or it stays bare.
- [ ] **PE umbrella case study** (`case-studies-plan.md` #1): Derek works out the grew-over-time story live on camera and sends notes; they get kept in order, in his words, in `pe-case-study-notes.md`. The How I work page gets built from these walkthroughs.

Open calls, no rush: ChromaDex vs the real-estate mastermind as case study #3 (`case-studies-plan.md`); the home page's `goal` brief line gets rewritten by Derek when the home page changes.

## Thinking about, not building yet: the sheriffderek circle (Derek, 2026-09-18)

Derek's insignia (the little two-tone pink circle) as this site's way home: above his name at rest, reappearing as a floating circle on scroll-up, maybe a big corner version on wide screens. The settled part: every page needs a visible way home, top-left. **Full write-up, his sketches, where the real SVG lives, and the open questions: `notes/home-circle-idea.md`.** Cheap first step whenever he says go: a favicon (the site has none).

## First impression

- [ ] **Hello video on the site** — Derek saying hello, on camera. The tradeoff Derek named (2026-09-15): in some ways it's giving them too much information — but if they don't like him right away, that's a pretty big hurdle to get over, and he wants people who want somebody who's enthusiastic and outgoing. So the video *is* the filter, and better upfront than after three interview rounds.
  - **Probably a `?target=` thing, not the general site** (Derek, 2026-09-15). Rather than one video for everyone, the general site stays text-first and the tailored view (`?target=<company>`) gets a per-target hello: "Hi GoFundMe team..." Personal, screens hard, only made for applications that matter. Depends on the per-target URL plumbing below.

## Per-target URL plumbing (the seam that unlocks tailored intros)

Discussion 2026-09-15. The `?target=<company>` view works when a recruiter clicks a link, but the resume PDF is what actually leaves the site — and a naked domain typed after seeing the PDF loses the target. Fixes, in order:

- [ ] **Pretty URLs per target** — `derekthomaswood.com/gofundme` maps to the same handler as `?target=gofundme`. Now the target *is* the URL — clean in the cover letter, sayable out loud, fits at the bottom of the resume PDF. One route rule in `index.php`; the query string stays as the implementation detail.
- [ ] **Per-target resume PDF export.** Export already goes per lane (product / engineer / advocate); add a target dimension so the portfolio link's `href` becomes the tailored URL. Filename convention already exists (role-type-name); extend with company. Note dropped in `styles/modules/resume.css` print block (2026-09-15).
- [ ] **Hidden `href` pattern for the portfolio link** — visible label reads `derekthomaswood.com` or `Portfolio →`, underlying `href` carries the full `/gofundme` (or whatever). Print stylesheet must NOT append `[href]` in parens for this link (defeats the point). QR in the corner is a possible belt-and-suspenders for the printed-and-retyped case, but probably not worth it — tech hiring in 2026 reads PDFs, doesn't type URLs.
- [ ] **Then** the per-target hello video slots in as the third piece — plumbing done, muscle to record and drop them is the only remaining cost.

Order matters: pretty URLs first (unlocks everything, low effort), then the PDF export change (locks the seam), then videos as the payoff. Only worth doing per-target for applications where the target notes already exist and matter — general sends stay general.



## Small screens / mobile

Most reviewers will look at this on a phone; mobile is important.

- [ ] **Small-screen check pass** — sit and go through the site on a phone, no major annoying problems anywhere.
- [ ] **Bleed decision.** Right now images bleed on small screens, which is cool. The alternative is recreating square (or different-ratio) images for every entry, and *that* means giving them a background color per theme — a whole new concern for every theme. Might be more work than it gives us. Decide: keep the bleed, or commit to the per-ratio-per-theme system.
- [ ] **Menu breakpoints** — some breakpoints look incorrect (whether the menu is on top or in the gutter). Double-check those are correct.

## Content read-over

- [ ] **Read the rest of the journal entries.** First ~16-20 were checked; the rest probably have some filler. Needs a nice relaxing sit to go through them.
- [ ] **Journal skeletons.** All visible journal posts should be at least semi-complete — a good skeleton. Some right now are just filler, which is embarrassing. Derek can make videos, take screenshots, do the writing; it doesn't take that long.
- [ ] **AI-filler audit — Derek reads through the copy himself.** The 2026-09-15 find: the "three lenses / pick the one that matches your role" paragraph on the opener milestone was AI-inserted, sounded off, and linked to a resume page that isn't ready. That pattern is likely elsewhere in `content/milestones.json`, journal entries, and other copy. Not a grep-and-scrub task — a read-through-and-catch-the-voice task.

## Homepage direction (2026-09-17, per Derek — after Wesley meeting + Frame 280/281 review)

The grid is already opt-in — Frame 281's settings/grid view lives behind a toggle in the header, and Frame 280 (the plain list home) is the default entrance. That architecture is right. What's missing is **what the plain home actually shows**.

**Direction:** the default home shows a set of **4-5 case-study-like conversations** — simple, sweet, "not much to snag on." A visitor who wants more can opt in to the full history and play with the settings.

**Case-study visual style: black and white, process-forward, not visual flourish.** Graphics are about process and core design, not high-fidelity UI screens. This is deliberate — the point is to work out **thought process over visual style**, which is:
- Consistent with the "invisible design" positioning (behind-the-scenes product design surfacing in screens).
- Consistent with Derek's own stance ("most of my work is thinking and planning, not finalized pristine Figma; anyone can copy UI details from Mobbin").
- Consistent with Wesley's bridge-story feedback (iterative friction, not finalized-looking prototypes).
- Defensible: a reviewer looking for "exceptional visual craft" sees restraint used on purpose, not absent. The visual system's craft carries through the frame, the type, the pace — not through polished mockups of other people's products.

Implications for the plan:
- **Case-study page design** (already on the fortify list) becomes concretely: black-and-white, process-diagram-forward, not project-brand-colored screens. This resolves the earlier "white pages, not theme-following" question — it's stronger than that: **process-graphic-first, chrome-restrained**.
- **Case-study build order** starts weighting *which cases have the clearest process story*, not which have the prettiest artifacts.
- **The milestone grid is not demoted.** It stays as the opt-in playground / design-system + cross-platform-theming showcase it already is. It's just no longer the front door.
- **Resume-page redo** still owes the story-of-three-lanes; that's independent of this.

Not yet committed to structurally; case studies need to exist before restructuring can be tested. But the design direction is now specific enough to design case-study pages against.

## Navigation / structure

- [ ] **Menu — no real home button.** Figure out how to handle that.
- [ ] **Layout consistency across page types.** Home page is extra-wide (that's fine). Everything else should be the same layout — mostly working, but don't let the menu bounce around between different types.
- [ ] **Journal probably needs categories.**
- [ ] **Regular one-off pages** — make sure the small pages don't look bad. Now page is fine. Contact page is fine.

## Resume page

- [ ] **Redo the resume page.** Some thoughts exist already. It has to tell the story of why there are three different options and why you'd want each one at which scope.

## Themes / settings / app UI

- [ ] **Themes dialed in more** — finalized fonts, color panels. Not redoing posters. The settings area and all the controls need to work good and be tight.
- [ ] **Style the actual app UI specifically** (the settings panels). Opportunity to show a lot of detail — the app UI is itself a portfolio piece.
- [ ] **Flip the starting mood back to Expressive when the colors land.** New visitors start in Quiet for now (2026-09-18) - `DEFAULT_MOOD` in `includes/config.php`; its comment lists the three places that move together (config, `MOODS` order + `DEFAULT_MOOD` in `scripts/settings-panel.js`, `$mood_names` order in `includes/settings/mood-switcher.php`).
- [ ] **"Reset to default" control** (Derek's side thought, 2026-09-18 - talked through, not built). The way home from a rough combo. Where we landed: resets the LOOK only (scheme, character, mood, flavor, red light - not interface sounds, layout, or filter); always present and disabled at default rather than appearing/disappearing (app-ui form law); a quiet text-level control under the rows, not another button row. Nearly free mechanically - each slider's `apply(defaultIdx)` already clears its own key. Label is Derek's to write.
- [ ] **Random - parked until the colors land** (Derek, 2026-09-18). Only in the settings band (grid view, >= 1450), the one place a visitor sees the whole wall repaint; band-only has precedent (`.filter-level-name`). Leaves scheme and red light alone. Parked because random showcases every cell, including the unfinished ones - the same reason the default went Quiet. Open question, unproven: draw only from combos with a passing verdict in `walk-notes.md`?
- [ ] **Theme chat lives in the same spot** (Derek, 2026-09-18). The "tell us what you want" AI version - a visitor describes their brand and the site re-paints into it - is a third option sitting beside Reset and Random in the band: three ways to drive the same system (go home / surprise me / make it mine). Same band-only reasoning as Random (you need to see the wall repaint). Full plan stays in `notes/theme-chat-plan.md`; this is just where its door goes.

## Case studies

- [ ] **Case-study page design.** Unlike journal pages, case-study pages probably need to be white and NOT follow the theme — theme colors would blow out project colors we don't control (each project has its own brand palette).
- [ ] **Decide which case studies to build, and in what order.** Separate decision from the page design itself.

---

## Rough grouping for fortify sittings

When picking a morning fortify sitting from this file, group work that fits one sitting:

- One sitting on mobile (small-screen check + bleed decision + menu breakpoints)
- One sitting on content (read-over) — repeatable, multiple entries at a time
- One sitting on journal skeletons — pick 2-3 entries, get them off filler
- One sitting on resume page redesign — story-of-three-options
- One sitting on themes / settings polish
- One sitting on case-study page design (before deciding which case studies)
- Multiple sittings on producing individual case studies once the page design is settled
