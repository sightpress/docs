# Getting started

This guide shows you how to run SightPress on your own computer, so that you can try it out. You don't need to know
how to program. It takes about 20 minutes, most of which is waiting for downloads.

!!! note "What you can try today"

    SightPress is in early development. At this stage it starts up and shows a page that says no site is set up
    yet. You can't create a website yet. These guides will grow as features are built.

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

SightPress comes in three parts, which must sit side by side in the same folder:

- `skeleton`, the project you start;
- `core`, which holds the features;
- `module-interface`, which describes how add-ons talk to SightPress.

1. Make a folder for SightPress and go into it:

    ```sh
    mkdir sightpress
    cd sightpress
    ```

2. Download the three parts:

    ```sh
    git clone https://github.com/sightpress/skeleton.git
    git clone https://github.com/sightpress/core.git
    git clone https://github.com/sightpress/module-interface.git
    ```

    After each command, you should see a few lines that end with `done.`

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

This is the right result. It means SightPress is running. Every address shows this page until you create a site,
which a later version will let you do.

The page follows your computer's light or dark setting. It has one heading and one main area, so a screen reader
can find the message straight away.

If the browser shows **502 Bad Gateway**, SightPress is still finishing its first start. Wait a minute, then
reload the page.

## Step 6: stop SightPress

1. Go back to the terminal window where SightPress is running.
2. Press <kbd>Ctrl</kbd>+<kbd>C</kbd>. You should see lines that end with `Stopped`.

To start SightPress again later, go to the `skeleton` folder and type `docker compose up` again.

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

## What to do next

The [how-to guides](how-to/index.md) show you how to run SightPress in the background, start a database, open the
test e-mail inbox and file storage, and try the command line tool.
