# Command line reference

SightPress has a command line tool, `bin/sightpress`, for the tasks that are done once per website or by whoever runs
the installation: creating websites, turning them on and off, and moving them. Everyday work, such as writing pages
and inviting people, happens in each website's admin panel.

The tool writes plain sentences, without tables or colours, so that its answers read well with a screen reader. It
never shows a database password.

## How to run it

With the `skeleton` running in Docker, start every command from the `skeleton` folder with
`docker compose run --rm --no-deps php`, for example:

```sh
docker compose run --rm --no-deps php bin/sightpress list
```

You should see every command with one line about what it does. For the details of one command, type
`bin/sightpress help` and its name, for example `bin/sightpress help site:create`.

Most commands need `APP_SECRET` to be set (see step 6 of [Getting started](../getting-started.md)).

## How commands ask questions

If you leave out something a command needs, it asks for it, one question at a time, and also asks the optional
questions that have a usual answer. Every command asks the same way:

- a question that ends with "Press Enter for" and a value takes that value when you just press Enter;
- a question that lists choices accepts one of them, in capitals or not;
- a password is not shown as you type it, and is never printed; you type it twice, and if the two are not the same
  you are asked for it again;
- some answers are read back in words for you to confirm, for example a language code;
- a wrong answer is explained and asked again; after 3 wrong answers in a row the command stops and changes
  nothing;
- at the end, the command lists your answers and asks "Is all of this right?". Answer `no` to go through the
  questions again, with your answers kept: press Enter for each one that was right.

If you give everything on the command line, nothing is asked. Add `--no-interaction` (or `-n`) to never be asked:
then a missing or wrong value stops the command with a sentence that says what is wrong.

`<slug>` below is the short name of a website, the one you gave `site:create`, such as `demo`.

## Create websites and admins

### site:create

```text
site:create [<slug>] [--domain=] [--db= | --db-engine= --db-path= | --db-host= --db-port= --db-name= --db-user= --db-password=]
            [--owner-type=] [--locale=] [--admin-email=] [--admin-first-name=] [--admin-last-name=]
            [--name=] [--timezone=] [--url=] [--create-db] [--with-demo-content] [--without=]
```

Creates a website: its database tables, its roles, its first admin, six starter pages, its menus and its logo, icon
and share image. It prints the first admin's setup code. Nothing is created if something is wrong.
[Create a website](../how-to/create-a-site.md) explains every option. `--without=` names optional features (module
ids, separated by commas) the website starts with off.

### site:user:create-admin

```text
site:user:create-admin <slug> --email= --first-name= --last-name= [--url=]
```

Creates an admin on a website and prints the setup code they choose their password with. For an admin who hasn't
chosen a password yet, it prints a new code, and the old one stops working. Someone who can already sign in is
refused: they use "Forgot your password?" instead.

## Turn websites on and off

| Command | What it does | Visitors see |
| --- | --- | --- |
| `site:enable <slug>` | Turns a website on, so it answers again. Also brings back a deleted website. | the website |
| `site:disable <slug>` | Turns a website off for a while. | "temporarily unavailable" (503) |
| `site:archive <slug>` | Archives a website. Its data is kept. | "gone" (410) |
| `site:delete <slug>` | Deletes a website, softly: it stops answering and its data is kept until `site:purge`. | "not found" (404) |
| `site:purge <slug> [--drop-database]` | Removes a deleted website for good. Its database is kept unless you add `--drop-database`, which can't be undone. | — |

Every change is written to the installation's log.

## Look after websites

| Command | What it does |
| --- | --- |
| `site:list` | Lists the websites: status, domains, language, time zone and database (engine, host and name only). |
| `site:migrate <slug>` or `site:migrate --all` | Updates a website's database tables after an update of SightPress. `--all` updates every website that isn't deleted; one failure doesn't stop the others. |
| `site:db:change <slug> [--db= or the --db-… parts] [--migrate]` | Points a **disabled** website at another database, asked for in parts like in `site:create`. **Nothing is copied**: move the data first. The new database must be up to date, or add `--migrate`. If anything fails, the website keeps its old database. |
| `site:seo:check <slug>` | Checks what search engines will find: titles longer than 60 characters, missing or too long descriptions, pages left out of search and why. Ends with an error when two pages have the same title. See [Search and AI visibility](search-and-ai.md). |

To move a website to another database:

```sh
bin/sightpress site:disable demo
# copy the data to the new database with your database's own tools
bin/sightpress site:db:change demo --db=postgresql://user:password@db.internal:5432/demo
bin/sightpress site:enable demo
```

## Features

| Command | What it does |
| --- | --- |
| `site:module:list <slug>` | Lists the website's optional features (modules): id, name and whether each one is on. |
| `site:module:enable <slug> <module>` | Turns a feature on, after updating the website's database for it. Refused while a feature it needs is off. |
| `site:module:disable <slug> <module> [--purge-data]` | Turns a feature off; its data is kept. Refused while a feature that is on needs it. `--purge-data` also deletes its data, for good. |

Features the installation set in `config/tenant.php` (`modules.locked`) can't be changed. See
[Turn features on or off](../how-to/turn-features-on-or-off.md).

## Site search

| Command | What it does |
| --- | --- |
| `site:search:reindex <slug> [--locale=<locale>]` | Builds the website's search index again from its published pages, in every language or one. |
| `site:search:status <slug>` | How many pages the search index holds in each language, when it last changed, and whether it is waiting to be rebuilt. |
| `site:search <slug> <words> [--locale=<locale>] [--members]` | Searches the website the way a visitor would (`--members`: as someone signed in) and lists the first results. |

They refuse a website with search turned off. See [Search your website](../how-to/search-your-website.md).

## Branding

| Command | What it does |
| --- | --- |
| `site:branding:set <slug> [--logo=] [--logo-alt=] [--icon=] [--share-image=] [--share-image-alt=]` | Uses picture files for the logo (SVG, PNG, JPEG or WebP, from 1:1 to 8:1 wide), the icon (a square PNG, JPEG or WebP of at least 512 × 512 pixels) or the share image (PNG, JPEG or WebP of 1200 × 630 pixels, or larger with the same proportions). The `-alt` options say what the picture shows, for screen readers; the default is the website's name. |
| `site:branding:reset <slug> [--logo] [--icon] [--share-image]` | Goes back to the generated logo, icon or share image; all three unless you name some. |
| `site:branding:regenerate <slug>` | Makes the generated pictures again from the website's name, for example after renaming it. Uploaded pictures stay. |

Admins can do the same in the admin panel, under **Branding**.

## The site registry

The installation keeps its list of websites in `config/sites.php`, with each database address encrypted with
`APP_SECRET`.

| Command | What it does |
| --- | --- |
| `site:registry:check` | Checks the file, and that every database address can be read with this `APP_SECRET`, and says what to fix. |
| `site:registry:upgrade` | Upgrades the file to the format this version of SightPress reads. |

Each change to the file keeps the previous version as `config/sites.php.bak`.

## Themes

### theme:validate

```text
theme:validate [<path>]
```

Checks a theme's colour contrast, in light and dark mode and with and without more contrast, and its base font size,
against WCAG 2.2 AA, and says what to fix. Without a path, it checks the active theme. It ends with an error when a
check fails.

## Database addresses

`--db` takes the address (DSN) of a database:

| Engine | Example |
| --- | --- |
| SQLite (a file) | `sqlite:////app/sites/demo.sqlite` |
| MySQL | `mysql://user:password@mysql:3306/demo` |
| MariaDB | `mariadb://user:password@mariadb:3306/demo` |
| PostgreSQL | `postgresql://user:password@postgres:5432/demo` |

A SQLite file and its folder are created when missing. On the other engines the database must exist, unless you add
`--create-db` to `site:create` and the database user is allowed to create databases. To start a database server on
your computer, see [Start a database server](../how-to/start-a-database.md).
