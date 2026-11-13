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

## Publishing

Every pull request builds the site with `zensical build --strict`, so a broken link or a missing page fails the
check. Each push to `main` publishes the docs to GitHub Pages as the `dev` version. When a release pull request
from release-please is merged, that release is published as `<major>.<minor>` with the `latest` alias. Commit
messages follow [Conventional Commits](https://www.conventionalcommits.org/).

`main` is protected by the ruleset in `.github/rulesets/main.json`. Changes reach it only through a pull request that
passes the `zensical build --strict` check and has one approval. Pull requests are squash-merged. Admins can bypass the
ruleset.

## Licence

GNU General Public License v3.0 or later. See [LICENSE](LICENSE).
