# Changelog

## 2.0.0 (2026-11-27)

The Poggit plugin version for the original [pmmp/PocketMine-MP](https://github.com/pmmp/PocketMine-MP) 5.x (1.4.2) is kept
on the [`legacy/pmmp-5.44`](https://github.com/presentkim-pm/IgnoreCase/tree/legacy/pmmp-5.44) branch.

### ⚠️ Breaking changes
- Targets [axolotl-pm/PocketMine-MP](https://github.com/axolotl-pm/PocketMine-MP) `5.51` (BedrockProtocol 64,
  Bedrock `1.26.60`) and **PHP 8.4+**.
- Distributed as a phar from GitHub Releases instead of Poggit.

### 🔄 Changes
- `composer.json` added (Composer is only used for development and testing); `plugin.yml` has
  `src-namespace-prefix` and the source files are no longer nested in `src/kim/present/ignorecase/`.
- `CommandLabelResolver` helper holding the case-correction logic, with PHPUnit tests.

### 🐛 Fixes
- The replacement cache grew with every mixed-case spelling of a command a player typed (2^n spellings per command), an
  unbounded memory growth reachable by players. Since command map keys are lower case, the label is now resolved
  directly without a cache.
- The missing `use function` imports for `is_dir()`, `count()`, `scandir()` and `rmdir()`, and a `TypeError` when the
  data folder cannot be scanned.
