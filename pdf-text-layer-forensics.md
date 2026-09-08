# PDF text-layer forensics (2026-09-06)

What a resume PDF's text layer actually looks like to parsers, viewers, and ATS systems. Collected the day we replaced the Figma-exported resume with the browser-printed one (derekthomaswood.com `/resume/*` routes). Every specimen below is a real copy/paste or extraction from that day's files.

The one-line thesis: **the page you see and the text a machine reads are two different documents living in one file.** Design tools can make the first one beautiful while silently shipping garbage as the second.

---

## Specimen 1: Figma PDF export, select-all in a viewer

Two failures at once. The columns interleave (Figma writes text in layer order, not reading order), and letter-spaced text breaks into fragments because the tracking is encoded as glyph offsets, not spaces.

```
Background in fine art and a genuine love for cultural institutions. N avigates
ambiguity, tests ideas early, and works closely with engineers and
stakeholders to bring designs to life. BFA from California College of the Arts. L i s t a t E a s e ( 2 0 2 5 ) - C o n t r a c t
Experience
P E R P E T U A L E D U C A T I O N ( 2 0 1 9 - 2 0 2 5 )
...
A ccessi bi
l it y C onsul tant
```

The right-column job labels ("List at Ease") land mid-sentence inside left-column paragraphs. "Accessibility Consultant" is unsearchable. Reordering Figma layers did not fix it.

## Specimen 2: Figma PDF export, raw stream extraction

Same file through `pdftotext -raw`. Figma does not encode real space characters between words in tracked runs, so the words fuse:

```
Productdesignerwithover15yearsacross UX,visualdesign,andfull-stack
webdev.Deepexperienceindesignsystems,accessibility,andmaking
complexinformationapproachablethroughthoughtfulinterfaces.
```

Every keyword a recruiter would search for ("product designer", "design systems") is gone.

## Specimen 3: browser-printed PDF with VARIABLE webfonts, copied from Preview

The first web version fixed the ordering but still shattered in Preview. Cause: Chrome embeds variable fonts as Type 3 glyph programs with custom encoding (`pdffonts` showed QuicksandVariable, GeneralSansVariable, SplineSans all as `Type 3 / Custom`). Copy walks the glyph runs literally:

```
I te
a
ch pro
duct design and front-end development, and I've d
one both for
re
al for 15 ye
ars.
...
derekthomaswoo
d.c
om
```

Kerning was NOT the culprit (tested: disabling font-kerning changed nothing). The font FORMAT was. Static cuts of the same typefaces embed as CID TrueType and extract cleanly.

## Specimen 4: the fixed pipeline, same copy gesture

Browser-printed from the live route, static print fonts, sections kept in normal paint flow. Preview select-all now yields:

```
Derek Wood
Design Advocate & Educator
Los Angeles, CA + Remote (213) 400 6366 · derekthomaswood.com
derekthomaswood@gmail.com | linkedin.com/in/derekthomaswood
I teach product design and front-end development, and I've done both for
real for 15 years. I founded and ran Perpetual Education, a full-stack design
school: 200+ learning modules, ...
```

Whole words, reading order, every role an unbroken company > title > body block, hidden "Experience" headings for parser segmentation, all on the same designed two-column sheet.

---

## What we learned (the transferable rules)

1. **Text order = DOM order** in a browser-printed PDF. CSS Grid lets visual placement and machine reading order be decided independently. Figma cannot separate them.
2. **Variable fonts break print PDFs.** Chrome converts them to Type 3; serve static instances for print. Check with `pdffonts` (zero "Type 3" = pass).
3. **`position: absolute` reorders the text stream.** Positioned boxes paint after normal flow. An absolutely-positioned section extracts at the END of the document. Fix: keep sections in the positioned paint phase together (`position: relative` on all).
4. **Print media queries see ~741px** regardless of `@page` margins, so a site's responsive breakpoints serve the wrong layout to print. Print layout must be declared in `@media print` at fixed letter geometry (816px paper, content box = 816 minus margins).
5. **Chrome does not shrink to fit.** Overflow becomes page 2, so `pdfinfo | grep Pages` is a binary fit test.
6. **Different readers, different failures.** `pdftotext -raw` (stream order), `pdftotext` (geometric), and PDFKit/Preview (Apple's clustering) can all disagree. The PDFKit check is the one that matches what a human's copy/paste does.

## Verification loop (the whole preflight in four commands)

```
"/Applications/Google Chrome.app/Contents/MacOS/Google Chrome" --headless --print-to-pdf=out.pdf --no-pdf-header-footer http://derek.local:8888/resume/<lane>
pdfinfo out.pdf | grep Pages        # must equal the page budget
pdffonts out.pdf                    # zero Type 3 fonts
pdftotext out.pdf -                 # reads as prose, in order
```

PDFKit order check (Preview's engine; offsets must be strictly increasing):

```
osascript -l JavaScript -e "ObjC.import('Quartz'); const t = \$.PDFDocument.alloc.initWithURL(\$.NSURL.fileURLWithPath('out.pdf')).string.js; ['PERPETUAL','LIST AT EASE','BETTERLIFE','EQUIVALENT','PXL','NIAGEN','EARLY CAREER','Speaking'].map(m => m+': '+t.indexOf(m)).join('\n');"
```

Where the truth lives: the print CSS is in `styles/modules/resume.css` (`@media print` block, with the measured facts in its comment). Content is `content/resume.json`. The original broken Figma export survives as `~/Desktop/example.pdf`.

## ATS research summary (2026-09-06, agent deep-dive)

Primary-source findings, kept because they contradict the folklore: parsing engines are consolidated (Textkernel powers iCIMS/SmartRecruiters/Oracle; Ashby uses LLM review; Workday/Greenhouse/Lever engines undisclosed). Modern engines handle clean two-column at ~90%; Greenhouse still lists columns as a parse-failure cause. No ATS auto-rejects on formatting; recruiters at Greenhouse/Lever/Ashby read the actual PDF; Workday-class flows let applicants correct parsed fields before submitting. Hidden structural text has no documented penalty, but prompt-injection detection (hidden instructions rose 7x in 16 months, per a Duke study) is making detectors flag any invisible text; our hidden headings are three benign words matching visible content. "PDFs fail ATS" and "ATS scores" trace to resume-service marketing, not evidence.
