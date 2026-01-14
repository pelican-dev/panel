#!/usr/bin/env python3
"""
CI Timing Comparison Script for Parallel Test Implementation

This script compares CI execution times before and after implementing parallel tests.
It calculates the performance improvement and generates a report for the PR description.

Usage:
    python compare-ci-timing.py <new_run_id>

Example:
    python compare-ci-timing.py 21234567890
"""

import sys
import json
import requests
from datetime import datetime

BASELINE_FILE = "baseline-ci-timing.json"
REPO = "pelican-dev/panel"

def load_baseline():
    """Load baseline timing data from JSON file."""
    with open(BASELINE_FILE, 'r') as f:
        return json.load(f)

def fetch_run_data(run_id):
    """Fetch job timing data for a specific CI run."""
    url = f"https://api.github.com/repos/{REPO}/actions/runs/{run_id}/jobs"
    response = requests.get(url)
    response.raise_for_status()
    return response.json()

def parse_job_timing(jobs_data):
    """Parse job timing data and group by database type."""
    db_jobs = {}

    for job in jobs_data.get('jobs', []):
        name = job['name']

        # Extract database type
        db_type = None
        for db in ['sqlite', 'mysql', 'mariadb', 'postgresql']:
            if db in name.lower():
                db_type = db
                break

        if not db_type:
            continue

        if db_type not in db_jobs:
            db_jobs[db_type] = []

        started = datetime.fromisoformat(job['started_at'].replace('Z', '+00:00'))
        completed = datetime.fromisoformat(job['completed_at'].replace('Z', '+00:00'))
        duration_seconds = (completed - started).total_seconds()

        db_jobs[db_type].append({
            'name': name,
            'duration_seconds': duration_seconds,
            'status': job['conclusion']
        })

    return db_jobs

def calculate_improvement(baseline, new_data):
    """Calculate performance improvement percentage."""
    if baseline == 0:
        return 0
    return ((baseline - new_data) / baseline) * 100

def generate_report(baseline, new_run_id):
    """Generate comparison report."""
    print("Fetching new CI run data...")
    new_jobs = fetch_run_data(new_run_id)
    new_db_jobs = parse_job_timing(new_jobs)

    print("\n" + "=" * 70)
    print("CI EXECUTION TIME COMPARISON")
    print("=" * 70)
    print()

    # Header
    print(f"Baseline Run: {baseline['baseline_run']['run_id']} (main branch, {baseline['baseline_run']['date'][:10]})")
    print(f"New Run: {new_run_id} (parallel tests)")
    print()

    # Compare by database
    improvements = []

    for db_type in sorted(baseline['job_timing_by_database'].keys()):
        baseline_data = baseline['job_timing_by_database'][db_type]
        baseline_avg = baseline_data['average_seconds']

        if db_type not in new_db_jobs:
            print(f"{db_type.upper()}: NO DATA AVAILABLE")
            continue

        new_jobs_list = new_db_jobs[db_type]
        new_avg = sum(j['duration_seconds'] for j in new_jobs_list) / len(new_jobs_list)
        improvement = calculate_improvement(baseline_avg, new_avg)
        improvements.append(improvement)

        print(f"{db_type.upper()}:")
        print(f"  Baseline: {baseline_avg:.0f}s ({baseline_avg/60:.1f} min)")
        print(f"  New:      {new_avg:.0f}s ({new_avg/60:.1f} min)")
        print(f"  Improvement: {improvement:.1f}% {'✓' if improvement >= 30 else '✗'}")
        print()

    # Overall summary
    overall_baseline = baseline['summary']['overall_average_seconds']
    all_new_durations = [j['duration_seconds'] for jobs in new_db_jobs.values() for j in jobs]
    overall_new = sum(all_new_durations) / len(all_new_durations) if all_new_durations else 0
    overall_improvement = calculate_improvement(overall_baseline, overall_new)

    print("OVERALL:")
    print(f"  Baseline: {overall_baseline:.0f}s ({overall_baseline/60:.1f} min)")
    print(f"  New:      {overall_new:.0f}s ({overall_new/60:.1f} min)")
    print(f"  Improvement: {overall_improvement:.1f}%")
    print()

    # Target validation
    target = baseline['target_improvement']
    print("TARGET VALIDATION:")
    if overall_improvement >= target['minimum_reduction_percent']:
        print(f"  ✓ Met minimum target of {target['minimum_reduction_percent']}% reduction")
    else:
        print(f"  ✗ Did not meet minimum target of {target['minimum_reduction_percent']}% reduction")

    if overall_improvement >= target['target_reduction_percent']:
        print(f"  ✓ Met target of {target['target_reduction_percent']}% reduction")

    if overall_improvement >= target['maximum_reduction_percent']:
        print(f"  ✓ Exceeded maximum target of {target['maximum_reduction_percent']}% reduction")

    print()
    print("=" * 70)

    # Generate PR description text
    print("\nPR DESCRIPTION TEXT:")
    print("---")
    print(f"""
## Performance Results

| Database | Baseline | With Parallel | Improvement |
|----------|----------|---------------|-------------|
""")

    for db_type in sorted(baseline['job_timing_by_database'].keys()):
        if db_type not in new_db_jobs:
            continue
        baseline_avg = baseline['job_timing_by_database'][db_type]['average_seconds']
        new_jobs_list = new_db_jobs[db_type]
        new_avg = sum(j['duration_seconds'] for j in new_jobs_list) / len(new_jobs_list)
        improvement = calculate_improvement(baseline_avg, new_avg)
        print(f"| {db_type.upper()} | {baseline_avg/60:.1f} min | {new_avg/60:.1f} min | {improvement:.1f}% |")

    print(f"| **Average** | **{overall_baseline/60:.1f} min** | **{overall_new/60:.1f} min** | **{overall_improvement:.1f}%** |")
    print()
    print(f"**Result:** {'✓ Target met' if overall_improvement >= target['minimum_reduction_percent'] else '✗ Target not met'} (target: {target['minimum_reduction_percent']}-{target['maximum_reduction_percent']}% reduction)")

if __name__ == "__main__":
    if len(sys.argv) < 2:
        print("Usage: python compare-ci-timing.py <new_run_id>")
        print("\nExample: python compare-ci-timing.py 21234567890")
        print("\nTo find the run ID:")
        print("1. Go to: https://github.com/pelican-dev/panel/actions")
        print("2. Click on the CI run for your PR")
        print("3. The run ID is in the URL: /actions/runs/<RUN_ID>")
        sys.exit(1)

    new_run_id = sys.argv[1]
    baseline = load_baseline()

    try:
        generate_report(baseline, new_run_id)
    except Exception as e:
        print(f"\nError: {e}")
        print("\nMake sure:")
        print("1. The run ID is correct")
        print("2. The CI run has completed")
        print("3. You have internet connectivity")
        sys.exit(1)
