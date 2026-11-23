# Update SightPress

This guide shows you how to move an installation to a newer version of SightPress, keeping every website, page,
account, menu and setting. It covers updates up to **0.4**.

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

4. Update the list of websites to the new format (from 0.4 on, it can hold each website's upload limits):

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:registry:upgrade
    ```

    You should see that the file was upgraded, or that there is nothing to do. The old file is kept as
    `config/sites.php.bak`.

5. Update the database tables of every website:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:migrate --all
    ```

    You should see one line per website saying how many database migrations ran. One website failing doesn't stop
    the others; run it again for that website once the problem is fixed.

6. Build the search index of each website, replacing `demo` with its short name:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:search:reindex demo
    ```

7. Start SightPress again with `docker compose up`. The **scheduler** starts with it now (see below).
8. Check: sign in to a website. The admin menu now has **Features**, **Analytics** and **Redirects**, and your pages,
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

## New in 0.4

Follow the same steps. Step 4 is needed for this update. After it:

- **The look of your websites stays the same.** The default theme now comes as its own package, which Composer
  installs. `composer update` also publishes the theme files and previews.
- **Your files stay where they are.** Nothing moves to the cloud unless you [set it up](keep-files-in-the-cloud.md).
- A **published page with a video without captions, or a recording without a transcript, stays published**, but the
  publish check asks for them the next time it is published. See
  [Add captions and transcripts](add-captions-and-transcripts.md).

`config/tenant.php` has new optional sections. Copy them from the new skeleton if Git can't merge them:

- `themes`: which installed themes websites may use, and the theme of new websites.
- `media.limits`: every website's upload limits and the most a website may set.
- `uploads.temp_path` and `uploads.max_chunked_bytes`: where large files wait while their parts arrive, and the largest
  file that can be sent in parts.

## If something goes wrong

**A website shows an error page after the update**
:   Its database tables are older than the code. Run `site:migrate` for it (step 5) and read the message it prints.

**An error says `config/sites.php` uses an older format version**
:   Run `site:registry:upgrade` (step 4).

**`composer update` fails**
:   Check that you are in the `skeleton` folder and that SightPress is stopped, then try again. Your websites are
    not changed until step 4.

**Search finds nothing**
:   Pages are added to the search index when they are published or changed. Run step 6 for that website to add
    the pages that were already there.
