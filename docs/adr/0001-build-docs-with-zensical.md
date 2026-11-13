# 0001 — Build the docs with Zensical

- Status: Accepted
- Date: 2026-11-13

## Context

The docs site was set up with MkDocs and Material for MkDocs, with `mike` for one version of the docs per release.
Material for MkDocs reaches end of life on 2027-05-05 and only gets critical and security fixes until then.
The docs have to stay versioned through 1.0 and later, so the generator must be supported for years.

Zensical is the successor to Material for MkDocs, made by the same team. It has the same theme, the same
Markdown extensions, a strict mode and link validation. The Zensical team maintains a fork of `mike` that builds
with Zensical. Native versioning in Zensical is planned but not released yet.

## Decision

- Build the docs with Zensical, pinned to an exact version in `requirements.txt`.
- Use the native `zensical.toml` config. Don't keep a `mkdocs.yml`.
- Set `strict = true` in `zensical.toml`, so every build fails on warnings, including builds started by `mike`.
- Version the docs with the Zensical team's fork of `mike`
  (`git+https://github.com/squidfunk/mike.git`), pinned to a release tag. It keeps the `mike` commands and the
  `gh-pages` layout we planned for.

## Consequences

- Commands change from `mkdocs build` / `mkdocs serve` to `zensical build` / `zensical serve`.
- The `mike` fork is not on PyPI. Installing it needs `git`, and Dependabot won't update it, so we bump its tag
  by hand.
- Only plugins that Zensical supports can be used. Check Zensical's compatibility list before adding one.
- When Zensical ships native versioning, we review this decision and may replace `mike` in a new ADR.
