# Change the ports SightPress uses

A port is a numbered door on your computer that a program listens on. Each address SightPress uses ends in a port
number, such as the `8080` in `http://localhost:8080`. If another program already uses one of these ports,
SightPress can't start and you see an error such as "port is already allocated". This guide shows you how to
choose different ports.

## The ports SightPress uses

| Setting              | Default | What it is for                              |
|----------------------|---------|---------------------------------------------|
| `HTTP_PORT`          | 8080    | SightPress itself                           |
| `MAILPIT_UI_PORT`    | 8025    | The test e-mail inbox                       |
| `MAILPIT_SMTP_PORT`  | 1025    | Where the test e-mail inbox receives e-mail |
| `MINIO_API_PORT`     | 9000    | File storage                                |
| `MINIO_CONSOLE_PORT` | 9001    | The file storage console                    |

The error message names the port that is taken, for example `127.0.0.1:8080`.

## Change a port

1. If SightPress is running, stop it. See [Run SightPress in the background](run-in-the-background.md#stop-sightpress),
   or press <kbd>Ctrl</kbd>+<kbd>C</kbd> in the terminal where it runs.
2. In the `skeleton` folder, check whether a file called `.env` exists. If it doesn't, create it by copying the
   example file:

    ```sh
    cp .env.example .env
    ```

    On Windows in PowerShell, `cp` works too.

3. Open `.env` in a text editor, such as Notepad on Windows or TextEdit on macOS. The name starts with a dot, so
   some file browsers hide it; you can open it from the editor's **Open** menu instead.
4. Find the line for the port you want to change, for example `HTTP_PORT=8080`, and change the number, for
   example to `HTTP_PORT=8081`. Pick a number between 1024 and 65535 that no other program uses.
5. Save the file.
6. Start SightPress again with `docker compose up`.
7. Use the new number in the address. With `HTTP_PORT=8081`, go to <http://localhost:8081>. You should see the
   **No site configured** page.

## Who can reach these ports

Every port listens only on your own computer (the address `127.0.0.1`, also called `localhost`). Other computers on
your network can't reach SightPress.
