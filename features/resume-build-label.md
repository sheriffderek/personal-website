# Resume build label

Every resume and letter PDF the export script publishes carries a hidden label. The label says which build of this site made it.

## Goal

A PDF leaves the kit. It gets uploaded, emailed, saved to a Desktop. Later we need to know which version it is. The label answers that from the file alone.

## How it works

1. The export script makes the PDFs and checks them.
2. If they pass, and something is new, it writes the label into each one.
3. Then it publishes them to the kit.

The label is the site's git commit. If a resume file had unsaved changes at export time, the label says so.

## How to read a label

- In Preview: Tools > Show Inspector. Look at Subject.
- In a terminal: `exiftool -Subject <file>`

To see what that build was: `git show <the hash>` in this repo.

## Choices, and why

- **Hidden, not printed.** Nothing on the sheet. Nothing in the text a parser or screen reader gets. Not in the filename, because recruiters and portals see filenames.
- **Not in the title.** The title shows in the reader's tab bar. A build number there looks like a draft.
- **The git commit, not a version number.** A number has to be bumped by hand, and that gets forgotten. The commit is always right.
- **Written after export.** Chrome only copies the page title into a PDF. It ignores every other meta tag (tested 2026-09-19). So a tool writes the label after Chrome is done.
- **Writing it changes nothing else.** Same pixels, same text, same tags (tested 2026-09-27).
- **A PDF that can't be labeled doesn't ship.** Same as any other failed check.
- **A kit PDF with no label counts as out of date.** That is how the kit got its first labels.
- **An old label is not out of date.** If the sheet has not changed, its label still names the build that made it. The kit is not republished just because the site got a new commit.

## What it does not cover

Company folders in the kit (`resumes/<lane>/<company>/`) are exported by hand. The script does not label them and does not touch them.

## Where the truth lives

- The code, and the rules it follows: `bin/resume-fit-check.sh`
- The kit's own record of its last export: `version.txt` in the kit folder. Its `code:` line is the same value as the label.
- Needs `exiftool` installed.
