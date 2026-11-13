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

## Licence

GNU General Public License v3.0 or later. See [LICENSE](LICENSE).
