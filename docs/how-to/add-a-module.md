# Add a module

A **module** is an extra package that gives SightPress new features, for example events, a shop or a booking form.
Once it is installed, each website's admins decide whether to use it: a module is a feature like the others on the
*Features* screen (see [Turn features on or off](turn-features-on-or-off.md)).

Installing and removing modules is done by whoever runs your SightPress installation, on the server. Turning a module
on or off for a website is done by one of its **admins**.

!!! note "No module is installed from the admin panel"

    For safety, modules are never uploaded through the website. They are installed on the server with Composer, the
    same way as SightPress itself and its themes.

## Before you start

- You need the name of the module's package, for example `acme/events`.
- The package must say which versions of `sightpress/module-interface` it works with. SightPress checks that when it
  starts. A module that doesn't fit your version is listed as unavailable, with the reason, and the websites keep
  working.

## Install a module

Run these in your project folder. With Docker, put `docker compose run --rm --no-deps php` in front of each one, as in
[Try the command line tool](try-the-command-line-tool.md).

1. Install the package: `composer require acme/events`.
2. Check that SightPress found it: `bin/sightpress site:module:list demo`, with the short name of one of your
   websites instead of `demo`. You should see a line for the module, for example `acme.events`, that says it is
   **off**.

A module you install is **off for every website that already exists**: nothing changes on them until an admin turns it
on. Websites you create afterwards start with the module's own choice, on or off, unless `config/tenant.php` says
otherwise (see [Choose for every website](#choose-for-every-website)).

## Turn it on for a website

An admin of the website does it:

1. Sign in to the admin panel and choose **Features** in the admin menu.
2. Find the module and choose **Turn on** followed by its name.
3. The message says it is on. SightPress has prepared the website's database for it, and the roles that should use it
   can now do so: admins always, and the roles the module names (for example editors).
4. Check: the module's screens are in the admin menu, and its public pages, if it has any, are on the website and in
   its sitemap.

Or, in a terminal: `bin/sightpress site:module:enable demo acme.events`.

To give the module's permissions to other roles, see [Create a custom role](create-a-custom-role.md): a module's
permissions are listed on the role screens with the others while the module is on.

## Turn it off

On the *Features* screen, choose **Turn off** followed by its name, and confirm. Its pages and screens are gone and
its data is kept, so turning it on again brings everything back. You can also delete its data for good: see
[Turn features on or off](turn-features-on-or-off.md#delete-a-features-data).

Or: `bin/sightpress site:module:disable demo acme.events` (add `--purge-data` to delete its data too).

## Remove a module

1. Turn it off on every website that uses it (see above). Delete its data first if you won't need it again: once the
   package is removed, SightPress can't do that for you.
2. Remove the package: `composer remove acme/events`.
3. Check: `bin/sightpress site:module:list demo` no longer shows it.

If you remove a module that a website still had **on**, that website keeps working without it, and its data stays in
the website's database. Its admins see the module under **Features that were removed**, which says to ask whoever runs
the server to install it again to bring it back.

## When a module can't be used

SightPress never stops because of a module package. When one can't be used, it is left out: the *Features* screen
lists it under **Features that can’t be used** and `site:module:list` marks it "unavailable", both with the reason,
for example:

- it works with another version of `sightpress/module-interface` than the one installed (update the module, or wait
  for a version that fits);
- it needs another module that isn't installed or can't be used (install that one too);
- its class is missing or fails to start (a broken package: reinstall it, or ask its author).

The reason is also written to the log once.

## Choose for every website

In `config/tenant.php`, the `modules` section works for modules as for every other feature: `defaults` chooses what
new websites start with, and `locked` turns a module on or off for every website. See
[Turn features on or off](turn-features-on-or-off.md#for-whoever-runs-the-installation) and the
[tenant configuration reference](../reference/tenant-config.md).

To build a module of your own, the [module interface reference](../reference/module-interface.md) lists everything a
module can use.
