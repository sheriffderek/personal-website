#!/bin/bash
# Resume fit check - the guard between the resume system and the export
# kit. Exports every lane's resume and approved letter, proves each one is
# right, and only then publishes them to the kit. Wired to run
# automatically via the PostToolUse hook in .claude/settings.json whenever
# a file the resume pages are built from or load is edited; also runnable
# by hand (run it before sending anything). Requires MAMP serving
# derek.local:8888, pdfinfo/pdffonts/pdftotext/pdftoppm (poppler), exiftool,
# PHP, and Node 22+. Prints hook-JSON so the result surfaces in-session.
#
# TWO WAYS TO APPLY (Derek, 2026-09-27):
#   - The defaults: send the lane's resume + letter from the kit as-is.
#     That's what a plain run (and the hook) keeps current.
#   - A special one: add an entry to resume.json's targets map holding
#     the unique parts (its lane, plus any lane field to swap - the role
#     line, the intro), then run: bin/resume-fit-check.sh --target <slug>
#     That builds the target's resume + cover letter - linking to
#     derekthomaswood.com/?target=<slug> - through the same checks, into
#     resumes/<lane>/<slug>/. A bespoke letter body goes in letters.json's
#     targets map and needs "approved": true to export; with none, the
#     lane's letter goes. Once applied, drop an empty file named SENT in
#     that folder: the script never writes to it again, and a by-hand
#     re-export can see it too. Once a PDF is uploaded, it's the record.
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
# Rules 2 and 3 are about keeping the kit current, so a --target run skips
# them: running it IS the decision to build, and SENT is its freeze.
#
# RESUME_KIT overrides the export folder - for testing this script only,
# so a test run can never touch the real kit.

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
CHROME="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
SITE="http://derek.local:8888"
BASE="$SITE/resume"
OUT=$(mktemp -d)
FAILS=""
SUMMARY=""
ACCEPT=""
TARGET=""
[ "$1" = "--accept" ] && ACCEPT=1
[ "$1" = "--target" ] && TARGET="$2"

# A target slug becomes a folder name in the kit, so it must be a plain
# slug - and a bare --target must never fall through to the default run.
if [ "$1" = "--target" ] && ! echo "$TARGET" | grep -qE '^[a-z0-9-]+$'; then
	echo '{"systemMessage":"RESUME CHECK FAILED - nothing published: --target needs a slug (lowercase letters, digits, hyphens)"}'
	exit 1
fi

# The one export destination (Derek, 2026-09-08): per-lane pair folders
# in the job-search repo - each lane's resume and letter live together.
# (Replaced the Desktop-set + briefing dual scheme; dual destinations
# were a sync-bug class, and a Finder alias to this folder covers the
# Desktop habit.) A special application's pair goes in a folder named
# for its target, beside its lane's pair: resumes/<lane>/<slug>/ (the
# letter's source md and job record stay in job-search's targets/).
KIT="${RESUME_KIT:-$HOME/projects/job-search/resumes}"

# The resume system's own files - the ones whose change is a deliberate
# change to the sheet (CLAUDE.md, Resume pages). Everything else the page
# loads is "outside", and must never change what prints. This script is
# one of them: it names the files, so renaming them is a system change.
SYSTEM_FILES="content/resume.json content/letters.json styles/modules/resume.css
templates/pages/resume-lane.php templates/pages/cover-letter.php
templates/resume-text.php templates/cover-letter-text.php
includes/resume-header.php fonts/print bin/resume-fit-check.sh"

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

# One field of a target entry ($1 = resume.json or letters.json, $2 = the
# target, $3 = the field). Empty when the target or field isn't there.
target_field() {
	php -r '$data = json_decode(file_get_contents($argv[1]), true); $value = $data["targets"][$argv[2]][$argv[3]] ?? ""; echo is_bool($value) ? ($value ? "true" : "false") : (is_string($value) ? $value : "set");' "$ROOT/content/$1" "$2" "$3"
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

# Export filenames are name -> type -> qualifier (Derek, 2026-09-27):
# derek-wood-resume-design-engineer.pdf, derek-wood-cover-letter-getty.pdf.
# The recipient's view wins - a portal or inbox truncates the tail, and a
# recruiter looks for "Derek...". (This reversed 2026-09-13's role-first
# order, which was for picking his own files out in Finder; the lane
# folders separate them now.) A lane's qualifier quotes the sheet's own
# role line, trimmed to two words; a target's is its slug. Files sent
# before the change keep their old names - they're the record.
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
# $3 = the role line the text must contain.
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

# Plain-text twin of a letter, LETTERS ONLY (Derek, 2026-09-08): letter
# text gets pasted into portal textboxes; resumes are always uploaded as
# PDF, so a resume .txt would have no reader and doesn't export. (The
# site's /resume/<lane>/text route still exists for on-demand use.) A
# fetch that comes back empty or as an error page fails the run.
# $1 = the letter's route, $2 = output txt, $3 = optional ?target= query.
export_letter_text() {
	curl -sf "$BASE/$1/text${3:-}" > "$2" || { fail "$1 text twin didn't download"; return; }
	grep -q "Derek Wood" "$2" || fail "$1 text twin is empty or an error page"
}

# The build label - which commit of this site made the sheet. It counts
# only the resume-system files as "uncommitted": an unrelated working
# edit elsewhere in the repo doesn't make the sheet any less this build.
BUILD="$(cd "$ROOT" && git rev-parse --short HEAD)$(cd "$ROOT" && [ -n "$(git status --porcelain -- $SYSTEM_FILES)" ] && echo '+uncommitted')"

# Every published PDF carries the label inside it (the Subject field), so
# a file that has left the kit can still say which build it is. Hidden
# metadata on purpose: nothing on the sheet, nothing in the text layer,
# and the title stays clean. Read it back: exiftool -Subject <file>, or
# Preview > Tools > Show Inspector. A sheet that can't be labeled doesn't
# ship (rule 1).
label_all() {
	for pdf in "$OUT"/*.pdf; do
		exiftool -q -overwrite_original -Subject="derekthomaswood.com build $BUILD" "$pdf" >/dev/null 2>&1 || fail "could not label $(basename "$pdf") with its build (is exiftool installed?)"
	done
}

# Hook JSON can't carry raw quotes or backslashes.
clean() {
	echo "$1" | tr -d '"\\'
}

report_failure() {
	printf '{"systemMessage":"RESUME CHECK FAILED - nothing published:%s","hookSpecificOutput":{"hookEventName":"PostToolUse","additionalContext":"Resume check FAILED, nothing was published:%s Do not proceed as if the resume is fine - fix the cause (content that overflows is trimmed only by Derek; sheet spec changes need his sanction), or flag it."}}\n' "$(clean "$FAILS")" "$(clean "$FAILS")"
}

# ---- A special application: bin/resume-fit-check.sh --target <slug> ----

if [ -n "$TARGET" ]; then
	lane=$(target_field resume.json "$TARGET" lane)
	role=$(php -r '$data = json_decode(file_get_contents($argv[1]), true); $target = $data["targets"][$argv[2]] ?? []; echo $target["role"] ?? ($data["lanes"][$target["lane"] ?? ""]["role"] ?? "");' "$ROOT/content/resume.json" "$TARGET")
	folder="$KIT/$lane/$TARGET"
	query="?target=$TARGET"

	if [ -z "$lane" ]; then
		fail "no target '$TARGET' in resume.json's targets map (it needs at least a lane)"
	elif ! echo " $RESUME_LANES " | grep -q " $lane "; then
		fail "target $TARGET names lane '$lane', which isn't a resume lane"
	elif [ -f "$folder/SENT" ]; then
		fail "$TARGET is marked SENT - what's in $folder is the record of what went out, so nothing is rebuilt there"
	fi

	# The sheets link to the target's page, so it has to be there to land
	# on - a link to a target with no notes just shows the plain timeline.
	[ -f "$ROOT/content/targets/$TARGET/target.json" ] || fail "no target page to link to (content/targets/$TARGET/target.json)"
	[ "$(curl -s -o /dev/null -w '%{http_code}' "$SITE/$query")" = "200" ] || fail "the target page $SITE/$query doesn't answer 200"

	# A bespoke letter exports only once Derek approves it; with none, the
	# lane's approved letter goes (with the target's header and link).
	letter_lane=$(target_field letters.json "$TARGET" lane)
	if [ -z "$lane" ]; then
		: # no target to send a letter with - already failed above
	elif [ -n "$(target_field letters.json "$TARGET" body)" ] && [ "$(target_field letters.json "$TARGET" approved)" != "true" ]; then
		fail "the $TARGET letter in letters.json isn't approved yet (\"approved\": true)"
	elif [ -n "$letter_lane" ] && [ "$letter_lane" != "$lane" ]; then
		fail "the $TARGET letter is for lane $letter_lane, the resume target is for $lane"
	elif ! echo " $LETTER_LANES " | grep -q " $lane "; then
		fail "lane $lane has no approved letter to send"
	fi

	if [ -z "$FAILS" ]; then
		export_and_check "$lane$query" "$OUT/resume.pdf" "$role"
		export_and_check "$lane/cover-letter$query" "$OUT/letter.pdf" "$role"
		export_letter_text "$lane/cover-letter" "$OUT/letter.txt" "$query"

		# The whole point of the special build: the sheets link to the
		# target's page. Proven from the PDFs' link annotations.
		for pdf in "$OUT/resume.pdf" "$OUT/letter.pdf"; do
			[ -f "$pdf" ] && { pdfinfo -url "$pdf" 2>/dev/null | grep -qF "derekthomaswood.com/$query"; } || fail "$(basename "$pdf") doesn't link to derekthomaswood.com/$query"
		done

		THEME_RESULT=$(node "$ROOT/bin/resume-theme-check.mjs" "$lane$query" "$lane/cover-letter$query" 2>&1) || fail "theme check: $(echo "$THEME_RESULT" | grep FAIL | head -1)"
	fi

	[ -z "$FAILS" ] && label_all

	if [ -z "$FAILS" ]; then
		mkdir -p "$folder"
		cp "$OUT/resume.pdf" "$folder/derek-wood-resume-$TARGET.pdf"
		cp "$OUT/letter.pdf" "$folder/derek-wood-cover-letter-$TARGET.pdf"
		cp "$OUT/letter.txt" "$folder/derek-wood-cover-letter-$TARGET.txt"
	fi

	rm -rf "$OUT"

	if [ -n "$FAILS" ]; then
		report_failure
		exit 1
	fi

	printf '{"systemMessage":"Target %s built:%s - published to %s. After applying: touch %s/SENT"}\n' "$TARGET" "$(clean "$SUMMARY")" "$(clean "$folder")" "$(clean "$folder")"
	exit 0
fi

# ---- The defaults: every lane's resume and approved letter ----

for lane in $RESUME_LANES; do
	export_and_check "$lane" "$OUT/$lane.pdf" "$(lane_role "$lane")"
done

for lane in $LETTER_LANES; do
	export_and_check "$lane/cover-letter" "$OUT/letter-$lane.pdf" "$(lane_role "$lane")"
	export_letter_text "$lane/cover-letter" "$OUT/letter-$lane.txt"
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

# A kit PDF whose label can't be traced to a commit is out of date,
# whatever its pixels say - the label is part of what gets published. That
# covers no label at all, and a "+uncommitted" one once the system files
# are committed (so commit, rerun, and the kit's labels come out clean).
labeled() {
	local subject
	subject=$(exiftool -s3 -Subject "$1" 2>/dev/null)
	[ -n "$subject" ] || return 1
	case "$subject" in
		*+uncommitted) case "$BUILD" in *+uncommitted) return 0 ;; *) return 1 ;; esac ;;
	esac
}

CHANGED=""

if [ -z "$FAILS" ]; then
	for lane in $RESUME_LANES; do
		kit_pdf="$KIT/$lane/derek-wood-resume-$(role_slug "$lane").pdf"
		[ -f "$kit_pdf" ] && labeled "$kit_pdf" && [ "$(pixels "$OUT/$lane.pdf")" = "$(pixels "$kit_pdf")" ] || CHANGED="$CHANGED $lane"
	done

	for lane in $LETTER_LANES; do
		kit_letter="$KIT/$lane/derek-wood-cover-letter-$(role_slug "$lane")"
		[ -f "$kit_letter.pdf" ] && labeled "$kit_letter.pdf" && [ "$(pixels "$OUT/letter-$lane.pdf")" = "$(pixels "$kit_letter.pdf")" ] || CHANGED="$CHANGED letter-$lane"
		cmp -s "$OUT/letter-$lane.txt" "$kit_letter.txt" || CHANGED="$CHANGED letter-$lane-txt"
	done
fi

SYSTEM_HASH=$(cd "$ROOT" && find $SYSTEM_FILES -type f | sort | xargs cat | md5 -q | cut -c1-8)
KIT_SYSTEM_HASH=$(awk '/^system:/{print $2}' "$KIT/version.txt" 2>/dev/null)

if [ -z "$FAILS" ] && [ -n "$CHANGED" ] && [ "$SYSTEM_HASH" = "$KIT_SYSTEM_HASH" ] && [ -z "$ACCEPT" ]; then
	fail "the sheets changed ($CHANGED ) but no resume-system file did - something outside the system reached the PDF. Look at what changed; for a deliberate outside change, rerun bin/resume-fit-check.sh --accept"
fi

[ -z "$FAILS" ] && [ -n "$CHANGED" ] && label_all

if [ -z "$FAILS" ] && [ -n "$CHANGED" ]; then
	for lane in $RESUME_LANES; do
		mkdir -p "$KIT/$lane"
		cp "$OUT/$lane.pdf" "$KIT/$lane/derek-wood-resume-$(role_slug "$lane").pdf"
	done

	# Approved letters land beside their lane's resume - a lane's letter
	# joins via LETTER_LANES once its copy is approved.
	for lane in $LETTER_LANES; do
		cp "$OUT/letter-$lane.pdf" "$KIT/$lane/derek-wood-cover-letter-$(role_slug "$lane").pdf"
		cp "$OUT/letter-$lane.txt" "$KIT/$lane/derek-wood-cover-letter-$(role_slug "$lane").txt"
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
		echo "code:      $BUILD"
		echo ""
		echo "files (md5, first 8) - staleness is mechanically checkable:"
		(cd "$KIT" && find . -name '*derek-wood*' -type f | sort | while read -r f; do
			echo "  $(md5 -q "$f" | cut -c1-8)  ${f#./}"
		done)
	} > "$KIT/version.txt"
fi

rm -rf "$OUT"

if [ -n "$FAILS" ]; then
	report_failure
	exit 1
elif [ -n "$CHANGED" ]; then
	printf '{"systemMessage":"Resume check passed:%s - published to the kit:%s"}\n' "$(clean "$SUMMARY")" "$(clean "$CHANGED")"
else
	printf '{"systemMessage":"Resume check passed:%s - kit already current, nothing published"}\n' "$(clean "$SUMMARY")"
fi
