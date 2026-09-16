# derekthomaswood.com — next up

Started 2026-09-15 from Derek's braindump. The site is going pretty good; this is the list of what still needs to happen for it to be A+ as a candidate-review surface. Existing planning files (`journal-plan.md`, `journal-ideas.md`, `poster-plans.md`, `theme-chat-plan.md`, `timeline-content-plan.md`) stay as their topic-specific homes; this file is the current work list.

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
