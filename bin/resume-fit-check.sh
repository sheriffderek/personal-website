#!/bin/bash
# Resume fit check - the guard between the resume system and the export
# kit. Exports every lane's resume and approved letter, proves each one is
# right, and only then publishes them to the kit. Wired to run
# automatically via the PostToolUse hook in .claude/settings.json whenever
# a file the resume pages are built from or load is edited; also runnable
# by hand (run it before sending anything). Requires MAMP serving
# derek.local:8888, pdfinfo/pdffonts/pdftotext/pdftoppm (poppler), PHP, and
# Node 22+. Prints hook-JSON so the result surfaces in-session.
#
# THE RULES (Derek, 2026-09-24: "it should be cut and dry"):
#   1. Any failed check publishes NOTHING - the last good kit survives.
#      Every export must: answer 200 (never an error page printed as a
#      resume), be exactly ONE page (Chrome never auto-shrinks; overflow
#      becomes page 2 silently), embed zero Type 3 fonts (Type 3 = broken
#      copy/paste and ATS extraction), carry a clean tag tree (the text
#      screen readers read - notes/pdf-text-layer-forensics.md rule 7), and
#      contain "Derek Wood" and its lane's role line. The sheet must also be
#      theme-proof (bin/resume-theme-check.mjs).
#   2. Fresh exports identical to the kit publish nothing - no churn.
#   3. Exports that DIFFER from the kit publish only when a resume-system
#      file changed since the kit was made (SYSTEM_FILES below, hashed into
#      version.txt). If the sheets changed but no system file did, something
#      outside the system reached the PDF - that fails, and nothing ships.
#      For a legitimate outside change (say a Chrome update re-rasterizes
#      the fonts), look at the new sheets and rerun with --accept.
#
# RESUME_KIT overrides the export folder - for testing this script only,
# so a test run can never touch the real kit.

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
BASE="http://derek.local:8888/resume"
OUT=$(mktemp -d)
FAILS=""
SUMMARY=""
ACCEPT=""
[ "$1" = "--accept" ] && ACCEPT=1

# The one export destination (Derek, 2026-09-08): per-lane pair folders
# in the job-search repo - each lane's resume and letter live together.
# (Replaced the Desktop-set + briefing dual scheme; dual destinations
# were a sync-bug class, and a Finder alias to this folder covers the
# Desktop habit.) Bespoke target letters are manual one-off exports
# into a company folder beside the lane's pair -
# resumes/<lane>/<company>/derek-wood-cover-letter-<company>.pdf
# (the letter's source md and job record stay in targets/<company>/).
KIT="${RESUME_KIT:-$HOME/projects/job-search/resumes}"

# The resume system's own files - the ones whose change is a deliberate
# change to the sheet (CLAUDE.md, Resume pages). Everything else the page
# loads is "outside", and must never change what prints.
SYSTEM_FILES="content/resume.json content/letters.json styles/modules/resume.css
templates/pages/resume-lane.php templates/pages/cover-letter.php
templates/resume-text.php templates/cover-letter-text.php
includes/resume-header.php fonts/print"

fail() {
	FAILS="$FAILS $1;"
}

# Lanes come from the data, never a list typed here - a renamed or added
# lane can't slip past the check by being missing from it.
lanes_in() {
	php -r '$data = json_decode(file_get_contents($argv[1]), true); echo implode(" ", array_keys($data["lanes"] ?? []));' "$ROOT/content/$1"
}

# The role line the sheet's header prints (includes/resume-header.php) -
# the one lane-specific line both the resume and its letter carry.
lane_role() {
	php -r '$data = json_decode(file_get_contents($argv[1]), true); echo $data["lanes"][$argv[2]]["role"] ?? "";' "$ROOT/content/resume.json" "$1"
}

RESUME_LANES=$(lanes_in resume.json)

if [ "$(echo $RESUME_LANES | tr ' ' '\n' | sort)" != "$(echo $(lanes_in letters.json) | tr ' ' '\n' | sort)" ]; then
	fail "resume.json and letters.json list different lanes"
fi

# Cover letters export only for APPROVED lanes - a lane joins this list
# when Derek approves its letter copy in letters.json (placeholder
# letters render at their routes but never export). All three approved
# (v3 final, 2026-09-08).
LETTER_LANES="product-designer design-engineer advocate"

# Export names use the short lane words: product / engineer / advocate.
letter_short() {
	case "$1" in
		product-designer) echo "product" ;;
		design-engineer) echo "engineer" ;;
		advocate) echo "advocate" ;;
	esac
}

# Export filenames are role -> type -> name (Derek, 2026-09-13): list views
# truncate the tail, so the role leads and "derek-wood" closes. The role slug
# quotes the sheet's own role line, trimmed to two words. Bespoke target
# letters follow the same shape with the company in the role slot
# (legalzoom-letter-derek-wood.pdf).
role_slug() {
	case "$1" in
		product-designer) echo "product-designer" ;;
		design-engineer) echo "design-engineer" ;;
		advocate) echo "designer-advocate" ;;
	esac
}

for lane in $RESUME_LANES; do
	[ -n "$(role_slug "$lane")" ] || fail "lane $lane has no export name in this script (role_slug)"
done

for lane in $LETTER_LANES; do
	echo " $RESUME_LANES " | grep -q " $lane " || fail "approved letter lane $lane is not a resume lane"
done

# Export one route and prove it's right. $1 = route, $2 = output pdf,
# $3 = the lane role line the text must contain.
export_and_check() {
	local route="$1" pdf="$2" role="$3" status pages
	status=$(curl -s -o /dev/null -w '%{http_code}' "$BASE/$route")

	if [ "$status" != "200" ]; then
		fail "$route answered $status"
		return
	fi

	"$CHROME" --headless --print-to-pdf="$pdf" --no-pdf-header-footer "$BASE/$route" >/dev/null 2>&1
	pages=$(pdfinfo "$pdf" 2>/dev/null | awk '/^Pages/{print $2}')
	SUMMARY="$SUMMARY $route=${pages:-ERR}p"
	[ "$pages" = "1" ] || fail "$route is ${pages:-no} pages, not 1"
	[ "$(pdffonts "$pdf" 2>/dev/null | grep -c 'Type 3')" = "0" ] || fail "$route embeds Type 3 fonts"
	[ "$(pdfinfo -struct-text "$pdf" 2>&1 | grep -c 'Syntax Error')" = "0" ] || fail "$route has tag-tree errors"
	pdftotext "$pdf" - 2>/dev/null | grep -q "Derek Wood" || fail "$route text is missing 'Derek Wood'"
	[ -n "$role" ] || fail "$route has no role line in resume.json"
	pdftotext "$pdf" - 2>/dev/null | grep -qF "$role" || fail "$route text is missing its role line '$role'"
}

for lane in $RESUME_LANES; do
	export_and_check "$lane" "$OUT/$lane.pdf" "$(lane_role "$lane")"
done

for lane in $LETTER_LANES; do
	export_and_check "$lane/cover-letter" "$OUT/letter-$lane.pdf" "$(lane_role "$lane")"
done

# Plain-text twins, LETTERS ONLY (Derek, 2026-09-08): letter text gets
# pasted into portal textboxes; resumes are always uploaded as PDF, so
# a resume .txt would have no reader and doesn't export. (The site's
# /resume/<lane>/text route still exists for on-demand use.) A fetch
# that comes back empty or as an error page fails the run.
for lane in $LETTER_LANES; do
	curl -sf "$BASE/$lane/cover-letter/text" > "$OUT/letter-$lane.txt"
	grep -q "Derek Wood" "$OUT/letter-$lane.txt" || fail "letter-$(letter_short "$lane") text twin is empty or an error page"
done

# Theme-proof: one resume and one letter under every theme a visitor can
# set. (The pin is shared CSS, so two sheets prove it for all six.)
THEME_RESULT=$(node "$ROOT/bin/resume-theme-check.mjs" product-designer product-designer/cover-letter 2>&1) || fail "theme check: $(echo "$THEME_RESULT" | grep FAIL | head -1)"

# Rule 2 + 3: compare with the kit. PDFs compare by their rendered pixels
# (the bytes always differ - Chrome stamps a creation date); text twins
# compare byte for byte.
pixels() {
	pdftoppm -r 100 -singlefile "$1" "$OUT/raster" 2>/dev/null && md5 -q "$OUT/raster.ppm"
}

CHANGED=""

if [ -z "$FAILS" ]; then
	for lane in $RESUME_LANES; do
		kit_pdf="$KIT/$lane/$(role_slug "$lane")-resume-derek-wood.pdf"
		[ -f "$kit_pdf" ] && [ "$(pixels "$OUT/$lane.pdf")" = "$(pixels "$kit_pdf")" ] || CHANGED="$CHANGED $lane"
	done

	for lane in $LETTER_LANES; do
		kit_letter="$KIT/$lane/$(role_slug "$lane")-letter-derek-wood"
		[ -f "$kit_letter.pdf" ] && [ "$(pixels "$OUT/letter-$lane.pdf")" = "$(pixels "$kit_letter.pdf")" ] || CHANGED="$CHANGED letter-$(letter_short "$lane")"
		cmp -s "$OUT/letter-$lane.txt" "$kit_letter.txt" || CHANGED="$CHANGED letter-$(letter_short "$lane")-txt"
	done
fi

SYSTEM_HASH=$(cd "$ROOT" && find $SYSTEM_FILES -type f | sort | xargs cat | md5 -q | cut -c1-8)
KIT_SYSTEM_HASH=$(awk '/^system:/{print $2}' "$KIT/version.txt" 2>/dev/null)

if [ -z "$FAILS" ] && [ -n "$CHANGED" ] && [ "$SYSTEM_HASH" = "$KIT_SYSTEM_HASH" ] && [ -z "$ACCEPT" ]; then
	fail "the sheets changed ($CHANGED ) but no resume-system file did - something outside the system reached the PDF. Look at what changed; for a deliberate outside change, rerun bin/resume-fit-check.sh --accept"
fi

if [ -z "$FAILS" ] && [ -n "$CHANGED" ]; then
	for lane in $RESUME_LANES; do
		mkdir -p "$KIT/$lane"
		cp "$OUT/$lane.pdf" "$KIT/$lane/$(role_slug "$lane")-resume-derek-wood.pdf"
	done

	# Approved letters land beside their lane's resume - a lane's letter
	# joins via LETTER_LANES once its copy is approved.
	for lane in $LETTER_LANES; do
		cp "$OUT/letter-$lane.pdf" "$KIT/$lane/$(role_slug "$lane")-letter-derek-wood.pdf"
		cp "$OUT/letter-$lane.txt" "$KIT/$lane/$(role_slug "$lane")-letter-derek-wood.txt"
	done

	# The version stamp - answers "is this kit current?" at a glance
	# without putting a build hash on the sheet itself (the text layer
	# stays clean by contract). Content hashes = resume.json +
	# letters.json (same hash, same words); system = every resume-system
	# file (rule 3 reads it); the per-file list makes staleness of any
	# single artifact mechanically checkable.
	{
		echo "exported:  $(date '+%Y-%m-%d %H:%M:%S')"
		echo "resume:    $(md5 -q "$ROOT/content/resume.json" | cut -c1-8)"
		echo "letters:   $(md5 -q "$ROOT/content/letters.json" | cut -c1-8)"
		echo "system:    $SYSTEM_HASH"
		echo "code:      $(cd "$ROOT" && git rev-parse --short HEAD)$(cd "$ROOT" && [ -n "$(git status --porcelain)" ] && echo '+uncommitted')"
		echo ""
		echo "files (md5, first 8) - staleness is mechanically checkable:"
		(cd "$KIT" && find . -name '*derek-wood*' -type f | sort | while read -r f; do
			echo "  $(md5 -q "$f" | cut -c1-8)  ${f#./}"
		done)
	} > "$KIT/version.txt"
fi

rm -rf "$OUT"

# Hook JSON can't carry raw quotes or backslashes.
clean() {
	echo "$1" | tr -d '"\\'
}

if [ -n "$FAILS" ]; then
	printf '{"systemMessage":"RESUME CHECK FAILED - nothing published:%s","hookSpecificOutput":{"hookEventName":"PostToolUse","additionalContext":"Resume check FAILED, nothing was published:%s Do not proceed as if the resume is fine - fix the cause (content that overflows is trimmed only by Derek; sheet spec changes need his sanction), or flag it."}}\n' "$(clean "$FAILS")" "$(clean "$FAILS")"
	exit 1
elif [ -n "$CHANGED" ]; then
	printf '{"systemMessage":"Resume check passed:%s - published to the kit:%s"}\n' "$(clean "$SUMMARY")" "$(clean "$CHANGED")"
else
	printf '{"systemMessage":"Resume check passed:%s - kit already current, nothing published"}\n' "$(clean "$SUMMARY")"
fi
