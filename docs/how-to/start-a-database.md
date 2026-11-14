# Start a database server

SightPress keeps its data in a single file on disk (an SQLite database), so it doesn't need a separate database
server. If you want to try one, SightPress can start MySQL, MariaDB or PostgreSQL for you. This guide shows you how.

!!! note "Not used yet"

    At this stage SightPress starts the database server but doesn't store anything in it. You can check that it
    runs. Later versions will let you use it for your sites.

## Start SightPress with a database

1. If SightPress is running, stop it with `docker compose --profile "*" down`.
2. In the `skeleton` folder, type one of these commands:

    ```sh
    docker compose --profile mysql up
    ```

    For MariaDB, use `--profile mariadb`. For PostgreSQL, use `--profile postgres`.

3. Wait until the text stops scrolling. The first time, Docker downloads the database, which can take a few minutes.
   You should see lines starting with `mysql-1` (or `mariadb-1`, or `postgres-1`) among the others.

To start it in the background instead, add `--detach --wait` to the end of the command.

## Check that the database runs

1. Open a second terminal and go to the `skeleton` folder.
2. Type:

    ```sh
    docker compose --profile "*" ps
    ```

3. You should see a row for the database, such as `mysql`, with `(healthy)` in the **STATUS** column. If it says
   `(health: starting)`, wait a few seconds and type the command again.

When you start SightPress without `--profile`, no database server runs, and this table has no database row.

## The database settings

The database is created with these values. You can change them in the `.env` file before the first start (see
[Change the ports SightPress uses](change-the-ports.md#change-a-port) for how to create and edit `.env`).

| Setting            | Default           | What it is                                  |
|--------------------|-------------------|---------------------------------------------|
| `DB_NAME`          | `sightpress`      | The name of the database                    |
| `DB_USER`          | `sightpress`      | The user name                               |
| `DB_PASSWORD`      | `sightpress`      | The user's password                         |
| `DB_ROOT_PASSWORD` | `sightpress-root` | The administrator password (MySQL, MariaDB) |

The database server can only be reached by the other SightPress containers, not from your computer or your network.

## Stop the database

Type `docker compose --profile "*" down`. This stops SightPress and the database. The data in the database is kept
for next time.
