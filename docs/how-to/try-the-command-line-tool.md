# Try the command line tool

SightPress has a command line tool for tasks such as creating a website. This guide shows you how to run it.

## List the commands

1. Open a terminal and go to the `skeleton` folder.
2. Type:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress list
    ```

3. You should see a list of commands, each with one line about what it does, such as
   `site:create   Creates a site: its database, its first admin and its roles.`

## List your websites

1. Type:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:list
    ```

2. You should see one sentence for each website, with its status, its addresses, its language and its database. If
   you haven't created a website yet, it says "There are no sites yet. Create one with site:create."

## Get help with one command

1. Type `help` and the name of the command, for example:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress help site:create
    ```

2. You should see what the command needs and what each option means.

To create a website, see [Create a website](create-a-site.md). The [command line reference](../reference/command-line.md)
lists every command.

!!! tip "Shortcut for macOS and Linux"

    If the `make` program is installed, `make site-create NAME=demo` runs `site:create demo` and asks for the rest.
