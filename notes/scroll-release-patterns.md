# Scroll release patterns - the catalog

Started 2026-09-20. The goal (Derek): "find all the patterns that work / and document them - so we can have the best of both worlds - by being sly." Both worlds = the held, staged moment a pin gives you AND scrolling that stays pleasant. Private working doc, and raw material for a journal piece (nobody has written about the release moment of a pin - see the research summary).

**Where the truth lives.** Every pattern here points at something you can feel:
- The lab: `experiments/practice-layers/index.html`, "THE RELEASE LAB" at the bottom of the phone column. Live at https://derekthomaswood.com/experiments/practice-layers/ - each test is a pin (three screens unless it says otherwise) that lets go into numbered blocks, with a row of jump links at the top (letters are never reused - B and D were ramps, and were cleared out with the rest of the failures on 2026-09-20); **the number you land on is the measurement.** The how and why of each test is in the comment beside its CSS or script.
- The research in full (Derek's own thread, the three passes, every source): `notes/scroll-release-research.md`.
- The verdict history: `experiments/practice-layers/HANDOFF.md` ("The sticky-release problem" and the lab verdicts above it).

**A status means Derek's thumb on his phone, nothing less.** "Unjudged" = built and measured in a desktop browser only.

## The vocabulary (names to reference)

- **Release overshoot** - the problem: the next section flies past when a pin lets go. (Coined here; nobody has named it.)
- **The let-go** - the line where a pin ends and the page is 1:1 again.
- **Flick mode** - what a pin trains on a phone: the thumb stops dragging and starts throwing.
- **Honest reference** (lab K) - something real always scrolling at true speed, so the hand keeps its pace.
- **Release by arrival** (lab L) - the pin ends when the next thing, moving at true speed, reaches its seat; they leave together.
- **Pin after pin** (lab P, the finale) - the next scrubbed animation eats the incoming fling as fast playback, then throws its own.
- **Pass-through fun** (lab N) - animation tied to an element's own trip through the screen; no pin.
- **Run-out** (lab G/H/P) - a stretch with nothing to read, for a fling to be spent in. Ruled out on its own.
- **The catch / the soft catch** (lab C/F) - script ends the momentum at the let-go. Ruled out.
- **Release ramp** (was lab B/D) - easing the pinned thing up to full speed before the let-go. Ruled out.
- **The three laws** - below. The first is the one-liner for the article: *a ramp lives at a place, a fling lives in time.*

## The problem, in four lines

1. Touch scrolling is 1:1. A pin makes the finger slide over content that doesn't move, so people stop dragging and start FLICKING.
2. iOS boosts repeated flicks, and while pinned the fling is invisible, so it can't be caught by eye.
3. It crosses the let-go line with about half a second of momentum left: 1.5 to 3 screens.
4. No instruction can fix it ("there's no way to say 'this is almost done - get ready'"): a fling is already spent, and nobody is reading a progress bar.

## The three laws

- **A ramp lives at a place; a fling lives in time.** Anything that shapes speed by position only helps a hand that is moving slowly and watching. It cannot stop a fling already thrown.
- **Every pin is an absorber AND a generator.** A scrubbed pin swallows the fling coming in (the animation just plays fast, and looks on purpose) and throws a new one out (the still stretch trains the flick). A chain of pins never settles the debt, it forwards it.
- **So every chain must end in something that absorbs without generating:** a run-out, the end of the page, or a pin too short to start the flicking.

## Patterns that REMOVE THE CAUSE (no flick is ever trained)

| Pattern | The move | Cost | Status | Feel it |
|---|---|---|---|---|
| **Short pin** | Hold for about one thumb stroke (half a screen to a screen). The hand barely registers it, like a sticky header. | Little room for choreography. | Out (Derek, 2026-09-22: "the rest all seem to fail") | lab I, J |
| **Time, not distance** | "Let it sink in" is an ask about TIME. Once pinned, play the beats on a clock; don't make the reader pay for them in scroll. | A flinger can miss the show. | Out (Derek, 2026-09-22) | lab M |
| **Honest reference** | Never freeze the whole screen: something always moves at true speed (text scrolling over a held graphic - the news-graphics pattern). The hand keeps steering by it. | A layout constraint. Derek rejected "2 things on screen" for the layer sections, not for endings. | **SAVES US** (Derek, 2026-09-22: "where you actually always see some form of real scroll to keep the pace") | lab K |
| **Release by arrival** | The pin ends when something moving at true speed reaches its seat, and they leave together. Nothing the eye follows ever changes speed, so there is nothing to ramp. | Needs a piece of content to do the arriving. | **SAVES US** (Derek, 2026-09-22: "you're reintroduced to the scrolling speed by the next section reveal") | lab L |
| **Pass-through fun** | No pin at all. The effect is tied to the element's own trip through the screen (it widens, fills, fans as it goes by). A fling just plays it fast. | A sequence can't happen in one place over time; it has to be laid out down the page. | Keep in mind (Derek, 2026-09-22: "not sure what it solves - but it looks cool... having some scroll animation, no pinning, but help between sections") | lab N |

## Patterns that ABSORB a fling

| Pattern | The move | Cost | Status | Feel it |
|---|---|---|---|---|
| **Pin after pin** | The next scrubbed animation eats the fling as fast playback. | Forwards the debt: it throws its own fling out the far end. | Works (Derek, on the black layer into the finale: "it can run it out in the next animation... but getting out of THAT animation is then its own issue") | the component; lab P/O: "a rough release but a planned pin animation after that allows you to land anywhere in it without feeling terrible. But that puts you back into the same position" (Derek, 2026-09-22) |
| **Run-out** | True speed, nothing to read, horizontal marks (so speed can be SEEN again) spreading apart (so it looks like slowing). No script, scrolling untouched. | Page length. Must be made to look meant. | Out (Derek, 2026-09-22) | lab G |
| **`<run-out>` block** | The run-out as one reusable element dressed as a loader ("something is happening, nothing to read yet"). One length knob. | Same. Generic look; a component with its own material should use that instead. | Out (Derek, 2026-09-22) | lab H |
| **Run-out in the piece's own material** | The same job, made of what was just on screen: columns that run on at true speed and end one by one, further and further apart. | Has to be designed per piece. | Only as a pin after a pin - lands anywhere without feeling terrible, then forwards the debt (Derek, 2026-09-22) | lab P |
| **The end of the page** | Put the pin last. A fling into the bottom just rubber-bands. | Only one per page, and only if the content order allows it. | Unbuilt | - |
| **Deep landing** | Whatever follows a pin is taller than the overshoot, with nothing vital in its first screen. | Page design. | Unbuilt | - |

## Patterns that TAKE the fling (they touch the scroll - use at one line, one direction, only on a fling)

| Pattern | The move | Cost | Status | Feel it |
|---|---|---|---|---|
| **The catch** | Crossing the let-go faster than a person reads: set the scroll position (that ends iOS momentum) and run a short glide by script. | Frame-by-frame scroll writes; lands a few frames late. | "Closest in feel... but it still doesn't really gradually change / just a slower return to normal" | lab C, D |
| **The soft catch** | Same trigger, ONE scroll write to where the glide will end, content pushed back by the same amount, and the browser's own animation lets it out from the fling's real speed. | Still a takeover, however brief. Possible one-frame flash on a phone (untested). | Out (Derek, 2026-09-22) | lab F |

## Tried and OUT

| Pattern | Why it's out | Evidence |
|---|---|---|
| **Ramp before the let-go** (ease the pinned thing up to full speed) | Fixes the visual step, not the overshoot. Back at full speed by the line, so a late fling is spent at full speed. | "B does feel way better than A" then "none of the tests feel right. They all zoom super far right after passing them." (Lab B and D, removed; the component's own release ramp removed with them.) |
| **Scroll-snap brake on the page** | On iOS, one snap point on the page scroller switches EVERY fling on the page to fast deceleration (WebKit bug 243582). | "it's not free scrolling like iOS normally does" |
| **Announcing the end** (progress bars, "almost done" cues) | Too late for a thrown fling, and nobody is looking. | Derek's Apple-page argument |
| **Short pin alone** (I, J) | Still a pin: even half a screen of frozen screen is a screen the finger slides over with nothing moving, and the fling that follows lands at full speed. Length is not the lever - visible motion is. | Derek, 2026-09-22: "the rest all seem to fail" |
| **Time, not distance** (M) | Playing the beats on a clock changes what the reader gets, not what the hand is doing - the screen is still frozen while it plays. | same |
| **Run-outs** (G, H, P, O's) | Nothing to read is still nothing to steer by until it goes past, and a fling at full speed goes past it in a blink. A run-out made of a scrubbed animation (P) does land you softly - but it is a pin, and forwards the fling to its own end. | same; on P: "puts you back into the same position" |
| **The catches** (C, F) | Ending iOS momentum by script lands a few frames late, and however the let-out is shaped it reads as the page taking over. | C: "just a slower return to normal"; F out with the rest |
| **Slow zone after the let-go** (E) | Same family as the ramps: a place-based speed change, and the fling is spent in it as scroll distance the reader then has to travel back. | out with the rest |
| **Taking over touch scrolling** (Lenis syncTouch, GSAP normalizeScroll, a custom scroller) | Rebuilds the thing people hate; loses address-bar, zoom and accessibility hand-offs. | Research only - rejected untested |

**Slow zone after the let-go** (lab E) - out with the rest (Derek, 2026-09-22).

## Being sly - the combinations

- **Pins only where holding still IS the message** (the stacking). Everywhere else, pass-through fun.
- **Let the next animation eat the fling** - then make sure the LAST thing in the chain is a run-out or the end of the page.
- **Make the run-out out of the piece's own material** so it reads as part of the show (for practice-layers: the columns running on, their stepped ends going by further and further apart).
- **Spend time, not distance,** for anything that is about letting a moment land.
- **The soft catch is the backstop,** not the plan: only for a pin that can't be designed around, and only if F survives a phone.

## The verdict (Derek, 2026-09-22, on his phone)

Two things save us, one is worth knowing, one is worth keeping in mind; everything else fails:
- **K, the honest reference** - something real is always scrolling, so the hand keeps its pace.
- **L, release by arrival** - the next section's reveal reintroduces the scrolling speed before the pin lets go.
- **Worth knowing: a pin after a pin** (P, O's run-out) - a rough release lands anywhere in the next planned animation without feeling terrible, but that puts you back in the same position at its end.
- **Keep in mind: N** - scroll animation with no pinning, as help between sections. Unclear what it solves; it looks good.
- Both winners remove the cause: they keep true-speed motion in view. Nothing that treats the fling after the fact (catches, run-outs, slow zones, short pins alone, timed beats) survived.

## Open questions

- How short is "short"? At what pin length does the thumb start flicking? (Lab: half / one / three screens, no ramp.)
- Does an honest reference beside a LONG pin beat a bare short one? If so, feedback is the lever, not length.
- How long does a run-out really need to be - where do natural flings die in G and H?
- Is the overshoot only momentum, or does the hand stay in flick mode for a gesture or two after? (Test A: fling, touch to stop at about 90% on the meter, lift, one normal gesture - landing on A1 or A2 means momentum only.)
- Trackpads: momentum arrives there as wheel events, which CAN be turned away at one line. Not explored yet.
