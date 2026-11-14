# Try the command line tool

SightPress has a command line tool for tasks such as creating a site. This guide shows you how to run it and what
it can do today.

## List the commands

1. Open a terminal and go to the `skeleton` folder.
2. Type:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress list
    ```

3. You should see a short list of commands, including `list` and `site:create <name>`.

## Try to create a site

1. Type:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:create demo
    ```

2. You should see this message:

    ```text
    Can't create the site "demo" yet: sites are not available in this version of SightPress.
    ```

This is the expected result. Creating sites will work in a later version, and this guide will show you how.

!!! tip "Shortcut for macOS and Linux"

    If the `make` program is installed, `make site-create NAME=demo` does the same thing.
