# Changelog - Maintenance actions

Date: 2025-09-07

Summary of maintenance actions performed via helper scripts:

- Removed temporary admin inspection & helper scripts from `scripts/` to avoid accidental reuse in production.
  - Removed files:
    - `scripts/list_school_admins_without_school.php`
    - `scripts/propose_admin_school_mappings.php`
    - `scripts/apply_admin_school_updates.php`
    - `scripts/delete_admins_without_school.php`
    - `scripts/inspect_user_and_complaints.php`

- Database updates performed during this session:
  - Updated user id 8 (email: BEA2345@sekolah.admin) → `school_id = 8`.
  - Deleted two `school_admin` users that had no `school_id`:
    - id 2 — `schooladmin@demo.com`
    - id 5 — `smktd@demo.com`

Reasoning / Notes:
- These temporary scripts were created to safely inspect and modify the local database when CLI DB tools were unavailable. After confirming and applying the intended changes, scripts were removed for safety.
- If you need an audit trail in VCS, consider committing `CHANGELOG.md` or recording this in your project management system.

Next steps (optional):
- Commit these changes to git with a concise commit message (e.g. "chore: remove maintenance scripts; update admin-school mapping") and push.
- If you want me to create a proper database migration or an administrative UI to manage school->admin mappings, I can implement that.

Verified-by: automated session script
