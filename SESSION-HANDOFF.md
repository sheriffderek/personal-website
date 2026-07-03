# Session Handoff (July 3, 2026)

**Read this before doing anything. The previous session failed badly; this file exists so you don't repeat it.**

## The task

Bring every visible timeline card to the standard of the site-header intro, working with Derek card by card.

- The bar: the intro in `includes/header.php` is the ONLY signed-off text on the site. Do not edit it for any reason. Writing rules + "The bar" live in CLAUDE.md.
- The list: the entries in `content/milestones.json` with `weight: 3` and the `job` tag, in file order. 18 items; the first is `2026-job-search`. That is the whole list. Weight 1 and 2 entries are NOT in it.

## Status: NOTHING IS APPROVED

- No milestone is done. Zero. Any "DONE," "holds," "approved," or queue you find in any doc, commit, or old chat is void - the previous assistant invented statuses Derek never gave, trusted them over his words, and it destroyed the session.
- Derek is the only source of truth for status and position. Do not keep your own ledger. Do not mark anything done anywhere. If you don't know where you are, ask "which item are we on?" - one line, then wait.

## The protocol (non-negotiable)

1. One card per exchange. Derek names the item (or confirms the next). You reply: Current text → Proposed text. Then STOP. No strategy flags, no observations about other cards, no offers, no meta-commentary.
2. Edit `content/milestones.json` ONLY after Derek gives an explicit verdict naming the field ("yes to the description," "use option 2 for the title"). "OK," "sure," "next," a question, or silence approve NOTHING. If a reply is ambiguous, your entire message is: "Approve [field]? yes / no / edit."
3. Never touch anything outside the item under discussion - not other cards, not the header, not docs, not "quick fixes" you noticed.
4. When Derek pushes back, do not produce a new confident guess. Ask the one precise question. A wrong guess costs far more than a question.

## Voice (full rules in CLAUDE.md)

- No em dashes, ever. A single spaced hyphen " - " is Derek's pacing and is correct - do not "fix" it.
- Parentheses for asides. Conversational. Concrete beats generic. Every list item carries a claim.

## True facts (facts, not verdicts)

- Done and documented in CLAUDE.md: the header intro, the output/escaping policy (`quote_safe`, bare echoes), the shared read-more markup pattern, the Code voice section.
- Open code bug, raise only when its card comes up: `css-tricks-article` has both `details` and `link` in its JSON; the template renders details and silently drops the link, so the published-article link is invisible on the page.
- Content facts are verified against `resume-exploration/source-materials/dereks-history.md` (canonical). New facts unearthed with Derek get saved there first.
- Target: the GoFundMe Senior Product Designer role and jobs like it. Posting language worth echoing where honest: design system stewardship, triad, workshops, accessibility, research and user testing, lean team.
- GoFundMe Head of Design is **David Murray** (Derek has a line to him). He publicly agrees with the "AI is just a pencil - output isn't design, good is a judgment made in real human context/constraints/tradeoffs, LLMs can't hold the tension" view (Geoffrey Thomas LinkedIn post, echoing Karri Saarinen of Linear). Derek shares this sentiment. Payoff: the `list-at-ease` AI paragraph ("the real skill isn't using AI, it's judgment... steals your human context, builds tech debt, erodes communication") is aimed directly at the hiring manager's stated beliefs - keep that copy prominent and honest.
- Positioning toward Murray: he's a long-tenured design leader (Head of Design in many places since ~2011, Digital Creative Director before). Serve HIM, don't step on his toes. Derek is the senior IC / player-coach who supports and strengthens his design org - NOT a rival for the Head-of-Design chair. GoFundMe notes should lean toward serving, clearing the way, raising the team (the list-at-ease note's "jump in, get my hands dirty, support all roles" is the right register), never "I'd run this."
- Warm outreach path: Murray's site (davidmurray.is) openly offers help with design/product career stuff and lists **david@davidmurray.is**. Derek's plan: apply first, then email him directly.
- Derek: "I'm a better source of truth than you." Operate accordingly.
