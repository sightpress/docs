# Run SightPress in the background

In [Getting started](../getting-started.md), SightPress runs in a terminal window and stops when you close it.
This guide shows you how to run it in the background instead.

## Start SightPress

1. Open a terminal and go to the `skeleton` folder, for example:

    ```sh
    cd sightpress/skeleton
    ```

2. Type:

    ```sh
    docker compose up --detach --wait
    ```

3. Wait until the command finishes. You should see one line per container (`php`, `nginx`, `mailpit` and
   `minio`), each ending with `Healthy`. You get your terminal back.
4. Go to <http://localhost:8080>. You should see the **No site configured** page.

    The very first time, SightPress keeps installing for a minute or two after the command finishes, and the
    browser shows **502 Bad Gateway**. Wait a minute, then reload the page.

You can now close the terminal. SightPress keeps running until you stop it or restart your computer.

## See what is running

Type:

```sh
docker compose ps
```

You should see a table with one row for each container. The **STATUS** column says `Up` and how long the
container has been running.

## Read the messages SightPress writes

Type:

```sh
docker compose logs --follow
```

You see the messages from every container, and new ones as they arrive. Press <kbd>Ctrl</kbd>+<kbd>C</kbd> to
stop reading. SightPress keeps running.

## Stop SightPress

Type:

```sh
docker compose --profile "*" down
```

You should see lines ending with `Removed`. The `--profile "*"` part also stops a database server if you started
one. Your settings and stored files are kept for next time.

!!! tip "Shortcuts for macOS and Linux"

    If the `make` program is installed, you can type `make up` to start SightPress in the background and
    `make down` to stop it. Type `make` on its own to list every shortcut.
