# SightPress documentation

The source of the SightPress documentation site. SightPress is a powerful CMS for everyone, designed with blind and
low-vision creators and audiences in mind, built on modern, object-oriented PHP.

The site is built with Zensical, and each release has its own version of the docs.

## Build locally

You need Python 3 and git (one build tool is installed straight from GitHub).

```sh
python3 -m venv .venv
.venv/bin/pip install -r requirements.txt
.venv/bin/zensical serve
```

Then open `http://127.0.0.1:8000`. Before you open a pull request, run `.venv/bin/zensical build --strict`.

## Branding

The site uses the SightPress brand: the logo, favicons, self-hosted fonts and design tokens in `docs/brand/` are
copied unchanged from the brand guide, and `docs/stylesheets/sightpress.css` maps the tokens onto the theme for the
light and dark schemes. `overrides/` swaps in the light or dark logo and adds the favicons. When the brand guide
changes, copy its files into `docs/brand/` again rather than editing them here.

## Publishing

Every pull request builds the site with `zensical build --strict`, so a broken link or a missing page fails the
check. Each push to `main` publishes the docs as the `dev` version. When a release pull request from release-please is
merged, that release is published as `<major>.<minor>` with the `latest` alias. mike writes every version to the
`gh-pages` branch, and the Amplify workflow then deploys that branch to AWS Amplify Hosting at
<https://docs.sightpress.org>. The redirect rules and response headers it applies live in `amplify/`. Commit
messages follow [Conventional Commits](https://www.conventionalcommits.org/).

`main` is protected by the ruleset in `.github/rulesets/main.json`. Changes reach it only through a pull request that
passes the `zensical build --strict` check and has one approval. Pull requests are squash-merged. Admins can bypass the
ruleset.

## Contributing

Everyone can help, not only developers. See [CONTRIBUTING.md](CONTRIBUTING.md) for the ways to take part, and
the [Code of Conduct](CODE_OF_CONDUCT.md) that everyone who takes part agrees to follow.

## Licence

GNU General Public License v3.0 or later. See [LICENSE](LICENSE).
