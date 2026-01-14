# Specification: Run Unit Tests in Parallel on GitHub Actions

## Overview

This task implements parallel test execution for the Pelican Panel project's CI pipeline on GitHub Actions. Currently, tests run sequentially (Unit tests followed by Integration tests), which increases CI execution time. By leveraging Pest's built-in parallel testing capabilities and GitHub Actions' parallel job execution, we will significantly reduce the overall test suite execution time while maintaining test reliability and coverage across multiple PHP versions and database backends.

## Workflow Type

**Type**: feature

**Rationale**: This is a feature enhancement to the CI/CD pipeline that adds parallel test execution capabilities. It improves developer experience by reducing feedback time without changing application functionality. The implementation requires configuration changes to the test workflow and potentially the PHPUnit/Pest configuration.

## Task Scope

### Services Involved
- **main** (primary) - CI/CD workflow configuration and test execution setup

### This Task Will:
- [ ] Enable Pest's parallel testing feature using the `--parallel` flag
- [ ] Configure PHPUnit/Pest to support parallel test execution
- [ ] Modify `.github/workflows/ci.yaml` to run Unit and Integration tests in parallel
- [ ] Optimize test suite configuration for parallel execution
- [ ] Ensure database isolation between parallel test processes
- [ ] Validate that all tests pass reliably in parallel mode across all database types (SQLite, MySQL, MariaDB, PostgreSQL)
- [ ] Document the parallel testing configuration and any considerations for test authors

### Out of Scope:
- Refactoring individual test files (unless they have parallelization issues)
- Changing test coverage or adding new tests
- Modifying database service configurations beyond what's needed for parallel testing
- Performance profiling or optimization of individual tests
- Implementing test result caching or artifact storage beyond existing setup

## Service Context

### Main Service

**Tech Stack:**
- Language: PHP (^8.2 || ^8.3 || ^8.4 || ^8.5)
- Framework: Laravel 12.37
- Testing Framework: Pest 3.7
- Key directories: tests/, .github/workflows/

**Entry Point:** `vendor/bin/pest`

**How to Run Tests:**
```bash
# Current approach (sequential)
vendor/bin/pest tests/Unit
vendor/bin/pest tests/Integration

# Proposed approach (parallel)
vendor/bin/pest --parallel
# or with explicit process count
vendor/bin/pest --processes=4
```

**CI Workflow:** `.github/workflows/ci.yaml`

## Files to Modify

| File | Service | What to Change |
|------|---------|---------------|
| `.github/workflows/ci.yaml` | main | Update test execution commands to use `--parallel` flag for Pest |
| `phpunit.xml` | main | Add parallel testing configuration attributes if needed |
| `.github/workflows/ci.yaml` | main | Optionally split Unit and Integration test jobs to run in parallel |

## Files to Reference

These files show patterns to follow:

| File | Pattern to Copy |
|------|----------------|
| `.github/workflows/ci.yaml` | Current test job structure with matrix strategy for PHP versions and databases |
| `phpunit.xml` | Test suite configuration and environment variable setup |
| `tests/Pest.php` | Pest configuration and test case bindings |
| `composer.json` | Current Pest version and testing dependencies |

## Patterns to Follow

### GitHub Actions Matrix Strategy

From `.github/workflows/ci.yaml`:

```yaml
strategy:
  fail-fast: true
  matrix:
    php: [8.2, 8.3, 8.4, 8.5]
```

**Key Points:**
- Matrix strategy already enables parallel job execution across PHP versions
- Each PHP version runs as a separate job concurrently
- `fail-fast: true` stops other jobs if one fails

### Pest Parallel Testing Configuration

Pest 3.x supports parallel execution with the `--parallel` flag:

```bash
# Run tests in parallel with automatic process detection
vendor/bin/pest --parallel

# Run tests with explicit process count
vendor/bin/pest --processes=4
```

**Key Points:**
- Pest automatically detects optimal process count based on CPU cores
- Each process gets its own isolated database connection
- Test state must be isolated between processes

### PHPUnit Configuration for Parallel Testing

From `phpunit.xml`:

```xml
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    <testsuites>
        <testsuite name="Integration">
            <directory>./tests/Integration</directory>
        </testsuite>
        <testsuite name="Unit">
            <directory>./tests/Unit</directory>
        </testsuite>
    </testsuites>
</phpunit>
```

**Key Points:**
- Test suites are already properly separated (Unit vs Integration)
- Environment variables in `<php>` section apply to all parallel processes
- Database configuration uses environment variables for flexibility

## Requirements

### Functional Requirements

1. **Enable Parallel Test Execution**
   - Description: Configure Pest to run tests in parallel within each job
   - Acceptance: Tests execute using multiple processes simultaneously, reducing execution time

2. **Maintain Test Reliability**
   - Description: Ensure all tests pass consistently in parallel mode
   - Acceptance: No flaky tests or race conditions introduced by parallelization

3. **Support Multiple Database Backends**
   - Description: Parallel tests work correctly with SQLite, MySQL, MariaDB, and PostgreSQL
   - Acceptance: All database matrix jobs pass with parallel execution enabled

4. **Preserve Test Isolation**
   - Description: Each test process maintains proper database and state isolation
   - Acceptance: Tests don't interfere with each other when running in parallel

5. **Optimize CI Execution Time**
   - Description: Reduce overall CI pipeline execution time through parallelization
   - Acceptance: Measurable reduction in test job duration (target: 30-50% faster)

### Edge Cases

1. **Database Locking with SQLite** - SQLite may have locking issues with parallel writes; ensure each process uses a unique database file or proper locking configuration
2. **Port Conflicts** - If tests spawn services, ensure ports don't conflict between parallel processes
3. **Shared State in Tests** - Identify and fix any tests that rely on global state or execution order
4. **Resource Exhaustion** - Limit parallel processes to avoid exhausting GitHub Actions runner resources (2-core runners)
5. **Test Output Interleaving** - Ensure test output remains readable when multiple processes write concurrently

## Implementation Notes

### DO
- Use Pest's built-in `--parallel` flag for simplicity and Laravel integration
- Test parallel execution locally before pushing to CI
- Monitor CI job execution times to measure improvement
- Configure database naming to avoid collisions (e.g., `testing_{process_id}.sqlite`)
- Keep parallel process count appropriate for GitHub Actions runners (2-4 processes)
- Ensure integration tests properly clean up database state in teardown

### DON'T
- Don't assume tests will run in any specific order
- Don't use shared files or global state without proper locking
- Don't set process count too high for CI runners (avoid resource exhaustion)
- Don't skip testing parallel execution on all database types
- Don't parallelize if tests have known interdependencies (fix those first)

## Development Environment

### Start Services

```bash
# Install dependencies
composer install

# Run tests locally
vendor/bin/pest

# Run tests in parallel locally
vendor/bin/pest --parallel

# Run specific test suite
vendor/bin/pest tests/Unit --parallel
vendor/bin/pest tests/Integration --parallel

# Run with explicit process count
vendor/bin/pest --processes=2
```

### Service URLs
- Local development: http://localhost/ (not required for test execution)

### Required Environment Variables

Testing environment variables (from `phpunit.xml`):
- `APP_ENV`: testing
- `DB_CONNECTION`: sqlite|mysql|mariadb|pgsql
- `DB_DATABASE`: testing.sqlite (or database name for SQL servers)
- `CACHE_DRIVER`: array
- `QUEUE_CONNECTION`: sync
- `SESSION_DRIVER`: array
- `MAIL_MAILER`: array

## Success Criteria

The task is complete when:

1. [ ] Pest tests execute in parallel mode using `--parallel` flag
2. [ ] All Unit tests pass consistently in parallel execution
3. [ ] All Integration tests pass consistently in parallel execution
4. [ ] Parallel execution works across all database types (SQLite, MySQL, MariaDB, PostgreSQL)
5. [ ] Parallel execution works across all PHP versions (8.2, 8.3, 8.4, 8.5)
6. [ ] CI workflow (`.github/workflows/ci.yaml`) is updated with parallel test commands
7. [ ] Test execution time is measurably reduced (documented in PR description)
8. [ ] No console errors or warnings related to parallel execution
9. [ ] Existing tests still pass without modification
10. [ ] Documentation is added to explain parallel test execution

## QA Acceptance Criteria

**CRITICAL**: These criteria must be verified by the QA Agent before sign-off.

### Unit Tests
| Test | File | What to Verify |
|------|------|----------------|
| Pest Parallel Execution | `vendor/bin/pest tests/Unit --parallel` | All unit tests pass in parallel mode |
| Pest Sequential Execution | `vendor/bin/pest tests/Unit` | All unit tests still pass in sequential mode |
| Process Count Configuration | `vendor/bin/pest tests/Unit --processes=2` | Tests pass with explicit process count |

### Integration Tests
| Test | Services | What to Verify |
|------|----------|----------------|
| SQLite Parallel Tests | SQLite database | Integration tests pass with parallel execution on SQLite |
| MySQL Parallel Tests | MySQL 8 | Integration tests pass with parallel execution on MySQL |
| MariaDB Parallel Tests | MariaDB 10.6, 10.11, 11.4 | Integration tests pass with parallel execution on MariaDB |
| PostgreSQL Parallel Tests | PostgreSQL 14 | Integration tests pass with parallel execution on PostgreSQL |

### End-to-End Tests
| Flow | Steps | Expected Outcome |
|------|-------|------------------|
| CI Workflow Execution | 1. Push to branch 2. Wait for CI 3. Check all jobs | All test jobs (sqlite, mysql, mariadb, postgresql) pass with parallel execution |
| Multiple PHP Versions | 1. CI runs matrix of PHP 8.2-8.5 2. Each version uses parallel tests | All PHP version jobs pass |
| Pull Request Testing | 1. Create PR 2. CI runs automatically 3. Review results | CI completes faster with parallel tests, all checks pass |

### GitHub Actions Verification
| Check | Command/Action | Expected |
|-------|----------------|----------|
| Workflow syntax | View `.github/workflows/ci.yaml` | Valid YAML, parallel flags added |
| Job execution time | Compare CI duration before/after | 30-50% reduction in test job duration |
| Parallel process logs | View CI job logs | Multiple processes shown executing concurrently |
| Resource usage | Monitor runner metrics | No resource exhaustion or OOM errors |

### Database Verification
| Check | Query/Command | Expected |
|-------|---------------|----------|
| Test database isolation | Check database names during parallel execution | Each process uses isolated database |
| Database cleanup | Verify test databases removed after run | Clean state after test execution |
| No locking errors | Review test output | No database lock timeout errors |

### Performance Verification
| Metric | Measurement | Target |
|--------|-------------|--------|
| Unit test duration | Time to complete all unit tests | Reduced by 30-50% |
| Integration test duration | Time to complete all integration tests | Reduced by 20-40% |
| Total CI duration | End-to-end workflow execution time | Measurable improvement |
| Success rate | Percentage of passing test runs | 100% (no flakiness introduced) |

### QA Sign-off Requirements
- [ ] All unit tests pass in parallel mode
- [ ] All integration tests pass in parallel mode
- [ ] All database types (SQLite, MySQL, MariaDB, PostgreSQL) work with parallel execution
- [ ] All PHP versions (8.2, 8.3, 8.4, 8.5) work with parallel execution
- [ ] CI workflow successfully executes with parallel tests
- [ ] Test execution time is reduced compared to sequential execution
- [ ] No flaky tests or race conditions introduced
- [ ] No regressions in existing functionality
- [ ] Code follows established patterns
- [ ] No security vulnerabilities introduced
- [ ] Database isolation is properly maintained
- [ ] Test output remains readable and useful
- [ ] GitHub Actions runner resources are not exhausted

## Reference Material

### GitHub Issue #1313
**URL**: https://github.com/pelican-dev/panel/issues/1313
**Title**: Run unit tests in Parallel

### Reference Article
**URL**: https://ohdear.app/news-and-updates/running-our-test-suite-in-parallel-on-github-actions
**Summary**: Practical guide to implementing parallel test execution on GitHub Actions, including configuration examples and best practices for Laravel applications using Pest/PHPUnit.

### Pest Documentation
- **Parallel Testing**: https://pestphp.com/docs/plugins#parallel-testing
- **Configuration**: https://pestphp.com/docs/configuring-tests

### Current Test Statistics
- **Total test files**: 74
- **Test suites**: 2 (Unit, Integration)
- **Database types**: 4 (SQLite, MySQL, MariaDB, PostgreSQL)
- **PHP versions**: 4 (8.2, 8.3, 8.4, 8.5)
- **CI jobs**: 4 (one per database type)
- **Matrix combinations**: 16 total (4 PHP versions × 4 database types)
