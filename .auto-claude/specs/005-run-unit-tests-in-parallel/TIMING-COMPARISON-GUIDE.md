# CI Timing Comparison Guide

## Overview

This guide provides step-by-step instructions for completing subtask-3-3: comparing CI execution time before and after implementing parallel tests.

## Status

✅ **Baseline data collected** - Real timing data from main branch CI run
❌ **PR not created yet** - Blocking new timing data collection
❌ **Comparison pending** - Waiting for PR CI run to complete

## Files Created

1. **baseline-ci-timing.json** - Actual timing data from main branch (Run ID: 20985925148)
2. **compare-ci-timing.py** - Python script to automate comparison and report generation
3. **TIMING-COMPARISON-GUIDE.md** - This guide

## Baseline Data Summary

**Source:** https://github.com/pelican-dev/panel/actions/runs/20985925148
**Date:** 2026-01-14
**Branch:** main (before parallel changes)

| Database | Average Duration | Jobs |
|----------|-----------------|------|
| SQLite | 2.3 min (139s) | 4 |
| MariaDB | 2.8 min (171s) | 12 |
| PostgreSQL | 3.1 min (184s) | 4 |
| MySQL | 3.4 min (205s) | 4 |
| **Overall** | **2.9 min (173s)** | **24** |

**Performance Target:** 30-50% reduction in test execution time

## Prerequisites

Before you can complete the timing comparison, you need:

1. ✅ Feature branch pushed to GitHub (DONE)
   - Branch: `auto-claude/005-run-unit-tests-in-parallel`
   - Commits: 3 (all test changes applied)

2. ❌ Pull request created (TODO)
   - URL: https://github.com/pelican-dev/panel/compare/main...auto-claude/005-run-unit-tests-in-parallel
   - This triggers the CI workflow

3. ❌ CI workflow completed on PR (TODO)
   - All 24 jobs must complete
   - Get the run ID from the GitHub Actions page

## Step-by-Step Instructions

### Step 1: Create Pull Request

```bash
# Option A: Via GitHub web UI
# Go to: https://github.com/pelican-dev/panel/compare/main...auto-claude/005-run-unit-tests-in-parallel
# Click "Create Pull Request"

# Option B: Via gh CLI (if available)
gh pr create \
  --title "feat: enable parallel test execution in CI" \
  --body "Adds --parallel flag to all Pest test commands in CI workflow for faster test execution"
```

### Step 2: Wait for CI to Complete

1. Go to https://github.com/pelican-dev/panel/actions
2. Find the "Tests" workflow run for your PR
3. Wait for all 24 jobs to complete
4. Note the Run ID from the URL: `/actions/runs/<RUN_ID>`

### Step 3: Run Comparison Script

```bash
# Navigate to spec directory
cd .auto-claude/specs/005-run-unit-tests-in-parallel/

# Run the comparison script with the new run ID
python3 compare-ci-timing.py <NEW_RUN_ID>

# Example:
python3 compare-ci-timing.py 21234567890
```

### Step 4: Review Results

The script will output:

1. **Detailed comparison by database type**
   - Baseline vs new timing
   - Improvement percentage
   - Pass/fail indicators

2. **Overall improvement summary**
   - Average across all jobs
   - Target validation

3. **PR description text**
   - Formatted table ready to paste into PR description
   - Results summary with pass/fail indicator

### Step 5: Document in PR Description

Copy the generated "PR DESCRIPTION TEXT" from the script output and paste it into your PR description. Add any additional observations:

```markdown
## Performance Results

[Generated table goes here]

## Analysis

- ✓ All tests passed in parallel mode
- ✓ No database locking issues observed
- ✓ No resource exhaustion errors
- ✓ Parallel execution verified in job logs

## Test Reliability

- All 24 job combinations passed successfully
- Tested across PHP 8.2, 8.3, 8.4, 8.5
- Tested across SQLite, MySQL, MariaDB (3 versions), PostgreSQL
```

### Step 6: Update Implementation Plan

```bash
# Mark subtask-3-3 as completed
# Edit implementation_plan.json and update:
{
  "id": "subtask-3-3",
  "status": "completed",
  "notes": "Performance comparison completed. Achieved X% reduction in test execution time (target: 30-50%). Baseline: 173s average, New: Xs average. All database types show improvement. Documented in PR description.",
  "updated_at": "<current_timestamp>"
}
```

## Manual Verification (Alternative)

If the Python script cannot be used, you can manually compare:

1. **Get baseline data:** See `baseline-ci-timing.json` or the summary table above

2. **Get new timing data:**
   - Go to your PR's CI run on GitHub Actions
   - Click on each job and note the duration
   - Record in a spreadsheet or text file

3. **Calculate improvement:**
   ```
   Improvement % = ((Baseline - New) / Baseline) × 100
   ```

4. **Verify target:**
   - Overall improvement should be ≥30%
   - Target range: 30-50% reduction
   - Document results in PR description

## Expected Results

### Success Criteria

✓ Overall average job duration reduced by **30-50%**
✓ All database types show improvement
✓ All 24 jobs pass successfully
✓ No database locking errors
✓ No resource exhaustion errors
✓ Parallel execution visible in logs

### What to Look For in Logs

Search CI job logs for indicators of parallel execution:

```
✓ "Running tests in parallel"
✓ "Parallel processes: 2"
✓ "Using 2 processes"
✓ Multiple test files running simultaneously
```

## Troubleshooting

### Issue: CI not triggered

**Cause:** Workflow only triggers on `pull_request` events
**Solution:** Create a PR (it's blocked until then)

### Issue: Jobs timing out

**Cause:** Parallel tests may reveal isolation issues
**Solution:** Check logs for deadlocks, investigate failing tests

### Issue: No improvement or regression

**Cause:** Tests may not be parallel-safe or overhead too high
**Solution:** Investigate logs, check for serialization bottlenecks

### Issue: Script fails with "Run not found"

**Cause:** Invalid run ID or run not completed
**Solution:** Verify run ID from GitHub URL, ensure run completed

## Completion Checklist

- [ ] PR created
- [ ] CI workflow completed (all 24 jobs)
- [ ] Comparison script run successfully
- [ ] Results meet target (30-50% improvement)
- [ ] Results documented in PR description
- [ ] Implementation plan updated (subtask-3-3 = completed)
- [ ] Subtask committed to git

## Next Steps After Completion

After subtask-3-3 is completed:

1. Proceed to subtask-4-1: Add documentation about parallel test execution
2. Update contributing.md with parallel testing guide
3. Complete Phase 4 (Documentation)
4. Request PR review

## Contact

If you encounter issues or need clarification:
- Review the implementation_plan.json for context
- Check build-progress.txt for session history
- Consult the original spec in spec.md
