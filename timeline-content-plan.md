# Timeline Content Plan

The long-term source of truth would be in the resume-exploration project (or in it's own history) - so, anytime we're confirming something - we can look there -- and also, if we unearth any new info - we can save it there too. Got it?

The site is a reverse-chronological timeline of work. Each entry is a visual card (16:9 poster ratio), swipeable or scrollable. Visual-first, one sentence, an image or short video. Like Jenny Wen's site but denser because the work is denser.

## Design notes

- Each entry: 1600x900ish card/poster
- Swipe or scroll to move through time
- Video walkthroughs replace traditional case studies (Derek talking through the problem)
- High-level, not formal case study format
- The timeline itself tells the story of who this person is

## Entry formats (pick per entry, whatever fits best)

- **Single card/poster** — one 16:9 image, title, one-liner. For things that speak for themselves visually.
- **Slider** — 3-5 slides within the card. For responsive showcases, before/after, multi-screen work.
- **Video** — Derek talking through the problem and the thinking. For complex projects where the story matters more than the screenshot. PE stuff, List at Ease, architecture decisions.

## Read more / case studies (decided)

Every entry has a short keyword-loaded blurb (recruiter scan) and a "Read more" that unfolds inline (`<details>`) with the longer narrative. That covers 95% of entries.

Both `description` and `details` render raw HTML, so inline links to external pages (live product, press, talk recording, GitHub) work in either spot.

Opt-in escape hatch: any entry can graduate to a full standalone case-study page later (e.g. `/work/list-at-ease`) when the work warrants it — deep process, multiple flows, embedded video. The data model can support this by swapping the inline `details` for a `link` to the case-study route. Not building any of these for Round 1.

---

## Timeline (reverse chronological)

### 2026

**Fulcrum Insights** (June 2026)
- Consulting, design
- STATUS: documented, not in round 1. Semi-stealth. May be useful later.

**ETERNL Health** (June 2026)
- Consulting, design, dev
- Longevity startup, VC rollup of longevity clinics
- STATUS: documented, not in round 1. Semi-stealth. May be useful later.

**PE Visual Design Curriculum** (June 2026)
- Built out a major section of visual design education for PE
- Artifacts: curriculum structure, workshop examples, student outcomes
- STATUS: needs artifact capture

**Longevity Startup Landing Page** (May-June 2026)
- Research and landing page for a VC rollup of longevity clinics, aimed at funding rounds
- Did deep research in the longevity/clinic space
- Artifacts: the landing page (if still live), research notes
- STATUS: needs artifact capture

**Dating App Research** (2026)
- Deep research into the dating app field (connects to foray.club)
- Artifacts: research synthesis, competitive analysis
- STATUS: needs artifact capture

**PE Code Editor / CodeStudy** (March 2026)
- Rebuilt the in-app coding interface for PE
- Artifacts: before/after, the working tool
- STATUS: needs artifact capture
- ROUND 1 ENTRY

**World IA Day LA** (March 2026, organized Nov 2025 - Mar 2026)
- Co-chair. Organized the event over 4 months.
- Community leadership, information architecture, event management
- Artifacts: event photos, site, program?
- STATUS: needs artifact capture
- ROUND 1 ENTRY

**Design Systems / PSSST CSS** (ongoing, 2026)
- Design systems methodology, Figma work, PSSST CSS framework (currently reworking)
- Artifacts: Figma files, PSSST docs, code examples
- STATUS: needs artifact capture

### 2025

**List at Ease** (September 2024 - June 2025)
- Product design, product management, led dev team, strategy
- Full-stack app design and development, small team
- Never got it live, but tons of thinking and work to show
- Artifacts: designs, flows, architecture decisions
- STATUS: needs artifact capture, high priority (lots to show)
- ROUND 1 ENTRY

**AICAD Symposium: "Teaching for Tomorrow"** (November 13-15, 2024)
- Speaker: "Untangling Web Design Education"
- At ArtCenter College of Design, Pasadena
- AICAD (Association of Independent Colleges of Art and Design) annual gathering
- Topics: AI/ML, New Learners, Pedagogy and Practice, Graduate Education
- Artifacts: talk recording?, slides?, photos?
- STATUS: needs artifact hunt
- ROUND 1 ENTRY

**Weekly Open Office Hours** (started March 2024, ongoing)
- Free portfolio review and career advice, Saturdays 11am PST
- Posted on r/codingbootcamp
- Link: https://www.reddit.com/r/codingbootcamp/comments/1b4ubr7/free_open_office_hours_for_portfolio_review_and/
- Artifacts: the Reddit post itself, ongoing practice
- STATUS: have the link

### 2024

**PE Self-Paced Transition** (2024)
- Major pivot from cohort-based to self-paced model ($300/mo subscription)
- Business model redesign, platform changes, curriculum restructuring
- Artifacts: before/after of the model, the reasoning
- STATUS: needs artifact capture

### 2023

**A Better Life** (April 2023 - March 2024)
- UX/UI/front-end for a product (site is gone now)
- Artifacts: screenshots, design files if saved
- STATUS: needs artifact hunt, site gone
- ROUND 1 ENTRY

### 2022

**Equivalent Design** (February - August 2022)
- SVG and accessibility R&D consulting
- Built internal tools to teach teams about accessible SVG graphics
- Prototyped habit tracker app
- Artifacts: ?
- STATUS: needs details and artifacts

### 2021

**PE First Official Cohort** (March 3, 2021)
- First cohort launches during COVID lockdown
- Overlapping cohorts every 3-4 months for next 4 years
- Managed TAs and interns (advanced students teaching newer ones)
- 2-3 hour daily workshops, 6 days a week
- Program grew from 6 to 9 months based on student feedback
- Artifacts: early PE site, curriculum, student work
- STATUS: rich with artifacts, needs curation

### 2020

**Perpetual Education Founded** (Jan 2019 research, 2020 real students)
- Program Designer & Researcher phase: Jan 2019 - Mar 2021
- Researched and designed cross-disciplinary product design program from scratch
- Tested group formats, schedules, pacing with small cohorts
- Built six-month intensive program, 200+ interactive modules
- Self-funded with $50k (Lambda started with $50 million)
- ISA payment model (aligned incentives with student success)
- Artifacts: early PE site, curriculum structure, student work, workshop examples
- STATUS: rich with artifacts, needs curation

### 2019-2020

**Shopify/Vue Configurators** (2019-2020)
- Custom interactive product configurators for e-commerce agency
- Built interactive Vue components for product customization
- Prototyping through production
- Side work while building PE
- Artifacts: ?
- STATUS: needs details

**CSS-Tricks Article** (January 19, 2021)
- "On Type Patterns and Style Guides"
- https://css-tricks.com/on-type-patterns-and-style-guides/
- Published on one of the most-read front-end publications
- Artifacts: HAVE (the article itself)

**1-on-1 Mentoring** (2018-2020)
- Codementor, MentorCruise, word of mouth
- Mentored designers and developers worldwide
- Pattern recognition from these sessions directly informed PE curriculum
- Artifacts: testimonials?
- STATUS: context, not a standalone entry

### 2018-2019

**PXL / DreamWorks** (Nov 2018 - Apr 2019, 6 months)
- Senior Front-End Developer
- Built dreamworks.com, interactive quiz games, marketing microsites
- Clients: DreamWorks, Universal
- Sat in on pitch brainstorms for film campaigns
- Mentored junior developers (mentee rapidly moved up)
- Artifacts: dreamworks.com (may have changed), screenshots
- STATUS: needs artifact hunt (Wayback Machine)

**Echo Park Laundry** (2018)
- Wife bought a laundromat, rebranding and rehabbing it
- Derek spent much of 2018 taking apart and rebuilding laundry machines
- Brand, visual identity
- Artifacts: photos, brand materials
- STATUS: needs artifact hunt

### 2017-2018

**ChromaDex / Tru Niagen** (Oct 2017 - May 2018, 8 months)
- Senior Product Designer (first time officially claiming this title)
- Hired by former GA UX teacher who gave him prerequisite reading list
- Read "The Elements of User Experience," realized he'd been doing UX all along
- Made giant wall chart mapping everything that needed to happen
- Worked with scientists and doctors to translate complex biology
- Designed user health tracking system (can't make FDA claims, so let users track their own experience)
- Built component libraries, style guides, ran user testing
- Managed external teams (3D modelers, developers)
- Prototyped in Principle and code (Ember.js app)
- Made $135k + bonuses
- Hired replacement designer before leaving (responsible handoff)
- Artifacts: screenshots, design files?
- STATUS: needs artifact hunt

**Sycle** (2016-2017)
- Custom CMS project with college friend (great graphic/web designer)
- Good collaboration, project going well
- Artifacts: ?
- STATUS: needs details

**Naughty September / School Logo** (April 2017)
- Consulting, branding, CSS, product, moodboards
- Artifacts: ?
- STATUS: needs details

**Olives vs Olives** (2017-2018)
- Brand development, consulting
- Artifacts: ?
- STATUS: needs details

### 2016

**School Loop** (late 2016)
- Design system audit for education platform
- Built entire component system in CodePen (couldn't work in their codebase)
- Themable by high school
- They never implemented any of it
- Worked creatively within Java constraints (replicated Sass with string templates)
- Walked away (too weird, getting paid for unused work)
- Artifacts: CodePen components?
- STATUS: needs artifact hunt

**SEW Digital + Agency Contracting** (2015-2017)
- Multiple agency contracts across different clients
- Notable projects:
  - **White House Social/Jail Education Game** - interactive game about criminal justice/social issues, shown at the White House. First major educational interactive work.
  - **AAA Auto Insurance** - game-based apps making insurance engaging
  - **Fox Films** - data dashboards with early ML integration
  - **Microsites: Gap, Shell, vodka company** - brand microsites for major companies
  - **Ticketfly** - venue sites, built API prototypes for better responsive layouts (rejected after management change)
- Also: animation, SVG animations, parallax, ASP.NET projects
- One entry on the site. The range of clients and project types tells the story.
- Artifacts: ?
- STATUS: needs artifact hunt (White House game especially)

**Clean Program** (2014-2015)
- Health ecommerce company, everything in Magento, desktop-only
- Hired to make it responsive
- Taught the team Sass, component-by-component migration strategy
- Walked away from $144k/year contract on principle (micromanaging dev)
- Artifacts: ?
- STATUS: context entry, unlikely to have artifacts

**ShoutQ Inc.** (May 2014 - Jan 2015, 9 months)
- Frontend Developer + UX Designer
- Angular MVP for social media influence marketplace ("way ahead of its time")
- Pre-Instagram influencer economy
- Front-end, responsive, UI polish, user testing
- Bridge between design and engineering (UX engineer before the title existed)
- Company got overvalued, investor issues, collapsed
- Artifacts: screenshots?
- STATUS: needs artifact hunt

**Large Education System** (2015, under NDA)
- Design system audit, CSS architecture review
- Artifacts: limited (NDA)
- STATUS: may only be a mention

### 2013-2014

**Nouveau Studios** (Mar 2013 - May 2014, 1 yr 3 mos)
- First real agency job
- Frontend developer, but quickly pulled into strategy, project planning, client relationships
- Built custom WordPress themes for SMBs and publishers
- Taught the team Git, introduced modern development practices
- Thinking about design systems before they were called that
- Agency eventually collapsed (misspent money), offered partner role, Derek declined
- Artifacts: HAVE screenshots (Cam & Benny, Murder Doll House, Jamie Palumbo, Time No Place all from this era)
- STATUS: have some assets

**Cam and Benny** (~2013, at Nouveau)
- Custom illustrated WordPress site for animation creators
- Full visual environment (illustrated shelf UI, hand-drawn elements)
- Responsive (desktop/tablet/phone)
- FORMAT: slider
- Artifacts: HAVE screenshots

**Murder Doll House / Volcano Productions** (~2013, at Nouveau)
- Custom WordPress site for a production company
- Dark theatrical visual world, illustrated characters
- Responsive
- FORMAT: slider
- Artifacts: HAVE screenshots

**Jamie Palumbo** (~2013, at Nouveau)
- Musician site, bold pink/red, personality-driven
- Responsive
- FORMAT: single poster card
- Artifacts: HAVE screenshots

### 2011-2013

**Fundamonium** (Jan 2011 - Mar 2013, 2 yrs 3 mos)
- Freelance web design and development (this was the business name)
- First real clients. Static sites, WordPress themes.
- Early responsive design expert (started with responsive from the beginning, Ethan Marcotte era)
- Learned by trying to teach girlfriend HTML, got hooked
- HTML5 and CSS3 right as they came out
- Notable projects from this phase:
  - **Time No Place** (~2012-2013) - record label site. Artists, releases, shows, blog. First dynamic WordPress custom theme. "All was revealed" moment about CMS, databases, relationships. HAVE screenshots.
  - **Animator Duo / Disney Book Deal** (mid-2015, carried over from this client relationship) - three-book deal with Disney. One base site, three themes (Metal, Gold, Rust). Early SVG + themable design systems. One codebase, multiple visual expressions.
- Artifacts: early client sites (Wayback Machine?), HAVE Time No Place screenshots
- STATUS: partially have assets, needs further hunt

### 2009-2011

**Los Angeles / Holloys**
- Played in Holloys
- Designed posters, logos, and built multiple websites for the band
- Built band sites in Dreamweaver (just guessing, didn't really know code)
- Odd jobs including hanging art at residences, galleries, museums
- Toured Europe twice, performed in 11+ countries
- Artifacts: band photos, show flyers, posters, logos, website screenshots?
- STATUS: needs artifact hunt

### 2005-2009

**Pizzaiolo, Oakland + Post-College**
- Worked at Pizzaiolo (restaurant). Service, food and wine, economy of motion.
- Interim manager
- Continued playing music, screenprinting, designing outfits and stage stuff
- Lighting design with Max/MSP
- No clear career direction yet
- Artifacts: photos?
- STATUS: personal story, light artifacts

### 2001-2005

**BFA, California College of the Arts** (graduated 2005)
- Individualized major: Printmaking, Painting, Sculpture, Installation, Performance
- Started with illustration, moved to loose painting, then printmaking
- Learned Flash (first web dev), built websites for friends
- MIDI sequencing, Max/MSP, programmed lighting for live performances
- MySpace era: learned to style pages (early web customization)
- Tried to convince all friends to get websites ("college wasn't teaching anyone how to make money and get their art seen")
- Won "Start to Be" contest, scholarship to CCA
- Artifacts: senior show photos (ask friend), course work, performance documentation?
- STATUS: needs artifact hunt

### Pre-college

**Art High School, Coronado** (11th-12th grade, 1998-1999)
- Studio art, computer lab, wood shop, cross-departmental work
- Heavy Photoshop, designed a stencil font (inspired by Shepard Fairey)
- Designed skateboard company using custom font
- Led cross-department visualizations
- Screenprinting, street art
- Branding mentor (Starbucks case study at meetings)
- Won scholarship contest to CCA, did summer program there
- School went to 4:30pm (chose intensive education)
- Artifacts: artwork, the font?, screenprints
- STATUS: needs artifact hunt

**Utah** (late 8th grade - 10th grade)
- Moved to Utah, started using Adobe products
- "Inventions" class: designed device very similar to iPod (before iPod existed)
- XM satellite-style pod, connects to boombox, digital music portability
- Skate video editing (VCR to VCR)
- Airbrush work
- Artifacts: invention drawings? unlikely but look

**San Dieguito Academy "The Academy"** (9th grade, Encinitas)
- Block/semester system (4 classes/day, concentrated learning)
- Combined class: Photo, Business, Woodshop, Auto, Metal
- Psychology class using games as teaching tools
- Artifacts: unlikely

**Skyline Elementary, San Diego** (~1990-1996)
- "The Alternative Program" (later "Global Education")
- Montessori/Waldorf-influenced teachers (Lynda Legrange, Bobby Hilton)
- Adaptive learning paths, flexible environment, mixed-grade classrooms
- Peer teaching, game-based learning (Number Munchers, Oregon Trail)
- Piano and saxophone
- Drew comic book characters (systems thinking)
- Built forts, skate ramps
- Artifacts: drawings, comics if they survived

### Additional context (not timeline entries)

**Music career stats:** 2+ albums, toured Europe twice, performed in 11+ countries, shown artwork in 7 states
**Various jobs (age 12 onward):** bagger, checker, stocker, manager, prep cook, personal assistant, lighting designer, video store clerk, security guard, handyman, painter, fabricator, cabinet maker, bartender, cashier, production assistant, art handler, art installer, general construction
**Professional development:** Brad Frost "Subatomic" design tokens course ($900), Samantha Gordashko "Theming Design Systems" workshop ($450), mentored by Jeffrey Biles (Ember), David at ChromaDex (UX). Audited hundreds of online courses.
**Publications:** CSS-Tricks article (January 19, 2021) on semantic type systems and pattern languages
**Speaking:** AICAD Symposium 2024, WIAD LA 2026 co-chair, guest lecturer Turing School, LA Design and Dev Podcast, weekly open office hours
**References:** Matthew Weir (CTO Fulcrum Insights), Joe Sprankle (Sr Solutions Architect Amazon, from ChromaDex), Brian Lowery (Developer Ravenna Interactive, longest PE connection), Kyle Ross (Sr Software Engineer Altruist)

---

## Weighted entry list

Live weights (1-6, weight 1 = flagship) are assigned per entry in `content/milestones.json` - that file is the source of truth, never this one (rubric in CLAUDE.md). The groupings below are the original Round-1 triage that decided build order: must-haves for the GoFundMe application, then the next wave, then the rest.

With `?target=gofundme`, must-have entries get a tailored paragraph connecting to GoFundMe's needs. Each entry needs a 16:9 poster image and a one-liner (plus expandable detail via "Read more" that disappears after opening).

### Must-haves (Round 1, the "ok this person is serious" set)

**Default Mode** (2026)
- Frame: "Whenever I don't have a primary work focus, I naturally take on short-term consulting and design/dev projects, and use the rest of my time to learn new things and fold them back into the PE curriculum."
- This is the natural state, not a gap. Full-time roles are the exception.
- Includes Fulcrum Insights (design), ETERNL Health (design/dev), and any others
- Recently used GoFundMe as a teaching example with students (crowdfunding flows, campaign pages, donation UX)
- Proves: always building, always learning, self-directed
- GoFundMe angle: "I literally used your product as a case study with my students." Domain engagement you can't fake. PE students regularly build crowdfunding apps as projects. Derek has watched many people reason through donation flows, campaign pages, and share mechanics.

**PE Code Editor / CodeStudy** (March 2026)
- End-to-end build of in-browser code editor and learning environment
- Proves: end-to-end execution, high craft, consumer-facing UX
- GoFundMe angle: designing for users in high-stakes moments (learning = vulnerable, same as fundraising)
- Artifact status: needs screenshots

**World IA Day LA** (March 2026, organized Nov 2025 - Mar 2026)
- Co-chair. 4 months organizing.
- Proves: cross-functional leadership, workshop facilitation, community building
- GoFundMe angle: leading cross-functional alignment for a whole community, not just one product team
- Artifact status: needs event photos, program materials

**List at Ease** (Sept 2024 - June 2025)
- Product design, product management, led dev team, strategy
- Proves: product strategy, triad collaboration, experience vision
- GoFundMe angle: closest match to the role. Triad model, end-to-end ownership, strategy.
- Artifact status: needs designs/flows. HIGH PRIORITY.

**AICAD Symposium** (Nov 13-15, 2024)
- Speaker: "Untangling Web Design Education" at ArtCenter College of Design
- Proves: thought leadership, articulating design decisions, mentorship
- GoFundMe angle: raising the bar for an entire professional community
- Artifact status: needs recording, slides, or photos

**Weekly Open Office Hours** (March 2024 - ongoing)
- Free portfolio review and career advice, Saturdays 11am PST
- Talked with hundreds of designers and developers
- Reddit: https://www.reddit.com/r/codingbootcamp/comments/1b4ubr7/
- Proves: mentorship at scale, community, generosity
- GoFundMe angle: empathy, user-centered, raising the bar

**Better Life** (April 2023 - March 2024)
- UX/UI/front-end for a consumer product (site is gone)
- Proves: consumer-facing product design, empathy-driven UX
- GoFundMe angle: designing for people trying to improve their lives, same emotional register
- Artifact status: needs screenshot hunt. Site gone.

**Equivalent Design** (Feb - Aug 2022)
- SVG and accessibility R&D consulting
- Frame toward education and accessibility narrative (don't need to use their name)
- Proves: accessibility expertise, consulting, education-adjacent
- GoFundMe angle: accessibility, inclusive design, WCAG compliance
- Artifact status: needs details and artifacts

**CSS-Tricks Article: "On Type Patterns and Style Guides"** (January 19, 2021)
- Published on one of the most-read front-end publications
- https://css-tricks.com/on-type-patterns-and-style-guides/
- Proves: published thinker, type systems expertise, design systems thinking
- GoFundMe angle: "expert understanding of formal elements of design, including typography"

**PE Founded** (Dec 2019 research, first cohort March 2021)
- Built entire education product from scratch: curriculum, platform, brand, business model
- Hundreds of workshops, 200+ interactive modules, progression systems
- Proves: end-to-end product ownership, building from zero, logistics, strategy
- GoFundMe angle: built and ran an entire product for real users with real outcomes

**Mentoring** (~2018-2020+, spread across years)
- Codementor, MentorCruise, ADPList, Quora, Reddit, Slacks, Discords, word of mouth
- Pattern recognition from hundreds of mentoring sessions informed PE curriculum
- Proves: empathy, user research (informal), community, raising the bar
- GoFundMe angle: deep pattern recognition across hundreds of people's career journeys

**PXL / DreamWorks** (Nov 2018 - Apr 2019)
- Senior Front-End Developer. Built dreamworks.com.
- Game-like web experiences, complex animations, mentored junior devs
- Proves: recognizable name, high craft, team collaboration
- GoFundMe angle: shipping polished consumer experiences at scale

**Niagen Bioscience (formerly ChromaDex)** (Oct 2017 - May 2018)
- Senior Product Designer
- Interactive infographics, data viz, mobile-first, user testing, component libraries
- Made $135k + bonuses. Managed external teams.
- Proves: senior IC at a real company, formal product design process
- GoFundMe angle: translating complex information for general audiences, design systems stewardship

**School Loop** (late 2016)
- Design system audit for education platform
- Built entire component system in CodePen, themable by school
- They never implemented it (walked away)
- Proves: design system stewardship, education domain, systematic thinking
- GoFundMe angle: directly proves Heart design system stewardship ability

**Agency work** (2013-2017, lumped)
- Nouveau Studios, SEW Digital, ShoutQ, freelance contracts
- Includes: White House social/jail education game, AAA Auto Insurance games, Fox Films dashboards, Gap/Shell microsites, Ticketfly venue sites
- Proves: range, volume, shipping under pressure, multiple clients/contexts
- GoFundMe angle: breadth of experience across industries and problem types

**Crowdfunding Competitor (at Nouveau)** (~2013-2014)
- Mapped out a GoFundMe/Kickstarter competitor at Nouveau Studios
- One of Derek's first real UX planning projects
- GoFundMe started May 10, 2010. Derek was already thinking about this space.
- Proves: domain familiarity, been circling this problem for over a decade
- GoFundMe angle: "I've been thinking about crowdfunding UX since before your platform was established"
- [NEEDS INFO] Exact scope. Was it GoFundMe or Kickstarter competitor? How far did it get?

### Next wave

**PE Syntax Highlighting v2** (late 2024 / early 2025?)
- Precursor to CodeStudy. Shows progression.
- [NEEDS INFO] exact dates

**PSSST CSS / Design Systems** (ongoing)
- Design systems methodology, Figma work, PSSST CSS framework
- Moves up to must-have when rework is ready

**PE Self-Paced Transition** (2024)
- Major pivot from cohort-based to self-paced
- Business model + product redesign

### Fills out the timeline later

**Conspire** (dates?). No name publicly.
**Echo Park Laundry** (2018). Fun personality piece.
**Fundamonium** (2011-2013). Cam & Benny, Murder Doll House, Time No Place, Jamie Palumbo. HAVE screenshots.
**Holloys** (~2009-2011). Band, posters, logos, websites.
**Pizzaiolo** (2005-2009). Service design origin story.
**CCA / BFA** (graduated 2005). Art school roots.

---

## Artifact hunt checklist

### Must-have entries (immediate)
- [ ] Screenshot PE CodeStudy
- [ ] Gather World IA Day LA photos, program, attendance
- [ ] Gather List at Ease designs/flows (HIGH PRIORITY)
- [ ] Find AICAD Symposium recording, slides, or photos
- [ ] Hunt for Better Life screenshots/design files (site gone)
- [ ] Gather Equivalent Design artifacts or create narrative
- [ ] CSS-Tricks article screenshot or link treatment
- [ ] PE screenshots (founding era, workshops, platform)
- [ ] DreamWorks screenshots (Wayback Machine circa 2018-2019)
- [ ] Niagen Bioscience/ChromaDex screenshots (Wayback Machine circa 2017-2018)
- [ ] School Loop component system screenshots (CodePen?)
- [ ] Agency work collage (White House game, AAA, Fox, microsites)
- [ ] Crowdfunding competitor artifacts from Nouveau (if any exist)
- [ ] Office hours: have the Reddit link, maybe a screenshot of the post

### Next-wave entries
- [ ] PE Syntax Highlighting v2 screenshots
- [ ] PSSST CSS / Figma design system captures (when rework is ready)
- [ ] PE Self-Paced Transition before/after

### Later entries (future)
- [ ] Fundamonium projects (HAVE some screenshots already)
- [ ] Holloys band photos, posters, logos, websites
- [ ] Ask friend for CCA senior show photos
- [ ] Echo Park Laundry brand materials
