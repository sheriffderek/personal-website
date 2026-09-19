# The circle: the sheriffderek insignia as this site's way home

**Status: an idea Derek is thinking about (2026-09-18). Nothing is built. Do not build it until he says go.** This file exists so the idea survives between sessions - it should be readable cold, with no memory of the conversation it came from.

## The one settled part

Every page needs a visible way home in the top-left. That is one of the oldest conventions on The Web, and today this site's only way home is opening the menu and finding "Home." This part is not a taste question. Everything below it is.

## What the insignia is

Derek's mark across the internet, for 14+ years, is a small circle: soft pink on the left, hot pink on the right, split by one slanted diagonal line (leaning like a forward slash, not centered - the hot pink side is bigger).

- **The real source file** lives in the consulting site's repo: `sheriffderek-consulting-website/meta/favicon.svg` (standalone SVG: two paths, `#f2abab` left, `#f80365` right, `viewBox='0 0 4167 4167'`) and `sheriffderek-consulting-website/templates/partials/insignia.php` (the same two paths, but painted from tokens - `var(--color)` and `var(--ink)` - and each half is a secret light/dark mode switch there). PNG/JPG exports at many sizes: `sheriffderek-consulting-website/resources/social/insignia/`.
- **Where it already lives** (see `home-circle-idea/where-it-already-lives.webp`): the favicon and the share-card corner mark on sheriffderek.consulting; the ring around his face on Codementor; his GitHub, Stack Overflow (via Gravatar), and browser-profile avatar.
- **Where it doesn't:** derekthomaswood.com. This site has no favicon at all (no `rel='icon'` in `includes/header.php`), and no mark on any page. It is the one surface in the constellation not wearing it.

## Derek's sketches

He drew these on the live home page using his screen recorder's orange cursor ring as a stand-in for the circle. **The orange ring in the screenshots is the stand-in, not part of the site.**

1. **At rest** (`home-circle-idea/sketch-1-at-rest-above-name.webp`): a small circle, about the size of the tray's round buttons, sitting directly above the "Derek Wood" heading, left-aligned with the text column. It is in the page flow - it scrolls away with the header like everything else. Nothing floats.
2. **After scrolling back up** (`home-circle-idea/sketch-2-floating-after-scroll-up.webp`): scroll down and it is gone; scroll UP and it reappears, now floating at the top-left - on the same row as the tray's three circles (top-right) and the same size, so it reads as one family with them. It gets a drop-shadow-type treatment to say "I've lifted off the page," and **each theme gets to style that lift** (the same way each character owns `--corners`).
3. **The big corner option** (`home-circle-idea/sketch-3-big-corner-circle.webp`): instead of (or combined with) the small one - a much bigger circle peeking in from the top-left corner, mostly off the page, cropped by the viewport edge. Same move as the posters: the "Now interviewing" dome is a circle cropped by its frame; this is the insignia cropped by the browser. At that size the diagonal split (the actual mark) finally reads.

**The combo that came out of talking it through:** big corner circle on wide screens at the top of the page (there is an empty left margin for it); small circle above the name on phones (no margin - the big one would cover the name); small floating circle on scroll-up at every width. One mark, three sizes of the same idea.

## What it does when pressed

Goes home. On the home page itself, "home" means scroll back to the top (where the at-rest circle is).

## Open questions (Derek's calls)

- **Pink everywhere, or re-painted by the theme?** A logo usually keeps its colors; everything else on this site repaints. Precedent worth knowing: on the consulting site the insignia is ALREADY token-painted (`--color` / `--ink`), so "it follows the theme, and is pink where the theme is pink" has been done before. Note the site now starts visitors in the Quiet mood.
- **Face or circle?** Derek: "I'm kinda entering a time where maybe I just use my face - but also I like the calling-card image." The working split: his face where a person is being decided on (LinkedIn, the opener card, a home share image); the circle where he is being recognized (favicon, the way home, a share-card corner). Codementor already combines them - face inside the ring.
- **Small, big, or the combo.**

## Things to check against before building (this site's own rules, in `CLAUDE.md`)

- **The shell** ("The shell" section): there is one control strip, the `.site-tray`, and a separate floating corner cluster was retired once already. This idea holds up because at rest the circle is page content, and when floating it lines up with the tray's row - but it would still be a second fixed element, on the opposite side. Decide that on purpose.
- **The app-ui contract** (Theme system section): the chrome does not change with the theme. This circle is identity, not a control, and Derek WANTS its lift styled per theme - so it lives on the page side of that line, not inside `[data-ui='app']`.
- **Motion policy:** hiding on scroll-down and returning on scroll-up is motion the visitor caused, so it is functional - no reduced-motion guard by reflex.
- **Progressive disclosure:** the smallest honest first layer ships first.

## The smallest first slice, when he says go

1. A favicon from the real SVG (this alone puts the circle next to the site's name in Google results and browser tabs, the way sheriffderek.consulting already has it).
2. The plain small circle above the name, linking home. No floating, no shadow.

Scroll-up behavior, the themed lift, and the big corner version layer on afterward, each as its own slice.
