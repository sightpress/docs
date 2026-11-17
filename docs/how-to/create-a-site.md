# Create a website

One SightPress installation can run many websites. Each website has its own pages, people, files and settings,
kept in its own database. This guide shows you how to create a website with the command line tool, and what each
part of the command means.

Before you start, follow [Getting started](../getting-started.md) up to step 6, so that SightPress runs and has a
secret key.

## Create the website

1. Open a terminal and go to the `skeleton` folder.
2. Type the command on one line. This example creates a website called "Riverside Library" for an organisation:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:create demo --domain=localhost --db=sqlite:////app/sites/demo.sqlite --url=http://localhost:8080 --owner-type=organization --locale=en-GB --name="Riverside Library" --admin-email=ada@example.org --admin-first-name=Ada --admin-last-name=Lovelace
    ```

3. You should see one sentence for each thing SightPress did, ending with: `The site "demo" is ready at
   http://localhost:8080.`
4. One of the sentences gives the **setup code** for the first admin, for example: "Give the setup code 388 641 to
   ada@example.org." Give the code to that person in a way you trust, such as in person or by phone. SightPress
   doesn't e-mail it.
5. The first admin goes to the address in that sentence (for example <http://localhost:8080/login/setup>), types
   their e-mail address and the code, and chooses a password. Step 8 of [Getting started](../getting-started.md)
   shows each screen.

If you leave out a part the command needs, it asks you for it, one question at a time. If something is wrong, it
says what, and creates nothing.

## What each part means

| Part | What to give | Example |
| --- | --- | --- |
| `demo` | A short name for the website: lower-case letters, digits and hyphens. You use it in other commands. | `library` |
| `--domain` | The web addresses of the website, without `https://`, separated by commas. Each address belongs to one website only. On your own computer, use `localhost`. | `library.example.org,www.library.example.org` |
| `--db` | Where the website's database is. On your own computer, a file in the `sites` folder is simplest. See [Start a database server](start-a-database.md) for the others. | `sqlite:////app/sites/library.sqlite` |
| `--owner-type` | Who runs the website: `organization` or `person`. The ready-made pages say "we" for an organisation and "I" for a person. | `person` |
| `--locale` | The website's main language. | `en-GB`, `pt-PT`, `fr-FR` |
| `--name` | The name visitors see. Optional: without it, the short name is used. | `"Riverside Library"` |
| `--admin-email`, `--admin-first-name`, `--admin-last-name` | The first admin of the website. | `ada@example.org` |
| `--timezone` | Optional. The time zone for dates on the website. | `Europe/London` |
| `--url` | Optional. The address printed with the setup code. Without it, SightPress prints `https://` and the first domain. On your own computer, use `http://localhost:8080`. | `http://localhost:8080` |
| `--create-db` | Optional. Creates the database on a MySQL, MariaDB or PostgreSQL server if it doesn't exist yet. | |
| `--with-demo-content` | Optional. Adds demo pages, files and people for trying SightPress out. Don't use it for a real website. | |

Put quotation marks around anything with spaces, such as the website's name.

## What a new website has

Every new website starts ready to use:

- six published pages: a home page called "Welcome to" and the website's name, About, Contact (with a contact form
  whose messages go to the first admin), a privacy notice, terms of use and an accessibility statement;
- a main menu with About and Contact, and a footer menu with the other three pages;
- a logo, an icon and a picture for sharing on social media, made from the website's name;
- the roles admin, editor, contributor and member (see [Roles and permissions](../reference/roles-and-permissions.md)).

The ready-made pages are in English, whatever the website's language. Change or translate them in the admin panel.

## Try SightPress with demo content

Add `--with-demo-content` to the command to fill the new website with examples:

- five files in the media library: a picture, a short video with captions, a sound recording and a PDF;
- a page called "Every kind of block" at `/every-block`, with one block of every kind, and a page under it;
- three more people, each waiting for their setup code: Bea Okafor (editor), Cleo Martins (contributor) and Max
  Müller (member).

The command prints a setup code for each person. The `example.org` addresses can't receive e-mail, so give the codes
to whoever tries the website out.

## Give someone a new setup code

A setup code works once, for 7 days. To make a new one for an admin who hasn't chosen a password yet, type:

```sh
docker compose run --rm --no-deps php bin/sightpress site:user:create-admin demo --email=ada@example.org --first-name=Ada --last-name=Lovelace --url=http://localhost:8080
```

You should see a new setup code. The old one stops working. The same command adds another admin when you give a new
e-mail address. Someone who already has a password uses **Forgot your password?** on the sign-in page instead.

## Turn a website off, or remove it

| To | Type | Visitors see |
| --- | --- | --- |
| turn it off for a while | `bin/sightpress site:disable demo` | "temporarily unavailable" |
| turn it on again | `bin/sightpress site:enable demo` | the website |
| close it and keep its data | `bin/sightpress site:archive demo` | "gone" |
| delete it, softly | `bin/sightpress site:delete demo` | "not found"; the data is kept |
| remove a deleted website for good | `bin/sightpress site:purge demo` | — |

Start each command with `docker compose run --rm --no-deps php`, as above. `site:purge` keeps the database unless
you add `--drop-database`, which can't be undone. The [command line reference](../reference/command-line.md) lists
every command.
