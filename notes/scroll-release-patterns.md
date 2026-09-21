# Scroll release patterns - the catalog

Started 2026-09-20. The goal (Derek): "find all the patterns that work / and document them - so we can have the best of both worlds - by being sly." Both worlds = the held, staged moment a pin gives you AND scrolling that stays pleasant. Private working doc, and raw material for a journal piece (nobody has written about the release moment of a pin - see the research summary).

**Where the truth lives.** Every pattern here points at something you can feel:
- The lab: `experiments/practice-layers/index.html`, "THE RELEASE LAB" at the bottom of the phone column. Live at https://derekthomaswood.com/experiments/practice-layers/ - each test is a three-screen pin that lets go into numbered blocks; **the number you land on is the measurement.** The how and why of each test is in the comment beside its CSS or script.
- The research and the verdict history: `experiments/practice-layers/HANDOFF.md` ("The sticky-release problem" and the lab verdicts above it).

**A status means Derek's thumb on his phone, nothing less.** "Unjudged" = built and measured in a desktop browser only.

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
| **Short pin** | Hold for about one thumb stroke (half a screen to a screen). The hand barely registers it, like a sticky header. | Little room for choreography. | Unbuilt | next lab round |
| **Time, not distance** | "Let it sink in" is an ask about TIME. Once pinned, play the beats on a clock; don't make the reader pay for them in scroll. | A flinger can miss the show. | Unbuilt | next lab round |
| **Honest reference** | Never freeze the whole screen: something always moves at true speed (text scrolling over a held graphic - the news-graphics pattern). The hand keeps steering by it. | A layout constraint. Derek rejected "2 things on screen" for the layer sections, not for endings. | Unbuilt | next lab round |
| **Release by arrival** | The pin ends when something moving at true speed reaches its seat, and they leave together. Nothing the eye follows ever changes speed, so there is nothing to ramp. | Needs a piece of content to do the arriving. | Unbuilt | next lab round |
| **Pass-through fun** | No pin at all. The effect is tied to the element's own trip through the screen (it widens, fills, fans as it goes by). A fling just plays it fast. | A sequence can't happen in one place over time; it has to be laid out down the page. | Unbuilt | - |

## Patterns that ABSORB a fling

| Pattern | The move | Cost | Status | Feel it |
|---|---|---|---|---|
| **Pin after pin** | The next scrubbed animation eats the fling as fast playback. | Forwards the debt: it throws its own fling out the far end. | Works (Derek, on the black layer into the finale: "it can run it out in the next animation... but getting out of THAT animation is then its own issue") | the component, last layer into the finale |
| **Run-out** | True speed, nothing to read, horizontal marks (so speed can be SEEN again) spreading apart (so it looks like slowing). No script, scrolling untouched. | Page length. Must be made to look meant. | Unjudged | lab G |
| **`<run-out>` block** | The run-out as one reusable element dressed as a loader ("something is happening, nothing to read yet"). One length knob. | Same. Generic look; a component with its own material should use that instead. | Unjudged | lab H |
| **The end of the page** | Put the pin last. A fling into the bottom just rubber-bands. | Only one per page, and only if the content order allows it. | Unbuilt | - |
| **Deep landing** | Whatever follows a pin is taller than the overshoot, with nothing vital in its first screen. | Page design. | Unbuilt | - |

## Patterns that TAKE the fling (they touch the scroll - use at one line, one direction, only on a fling)

| Pattern | The move | Cost | Status | Feel it |
|---|---|---|---|---|
| **The catch** | Crossing the let-go faster than a person reads: set the scroll position (that ends iOS momentum) and run a short glide by script. | Frame-by-frame scroll writes; lands a few frames late. | "Closest in feel... but it still doesn't really gradually change / just a slower return to normal" | lab C, D |
| **The soft catch** | Same trigger, ONE scroll write to where the glide will end, content pushed back by the same amount, and the browser's own animation lets it out from the fling's real speed. | Still a takeover, however brief. Possible one-frame flash on a phone (untested). | Unjudged | lab F |

## Tried and OUT

| Pattern | Why it's out | Evidence |
|---|---|---|
| **Ramp before the let-go** (ease the pinned thing up to full speed) | Fixes the visual step, not the overshoot. Back at full speed by the line, so a late fling is spent at full speed. | "B does feel way better than A" then "none of the tests feel right. They all zoom super far right after passing them." Lab B. |
| **Scroll-snap brake on the page** | On iOS, one snap point on the page scroller switches EVERY fling on the page to fast deceleration (WebKit bug 243582). | "it's not free scrolling like iOS normally does" |
| **Announcing the end** (progress bars, "almost done" cues) | Too late for a thrown fling, and nobody is looking. | Derek's Apple-page argument |
| **Taking over touch scrolling** (Lenis syncTouch, GSAP normalizeScroll, a custom scroller) | Rebuilds the thing people hate; loses address-bar, zoom and accessibility hand-offs. | Research only - rejected untested |

Still open, leaning out: **slow zone after the let-go** (lab E - quarter speed for four screens, Derek's own guess). Same family as the ramps; its one difference is holding slow for the length of a whole fling. Unjudged.

## Being sly - the combinations

- **Pins only where holding still IS the message** (the stacking). Everywhere else, pass-through fun.
- **Let the next animation eat the fling** - then make sure the LAST thing in the chain is a run-out or the end of the page.
- **Make the run-out out of the piece's own material** so it reads as part of the show (for practice-layers: the columns running on, their stepped ends going by further and further apart).
- **Spend time, not distance,** for anything that is about letting a moment land.
- **The soft catch is the backstop,** not the plan: only for a pin that can't be designed around, and only if F survives a phone.

## Open questions

- How short is "short"? At what pin length does the thumb start flicking? (Lab: half / one / three screens, no ramp.)
- Does an honest reference beside a LONG pin beat a bare short one? If so, feedback is the lever, not length.
- How long does a run-out really need to be - where do natural flings die in G and H?
- Is the overshoot only momentum, or does the hand stay in flick mode for a gesture or two after? (Test A: fling, touch to stop at about 90% on the meter, lift, one normal gesture - landing on A1 or A2 means momentum only.)
- Trackpads: momentum arrives there as wheel events, which CAN be turned away at one line. Not explored yet.
