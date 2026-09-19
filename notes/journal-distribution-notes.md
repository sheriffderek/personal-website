# Journal distribution notes

Working notes from the subscribe / comments / Substack conversation (2026-09-02).
Same posture as copy-notes.md - a running scratchpad, not doctrine.

## Decided

- **RSS: built.** `/journal/feed` (templates/journal-feed.php, routed in index.php,
  discovery `<link>` in header.php). Listed entries only, unlisted stays out, loose
  "Month Year" dates pin to the first of the month. Low usage expected; nearly free.
- **The journal stays on the site.** It's part of the exhibit, not just writing -
  entries render inside the theme system, and site-native entries (testing-code-study
  runs the CodeStudy editor, new-website-direction is about the site itself) cannot
  exist on Substack. For the Round-1 audience (a recruiter hearing Derek talk without
  leaving the surface), the site is the right home.
- **Substack = distribution channel, not home.** Existing subscribers keep getting
  email by cross-posting: prose-y entries can go over in full (site keeps the
  canonical URL), site-native entries get a short note linking home. Subscriber CSV
  is exportable anytime, so there's no lock-in clock - no migration needed now.
- **No roll-your-own email.** First server endpoint + deliverability obligations,
  too much machinery for current scale. If email-at-the-site is ever wanted,
  Buttondown is the shape (form posts to them, they can auto-send from the RSS).

## Comments - option map (nothing built)

- Disqus and kin: no. Ads, tracking, someone else's design.
- Giscus/utterances: no ads but an iframe in GitHub's clothes, and commenters need
  a GitHub account - wrong for the recruiter/designer audience.
- Webmentions (webmention.io + Bridgy): the indie-web answer. No comment box -
  people reply from their own site / Mastodon / Bluesky, replies render under the
  entry in our own markup, fully themeable. Caveat: volume looks like RSS usage.
  This is the build if social replies should ever display under entries.
- Roll-your-own: the only option that fully obeys the theme system, but it's the
  site's first user-generated content (escaping, spam, moderation forever).
- **Current shape: none of the above.** Reply-by-email link on entries (zero
  infrastructure, good replies can be quoted back into the entry) + share entries
  on social and let conversation happen there. Revisit webmentions when there are
  real replies worth displaying.

## Open question

Is the journal's job the pitch (recruiter reads it inside the site) or the audience
(designers/devs following Derek-the-thinker)? Currently answered "the pitch" because
of Round 1. If the audience job takes over post-search, the Substack balance shifts
and this file gets revisited.
