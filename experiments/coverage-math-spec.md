# Trio coverage math - the coats model (agent spec, 2026-08-09)

Diagnosis: every observed failure (visible paint reading 0%, stacked trios
failing the bar, shrinking qualifying cores) came from judging paint in
DARKNESS space (composited opacity + hard threshold). The fix: judge in
COATS space - count layers of paint, compare to the layers the bar demands,
give proportional credit. The pixels keep compositing (that IS what
translucent paint looks like); the metric reads the same physics
logarithmically, so picture and number can never contradict.

## The model

- density(baseR) = clamp(DENSITY_DEEP * (DEEP_R/baseR)^WIDTH_COST, FLOOR, DEEP)
  - DENSITY_DEEP 0.70 (not 1.0: "even the best person lets some light
    through" - and opacity 1.0 would be infinite layers in log space)
  - DENSITY_FLOOR 0.15 (old 0.30 floor existed to survive the hard
    threshold; soft credit does that honestly now)
  - WIDTH_COST 1.5 (area-honest 2.0 makes generalists 5x thinner than
    anyone believes; 1.0 makes width free; 1.5 calibrates a mid to be
    EXACTLY 1/3 of a specialist in coats - the round-number story)
- layers per person = -ln(1 - density) / LAYER_UNIT, LAYER_UNIT = -ln(1 - 0.70)
  - deep = 1.000 coats, mid = 0.332, wide = 0.185
  - log space makes stacking ADDITIVE: every identical hire adds equally
    (the alpha-compositing independence story survives, read fairly)
- THE METRIC USES NO FALLOFF - uniform inside the radius. The feathered
  edge stays visual-only; charging it in the metric taxed width twice
  (thinness AND a confiscated rim) - that was the shrinking-core bug.
- bar: requiredLayers = lerp(0.15, 0.95, t)
  - left third = one honest coat certifies - betting on people
  - middle = pairs - wanting a second opinion
  - right third = three solid coats - buying certainty
- credit per point = clamp(layers / required, 0, 1); coverage % = average
  credit. No threshold, no cliffs, monotonic by construction.

## People needed to fully certify a patch of ground

| bar | N req | deep | mid | wide |
|---|---|---|---|---|
| 0 | 0.15 | 1 | 1 | 1 |
| 1/3 | 0.41 | 1 | 2 | 3 |
| 1/2 | 0.55 | 1 | 2 | 3 |
| 2/3 | 0.69 | 1 | 3 | 4 |
| max | 0.95 | 1 | 3 | 6 |

## Acceptance scenarios (computed, person on Design center)

- S1 one wide, bar 0 -> Design 100%
- S2 one wide, bar max -> ~19% (was 0%)
- S3 one deep, bar max -> ~26% (their core fully certified)
- S4 three mids stacked, bar max -> ~71% (was 0%)
- S5 two mids stacked, bar max -> ~50% (visible third-hire reward)
- S6 one mid, bar middle -> ~43%
- S7 monotonic: adding a person never lowers a number (sum of
  non-negatives); raising the bar never raises one.

## Open questions (Derek's calls)

1. Three WIDE generalists at max bar top out ~67%, never certified - kept
   as the lesson ("the far right is bought with solid people"). Making 3
   wides clear would need WIDTH_COST ~1.05, erasing wider-is-thinner.
2. Readout wording: "covered at this bar" implied pass/fail; the number
   now means "average share of the demanded coats laid down."
   Implemented as "meets the bar: X%".
3. S4's missing 29% is honestly bare paper (a mid's r215 disk < the r255
   circle). If "3 mids = whole circle" should read >=80%, widen the shape
   range (WIDE_R ~340) and recalibrate.
4. The veil still washes in darkness space - roughly agrees with the new
   metric. Re-derive it from coats later, or keep the impressionistic wash?
5. Optional: two-tone rendering (certified vs partial ground) now that
   credit is continuous. Only if the blended number ever confuses.
