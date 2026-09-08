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

# On a green check, the verified PDFs ARE the deliverables - publish them
# to the export folder instead of throwing them away (checking and
# exporting are one gesture). A failing check publishes nothing, so the
# last good exports survive.
if [ -z "$FAIL" ]; then
	mkdir -p "$HOME/Desktop/resume-exports"
	for lane in product-designer design-engineer advocate; do
		cp "$OUT/$lane.pdf" "$HOME/Desktop/resume-exports/derek-wood-$lane.pdf"
	done

	# The version stamp - answers "are these PDFs current?" at a glance
	# without putting a build hash on the sheet itself (the text layer
	# stays clean by contract). Content hash = resume.json, so the same
	# hash means the same words.
	{
		echo "exported:  $(date '+%Y-%m-%d %H:%M:%S')"
		echo "content:   $(md5 -q "$(dirname "$0")/../content/resume.json" | cut -c1-8)"
		echo "code:      $(cd "$(dirname "$0")/.." && git rev-parse --short HEAD)$(cd "$(dirname "$0")/.." && [ -n "$(git status --porcelain)" ] && echo '+uncommitted')"
	} > "$HOME/Desktop/resume-exports/version.txt"
fi

rm -rf "$OUT"

if [ -n "$FAIL" ]; then
	printf '{"systemMessage":"RESUME FIT CHECK FAILED:%s (every lane must be 1 page)","hookSpecificOutput":{"hookEventName":"PostToolUse","additionalContext":"Resume fit check FAILED:%s. A lane PDF overflows one page. Do not proceed as if it fits - trim content (Derek decides), tighten the sheet spec (Derek sanctions), or flag it."}}\n' "$SUMMARY" "$SUMMARY"
else
	printf '{"systemMessage":"Resume fit check:%s - exports refreshed on Desktop"}\n' "$SUMMARY"
fi
