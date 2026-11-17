# Create a website

One SightPress installation can run many websites. Each website has its own pages, people, files and settings,
kept in its own database. This guide shows you how to create a website with the command line tool, and what each
part of the command means.

Before you start, follow [Getting started](../getting-started.md) up to step 6, so that SightPress runs and has a
secret key.

## Create the website, answering questions

The easiest way is to let the command ask you everything, one question at a time.

1. Open a terminal and go to the `skeleton` folder.
2. Type:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:create
    ```

3. Answer each question and press Enter. Each question says what to type. When it ends with "Press Enter for"
   and a value, pressing Enter without typing takes that value. When it lists choices, such as "Type person or
   organization", type one of them; capital letters don't matter.
4. For the database, the command first asks which engine to use. Press Enter for **SQLite**: it keeps the
   website in one file and needs nothing else. Press Enter again to keep the file in the `sites` folder. If you
   choose MySQL, MariaDB or PostgreSQL instead, the command asks for the server, port, database name, user name and
   password one by one, each with a usual value you can keep with Enter. The password isn't shown as you type it,
   and you type it twice so a typing mistake is caught.
5. For the main language, type a code such as `en-GB` or `pt-BR`. Capital letters don't matter and you can use
   `_` instead of `-`. The command says the language in words, for example "That is Brazilian Portuguese (pt-BR).
   Is that right?". Type `yes` or press Enter if it is, or `no` to type it again.
6. If an answer isn't right, for example an e-mail address without the part after `@`, the command says what is
   wrong and asks again. After 3 wrong answers in a row it stops and creates nothing; just start again.
7. At the end, the command lists all your answers, one per line, and asks "Is all of this right?". Press Enter
   to create the website. Type `no` to go through the questions again: your answers are kept, so press Enter for
   each one that was right and type only what you want to change.
8. You should see one sentence for each thing SightPress did, ending with: `The site "demo" is ready at
   https://localhost.` On your own computer, the address to open is <http://localhost:8080>: answer
   `http://localhost:8080` to the question "Address the site is opened at".
9. One of the sentences gives the **setup code** for the first admin, for example: "Give the setup code 388 641 to
   ada@example.org." Give the code to that person in a way you trust, such as in person or by phone. SightPress
   doesn't e-mail it.
10. The first admin goes to the address in that sentence (for example <http://localhost:8080/login/setup>), types
    their e-mail address and the code, and chooses a password. Step 8 of [Getting started](../getting-started.md)
    shows each screen.

## Create the website with one command

If you already know every answer, for example in a script, give them all on one line. The command then asks
nothing. This example creates a website called "Riverside Library" for an organisation:

```sh
docker compose run --rm --no-deps php bin/sightpress site:create demo --domain=localhost --db-engine=sqlite --url=http://localhost:8080 --owner-type=organization --locale=en-GB --name="Riverside Library" --admin-email=ada@example.org --admin-first-name=Ada --admin-last-name=Lovelace
```

If something is wrong, the command says what, and creates nothing.

## What each part means

| Part | What to give | Example |
| --- | --- | --- |
| `demo` | A short name for the website: lower-case letters, digits and hyphens. You use it in other commands. | `library` |
| `--domain` | The web addresses of the website, without `https://`, separated by commas. Each address belongs to one website only. On your own computer, use `localhost`. | `library.example.org,www.library.example.org` |
| `--db-engine` | The database engine: `sqlite`, `mysql`, `mariadb` or `postgresql`. On your own computer, SQLite is simplest. See [Start a database server](start-a-database.md) for the others. | `sqlite` |
| `--db-path` | For SQLite: the file. Optional: without it, a file named after the short name in the `sites` folder. | `library.sqlite` |
| `--db-host`, `--db-port`, `--db-name`, `--db-user`, `--db-password` | For the other engines: the server, its port, the database, and the user name and password. Without them, `localhost`, the engine's usual port, `sightpress_` and the short name, `sightpress` and no password. | `--db-host=db --db-user=library` |
| `--db` | Instead of the parts above: the whole database address in one go. | `sqlite:////app/sites/library.sqlite` |
| `--owner-type` | Who runs the website: `organization` or `person`, in capitals or not. The ready-made pages say "we" for an organisation and "I" for a person. | `person` |
| `--locale` | The website's main language. Capital letters don't matter, and `_` works as well as `-`. | `en-GB`, `pt_br`, `fr-FR` |
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
