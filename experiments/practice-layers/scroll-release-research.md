# Scroll release - the research, in full

Companion to `scroll-release-patterns.md` (beside this file) (the catalog: what was tried, what Derek's thumb said, why). This file keeps the source material so the article can cite it: Derek's own research thread (2026-09-20), and the three research passes run the same day. Reports are kept as delivered. "Verified" in them means a source says it; "Inference" means the agent's reasoning, not tested on a device.

---

## Part 1 - Derek's research thread (pasted in 2026-09-20, from another session)

### What Apple actually built

Take a typical Apple product page - the one where the machine comes apart layer by layer as you scroll. That's not video. It's a pinned full-viewport canvas and a folder of pre-rendered frames - typically 60 to 200. Scroll progress through the pinned range picks the frame index. The page's own scrollbar keeps moving during it (NN/G's clip of the Watch Ultra page points at exactly this: the scrollbar advances while the page position stays put), so from the browser's point of view you're scrolling normally through, say, 4,000 pixels of document. From your eyes' point of view, nothing is scrolling at all.

**The gain math on that page.** 150 frames over 4,000 px of pin is about 27 px of scroll per frame. A normal wheel notch on macOS is often in that neighbourhood, so one notch is roughly one frame - a change so small you only see it as motion if you keep going. The felt gain has dropped by a large factor, and dropped in kind: instead of "the page moved," the feedback is "the picture changed slightly," which reads as slower than it is.

**Add the overlay text.** Apple fades in captions at specific progress points. So the user alternates between pushing (to see what's next) and stopping (to read). NN/G found this combination - altered scroll rate plus text you have to read - the worst case. It's also the case that most aggressively trains the hand: push, stop, push, stop, and each push is a bigger flick than the last.

**The encounter.** You hit the section; the machine hangs there. You scroll a normal amount; the chassis lifts a millimetre. By the second or third swipe you've settled into big trackpad swipes. macOS has noticed sustained fast input and its acceleration is up. You're clutching with inertia. The last frame arrives: the machine reassembled. There's no visual change that says "we're done," and your last swipe is still paying out inertia. The next section passes through the viewport in under half a second. Then you scroll back up to find it - re-entering the pin from the bottom, at the slow gain. That's the second punishment.

**Why Apple's version is the maximal case.** The pin fills the viewport (no honest velocity reference). The feedback is frame-to-frame change rather than translation (under-reads). The pin is long (the hand fully retrains, OS gain climbs). There's text (push-stop rhythm of big flicks). The release has no cue (the final frame looks like the first).

**Why Apple gets away with it.** Placement (deep in the page, after the buy path). Audience (NN/G: exploratory users tolerated it, task-oriented didn't). Brand (benefit of the doubt). And the makers never feel it - they scroll slowly, looking at the animation, often on a mouse.

**The one thing Apple makes clear:** the problem is at its worst precisely when the effect is at its best. The quality of the illusion is the size of the overshoot.

### The control loop

Nobody scrolls by distance. You push, watch the page move, and adjust. The hand is a controller reading one sensor - motion on screen - regulating visual velocity. When content streams slower than expected, you push harder. Not a decision; a reflex.

The number that ties effort to result is the **gain**: pixels of visual change per unit of hand movement. Normally constant across a document and across every app, which is why it never registers - until it changes.

**Two transfer functions between the hand and the pixels; you control one.** First, the OS driver: on macOS a table-driven gain with a smoothing window over the last eight events; sustained fast input pushes gain up, into the teens. That "lately" has memory - gain decays as the window refills. On a trackpad, inertia: after the fingers lift, the OS keeps emitting synthetic wheel events. Second, your page: a pinned section takes N pixels of scroll and moves the document 0.

**One encounter.** The pin engages; visual velocity drops; within a second the hand compensates; the OS raises its gain. New equilibrium: high effort, high OS gain, low page gain. The pin ends; page gain snaps back to 1:1 in a single frame; OS gain is still elevated; inertia is still paying out. For several hundred milliseconds the document receives high effort x high OS gain x full page gain - a multiplication that never occurred anywhere else on the page. The user's loop catches it after 200-400 ms; at that velocity that's a screen or two.

**Why the fix can't be "the user adapts."** The adaptation IS the problem. A step change in plant gain with no feedforward cue - every controller overshoots that. And the OS gain memory and inertia are outside the user's control anyway.

**Sticky is the same shape, usually milder** - sticky elements typically don't fill the viewport, so there's an honest velocity reference. Full-viewport pins remove it.

**Why it's invisible to the people who build it.** Designers test by scrolling slowly, looking at the animation, on a mouse. The failure needs an actual user on a trackpad.

**One-sentence version:** a pin trains the hand into a high-effort regime, the OS amplifies that effort with a gain that has memory, and the page restores full gain in one frame while both are still in the old regime.

### The survey (from that thread)

- HCI term: **control-display gain**. Researchers reverse-engineering OS drivers found macOS applies an acceleration table with a smoothing window over the last eight events; max scale factors ~14x (macOS) to 21x (IntelliPoint). The window resets on direction change but a same-direction release after a pin gets no reset.
- NN/G's scrolljacking study: fewer scroll interactions per visible change = less disorientation; most participants at least mildly disoriented, some read it as a bug. They never discuss the release problem - closest is "illusion of completeness."
- `scroll-snap-stop`: per the spec editor, "about draining the momentum out of a fling." Needs a snap container; Firefox trackpad bugs; doesn't stop Mac trackpad momentum triggering multiple section changes in custom implementations.
- GSAP: ScrollTrigger `snap` (snapTo, delay, directional, duration as {min,max} clamped by velocity, `inertia: false`), `anticipatePin` (entry-side cousin), `fastScrollEnd` (animation-side), `Observer.wheelSpeed`, `normalizeScroll` with a `momentum` function of velocity. ScrollSmoother smooths output, not input.
- Lenis: `virtualScroll` (per-event transfer function you own), `velocity`/`lastVelocity`, `scrollTo` with `lock`; no CSS scroll-snap support; honors `prefers-reduced-motion` by forcing lerp to 1.
- What doesn't exist: any article, library, or thread addressing release velocity after a pin as its own problem. Terms to borrow: control-display gain, scrolling transfer function, gain persistence, momentum drain. Terms coined here: **release overshoot, post-pin damping zone, gain ramp.**

**Note (2026-09-22):** the trackpad-gain model above is right for mouse and trackpad. On iOS touch there is no gain to retrain - touch is 1:1 - and the mechanism is different (Part 3). The lab was judged on an iPhone.

---

## Part 2 - iOS Safari and macOS: what the platform allows (agent report, 2026-09-20)

**Bottom line.** The "catch" rests on a real WebKit behaviour: current source stops the fling when a programmatic scroll arrives. The catch is always a few frames late, and one `scrollTo` should do the job rather than a per-frame glide. The reason root scroll-snap changed the whole page is visible in WebKit source, and it also suggests a way to turn snap on for one zone only.

### 1. Does `window.scrollTo` / `scrollTop` cancel in-flight momentum on iOS Safari?

Verified in current WebKit main source: when web content requests a scroll, WebKit's iOS handler is `_scrollToContentScrollPosition:scrollOrigin:animated:interruptAnimation:`; when `interruptAnimation` is set it calls `[_scrollView _wk_stopScrollingAndZooming]` before setting the content offset. Source: https://raw.githubusercontent.com/WebKit/WebKit/main/Source/WebKit/UIProcess/API/ios/WKWebViewIOS.mm. Not pinned down: which iOS version introduced it, or that a plain `window.scrollTo` takes that path.

Older reports contradict: a 2021 post says setting scrollTop does not work during momentum, with an `overflow: hidden` toggle workaround (https://lovemewithoutall.github.io/it/setting-scrollTop-on-momentum-scroll/); react-window issue 122 reports Safari oscillating between the momentum position and the written position.

Inference: the fling runs in the UI process inside UIScrollView; `scrollTo` runs in the web process and crosses asynchronously. So the cancel lands a frame or more late, the `scrollY` you computed from is stale, and calling `scrollTo` every frame is the oscillation pattern. Do the catch in one call and let the browser animate the rest (transform or scroll-driven animation on the content, not repeated scroll writes).

Tricks: `-webkit-overflow-scrolling: auto` stopped disabling momentum in iOS 13 (Apple forums 124077, 127392). `overflow: hidden` toggle reports are for overflow elements, and on the root it has long been unreliable at even blocking scroll (benfrain.com). `touch-action`/`pointer-events`/overlays: no source shows any of them stops a fling in flight; `touch-action` is consulted at drag end, not mid-deceleration.

### 2. Scroll-snap on iOS, and can it be local?

Verified from WebKit source: `RemoteScrollingCoordinatorProxyIOS::shouldSetScrollViewDecelerationRateFast()` returns true if the root node has ANY snap offsets on either axis - no distinction between `mandatory` and `proximity`, no matter how many areas or where. In `scrollViewWillBeginDragging`, WKWebView sets `UIScrollViewDecelerationRateFast` when true. So a single snap area anywhere on the root makes every fling on the page use Fast deceleration. Overflow scrollers follow the same rule per scroller. Sources: RemoteScrollingCoordinatorProxyIOS.mm, WKWebViewIOS.mm, ScrollingTreeScrollingNodeDelegateIOS.mm.

Bugs: 243582 "[iOS] CSS Scroll Snap disables momentum-based scrolling" - opened 2022, still NEW as of April 2026 (https://bugs.webkit.org/show_bug.cgi?id=243582). 223406 (`scroll-snap-stop: always` ignored during momentum) fixed 2021. 245722: smooth scrolling in a snapping viewport fails on iOS.

Inference (untested): the deceleration rate is sampled when each drag begins, so snap could be toggled on the root only while the viewport is inside the last screen of the pin (IntersectionObserver on a sentinel). Only gestures that START in that zone get Fast deceleration. Risks: offsets must reach the UI process before the touch; a fling thrown from above the zone gets nothing; turning snap on may itself re-snap. A wrapper scroller keeps it local but loses address-bar collapse and tap-status-bar - too high a cost.

### 3. Are scroll events synchronous with paint during momentum?

Verified: momentum is UIScrollView's, outside WebKit (https://trac.webkit.org/wiki/Scrolling); scroll events fire continuously during momentum since iOS 8; sticky/fixed are positioned by the scrolling tree, not the main thread. Inference: `scrollY` trails the screen by one to two frames, updates can be coalesced; velocity over a few samples is fine for a threshold test; by the time JS sees the line crossed at a fast fling the page is tens of pixels past it - a catch has to trigger ahead of the line.

### 4. macOS inertial wheel events

Verified: momentum arrives as synthetic wheel events; historically nothing exposes the phase (`momentumPhase` is native-only; lethargy uses delta decay). New: `WheelEvent.momentum`, a boolean added by pointerevents PR 643; Blink Intent to Ship 2026-06-16 for Chrome 151; Gecko positive; WebKit no signal (standards-positions 688). `preventDefault` on a `{passive: false}` wheel listener cancels momentum wheel events (document-level wheel listeners default passive since Chrome 73); cost: the browser waits for JS, losing the fast path. Inference: swallow momentum-phase wheel events for a few hundred ms after the release line, with the listener attached only near the boundary.

### 5. Platform primitives

`scroll-snap-stop` is the only momentum drain (with the iOS side-effect above). `scroll-behavior`/`overscroll-behavior` don't touch fling physics. `scroll-initial-target`: Chrome 133+, initial position only. `scrollend`: Chrome 114, Firefox 109, Safari 26.2 - detects the end, doesn't shape it. Scroll-driven animations: Safari 26.0+, accuracy fixes in 26.5 for view-timeline progress near 0%/100%. `scrollsnapchange`/`scrollsnapchanging`: Chrome 129+, Safari unverified. Friction/deceleration control: nothing shipped or proposed at the CSSWG; Firefox has `apz.fling_*` prefs, not author-exposed.

### 6. Smooth-scroll libraries on touch

Lenis: native touch by default; `syncTouch: true` replaces it with simulated inertia (`syncTouchLerp` 0.075, `touchInertiaExponent` 1.7, `touchMultiplier`), "can be unstable on iOS<16"; Safari capped at 60fps, 30 in low-power. GSAP `normalizeScroll`: moves scrolling to the JS thread, skips every other `touchmove` on iOS, own momentum (`momentum` = function of velocity returning a duration), prevents bounce and mostly the address bar; marked experimental; gives up during multi-touch/zoom; scrollbars may not appear; forum reports of forced address bar in portrait, broken long-press, slight lag, momentum cut short at page bottom (confirmed on 3.14.2). ScrollSmoother `smoothTouch`: off by default because it "feels odd"; freezes with normalizeScroll. Locomotive v5: rebuilt on Lenis, native on touch.

### Summary

Possible without replacing native scroll on iOS: cancel a fling after the fact with one programmatic scroll (late); toggle snap on only in a boundary zone (untested); design around it (shorter pins, more motion on screen, a buffer after). Possible only by replacing native scroll: shaping deceleration, capping fling distance, friction at a place. macOS is more tractable: momentum is wheel events, cancellable at one line, and Chrome 151+ flags them.

---

## Part 3 - First-principles critique of this component (agent report, 2026-09-20)

*Nothing in this was run or tested on a device; two iOS facts are from memory and flagged.*

### The control-loop model

Right: "a ramp lives at a place, a fling lives in time" rules out every place-based fix.

Wrong: "the hand recalibrates its gain" is a mouse/trackpad idea. On iOS touch content tracks the finger 1:1. Under a pin the finger slides over pixels that don't move, which reads as "stuck, or at an edge" (the rubber-band is the only other place iOS breaks 1:1). The response is a change of mode, not harder pushing: the visitor stops dragging and starts flicking repeatedly.

Missing: (1) flings are the only sane way through a long pin - a thumb stroke is about half a screen, so 4.85 screens is ~10 drags, and nobody drags that far; (2) the expected overshoot is large - iOS momentum decays with a time constant of ~500ms (from memory), so a crossing at 2-4 px/ms leaves 1000-2000px, 1.5 to 3 screens, with no recalibration involved; (3) iOS boosts velocity on rapid consecutive same-direction flicks (as recalled from UIScrollView behaviour) - a pin where nothing confirms progress provokes exactly that pattern; (4) normally you catch a fling by watching content stream past; in a pin the fling is invisible, so reaction time alone costs 400-800px.

The real finale is nearly as bad as the lab pins: the hand's loop runs on displacement, not opacity or colour. Of 4.85 screens only the widening (1.4) moves anything, at a displacement gain of ~0.2. Frame, words, rest and release are ~3.4 screens of no displacement. The last layer's words pin for `--last-dwell: 110vh` immediately before - about six screens of back-to-back pinning.

**The root design error: "let it sink in" and "stick longer" are asks about time, implemented as scroll distance.** Distance cannot hold a reader. It only taxes them in flings, spent on the next section.

Verdict: mainly momentum thrown during the pin, amplified behaviourally (flick mode, the consecutive-flick boost, invisible flings). Persisting recalibration is worth maybe one gesture.

Cheap experiments: on Test A, fling, touch to stop at ~90% on the meter, lift, one natural gesture - landing on block 1-2 means recalibration is negligible. Drag only, never flick, through Test A - overshoot should vanish; count the strokes to feel why nobody scrolls that way.

### Design space (cause or symptom / survives "fling lives in time" / cost)

- (i) Trigger, don't scrub - cause; yes; flingers miss the show, an unpinned figure drifts while animating, less code.
- (ii) Short pin (about one stroke) - cause; yes; less choreography room.
- (iii) Honest 1:1 reference on screen - cause; mostly; a layout constraint.
- (iv) High visual gain - cause; partly; only displacement counts.
- (v) Discrete steps (horizontal snap carousel; iOS doesn't pass momentum from an inner scroller to its parent, as recalled) - cause; yes; swipe discoverability, and the vertical seam is lost.
- (vi) Landing zone - symptom, cheap, always correct; the retelling is ~500px, the worst geometry for it.
- (vii) The catch - symptom; only if `scrollTo` kills momentum; a scrolljack.
- (viii) Custom touch scroller - reject untested; it reimplements what people hate.
- (ix) **Release by arrival** - cause; the reusable idea: a pin should end when a 1:1-moving element arrives at its seat, not by the pinned thing accelerating. The element the eye follows never changes speed, so there is nothing to ramp.

### Restructuring the finale

Plan A - one-screen pause, rest played on time: delete the rest and release beats; runway ~100vh; scrub only the widening (or play it on time, ~1.2s from the pin); frame, line, words, colour wave as timed CSS transitions from one flag; shrink `--last-dwell`.

Plan B - release by arrival: the closing words stop being absolutely positioned inside the figure and become ordinary content at the bottom of `.finale`; they scroll up 1:1 into the empty corner under the short columns; the pin ends exactly when they reach their seat; figure, words and line leave together; pure CSS; ~1.2-1.5 screens of pin.

Both: make the retelling at least one screen tall, nothing critical in the first screen after it.

### Staged plan

0. The two no-code tests plus an instrument chip (crossing speed, finger down or not, touchstarts during the pin, rest position).
1. Rebuild the lab with a no-pin control and five pins: 0.5, 1, 3 screens; 3 screens with a half-screen 1:1 reference; 1.5 screens released by arrival.
2. Port the winner to the finale behind the test switches - Plan A first (it deletes code), then B.
3. Size the landing zone.
4. Only if 1-3 fail: the horizontal snap carousel.

Remove: the ramp in the component, root scroll-snap, the catch. Likely end state: a pin of about one screen, beats on time, words arriving at 1:1, less script. What cannot be solved: there is no API on the root scroller that reads or cancels momentum; any pin crossed mid-fling overshoots by about crossing speed x 0.5s.

*(What happened: the lab was built as step 1; Derek's thumb said K - honest reference - and L - release by arrival - are what save us. See the catalog.)*

---

## Part 4 - HCI literature and practitioner prior art (agent report, 2026-09-20)

The literature says a gain change tied to a place works when the hand is moving slowly, but can't absorb a fling already in flight. Options left: keep the fling from building, make the landing forgiving, or drain the momentum at the let-go. CSS can't drain on iOS without collateral damage. No source addresses the release moment of a pin.

Sourcing: Snap-and-go and the 2012 scrolling-transfer-function paper were read directly; the content-aware kinetic scrolling paper, "Touch scrolling transfer functions" and the Springer scrolljacking chapter were blocked (abstracts only).

### HCI

- **Snap-and-go** (Baudisch, Cutrell, Hinckley, Eversole, CHI 2005). Traditional snapping warps the object to the target, making nearby positions unreachable. Snap-and-go inserts extra motor space ("friction pixels") at the target instead; the object is held there and breaks free if the user keeps moving. Alignment up to 138% faster (1D) / 231% (2D) than no snapping, only 3%/14% slower than traditional snapping. Users preferred friction values of 20-30. Some wanted a visible latch cue; the authors added one (brief overshoot-and-return on latch, backwards-first on break-free). Closed-loop mouse dragging only - nothing about ballistic passes. https://www.patrickbaudisch.com/publications/2005-Baudisch-CHI05-SnapAndGo.pdf. Inference: the release ramp IS snap-and-go applied to scrolling; it works in the literature because the hand is under visual control the whole way, and a fling is not.
- **Semantic pointing** (Blanch, Guiard, Beaudouin-Lafon, CHI 2004). Each target has a visual size and a motor size (control-display ratio changed near it); pointing difficulty is governed by motor-space size. http://iihm.imag.fr/blanch/publications/chi2004/chi2004-sp.pdf. Inference: the pin makes itself several screens deep in motor space while the diagram after it is one screen deep, right behind a region that taught the hand to fling.
- **Pseudo-haptics / sticky icons** (Lécuyer et al., CHI 2004 "Feeling bumps and holes"): changing CD ratio by location is perceived as texture. Inference: a pinned scrub reads as "stuck" or "heavy," and pushing harder is the natural reaction.
- **Scrolling transfer functions** (Quinn, Cockburn, Casiez, Roussel, Gutwin, UIST 2012): reverse-engineered macOS/Windows/Logitech drivers; gain is a function of input velocity, max factors ~14-18x; some drivers add gain cumulatively across rapid flicks; a "persistence" component carries inertia across time. https://gery.casiez.net/publications/UIST2012-scrolling.pdf.
- **Touch scrolling transfer functions** (Quinn, Malacria, Cockburn, UIST 2013) - abstract only; from memory documents flick-accumulation gain on iOS.
- **Content-aware kinetic scrolling** (Kim et al., UIST 2014) - the closest prior art: pseudo-haptic friction during inertial touch scrolling as content passes points of interest, decelerating the momentum phase itself (a time-domain intervention). Custom scroller. Follow-up: "Adaptive Kinetic Scrolling." https://dl.acm.org/doi/10.1145/2642918.2647401.
- **Content-aware scrolling** (Ishak & Feiner, UIST 2006) - content determines direction, speed and zoom along a path; a custom widget.
- **Speed-dependent automatic zooming** (Igarashi & Hinckley, UIST 2000; Cockburn, Savage & Wallace, CHI 2005): zoom out as scroll speed rises so visual flow stays constant; "hunting" (overshoot when the zoom returns). Inference: fast movement should show a larger landing zone; a pin does the opposite.
- **Flick-and-Brake** (Baglioni, Malacria, Lecolinet, Guiard, CHI EA 2011): after a flick, a stationary finger's pressure modulates friction. Inference: a touch is the user's native brake - but a user who doesn't know a target is coming won't brake.
- **Adaptation to gain steps** (motor-control studies, J Neurophysiol 2021; PMC11836133): an abrupt visuomotor gain change makes the first movement overshoot, shrinking trial by trial; gradual changes produce smaller errors. No HCI study of a mid-scroll gain step. Inference: one pin gives the user a single trial; the first-trial overshoot is the whole experience.

### UX research

NN/G "Scrolljacking 101" (https://www.nngroup.com/articles/scrolljacking-101/): most participants at least mildly disoriented; long scrolljacks read as bugs; quote: "That was a full swipe, and it moved nowhere... I would get severely agitated"; mobile makes it worse. Recommendations: functional progressive disclosure only, tune the scroll rate, include normal sections, keep it vertical, minimal text, avoid on mobile. No discussion of the release moment. Springer 2026 chapter (paywalled): 20 participants, scrolljacking worse on usability. No source found discusses the exit of a pin.

### Practitioner prior art

- Apple: apple.com/macbook-pro probed at 375px and 1024px - 14 muted videos with Play/Pause, no canvases, no long sticky pin found by heuristic. Time-based playback with controls, not scroll-scrubbed (one page, one heuristic). Older teardowns describe canvas image sequences (css-tricks).
- GSAP: `anticipatePin` fixes entry flash; `fastScrollEnd` finishes the animation, doesn't slow the user; `snap` fires after the overshoot. Jack on snap inertia: "toying with the idea of just eliminating inertia altogether." Observer "one gesture, one section" setups need time-based lockouts. Calling `refresh()` mid-fling stops iOS momentum dead (forum 37478) - inference: any layout or scroll write kills an iOS fling. Pinning guides recommend an unpinned variant on phones via `matchMedia`. No thread on "flies past the next section."
- Killing iOS momentum: `overflow: hidden` for ~10ms then restore (jsfiddle prud/umr0qegs); no official API (Apple forums 124077).
- Scroll-snap: Tab Atkins - `scroll-snap-stop` is about "draining the momentum out of a fling, so you don't overshoot by accident" (w3c archive 2017Jul/0256). WebKit bug 243582 confirms the removed snap-brake test was a known bug, not a mistake.
- "Don't scrub, trigger": `animation-trigger` (Chrome 145) - time-based animations fired when a scroll offset is crossed (developer.chrome.com, bram.us, css-tricks, utilitybend); no Safari support; the UX argument is weak in the sources.
- The Pudding (responsive scrollytelling; scrollytelling-sticky): the sticky graphic holds while text steps scroll over it at 1:1, crossing a step triggers a transition; on mobile keep the scroll only if the transition is meaningful, else stack; "short and sweet"; pixel heights from `innerHeight`, not `vh`; against steppers.

### Design-side mitigations

Keep the pin short (NN/G, The Pudding). Never freeze the whole viewport (The Pudding's step pattern). Discrete steps (runs into the iOS snap bug). Trigger instead of scrub. Stack instead of pin on mobile. Progress or end cues (unmeasured; follows from Snap-and-go's latch cue and Flick-and-Brake). User-driven steppers (Apple yes, The Pudding no).

### Ranked shortlist (the agent's, before the lab)

1. On phones, don't scrub the chart: trigger it, and keep 1:1 motion on screen (Pudding pattern). 2. Keep the catch as a backstop (Kim et al. is the precedent; cite bug 243582 beside the code). 3. Make the landing forgiving. 4. Announce the end before it arrives. 5. The release ramp for visual continuity only. Drop: CSS scroll-snap anywhere on the page, normalizeScroll/Lenis/Observer takeovers, GSAP `snap`.

*(What happened: 1 was confirmed by the lab - K and L. 2, 4 and 5 were ruled out on the phone; see the catalog.)*
