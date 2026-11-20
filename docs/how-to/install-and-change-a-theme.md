# Install and change a theme

A **theme** decides how your websites look: colours, fonts, spacing and the layout of the pages. Every SightPress
installation has the **default theme**, and you can install more. Each website then uses one of the installed
themes, and you can change it at any time. Your pages and their content stay the same: only the look changes.

Installing a theme is done by whoever runs your SightPress installation, on the server. Choosing a theme for a
website is done by one of its **admins**.

!!! note "No theme is installed from the admin panel"

    For safety, themes are never uploaded through the website. They are installed on the server, the same way as
    SightPress itself.

## Install a theme

You need the name of the theme's package, for example `acme/theme-harbour`. Run these in your project folder. With Docker,
put `docker compose run --rm --no-deps php` in front of each one, as in
[Try the command line tool](try-the-command-line-tool.md):

1. Install the package: `composer require acme/theme-harbour`.
2. Let SightPress notice it: `bin/sightpress cache:clear`.
3. Check it: `bin/sightpress theme:validate acme-harbour`. You should see "The theme … passes". If it fails, the
   message says what is wrong, for example a colour pair that is too hard to read, with the colours and the ratio.
   A theme that fails can't be used until its author fixes it.
4. See every theme: `bin/sightpress theme:list`. Each line shows the theme, its version, whether websites may use it,
   and which websites use it.

## Choose which themes websites may use

By default, every installed theme can be used by every website, and new websites start with the default theme. To
change that, edit `config/tenant.php`:

```php
'themes' => [
    // Only these themes can be chosen; null means every installed theme.
    'allowed' => ['default', 'acme-harbour'],
    // The theme of new websites. It must be one of the allowed themes.
    'default' => 'acme-harbour',
],
```

If the default theme isn't one of the allowed themes, SightPress won't start and says why.

## Change the theme of a website

From the command line: `bin/sightpress site:theme:apply demo acme-harbour` (the website's short name, then the
theme). SightPress checks the theme again before it applies it, and the website's activity log records the change.

Open the website: it now has the new look. The other websites keep theirs.

## Remove a theme

1. Run `bin/sightpress theme:list` to see which websites use the theme.
2. Give those websites another theme first (see above).
3. Remove the package: `composer remove acme/theme-harbour`, then `bin/sightpress cache:clear`.

If a website still used the theme, nothing breaks: the website is shown in the default theme. Its admins see
"Your site is shown in the default theme" on the dashboard, with the reason, and the server log notes it too.
Choosing a theme for the website again makes the message go away.

## The admin panel keeps its look

The admin panel always uses the look of the default theme, whatever theme a website has. Admins can always find
their way around, and the admin panel keeps its contrast.
