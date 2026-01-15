# Quick Start: Database Jobs Verification

## 🚀 One-Command Verification

Once the PR is created and CI completes, verify all database jobs with:

```bash
./verify-database-jobs.sh
```

That's it! The script automatically checks:
- ✅ All 24 database jobs (SQLite, MySQL, MariaDB, PostgreSQL)
- ✅ Parallel execution indicators
- ✅ No database locking errors
- ✅ No OOM errors
- ✅ Comprehensive pass/fail report

---

## 📋 What Gets Verified

### Database Matrix (24 Jobs Total)
- **SQLite**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5
- **MySQL**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5
- **MariaDB 10.6**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5
- **MariaDB 10.11**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5
- **MariaDB 11.4**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5
- **PostgreSQL**: 4 jobs → PHP 8.2, 8.3, 8.4, 8.5

### Checks Performed
1. ✅ Workflow configuration has `--parallel` flags
2. ✅ All 24 jobs show "success" status
3. ✅ Logs indicate parallel test execution
4. ✅ No "SQLITE_BUSY" or database lock errors
5. ✅ No "Out of Memory" or resource exhaustion errors

---

## 🔗 Create PR First

Before running verification, create the PR:

**URL**: https://github.com/pelican-dev/panel/compare/main...auto-claude/005-run-unit-tests-in-parallel

**Title**: `feat: enable parallel test execution in CI`

**Body**: See PR template in SUBTASK-3-2-VERIFICATION.md

---

## 📊 Expected Output

### Success ✅
```
✓ GitHub CLI is available
✓ Workflow configuration is correct
✓ SQLite (8.2): PASSED
✓ SQLite (8.3): PASSED
...
✓ All database jobs passed!
✓ No database locking or OOM errors found

======================================
✓ All verification checks passed!
======================================
```

### Failure ❌
```
✗ SQLite (8.2): FAILED
✗ Database jobs verification failed
```

Check job logs at: https://github.com/pelican-dev/panel/actions

---

## 🆘 Troubleshooting

### "no runs found"
- **Cause**: PR not created yet or CI hasn't triggered
- **Fix**: Create PR and wait for CI to start

### "Requires authentication"
- **Cause**: GitHub CLI not authenticated
- **Fix**: Run `gh auth login`

### Jobs failing
- **Cause**: Test failures or configuration issues
- **Fix**: Check specific job logs for error messages

---

## 📖 More Details

- **Full Guide**: `SUBTASK-3-2-VERIFICATION.md`
- **Completion Summary**: `SUBTASK-3-2-COMPLETION-SUMMARY.md`
- **Script Source**: `verify-database-jobs.sh`

---

## ✅ Quick Checklist

- [ ] PR created
- [ ] CI workflow triggered
- [ ] All jobs completed (5-10 minutes)
- [ ] Run: `./verify-database-jobs.sh`
- [ ] All checks pass
- [ ] Proceed to next phase

---

**Estimated Time**: 5-10 minutes for CI + 30 seconds for verification script
