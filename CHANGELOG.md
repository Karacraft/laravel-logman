# Changelog

All notable changes to this project will be documented in this file.

The format of each entry follows the [Keep a Changelog](https://keepachangelog.com/en/1.0.0/) format.

## [Unreleased]

### Added
- Initial production-ready release (v3.0.0)
- `README.md` with comprehensive installation, usage, and production checklist
- `CHANGELOG.md` template with Keep a Changelog format
- `CONTRIBUTING.md` with contribution guidelines
- `.env.example` template with recommended settings
- Production readiness checklist in README
- Config file support (`config/logman.php`) for runtime configuration
- Verification steps after installation (run `php artisan migrate --force`)
- Detailed error handling and logging improvements

### Changed
- Updated composer.json: added php ^8.1 requirement, autoload-dev, tags, minimum-stability
- Improved ModelEventLogger trait to use more robust user name resolution
- Enhanced EventLogger model with proper fallback for action names
- Added IP address detection headers list in getUserIpAddr()
- Changed service provider type from "project" to "library"

### Fixed
- README now includes installation verification step (`php artisan migrate --force`)
- Added production readiness checklist table
- Added troubleshooting section with common issues (migration, user_name null, IP UNKNOWN)
- Clarified that log viewer requires Tabulator JS library

## [3.0.0] - 2026-11-10

### Added
- Production readiness checklist in README
- `docs/` folder structure for future documentation
- Config file support via `.env` and `config/logman.php`
- Installation verification step (`php artisan migrate --force`)
- CHANGELOG.md following Keep a Changelog format
- CONTRIBUTING.md with contribution guidelines
- SECURITY section in README
- Rate limiting recommendation for `/logman` route
- Backup strategy note
- Security headers recommendation (CSP, HSTS, X-Frame-Options)
- Tabulator JS library requirement documentation
- File permissions guidance
- LogController implementation for `/logman` API endpoint

### Changed
- composer.json: `type` changed from "project" to "library", added autoload-dev, tags, php version constraint
- README: restructured with Installation → Usage → Log Viewer → Production Readiness Checklist → Troubleshooting sections
- EventLogger model: `original` and `changes` columns changed from `text` to `json` for better performance with large models

### Fixed
- README now includes verification steps after migration
- Added proper error handling for missing user name attributes
- Clarified that `user_name` comes from `$user->name` or configured attributes
- IP address detection improved: now uses Laravel's `request()->ip()` as primary source, with fallback to raw headers