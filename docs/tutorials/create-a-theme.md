# Create a theme

In this tutorial you make a SightPress theme of your own, "Harbour". You start from the theme template, change its
colours, override a template, add a layout, check and preview it, install it on a website, and publish it. You need
some experience with the command line, PHP and CSS. The template already has everything a theme needs, and the checks
tell you in words when something isn't right.

You need:

- PHP 8.5 and Composer 2;
- a SightPress project to try the theme in (see [Getting started](../getting-started.md));
- for screenshots only: Node.js and Playwright's Chromium (`npx playwright install chromium`).

## 1. Create your theme from the template

```sh
composer create-project sightpress/theme-template harbour
cd harbour
composer check
```

You should see each check pass, ending with:

```text
The theme "My theme" passes: 172 colour pairs checked in light and dark mode, with and without more contrast; the base font size is at least 16 px; the required templates are there; it ships no screenshot.
```

On GitHub, you can instead open the [theme template](https://github.com/sightpress/theme-template) and choose
**Use this template**, then clone your new repository and run `composer install`.

## 2. Make it yours

A theme has an id: lower-case words joined by hyphens, starting with your vendor name. Ours is `acme-harbour`.

1. In `composer.json`, change `name` to `acme/theme-harbour`, and write your own `description` and `homepage`.
2. In `src/MyTheme.php`, change `ID` to `acme-harbour`. Change the name to `Harbour` and write a description of one
   or two sentences. Fill in `author`.
3. In `theme.json`, change `name` to `acme-harbour` and `label` to `Harbour`.
4. Write `previewDescription`: what the top of your theme's preview page looks like, for people who can't see its
   screenshot. Describe the look, not the file. For example: "A dark blue top bar with the site name in white, a pale
   blue band with a large heading and a yellow Download button."

The template's README lists the rest: renaming the class and its namespace when you want to.

## 3. Change the colours

Colours are design tokens in `theme.json`, one set for light mode and one for dark mode. Try a light blue for buttons
and highlights in light mode:

```json
"primary": "#7fb3ff",
```

Run the checks:

```sh
composer check
```

They fail, and say why:

```text
The theme "Harbour" fails with 3 problems:
1. In light mode, text "on-primary" (#ffffff) on "primary" (#7fb3ff) has a contrast of 2.14:1; it needs at least 4.50:1.
2. In light mode, interface colour "primary" (#7fb3ff) on "background" (#ffffff) has a contrast of 2.14:1; it needs at least 3.00:1.
3. In light mode, interface colour "primary" (#7fb3ff) on "surface" (#f3f4f6) has a contrast of 1.94:1; it needs at least 3.00:1.
```

White text on a light blue is too hard to read, and the light blue itself is hard to see on the white page. Use a darker blue, and run the checks again:

```json
"primary": "#0b4f8a",
```

Every colour pair in the `contrast` section of `theme.json` is checked in light and dark mode, with and without "more
contrast": text needs 4.5:1, borders and other interface colours 3:1. Chart colours are always checked.

## 4. Override a template

A theme's templates are in `templates/`. Change the footer of every page: in `templates/master.html.twig`, replace the
`footer` block with:

```twig
{% block footer %}<p>{{ site_name }}, made with the Harbour theme.</p>{% endblock %}
```

A template you don't have comes from the default theme, so you only keep the ones you change. Keep the shell's skip
link, its landmarks and `{% include '@seo/head.html.twig' %}`: the checks refuse a shell without that line, because
it holds every page's description, canonical address and structured data.

## 5. Add a layout

Editors choose a layout for each page. Besides the required ones (`default`, `home`, `search`), a theme can have the
standard layouts `landing`, `sidebar` and `full-width`. Add `sidebar`:

1. In `src/MyTheme.php`, list it in the manifest:

    ```php
    layouts: [...LayoutDefinition::required(), new LayoutDefinition(LayoutDefinition::SIDEBAR, 'Sidebar')],
    ```

2. Create `templates/layouts/sidebar.html.twig`:

    ```twig
    {% extends 'master.html.twig' %}
    {% block title %}{{ title }}{% endblock %}
    {% block main %}
    {%- set side = block('aside') is defined ? block('aside')|trim : '' %}
    <div class="container">
        <h1>{{ title }}</h1>
        {%- if lead is defined and lead is not empty %}<p class="lead">{{ lead }}</p>{% endif %}
        {% block content %}{% endblock %}
        {%- if side %}
        <aside aria-labelledby="page-aside-title">{{ side|raw }}</aside>
        {%- endif %}
    </div>
    {% endblock %}
    {% block aside %}{% endblock %}
    ```

SightPress fills the `aside` block with "In this section", the pages around this one, starting with a heading whose
id is `page-aside-title`. When there's nothing to show, the page has no `<aside>`. A page whose layout a theme doesn't
have is shown in the standard layout, and keeps its own for the next theme that has it.

## 6. Check it

```sh
composer check
```

Besides the code style, static analysis and tests, this runs `theme:validate`, the checks SightPress runs before it
applies a theme. It also refuses a screenshot or a `build/` folder that would end up in your package.

## 7. Preview it

```sh
composer preview
```

Open `build/theme-preview/acme-harbour/index.html` in any browser. It works offline and needs no PHP. It is the same
page for every theme, so you can compare them:

- a top bar with links to each part of the page;
- a band with your theme's name, description and a **Download** button;
- one block of every kind;
- the footer.

Go through it with the keyboard alone, at 200% and 400% zoom, in dark mode and with a contrast theme.

With Node.js and Chromium installed, `composer preview -- --screenshot` also writes `screenshot.png`,
`screenshot-dark.png` and `page.png` in the same folder. Use them for your README. Never commit them: your theme's
releases get them attached automatically.

## 8. Try it on a website

In your SightPress project, add the theme as a path repository, so changes show up as you make them:

```sh
composer config repositories.harbour '{"type": "path", "url": "../harbour", "options": {"symlink": true}}'
composer require acme/theme-harbour:@dev
bin/sightpress cache:clear
bin/sightpress theme:validate acme-harbour
```

Then apply it, from the command line:

```sh
bin/sightpress site:theme:apply demo acme-harbour
```

or in the admin panel, under **Appearance**: choose **Use Harbour**. If pages use a layout your theme doesn't have,
SightPress lists them first. To see the screenshot in the theme picker, run `bin/sightpress theme:publish-assets`.

## 9. Open a pull request

Push your theme to GitHub and open a pull request with a change. The template's workflows add two things:

- **a download**: the preview page, its screenshots and an accessibility report, in one zip;
- **a comment** on the pull request with the download link, your `previewDescription` and the accessibility result in
  light mode, dark mode and forced colours.

A reviewer can judge the change without installing anything (see [Review a theme change](../how-to/review-a-theme-change.md)).

## 10. Publish it

1. Merge your pull requests. Release Please keeps a release pull request open; merging it tags the release and
   attaches the preview and the screenshots to it.
2. Submit your repository on [packagist.org](https://packagist.org/packages/submit) and turn on its GitHub hook (or
   add the `PACKAGIST_USERNAME` and `PACKAGIST_API_TOKEN` secrets the release workflow uses).
3. Anyone running SightPress can now install your theme with `composer require acme/theme-harbour`.

That's it: you have made, checked, previewed, applied and published a theme.
