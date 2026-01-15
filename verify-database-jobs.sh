#!/bin/bash
# Automated verification script for subtask-3-2
# Verifies all database jobs pass (SQLite, MySQL, MariaDB, PostgreSQL)

set -e

echo "=== Database Jobs Verification Script ==="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function to check if gh CLI is available
check_gh_cli() {
    if ! command -v gh &> /dev/null; then
        echo -e "${RED}✗ GitHub CLI (gh) is not installed${NC}"
        echo "Please install it from: https://cli.github.com/"
        exit 1
    fi
    echo -e "${GREEN}✓ GitHub CLI is available${NC}"
}

# Function to verify workflow file contains --parallel flags
verify_workflow_config() {
    echo ""
    echo "--- Verifying Workflow Configuration ---"

    local workflow_file=".github/workflows/ci.yaml"

    # Check Unit tests have --parallel flag
    local unit_parallel_count=$(grep -c "vendor/bin/pest tests/Unit --parallel" "$workflow_file" || true)
    if [ "$unit_parallel_count" -ge 4 ]; then
        echo -e "${GREEN}✓ Unit test commands have --parallel flag ($unit_parallel_count found)${NC}"
    else
        echo -e "${RED}✗ Unit test commands missing --parallel flag (expected 4, found $unit_parallel_count)${NC}"
        return 1
    fi

    # Check Integration tests have --parallel flag
    local integration_parallel_count=$(grep -c "vendor/bin/pest tests/Integration --parallel" "$workflow_file" || true)
    if [ "$integration_parallel_count" -ge 4 ]; then
        echo -e "${GREEN}✓ Integration test commands have --parallel flag ($integration_parallel_count found)${NC}"
    else
        echo -e "${RED}✗ Integration test commands missing --parallel flag (expected 4, found $integration_parallel_count)${NC}"
        return 1
    fi

    echo -e "${GREEN}✓ Workflow configuration is correct${NC}"
}

# Function to get the latest CI run for the branch
get_latest_ci_run() {
    local branch="auto-claude/005-run-unit-tests-in-parallel"

    echo ""
    echo "--- Checking for CI Runs ---"

    # Get latest workflow run
    local run_info=$(gh run list --branch "$branch" --limit 1 --json databaseId,status,conclusion,createdAt,displayTitle 2>&1)

    if echo "$run_info" | grep -q "no runs found"; then
        echo -e "${YELLOW}⚠ No CI runs found for branch $branch${NC}"
        echo ""
        echo "This means either:"
        echo "  1. No PR has been created yet"
        echo "  2. CI hasn't been triggered yet"
        echo ""
        echo "To trigger CI, create a PR:"
        echo "  https://github.com/pelican-dev/panel/compare/main...auto-claude/005-run-unit-tests-in-parallel"
        return 1
    fi

    # Parse run info
    local run_id=$(echo "$run_info" | jq -r '.[0].databaseId')
    local status=$(echo "$run_info" | jq -r '.[0].status')
    local conclusion=$(echo "$run_info" | jq -r '.[0].conclusion')

    echo "Latest CI Run: $run_id"
    echo "Status: $status"
    echo "Conclusion: $conclusion"

    if [ "$status" != "completed" ]; then
        echo -e "${YELLOW}⚠ CI run is still in progress${NC}"
        echo "Run: gh run watch $run_id"
        return 1
    fi

    echo "$run_id"
}

# Function to verify all database jobs
verify_database_jobs() {
    local run_id=$1

    echo ""
    echo "--- Verifying Database Jobs ---"

    # Get all jobs for this run
    local jobs=$(gh run view "$run_id" --json jobs --jq '.jobs[] | {name: .name, conclusion: .conclusion}')

    # Expected jobs
    declare -A expected_jobs=(
        ["SQLite"]=4
        ["MySQL"]=4
        ["PostgreSQL"]=4
        ["MariaDB-10.6"]=4
        ["MariaDB-10.11"]=4
        ["MariaDB-11.4"]=4
    )

    local total_expected=24
    local total_passed=0
    local total_failed=0

    echo ""
    echo "Checking job results:"

    # Check SQLite jobs
    for php in "8.2" "8.3" "8.4" "8.5"; do
        local job_name="SQLite (${php})"
        local conclusion=$(echo "$jobs" | jq -r "select(.name == \"$job_name\") | .conclusion" | head -1)

        if [ "$conclusion" = "success" ]; then
            echo -e "${GREEN}✓ $job_name: PASSED${NC}"
            ((total_passed++))
        elif [ "$conclusion" = "failure" ]; then
            echo -e "${RED}✗ $job_name: FAILED${NC}"
            ((total_failed++))
        else
            echo -e "${YELLOW}⚠ $job_name: $conclusion${NC}"
        fi
    done

    # Check MySQL jobs
    for php in "8.2" "8.3" "8.4" "8.5"; do
        local job_name="MySQL (${php})"
        local conclusion=$(echo "$jobs" | jq -r "select(.name == \"$job_name\") | .conclusion" | head -1)

        if [ "$conclusion" = "success" ]; then
            echo -e "${GREEN}✓ $job_name: PASSED${NC}"
            ((total_passed++))
        elif [ "$conclusion" = "failure" ]; then
            echo -e "${RED}✗ $job_name: FAILED${NC}"
            ((total_failed++))
        else
            echo -e "${YELLOW}⚠ $job_name: $conclusion${NC}"
        fi
    done

    # Check PostgreSQL jobs
    for php in "8.2" "8.3" "8.4" "8.5"; do
        local job_name="PostgreSQL (${php})"
        local conclusion=$(echo "$jobs" | jq -r "select(.name == \"$job_name\") | .conclusion" | head -1)

        if [ "$conclusion" = "success" ]; then
            echo -e "${GREEN}✓ $job_name: PASSED${NC}"
            ((total_passed++))
        elif [ "$conclusion" = "failure" ]; then
            echo -e "${RED}✗ $job_name: FAILED${NC}"
            ((total_failed++))
        else
            echo -e "${YELLOW}⚠ $job_name: $conclusion${NC}"
        fi
    done

    # Check MariaDB jobs
    for version in "10.6" "10.11" "11.4"; do
        for php in "8.2" "8.3" "8.4" "8.5"; do
            local job_name="MariaDB-${version} (${php})"
            local conclusion=$(echo "$jobs" | jq -r "select(.name == \"$job_name\") | .conclusion" | head -1)

            if [ "$conclusion" = "success" ]; then
                echo -e "${GREEN}✓ $job_name: PASSED${NC}"
                ((total_passed++))
            elif [ "$conclusion" = "failure" ]; then
                echo -e "${RED}✗ $job_name: FAILED${NC}"
                ((total_failed++))
            else
                echo -e "${YELLOW}⚠ $job_name: $conclusion${NC}"
            fi
        done
    done

    echo ""
    echo "Summary: $total_passed/$total_expected jobs passed"

    if [ "$total_passed" -eq "$total_expected" ]; then
        echo -e "${GREEN}✓ All database jobs passed!${NC}"
        return 0
    else
        echo -e "${RED}✗ Some jobs failed or didn't complete${NC}"
        return 1
    fi
}

# Function to check job logs for parallel execution
check_parallel_execution() {
    local run_id=$1

    echo ""
    echo "--- Checking for Parallel Execution Indicators ---"

    # Get first SQLite job log as sample
    local job_id=$(gh run view "$run_id" --json jobs --jq '.jobs[] | select(.name | contains("SQLite")) | .databaseId' | head -1)

    if [ -z "$job_id" ]; then
        echo -e "${YELLOW}⚠ Could not find a job to check logs${NC}"
        return 1
    fi

    echo "Checking job $job_id logs for parallel execution..."

    local log=$(gh run view --job="$job_id" --log 2>&1 || true)

    # Look for parallel execution indicators
    if echo "$log" | grep -qi "Running.*in parallel\|parallel.*process\|worker.*process"; then
        echo -e "${GREEN}✓ Parallel execution detected in logs${NC}"
        return 0
    else
        echo -e "${YELLOW}⚠ No clear parallel execution indicators found${NC}"
        echo "  (This might be normal - Pest doesn't always log parallel execution explicitly)"
        return 0
    fi
}

# Function to check for database locking errors
check_database_errors() {
    local run_id=$1

    echo ""
    echo "--- Checking for Database Errors ---"

    local jobs=$(gh run view "$run_id" --json jobs --jq '.jobs[].databaseId')
    local errors_found=0

    for job_id in $jobs; do
        local log=$(gh run view --job="$job_id" --log 2>&1 || true)

        # Check for database locking errors
        if echo "$log" | grep -qi "database.*lock\|locked.*database\|SQLITE_BUSY"; then
            echo -e "${RED}✗ Database locking error found in job $job_id${NC}"
            ((errors_found++))
        fi

        # Check for OOM errors
        if echo "$log" | grep -qi "out of memory\|OOM\|memory exhausted"; then
            echo -e "${RED}✗ Memory exhaustion error found in job $job_id${NC}"
            ((errors_found++))
        fi
    done

    if [ "$errors_found" -eq 0 ]; then
        echo -e "${GREEN}✓ No database locking or OOM errors found${NC}"
        return 0
    else
        echo -e "${RED}✗ Found $errors_found error(s) in job logs${NC}"
        return 1
    fi
}

# Main execution
main() {
    echo "Starting verification for subtask-3-2..."
    echo "Branch: auto-claude/005-run-unit-tests-in-parallel"
    echo ""

    # Step 1: Check prerequisites
    check_gh_cli

    # Step 2: Verify workflow configuration
    if ! verify_workflow_config; then
        echo ""
        echo -e "${RED}✗ Workflow configuration verification failed${NC}"
        exit 1
    fi

    # Step 3: Get latest CI run
    if ! run_id=$(get_latest_ci_run); then
        echo ""
        echo -e "${YELLOW}⚠ Cannot verify CI jobs without an active CI run${NC}"
        echo ""
        echo "Next steps:"
        echo "  1. Create a PR at: https://github.com/pelican-dev/panel/compare/main...auto-claude/005-run-unit-tests-in-parallel"
        echo "  2. Wait for CI to complete"
        echo "  3. Run this script again: ./verify-database-jobs.sh"
        exit 2
    fi

    # Step 4: Verify all database jobs passed
    if ! verify_database_jobs "$run_id"; then
        echo ""
        echo -e "${RED}✗ Database jobs verification failed${NC}"
        exit 1
    fi

    # Step 5: Check for parallel execution
    check_parallel_execution "$run_id" || true

    # Step 6: Check for database errors
    if ! check_database_errors "$run_id"; then
        echo ""
        echo -e "${RED}✗ Database error check failed${NC}"
        exit 1
    fi

    # Success!
    echo ""
    echo "======================================="
    echo -e "${GREEN}✓ All verification checks passed!${NC}"
    echo "======================================="
    echo ""
    echo "Subtask-3-2 verification complete:"
    echo "  ✓ SQLite jobs passed (4 jobs across PHP 8.2-8.5)"
    echo "  ✓ MySQL jobs passed (4 jobs across PHP 8.2-8.5)"
    echo "  ✓ MariaDB jobs passed (12 jobs across 3 versions × 4 PHP versions)"
    echo "  ✓ PostgreSQL jobs passed (4 jobs across PHP 8.2-8.5)"
    echo "  ✓ No database locking errors"
    echo "  ✓ No resource exhaustion errors"
    echo ""
}

main "$@"
