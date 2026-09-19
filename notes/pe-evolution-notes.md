# PE Website Evolution (from commit log)

## 2019 — Foundation & CMS Setup
**Page Modules & Content Structures** (May 2019 — Dec 2019)
- What shipped: Foundational WordPress theme, flexible content blocks (call-out, list-block, optional-reading), activity dashboard, and core lesson page architecture
- Evidence:
  - `2019-05-21 Add theme` / `Add other post types to activity dashboard`
  - `2019-06-13 Add optional-block module` / `Add svg sprite and work on call-out modules`
  - `2019-07-20 Add 'slide mode'` / `2019-09-09 Add todo-list`

## 2020 — Content Drip & Rich Page System  
**Page Module Expansion** (Feb 2020 — Dec 2020)
- What shipped: Picture/figure modules, goal/challenge/key-concept modules, bulletin system, rich-page template for flexible content delivery, video embedding with lazy-loading, and book-meeting CTA
- Evidence:
  - `2020-02-29 Add picture and figure modules` / `Finish stubbing out primary modules`
  - `2020-03-16 Create goal and challenge modules properly` / `Add key-concept module`
  - `2020-09-06 Add modules to rich-page` / `Create bulletin module`
  - `2020-10-30 Add rich-list module` / `Add video-size options`

## 2021 — Study Hall & Lesson Infrastructure
**Study Hall & Learner Interactivity** (June 2021 — July 2021)
- What shipped: Study-hall module with interactive tracking, lesson-loop overhaul, JavaScript section link creation, page edit links for instructors, and activity feed curation
- Evidence:
  - `2021-06-11 Add initial study-hall templates` / `Continue study-hall build out` / `2021-07-09 Sort out count for study-hall tracking`
  - `2021-06-25 Add JS section #link creation` / `Add page edit link to header`
  - `2021-06-28 Curate what shows in the activity feed`

## 2022 — Calendar, Milestones & Syntax Highlighting
**Calendar View & Major Content Restructuring** (May 2022 — Aug 2022)
- What shipped: Calendar view for lesson lists with admin-only access, milestones system with filtering and supplemental content, early syntax highlighting feature (tied to learner progress), code-example modules, and feature cards for CSS/HTML concepts
- Evidence:
  - `2022-05-29 Add "calendar view" base code for lesson list` / `Add links to calendar and standard views`
  - `2022-06-04 Add initial working milestone loop` / `Add milestones and supplemental includes`
  - `2022-07-24 Set earlySyntax option to be tied to the visitor's days passed` / `Add code_example_module`
  - `2022-08-06 Initial Vimeo rework` / `Add codepen_module`

## 2023 — Full Site Redesign & Syntax System Overhaul
**Visual Redesign + Syntax Highlighting Enhanced** (Feb 2023 — Sept 2023)
- What shipped: Comprehensive landing page redesign with GSAP scroll animations, improved syntax highlighting across all code modules (HTML, CSS, PHP, Vue), dashboard for students/admins with progress visualization, and major CSS architecture refresh
- Evidence:
  - `2023-02-08 Initial redesign commit` / `add circle charts section and learning section`
  - `2023-02-11 Add primitive gsap scrolltriggers to color blocks`
  - `2023-04-21 Add new dftw dashboard page` / `2023-05-04 Style dashboard page`
  - `2023-09-14 Add syntax highlighting option to all code modules`

## 2024 — Quiet Consolidation
**Maintenance & Infrastructure Refinement** (ongoing, 160 commits)
- What shipped: Primarily bug fixes, plugin updates, and incremental improvements to existing features; no major new user-facing features released
- Evidence: Commits heavily weighted toward updates, adjustments, and problem-solving rather than new module types

## 2025 — Language Infrastructure & Code System Overhaul
**Language Features & CodeStudy Framework** (Dec 2025 onward, 167 commits)
- What shipped: Multi-language support groundwork, major code syntax highlighting system refactor with HTML/CSS/PHP early syntax, new CodeStudy module architecture, component syntax transition, and normalization of code-centric components
- Evidence:
  - `2025-12-11 Transition to our component syntax` / `2025-12-18 Ensure early CSS syntax examples use HTML`
  - `2026-03-27 Add codestudy module test`
  - `2026-04-03 Continue work on normalizing the code syntax-centric components`
  - `2026-04-13 Save huge syntax highlighting progress and general setup`

## 2026 — Figure System & Admin Dashboard Redesign (Current)
**Figure Display Options & Design System Integration** (Apr 2026 — Jun 2026, 528+ commits)
- What shipped: Figure display-as options (frame concept for browser/app chrome wrappers), redesigned admin dashboard with design system integration, visibility/archive content management system, major code quality refactoring with service/integration organization, and site-wide tables/interactive components
- Evidence:
  - `2026-05-25 Change display_as concept to "frame" for figure` / `Document SVG figure proposal`
  - `2026-04-30 Continue to style admin and design-system` / `Wire up CMS to design system`
  - `2026-05-03 New visibility model - and how content types work`
  - `2026-05-02 split functions into integrations, services, api etc`

---

## Synthesis

**Major Feature Arcs Identified (7 distinct eras):**

1. **CMS Foundation (2019)** — Multi-block content system with flexible fields
2. **Content Delivery (2020)** — Rich media modules & drip system
3. **Learner Interactivity (2021)** — Study hall & progress tracking
4. **Curriculum Structure (2022)** — Calendar, milestones, early syntax awareness
5. **Visual Polish & Analytics (2023)** — Redesign, animations, admin dashboard
6. **Consolidation (2024)** — Bug fixes, infrastructure stability
7. **Language & Code Systems (2025–2026)** — Multi-language groundwork, CodeStudy framework, figure presentation options

**Storytelling Recommendation:**

Given Derek's portfolio context, **one robust carousel entry covering 2019–2026** works best, with **5–6 slides** representing the major inflection points:
- Slide 1: "Built the foundation" (CMS, modules, 2019–2020)
- Slide 2: "Made it interactive" (study hall, calendar, 2021–2022)
- Slide 3: "Redesigned for scale" (2023 visual overhaul + dashboard)
- Slide 4: "Evolved content delivery" (syntax highlighting, figure systems, 2025–2026)
- Slide 5: "Architecture maturity" (admin dashboard redesign, service organization, 2026)

Alternative: Create **two separate milestone entries** if focusing specifically on:
1. "PE Platform (2019–2023)" — The growth story
2. "PE Admin & Figure Systems (2025–2026)" — The recent infrastructure evolution

The cadence shows **consistent feature development with a major refocus every 12–18 months**, suggesting Derek was responsive to user needs and pedagogical requirements rather than following a strict roadmap.
