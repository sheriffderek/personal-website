# derekthomaswood.com - backlog

Future ideas and parked work - after version 1 (`v1.md`). Each item keeps the reasoning that got it here, so picking one up doesn't mean re-deriving it. Nothing here is scheduled; an item moves to `v1.md` only when Derek brings it up.

## Timeline filter: below 16 shows only case studies (Derek, 2026-09-24 - an idea)

Replaces the 2026-09-17 "homepage as 4-5 case-study conversations" direction, which is **dropped** - no new page. The idea instead: the filter sits at 16 (the weight-1 pitch) by default, and moving it further left narrows to only the case studies. This is the "Shape B" the Timeline weights section of CLAUDE.md says was chosen against (narrowing below the pitch), so it's a deliberate revisit of that call, not a drift into it. Needs case studies to exist first.

## Per-target URL plumbing (the seam that unlocks tailored intros)

Discussion 2026-09-15. The `?target=<company>` view works when a recruiter clicks a link, but the resume PDF is what actually leaves the site - and a naked domain typed after seeing the PDF loses the target. Fixes, in order:

- [ ] **Pretty URLs per target** - `derekthomaswood.com/gofundme` maps to the same handler as `?target=gofundme`. The target *is* the URL - clean in the cover letter, sayable out loud, fits at the bottom of the resume PDF. One route rule in `index.php`; the query string stays as the implementation detail.
- [ ] **Per-target resume PDF export.** Export already goes per lane (product / engineer / advocate); add a target dimension so the portfolio link's `href` becomes the tailored URL. Filename convention exists (role-type-name); extend with company. Note dropped in `styles/modules/resume.css` print block (2026-09-15).
- [ ] **Hidden `href` pattern for the portfolio link** - visible label reads `derekthomaswood.com` or `Portfolio →`, the `href` carries the full `/gofundme`. The print stylesheet must NOT append `[href]` in parens for this link. A QR in the corner is possible for the printed-and-retyped case, but probably not worth it - tech hiring reads PDFs, it doesn't type URLs.
- [ ] **Then** the per-target hello video slots in (below) - plumbing done, recording is the only remaining cost.

Order matters: pretty URLs first (unlocks everything, low effort), then the PDF export (locks the seam), then videos as the payoff. Only worth doing per-target where the target notes already exist and matter - general sends stay general.

## Hello video

- [ ] **Derek saying hello, on camera.** The tradeoff Derek named (2026-09-15): in some ways it's giving them too much information - but if they don't like him right away, that's a big hurdle to get over, and he wants people who want somebody enthusiastic and outgoing. So the video *is* the filter, and better upfront than after three interview rounds.
  - **Probably a `?target=` thing, not the general site** (2026-09-15). The general site stays text-first; the tailored view gets a per-target hello ("Hi GoFundMe team..."). Personal, screens hard, only made for applications that matter. Depends on the plumbing above.

## Case studies

- [ ] **Case-study page design.** Unlike journal pages, case-study pages probably need to be white and NOT follow the theme - theme colors would blow out project colors we don't control. The design direction from 2026-09-17 still stands: **black and white, process-forward, not visual flourish** - graphics about process and core design, not high-fidelity UI screens. Thought process over visual style, consistent with the "invisible design" positioning and Wesley's bridge-story feedback (iterative friction, not finished-looking prototypes). A reviewer looking for visual craft sees restraint used on purpose; the craft carries through the frame, the type, the pace.
- [ ] **Decide which case studies to build, and in what order** - weight toward the clearest process story, not the prettiest artifacts. Open call, no rush: ChromaDex vs the real-estate mastermind as #3 (`case-studies-plan.md`).
- [ ] **PE umbrella case study** (`case-studies-plan.md` #1): Derek works out the grew-over-time story live on camera and sends notes; they get kept in order, in his words, in `pe-case-study-notes.md`. The How I work page gets built from these walkthroughs.

## Journal

- [ ] **Journal categories** - probably needed eventually.

## The sheriffderek circle (Derek, 2026-09-18 - thinking about, not building)

Derek's insignia (the little two-tone pink circle) as the site's way home: above his name at rest, reappearing as a floating circle on scroll-up, maybe a big corner version on wide screens. The v1 part is just "a visible way home" (see `v1.md`). Full write-up, sketches, where the real SVG lives, and the open questions: `notes/home-circle-idea.md`.

## Settings band extras

Three ways to drive the same system, all in the settings band (grid view, >= 1450 - the one place a visitor sees the whole wall repaint): go home / surprise me / make it mine.

- [ ] **"Reset to default" control** (2026-09-18 - talked through, not built). The way home from a rough combo. Resets the LOOK only (scheme, character, mood, flavor, red light - not interface sounds, layout, or filter); always present and disabled at default rather than appearing/disappearing (app-ui form law); a quiet text-level control under the rows, not another button row. Nearly free mechanically - each slider's `apply(defaultIdx)` already clears its own key. Label is Derek's to write.
- [ ] **Random - parked until the colors land** (2026-09-18). Band-only (precedent: `.filter-level-name`). Leaves scheme and red light alone. Parked because random showcases every cell, including unfinished ones - the same reason the default went Quiet. Open, unproven: draw only from combos with a passing verdict in `notes/walk-notes.md`?
- [ ] **Theme chat** (2026-09-18). A visitor describes their brand and the site re-paints into it. Same band-only reasoning as Random. Full plan: `notes/theme-chat-plan.md`.

## Viewers

- [ ] **Device stage - parked, not pressing** (2026-09-19). On large screens, show the site in a phone/tablet-width stage so a desktop visitor sees the small-screen design without resizing or losing their place. Iframe ruled out; the route is container queries. Everything learned, the hazards, and the one-hour experiment to run first: `notes/device-stage-plan.md`.

## Mock reviews - a joke testimonials section (Derek, 2026-09-26 - an idea)

Inspired by CodeKit's "Entirely legitimate and serious opinions" reviews block: a wall of deadpan testimonial cards that are obviously jokes. First one Derek named: Claude saying something very AI about him ("Derek always has the very best ideas...") - the joke lands because the flattery is exactly the sycophancy everyone recognizes.

The one line to hold: **every "reviewer" is either not a real person (Claude, a cat, a linter) or a real person who actually said it and is fine with it.** CodeKit puts made-up words in real executives' mouths, which works for a famous app's parody but not on a hiring site - a recruiter skimming could take a fabricated quote from a real, named person as real, and that's a credibility cost, not a laugh. Real quotes from real people (a student, Ivy, a former colleague) are the strongest version anyway: funny AND true. Where it would live, and whether it's a first-layer thing or behind a door, is open (the progressive-disclosure rule decides).

**Lines so far (2026-09-26)** - Derek: "I care more about making the good jokes than anything else." Picks marked ★. Raw material, not final copy - his call on every word when the section gets built.

Claude (the sycophant) - a bookend pair Derek likes: open the wall with the first, close it with the callback:
- ★ **Claude**, *Enthusiastically Agreeable Assistant*: "Derek always has the very best ideas."
- ★ **Claude**, *Enthusiastically Agreeable Assistant*: "You're absolutely right!"

What makes CodeKit's set work (their full text was pasted 2026-09-26 - borrow the shape, never the lines): the reviewers talk to each other like a group chat (Tim Cook: "stop quoting my employees" / Ternus: "*My* employees"); callbacks (Siri twice, funnier the second time); the job titles are half the joke; and one card is TRUE (their trademark rejection, "Not Kidding; This Really Happened"). Open: Derek's own true card - something absurd that actually happened.

The industry types (the testimonial genre itself is the joke - the title does the work):
- ★ **Trendy Conference Guy**, *Has Not Taken the Course*: "Derek's course is really great. The way he teaches, just - wow." (Derek's)
- ★ **Hipster Design Leader**, *Loves Fantasy Football*: "Derek is always asking questions and saying things. I'm not really sure - back to posting about design being dead and shilling for random AI startups" (Derek's)

The bots (the joke is how that bot actually talks):
- ★ **LinkedIn AI**, *Profile Writing Assistant*: "Derek Wood is an experienced professional with experience in experiences."
- ★ **Automated Recruiter**, *Personalized Just For You*: "Hi Derek, your background in Java looks like a great fit for our Senior Java role!" (every recruiter reading it has sent one)
- ★ **Grace Hopper**, *Rear Admiral, U.S. Navy*: "Derek's right. It's not a 'bug' if you just wrote a broken program." (Derek's line. Deliberately NO mention of the 1947 Mark II moth - the people who know the history get it instantly, and explaining it kills the joke. The deadpan true title does the work.)

**The rule for real people (Derek, 2026-09-26):** we're imagining what these people would say, in good faith - the test is **would that person see it and laugh?** Their real persona, affectionately exaggerated, passes (Theo's sponsor cutoff, Prime being upset that PHP is fast). Words that would embarrass them or misrepresent them fail - and so does making them Derek's hype man ("Derek's a genius" is bragging in someone else's voice). Only the obvious fakes gush (Claude, the bots, the conference guy who never took the course) - their praise IS the joke because it's hollow.

YouTube (each one in their own on-camera voice):
- ★ **Theo**, *Has Thoughts*: "He doesn't use React?? This is actually insane. And he understands CSS margins? Nobody understands margins. Anyway, this video is sponsored by…" (Derek's riff - the React shock plus Theo's own CSS-margins confusion. If Theo really said something like the margins line on video, find the clip: a real quote is the TRUE card, and a timestamp link is the punchline.)
- ThePrimeagen, *Reads It Out Loud So You Don't Have To* (unpicked; joke on him, per the rule): "It's plain PHP. It's actually blazingly fast. I'm upset." / "Does he use Vim? No? …I'm going to allow it."

Steve Jobs (riffing on his own famous lines):
- ★ "Derek is exactly who Apple should hire. But he cares whether you can read the button, and right now we're doing Liquid Glass." (Derek's, tightened - Liquid Glass's legibility complaints)
- ★ "I always said design is how it works. Derek actually went and checked." (Derek's trim)
- "He'd have been perfect at Apple. But he's intent on making things people can read." (the short Liquid Glass jab - Derek's wording)
- ★ "I took one calligraphy class and it changed the Mac. Derek took the whole art school. Honestly, it's a little much." (Derek: Jobs would never say "it's a little much" - it's Gen Z, which is what makes it funnier)
- "…and one more thing. He writes the CSS by hand."
- "Derek is a true visionary. Unfortunately, he'll have to wait around until people have the higher resolution to see it." (Derek's first one)

**Round 2 (2026-09-26, Derek + an outside brainstorm session)**

**Format decision: quote first, always** (Derek: "objectively better in every way"). The attribution is the reveal: the reader meets the testimonial, believes it for a beat, then the name + title flips the whole thing. It's also just the right order for any testimonial - the words carry it and the name backs them after. Never lead with the person. Quotes can run long and earnest - the length is the setup. (Cards earlier in this list are written name-first as shorthand; they get flipped when this is built.)

    "Derek's course is really great. The way he teaches, just - wow. I mean, you can tell he really knows this stuff. The whole thing is very thoughtful. Really great course. I've heard nothing but great things."
    ~ Trendy Conference Guy
    Has Not Taken The Course

**The balance rule.** One "the org didn't get it" joke is great; a category of them tells recruiters "brilliant, but nobody knows what to do with him" - the wrong subtext. The recurring character is Derek asking for the thing everyone already agreed they wanted, while perfectly recognizable workplace behavior happens around him. Spread the targets: sometimes the organization, sometimes AI, sometimes the industry - and sometimes Derek is absolutely the problem.

Derek's (★):
- ★ "Derek's background really stood out to us. His extensive Java experience makes him an exceptional match for this opportunity." ~ Automated Recruiter, Personalized Just For You (replaces the earlier recruiter line)
- ★ "Derek Wood is an experienced professional with experience creating experiences that deliver meaningful experiences." ~ LinkedIn AI, Profile Writing Assistant (longer version)
- ★ "Derek fundamentally changed the way I think about design." ~ ChatGPT, Started This Conversation 11 Seconds Ago
- ★ "Once, Derek mapped out the entire product journey and how we were going to sell it on a huge wall in the office. Very impressive. No idea how his mind works. Keep up the good work. We're just going to keep doing what we always do." ~ CEO, Hired Derek as Senior Product Designer (alt title: "Hired Derek to Change How They Did Things"). "Keep up the good work" stays - the spectacularly useless response is the joke. This is THE one org joke (see the balance rule).

Candidates from the outside session (unpicked):
- "Derek kept asking what success looked like, so eventually we added 'Define Success' to the backlog." ~ Product Manager, Moved It To Q4
- "He asked if anyone had talked to the users. We had." ~ Executive Team, Talked To Sales
- "Derek asked why we were redesigning it." ~ Stakeholder, Requested The Redesign
- The jokes on Derek: "It was supposed to be a 15-minute kickoff." ~ Calendar Invite, 2:47:13 / "He said he just had one question." ~ Whiteboard, Full

## Prose rhythm: the container owns the space (Derek, 2026-09-29 - house-level, pssst first)

Today three one-offs each space a figure inside prose - `.entry-figure` (journal.css), `.study-figure` (case-study.css), `.page-figure` (how-i-work.css) - because the shared rhythm in `typography.css` only knows specific pairs (p+p, h+p, div+p), so any new pairing falls through (the How I work sketch sat flush against the paragraph below it). The long-term plan: one flow rule on the prose container - `:where(.styled) > * + * { margin-block-start: var(--flow, 1em) }`, with figures/pre/tables (and the element after them) setting `--flow: 2em` - so the container owns the space between its children and no child sets its own outer margin (the "components never position themselves" rule, applied to prose). Rollout order: pssst-css first (the methodology source), then this site (replace the pair rules, strip the spacing from the three figure classes), then other sites on next touch. `--flow` is also the natural first `--rhythm-*` token that typography.css's comment already names. A good PE lesson too: the same idea as `gap`, applied to flowing text.
