# AI thoughts

Loose running notes on AI and process - things worth thinking about later, maybe journal or workshop material. Capture the idea rough; polish only if it becomes something. Each entry: date, a short overview of where it came from and what the point is, then the raw fragments.

## 2026-09-05 - Friction is part of the process

**Overview:** Recurring theme from a bunch of recent conversations: AI removes friction, but some of that friction was load-bearing - the struggle is where the understanding gets built. This round came from a r/softwarearchitecture thread where a dev shipped a 9k-line AI-built feature in a day, then couldn't say what the third service in his own branch was for without scrolling for a minute. The team was split between "he reviews it himself, we only look at the final PR" and "we should have been reading it in pieces since Tuesday."

- 9k lines of code doesn't sound like "a feature."
- Pushing the understanding until later just moves the expense - to a time where you basically have to start over building context (the human kind).
- Our main job is creating TRUST, so the business can make smart decisions based on that trust. By getting things done *faster* we actually erode that core value. The trust is more important than the code. But we'll have to find a way to make that visible.
- The best programmers aren't the ones you keep when you downsize because of coding skill - it's the people who know where everything is, who remember the whacky little config things, what went wrong, who have actual memory of the project decisions. We're renting that person's brain for storage. If the person fully offloads that, they aren't really needed.
- Would pairing through it be faster than reviewing it? What does it cost if it goes south? Now that there's a proof of concept, how long to just do it right?

## 2026-09-05 - TDD with AI

**Overview:** Same thread. A reply claimed "TDD is useless now, AI just builds tests that will pass based on the code it's written." The rebuttal: that's backwards - test-first is exactly what makes working with an LLM safe, and TDD is a concept that AI doesn't touch.

- It can't write tests for code it hasn't written yet. That's the whole point.
- Not "write a bunch of tests" - as a pair (you and the LLM) you walk through deciding what you want, use the test to design the thing, then use that as the guide to implement it. Tests written FIRST. Yes they then act as regression tests - and without those you wouldn't be able to use an LLM this way at all.
- TDD is a concept. Nothing about "AI" changes whether it's useful or not.
- There are totally scopes where you can test it and basically never read it - throw out directions for a super complex UI thing, and because it's a clear island, it doesn't matter. But that only works when it IS a clear island, in step-by-step slices - not one big squashed lump.
