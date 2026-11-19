# Getting started

This guide shows you how to run SightPress on your own computer, create your first website and sign in to its admin
panel, so that you can try it out. You don't need to know how to program. It takes about 30 minutes, most of which is
waiting for downloads.

!!! note "What you can try today"

    You can create a website with ready-made pages, write, schedule and publish pages, translate them into other
    languages, upload pictures and other files, invite people or let them sign up, make pages members-only, search
    the website, read its visitor statistics, turn features on or off, and set up an optional AI assistant with your
    own key. Running SightPress on a public server is not covered yet.

## What you need

- A computer running Windows 10 or 11, macOS, or Linux.
- About 5 GB of free disk space.
- An internet connection.
- Permission to install programs on the computer.

You type some commands in a terminal. A terminal is a window where you type instructions instead of clicking:

- On Windows, open **Windows Terminal** or **PowerShell** from the Start menu.
- On macOS, open **Terminal** from Applications, then Utilities.
- On Linux, open your terminal app.

Type or paste each command, then press <kbd>Enter</kbd>.

## Step 1: install Docker

Docker runs SightPress and everything it needs inside separate, self-contained boxes called containers. You don't
have to install PHP, a web server or a database yourself.

1. Install Docker:
    - On Windows or macOS, install [Docker Desktop](https://docs.docker.com/desktop/). On Windows, accept the
      option to use WSL 2 if the installer asks.
    - On Linux, install [Docker Engine and the Docker Compose plugin](https://docs.docker.com/engine/install/).
2. On Windows or macOS, open Docker Desktop and wait until it says that Docker is running.
3. In the terminal, type:

    ```sh
    docker compose version
    ```

    You should see a line such as `Docker Compose version v5.3.0`. The number may be different. If you see
    "command not found" or a similar message, Docker is not installed correctly, or Docker Desktop is not running.

## Step 2: install Git

Git downloads the SightPress files from GitHub, where they are published.

1. Install Git from the [Git downloads page](https://git-scm.com/downloads). On macOS, you can instead type
   `git --version` in the terminal and accept the offer to install the developer tools.
2. In the terminal, type:

    ```sh
    git --version
    ```

    You should see a line such as `git version 2.51.0`.

## Step 3: download SightPress

You only download the `skeleton`, the project you start. The other parts of SightPress are fetched for you
automatically the first time you start it, in step 4.

1. Make a folder for SightPress and go into it:

    ```sh
    mkdir sightpress
    cd sightpress
    ```

2. Download the skeleton:

    ```sh
    git clone https://github.com/sightpress/skeleton.git
    ```

    You should see a few lines that end with `done.`

3. Go into the `skeleton` folder:

    ```sh
    cd skeleton
    ```

!!! tip "Only on Linux"

    The SightPress container writes files as user number 1000. If your Linux user has a different number, files
    can end up owned by the wrong user. Type `id -u`. If it shows anything other than `1000`, copy the settings
    file with `cp .env.example .env`, open `.env` in a text editor, and set `UID` and `GID` to the numbers that
    `id -u` and `id -g` show. Windows and macOS users can skip this.

## Step 4: start SightPress

1. In the `skeleton` folder, type:

    ```sh
    docker compose up
    ```

2. Wait. The first time, Docker downloads and builds everything it needs. This can take 5 to 15 minutes. You see
   many lines of text scroll past. Later starts take a few seconds.
3. SightPress is ready when the text stops scrolling and you see lines starting with `php-1` that end with
   `ready to handle connections`.

Leave this terminal window open. SightPress stops when you close it.

## Step 5: open SightPress in your browser

1. Open your web browser and go to <http://localhost:8080>.
2. You should see a plain page with the heading **No site configured** and this text: "SightPress is running, but
   no site is set up for this address yet. If you look after this server, create a site and link this address to
   it." The browser tab is called "No site configured - SightPress".

This is the right result. It means SightPress is running, but has no website yet. You create one in the next steps.

If the browser shows **502 Bad Gateway**, SightPress is still finishing its first start. Wait a minute, then
reload the page.

## Step 6: make a settings file with a secret key

SightPress keeps its settings in a file called `.env` in the `skeleton` folder. One setting, the secret key, protects
the address of each website's database. You make it once.

1. Open a second terminal window and go to the `skeleton` folder, as in step 3. Leave the first window running.
2. Copy the example settings file:

    ```sh
    cp .env.example .env
    ```

3. Make a secret key:

    ```sh
    docker compose run --rm --no-deps php php -r 'echo bin2hex(random_bytes(32)), PHP_EOL;'
    ```

    You should see one line of 64 letters and digits, such as `3f9a…`. Yours will be different.

4. Open the `.env` file in a text editor, such as Notepad on Windows or TextEdit on macOS. Find the line
   `APP_SECRET=` and paste your key straight after the `=` sign, with no spaces. Save the file.
5. Keep a copy of the key somewhere safe, such as a password manager. Without it, SightPress can't open your
   websites' databases.
6. Go back to the first terminal window and press <kbd>Ctrl</kbd>+<kbd>C</kbd> to stop SightPress. Then start it
   again with `docker compose up`, so that it reads the new settings.

The copied file also turns on the test e-mail inbox, which catches every e-mail SightPress sends. See
[Open the test e-mail inbox](how-to/open-the-test-email-inbox.md).

## Step 7: create your first website

1. In the second terminal window, type this command on one line. Change the e-mail address and the names to your
   own if you like. (You can also type just `docker compose run --rm --no-deps php bin/sightpress site:create` and
   answer one question at a time: see [Create a website](how-to/create-a-site.md).)

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:create demo --domain=localhost --db=sqlite:////app/sites/demo.sqlite --url=http://localhost:8080 --owner-type=organization --locale=en-GB --name="Riverside Library" --admin-email=ada@example.org --admin-first-name=Ada --admin-last-name=Lovelace
    ```

2. After a few seconds, you should see lines like these:

    ```text
    Getting the SQLite file /app/sites/demo.sqlite ready…
    Ran 25 database migrations.
    Created the account of the first admin, Ada Lovelace (ada@example.org).
    Set up the roles admin, editor, contributor and member.
    Gave the first admin the admin role.
    Give the setup code 388 641 to ada@example.org. They go to http://localhost:8080/login/setup, type their email address and the code, and choose their password. The code works once, for 7 days.
    Made the site's icons, wordmark and share image from its name; replace them with site:branding:set.
    Published the starter pages in British English: Welcome to Riverside Library, About us, Contact us, Privacy notice, Terms of use, Accessibility statement.
    Added 5 starter pages to the main and footer menus.
    The site "demo" is ready at http://localhost:8080.
    ```

3. Write down the **setup code**, the six digits after "Give the setup code". Yours will be different. You need it
   in the next step.

[Create a website](how-to/create-a-site.md) explains every part of the command.

## Step 8: choose your password and sign in

1. In your browser, go to <http://localhost:8080/login/setup>. You should see the heading **Set up your account**.
2. In **Email address**, type the address you used, for example `ada@example.org`.
3. In **6-digit code**, type the setup code. You can type it with or without the space.
4. Choose **Continue**. You should see the heading **Choose your password**.
5. In **New password**, type a password of at least 12 characters. A few unrelated words make a strong password
   that is easy to remember.
6. Choose **Set password**. You should see the sign-in page with the message "Your password has been set. Sign in
   with it now."
7. Type your e-mail address and your new password, then choose **Sign in**.
8. You should see the admin panel's **Dashboard**. The browser tab says "Dashboard - Admin - Riverside Library".
   The admin menu has links such as Dashboard, Pages, Analytics, Redirects, Media, Menus, People, Invite someone,
   Site settings, Features and Audit log.

SightPress never sends links to sign in or to set a password. It always gives you a code to type on the website
instead. See [Codes we send you](how-to/codes-we-send-you.md).

## Step 9: look at your website

1. Go to <http://localhost:8080>.
2. You should see the home page, with the heading **Welcome to Riverside Library**, a main menu with About us and
   Contact us, and a footer menu with the privacy notice, the terms of use and the accessibility statement.

Every new website starts with these six pages, already published. Change them in the admin panel under **Pages**.
See [Write and publish a page](how-to/write-and-publish-a-page.md).

The pages follow your computer's light or dark setting, and every page works with a keyboard alone and with a
screen reader. While SightPress runs on your computer, every page asks search engines not to list it.

## Step 10: stop SightPress

1. Go back to the terminal window where SightPress is running.
2. Press <kbd>Ctrl</kbd>+<kbd>C</kbd>. You should see lines that end with `Stopped`.

To start SightPress again later, go to the `skeleton` folder and type `docker compose up` again. Your website and
your account are kept.

## If something goes wrong

**"port is already allocated" or "address already in use"**
:   Another program on your computer is using one of the ports SightPress needs. See
    [Change the ports SightPress uses](how-to/change-the-ports.md).

**"Cannot connect to the Docker daemon"**
:   Docker is not running. On Windows or macOS, open Docker Desktop and wait until it says that Docker is running.
    On Linux, type `sudo systemctl start docker`.

**The page doesn't load at all**
:   Check that the terminal still shows SightPress running, and that the address is exactly
    `http://localhost:8080`, with `http`, not `https`.

**"APP_SECRET is not set" when you create the site**
:   The secret key is missing from `.env`. Go back to [step 6](#step-6-make-a-settings-file-with-a-secret-key).

**The website shows an error page after you created it**
:   SightPress was not restarted after you added the secret key. Stop it with <kbd>Ctrl</kbd>+<kbd>C</kbd> and
    start it again with `docker compose up`.

**"This code doesn't work" on the setup page**
:   Check the e-mail address and the six digits. A setup code works once, for 7 days. To get a new one, see
    [Create a website](how-to/create-a-site.md#give-someone-a-new-setup-code).

## What to do next

The [how-to guides](how-to/index.md) show you how to write and publish pages, add a language and translate a page,
schedule, move or restore pages, make a site members-only, let people sign up, turn features on or off, search the
website, read its analytics, set up the AI assistant and run SightPress in the background. To move an older
installation to this version, see [Update SightPress](how-to/update-sightpress.md).

To start your website in more than one language, give `site:create` a list in `--locale`, for example
`--locale=en-GB,pt-PT`: every starter page is then written in each language. See
[Add a language and translate a page](how-to/add-a-language.md) to add one later, and
[Turn features on or off](how-to/turn-features-on-or-off.md) to choose the optional features.

You can help improve SightPress without being a developer: see [Report a problem](how-to/report-a-problem.md) and
the other guides under *Help improve SightPress*.

The reference pages explain [roles](reference/roles-and-permissions.md), [blocks](reference/blocks.md),
[search and AI visibility](reference/search-and-ai.md) and the [command line tool](reference/command-line.md).
