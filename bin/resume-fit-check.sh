#!/bin/bash
# Resume fit check - the one-page guarantee. Every content change must keep
# each lane's PDF at exactly ONE page (Chrome never auto-shrinks; overflow
# becomes page 2 silently). Wired to run automatically via the PostToolUse
# hook in .claude/settings.json whenever content/resume.json is edited; also
# runnable by hand. Requires MAMP serving derek.local:8888 and pdfinfo
# (poppler). Prints hook-JSON so the result surfaces in-session.

CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
BASE="http://derek.local:8888/resume"
OUT=$(mktemp -d)
FAIL=""
SUMMARY=""

for lane in product-designer design-engineer advocate; do
	"$CHROME" --headless --print-to-pdf="$OUT/$lane.pdf" --no-pdf-header-footer "$BASE/$lane" >/dev/null 2>&1
	pages=$(pdfinfo "$OUT/$lane.pdf" 2>/dev/null | awk '/^Pages/{print $2}')
	SUMMARY="$SUMMARY $lane=${pages:-ERR}p"
	[ "$pages" = "1" ] || FAIL=1
done

# Cover letters ride the same check, but only APPROVED lanes - a lane
# joins this list when Derek approves its letter copy in letters.json
# (placeholder letters render at their routes but never export).
# Export names use the short lane words: product / engineer / advocate.
# All three approved (v3 final, 2026-09-08).
LETTER_LANES="product-designer design-engineer advocate"

# The one export destination (Derek, 2026-09-08): per-lane pair folders
# in the job-search repo - each lane's resume and letter live together.
# (Replaced the Desktop-set + briefing dual scheme; dual destinations
# were a sync-bug class, and a Finder alias to this folder covers the
# Desktop habit.) Bespoke target letters are manual one-off exports
# into a company folder beside the lane's pair -
# resumes/<lane>/<company>/derek-wood-cover-letter-<company>.pdf
# (the letter's source md and job record stay in targets/<company>/).
KIT="$HOME/projects/job-search/resumes"

letter_short() {
	case "$1" in
		product-designer) echo "product" ;;
		design-engineer) echo "engineer" ;;
		advocate) echo "advocate" ;;
	esac
}

for lane in $LETTER_LANES; do
	"$CHROME" --headless --print-to-pdf="$OUT/letter-$lane.pdf" --no-pdf-header-footer "$BASE/$lane/cover-letter" >/dev/null 2>&1
	pages=$(pdfinfo "$OUT/letter-$lane.pdf" 2>/dev/null | awk '/^Pages/{print $2}')
	SUMMARY="$SUMMARY letter-$(letter_short "$lane")=${pages:-ERR}p"
	[ "$pages" = "1" ] || FAIL=1
done

# Plain-text twins, LETTERS ONLY (Derek, 2026-09-08): letter text gets
# pasted into portal textboxes; resumes are always uploaded as PDF, so
# a resume .txt would have no reader and doesn't export. (The site's
# /resume/<lane>/text route still exists for on-demand use.) A fetch
# that comes back empty or as an error page fails the run.
for lane in $LETTER_LANES; do
	curl -sf "$BASE/$lane/cover-letter/text" > "$OUT/letter-$lane.txt"
	grep -q "Derek Wood" "$OUT/letter-$lane.txt" || { FAIL=1; SUMMARY="$SUMMARY letter-$(letter_short "$lane")-txt=ERR"; }
done

# On a green check, the verified PDFs ARE the deliverables - publish them
# to the export folder instead of throwing them away (checking and
# exporting are one gesture). A failing check publishes nothing, so the
# last good exports survive.
if [ -z "$FAIL" ]; then
	for lane in product-designer design-engineer advocate; do
		mkdir -p "$KIT/$lane"
		cp "$OUT/$lane.pdf" "$KIT/$lane/derek-wood-resume-$(letter_short "$lane").pdf"
	done

	# Approved letters land beside their lane's resume - a lane's letter
	# joins via LETTER_LANES once its copy is approved.
	for lane in $LETTER_LANES; do
		cp "$OUT/letter-$lane.pdf" "$KIT/$lane/derek-wood-letter-$(letter_short "$lane").pdf"
		cp "$OUT/letter-$lane.txt" "$KIT/$lane/derek-wood-letter-$(letter_short "$lane").txt"
	done

	# The version stamp - answers "is this kit current?" at a glance
	# without putting a build hash on the sheet itself (the text layer
	# stays clean by contract). Content hashes = resume.json +
	# letters.json (same hash, same words); the per-file list makes
	# staleness of any single artifact mechanically checkable.
	{
		echo "exported:  $(date '+%Y-%m-%d %H:%M:%S')"
		echo "resume:    $(md5 -q "$(dirname "$0")/../content/resume.json" | cut -c1-8)"
		echo "letters:   $(md5 -q "$(dirname "$0")/../content/letters.json" | cut -c1-8)"
		echo "code:      $(cd "$(dirname "$0")/.." && git rev-parse --short HEAD)$(cd "$(dirname "$0")/.." && [ -n "$(git status --porcelain)" ] && echo '+uncommitted')"
		echo ""
		echo "files (md5, first 8) - staleness is mechanically checkable:"
		(cd "$KIT" && find . -name 'derek-wood-*' -type f | sort | while read -r f; do
			echo "  $(md5 -q "$f" | cut -c1-8)  ${f#./}"
		done)
	} > "$KIT/version.txt"
fi

rm -rf "$OUT"

if [ -n "$FAIL" ]; then
	printf '{"systemMessage":"RESUME FIT CHECK FAILED:%s (every lane must be 1 page)","hookSpecificOutput":{"hookEventName":"PostToolUse","additionalContext":"Resume fit check FAILED:%s. A lane PDF overflows one page. Do not proceed as if it fits - trim content (Derek decides), tighten the sheet spec (Derek sanctions), or flag it."}}\n' "$SUMMARY" "$SUMMARY"
else
	printf '{"systemMessage":"Resume fit check:%s - kit refreshed in job-search/resumes"}\n' "$SUMMARY"
fi
