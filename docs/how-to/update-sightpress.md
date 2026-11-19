# Update SightPress

This guide shows you how to move an installation to a newer version of SightPress, keeping every website, page,
account, menu and setting. It is written for **0.1 to 0.3**, the first update with new database tables.

!!! warning "Make a copy first"

    Before you update, make a copy of the `skeleton` folder, including the `sites` folder and the `.env` file, and of
    every website's database if it isn't an SQLite file in `sites`. Without the `APP_SECRET` from `.env`, SightPress
    can't open the websites' databases.

## Update

Type these commands in the `skeleton` folder.

1. Stop SightPress: press <kbd>Ctrl</kbd>+<kbd>C</kbd> in the window where it runs, or type `docker compose down`.
2. Get the new skeleton:

    ```sh
    git pull
    ```

    If Git says that your changes to `config/tenant.php` or another file would be overwritten, see
    [New settings in 0.3](#new-settings-in-03) and copy the new parts in by hand.

3. Get the new version of SightPress itself:

    ```sh
    docker compose run --rm --no-deps php composer update
    ```

4. Update the database tables of every website:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:migrate --all
    ```

    You should see one line per website saying how many database migrations ran. One website failing doesn't stop
    the others; run it again for that website once the problem is fixed.

5. Build the search index of each website, replacing `demo` with its short name:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:search:reindex demo
    ```

6. Start SightPress again with `docker compose up`. The **scheduler** starts with it now (see below).
7. Check: sign in to a website. The admin menu now has **Features**, **Analytics** and **Redirects**, and your pages,
   people and menus are as they were.

## What changes for your websites

- **Site search** and **Analytics** are **on** for every website after the update. Turn them off on the
  [Features](turn-features-on-or-off.md) screen if you don't want them.
- The **AI assistant** is **off**. Nothing is sent to any AI service until an admin sets it up; see
  [Use the AI assistant](use-the-ai-assistant.md).
- **Sign-up** is off and every website is still readable by everyone, as before.
- The *Privacy notice* starter page of websites made before the update doesn't describe analytics. Add a section
  about it yourself; [See who visits your website](see-who-visits-your-website.md) explains what is counted.

## New settings in 0.3

- `.env` has a block of **upload limits** (`UPLOAD_MAX_SIZE`, `INPUT_MAX_SIZE` and others). Copy the block from
  `.env.example`; without it, the recommended values are used. See [Fix upload size limits](fix-upload-size-limits.md).
- `config/tenant.php` has two new sections: `modules`, which sets the features of every website (see
  [Turn features on or off](turn-features-on-or-off.md#for-whoever-runs-the-installation)), and `uploads`, which
  tells SightPress the web server's limit.
- `docker-compose.yml` has a **scheduler** service, which publishes scheduled pages and keeps the analytics up to
  date. It runs `bin/sightpress schedule:run` once a minute. On a server without Docker, add this line to the
  crontab of the user that runs SightPress (`crontab -e`), with the path of your project:

    ```text
    * * * * * cd /var/www/my-site && php bin/sightpress schedule:run --no-interaction >> var/log/schedule.log 2>&1
    ```

    Run it in one place only per installation.

## If something goes wrong

**A website shows an error page after the update**
:   Its database tables are older than the code. Run `site:migrate` for it (step 4) and read the message it prints.

**`composer update` fails**
:   Check that you are in the `skeleton` folder and that SightPress is stopped, then try again. Your websites are
    not changed until step 4.

**Search finds nothing**
:   Pages are added to the search index when they are published or changed. Run step 5 for that website to add
    the pages that were already there.
