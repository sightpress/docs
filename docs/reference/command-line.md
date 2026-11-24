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

| `system:check [--site=<slug>] [--probe-url=<address>] [--recommend]` | Lists the server's upload and input limits, the largest file of each kind that can be uploaded, and the settings to change. Ends with an error when something must be fixed. `--site` checks one website's limits and counts the uploads refused for it. `--probe-url` sends test requests to find the web server's real limit. `--recommend` prints the server values the limits need. Run it with `docker compose exec php` so it reads the same settings as the website. See [Choose upload limits](../how-to/choose-upload-limits.md). |
| `site:media:limits <slug> [--image=] [--video=] [--audio=] [--document=] [--pixels=] [--reset]` | Shows or changes the largest files a website accepts, up to the most the installation allows. See [Choose upload limits](../how-to/choose-upload-limits.md). |

To move a website to another database:

```sh
bin/sightpress site:disable demo
# copy the data to the new database with your database's own tools
bin/sightpress site:db:change demo --db=postgresql://user:password@db.internal:5432/demo
bin/sightpress site:enable demo
```

## Languages

| Command | What it does |
| --- | --- |
| `site:locale:add <slug> <locale>` | Adds a language to the website, for example `site:locale:add demo pt-PT`. Pages are then translated into it from each page's editor. |
| `site:locale:default <slug> <locale>` | Makes one of the website's languages the main one, whose pages have addresses without a language part. Warns that addresses change. |
| `site:locale:remove <slug> <locale> [--yes]` | Removes a language. Without `--yes` it only explains what would happen. Its translations are kept, unreachable, and come back if the language is added again. The main language can't be removed. |

See [Add a language and translate a page](../how-to/add-a-language.md).

## Things that happen at a set time

### schedule:run

```text
schedule:run
```

Does everything that is due on every website that is on: publishes and unpublishes scheduled pages, rolls up and
deletes old analytics, rebuilds search indexes that are out of date and removes sign-ups that were never confirmed.
It prints a line for each thing it did and a summary. Run it **once a minute**: the skeleton's `scheduler` service does
this in Docker; on a server, add a crontab line (see [Update SightPress](../how-to/update-sightpress.md#new-settings-in-03)).
Two runs never overlap: the second says "Another run is still going; this one stops." A missed run catches up on the
next one. See [Schedule a page](../how-to/schedule-a-page.md).

## Features

| Command | What it does |
| --- | --- |
| `site:module:list <slug>` | Lists the website's optional features (modules): id, name and whether each one is on. |
| `site:module:enable <slug> <module>` | Turns a feature on, after updating the website's database for it. Refused while a feature it needs is off. |
| `site:module:disable <slug> <module> [--purge-data]` | Turns a feature off; its data is kept. Refused while a feature that is on needs it. `--purge-data` also deletes its data, for good. |

Features the installation set in `config/tenant.php` (`modules.locked`) can't be changed. See
[Turn features on or off](../how-to/turn-features-on-or-off.md).

## Roles

| Command | What it does |
| --- | --- |
| `site:role:list <slug>` | Lists the website's roles: name, label, how many people have each one and its permissions. |
| `site:role:create <slug> <name> [--label=] [--permissions=a,b]` | Creates a role. The name is lower-case letters, digits and `_`; the label defaults to the name. |
| `site:role:grant <slug> <role> <permission>` | Gives a role one more permission. |
| `site:role:revoke <slug> <role> <permission>` | Takes a permission away from a role. |
| `site:role:delete <slug> <role>` | Deletes a role nobody has. |

The rules of the **Roles** screen apply: Admin and Member can't be changed, the sign-up role only gets permissions
that are safe on sign-up, and only permissions of features that are on can be given. Changes are written to the
audit log. See [Create a custom role](../how-to/create-a-custom-role.md).

## Site search

| Command | What it does |
| --- | --- |
| `site:search:reindex <slug> [--locale=<locale>]` | Builds the website's search index again from its published pages, in every language or one. |
| `site:search:status <slug>` | How many pages the search index holds in each language, when it last changed, and whether it is waiting to be rebuilt. |
| `site:search <slug> <words> [--locale=<locale>] [--members]` | Searches the website the way a visitor would (`--members`: as someone signed in) and lists the first results. |

They refuse a website with search turned off. See [Search your website](../how-to/search-your-website.md).

## Analytics

| Command | What it does |
| --- | --- |
| `site:analytics:report <slug> [--from=YYYY-MM-DD] [--to=YYYY-MM-DD] [--report=<name>\|all] [--format=table\|csv\|json]` | Shows the website's analytics, the last 30 days by default. Reports: `summary`, `daily`, `pages`, `sources`, `languages`, `devices`, `searches`, `searches-without-results`, `not-found`, `bots`. Warns when the hourly roll-up hasn't run for two hours. |
| `site:analytics:purge <slug> [--before=YYYY-MM-DD]` | Deletes the figures from before a day; without `--before`, those older than the website keeps them. |

They refuse a website with analytics turned off. See [See who visits your website](../how-to/see-who-visits-your-website.md).

## AI assistant

| Command | What it does |
| --- | --- |
| `site:assistant:set <slug> --provider=<id> --model=<model> [--base-url=<address>] [--key-stdin]` | Chooses the AI service (`anthropic`, `openai`, `mistral` or `openai_compatible`) and model. With `--key-stdin` it reads the API key from standard input, so it never appears in the shell history: `printf %s "$KEY" \| bin/sightpress site:assistant:set …`. |
| `site:assistant:test <slug>` | Sends a tiny request and says whether the service answered. |
| `site:assistant:usage <slug> [--month=YYYY-MM]` | Shows the tokens used this month (or another), per task, and the budget. |
| `site:assistant:disable <slug> [--remove-key]` | Turns the assistant off for the website, and removes its key with `--remove-key`. |
| `assistant:ask <slug> "<question>"` | Asks the assistant a question from the console. |

They refuse a website with the assistant turned off; turn it on with `site:module:enable <slug> assistant`. See
[Use the AI assistant](../how-to/use-the-ai-assistant.md).

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

## Files and cloud storage

See [Keep your files in the cloud](../how-to/keep-files-in-the-cloud.md) and
[Import files from a folder](../how-to/import-files.md).

| Command | What it does |
| --- | --- |
| `site:storage:provision <slug> --provider=aws\|cloudflare [--region=] [--bucket=] [--domain=] [--dry-run]` | Creates a bucket, its upload rules and a CDN at AWS or Cloudflare. `--dry-run` prints the plan and contacts nobody. The provider's keys come from the environment and are never saved. The website doesn't change. |
| `site:storage:s3 <slug> --bucket= --region= [--endpoint=] [--prefix=] [--path-style]` | Connects a website to an S3-compatible bucket after checking it works. The keys come from `SIGHTPRESS_S3_ACCESS_KEY` and `SIGHTPRESS_S3_SECRET_KEY`, and are stored encrypted. |
| `site:storage:migrate <slug> [--delete-source]` | Copies a website's files to its bucket, checks each one and switches the website. It can be run again if it stops. `--delete-source` deletes the local copies after the check, which can't be undone. |
| `site:storage:cdn <slug> [--url=] [--remove]` | Shows, sets or removes the `https://` CDN address that delivers a website's public files. Run `theme:publish-assets` after setting it. |
| `site:media:import <slug> <path>… [--recursive] [--publish] [--alt=] [--locale=] [--memory-limit=]` | Adds files of any size from the server to the media library, with every safety check. Ends with an error if any file was refused, after trying the others. |
| `site:media:uploads <slug> [--purge-expired]` | Lists uploads in progress. `--purge-expired` removes those idle for 24 hours and the records of those finished 7 days ago. The scheduler also does this. |

## Themes

See [Install and change a theme](../how-to/install-and-change-a-theme.md).

### theme:list

```text
theme:list
```

Lists the installed themes: id, name, version, whether websites may use it (or why it can't be used), whether new
websites start with it, and the websites that use it. Websites whose theme isn't installed any more are listed as
errors; they are shown in the default theme.

### theme:validate

```text
theme:validate [<theme>|<path>] [--axe]
```

Checks a theme the way it is checked before it is applied, and says what to fix:

- `theme.json` and the theme's manifest agree;
- the colour contrast passes in light and dark mode, with and without more contrast, chart colours included, against
  WCAG 2.2 AA, and the base font size is at least 16 px;
- the required templates are there, and the page shell includes SightPress's `<head>` partial;
- the theme ships no screenshot or preview output.

Give an installed theme's id or the folder of a theme. Without one, it checks every installed theme. `--axe` also
scans the theme's preview page with axe in light, dark and forced-colours mode; this needs Node.js and Playwright's
Chromium. It ends with an error when a check fails.

### theme:preview

```text
theme:preview <theme|path> [--out=build/theme-preview/<id>] [--locale=en-GB] [--screenshot]
```

Writes the theme's preview page to a folder that opens in any browser without a server or an internet connection: a
top bar, a band with the theme's name and a Download button, one block of every kind, and the footer. `--screenshot`
also writes `screenshot.png`, `screenshot-dark.png` and `page.png` (needs Node.js and Playwright's Chromium). Theme
authors can run the same commands without a project with `vendor/bin/sightpress-theme`.

### site:theme:apply

```text
site:theme:apply <site> <theme>
```

Applies an installed theme to a website. The theme must be allowed (`themes.allowed` in `config/tenant.php`) and pass
the checks of `theme:validate`. The website's activity log records the change.

### theme:publish-assets

```text
theme:publish-assets [--no-screenshot]
```

Writes the preview page of every installed theme to `public/themes/<theme>/<version>/preview/`, with its screenshots
where Node.js and Chromium are installed, for the **Appearance** screen. The project runs it after `composer install`
and `composer update`.

### cache:clear

```text
cache:clear
```

Empties the cache folder. Run it after installing or removing a theme or a module with Composer.

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
