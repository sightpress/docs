# Fix upload size limits

How large a file you can upload depends on two things: what the website accepts for each kind of file, and what the
server accepts in one go. This guide explains the warning admins see when the server's settings are too low, and
how to change them.

## What you see

- Next to the upload field in **Media**, everyone sees the largest file they can upload, for example "Largest file
  you can upload: pictures 20 MB, documents 50 MB, audio files 64 MB and videos 64 MB."
- If you choose a file that is too large, the browser refuses it before sending it. The reason appears under the
  field, which gets focus, so screen readers read it out.
- If something too large is sent anyway, you see a page called **This is too large to send**. It says how much the
  server accepts and has a **Go back to the form** link.
- Admins see a warning called **Your server’s settings stop some uploads** on the dashboard and in **Media**. Each
  point names a server setting and the value it needs. It also says how many uploads the web server refused in the
  last 7 days. Editors and contributors don't see it.
- To put the warning away, choose **Hide until this changes**. It comes back by itself if the result changes.

## Change the limits on your own computer

With SightPress running in Docker, the limits are one group of lines in the `.env` file of the `skeleton` folder.
PHP and the web server both read them, so they always match.

1. If there is no `.env` file in the `skeleton` folder yet, create it by copying the example:

    ```sh
    cp .env.example .env
    ```

2. Open `.env` in a text editor and find the lines that start with `UPLOAD_MAX_SIZE`.
3. Change the values you need:

    | Setting            | Recommended | What it is                                                      |
    |--------------------|-------------|-----------------------------------------------------------------|
    | `UPLOAD_MAX_SIZE`  | `64M`       | The largest file in one upload                                  |
    | `INPUT_MAX_SIZE`   | `8M`        | The largest form without files, such as a long page             |
    | `INPUT_MAX_VARS`   | `5000`      | How many fields one form can have; lower values can lose content |
    | `MAX_FILE_UPLOADS` | `20`        | How many files one upload can hold                               |
    | `PHP_MEMORY_LIMIT` | `256M`      | Memory for one request; `512M` for pictures over 25 million pixels |
    | `UPLOAD_TIMEOUT`   | `300`       | Seconds a slow upload may take                                   |

    Sizes are whole megabytes or gigabytes, such as `64M` or `1G`. On a small site or shared hosting, `32M` is
    enough for `UPLOAD_MAX_SIZE`.

4. Save the file and restart SightPress:

    ```sh
    docker compose up -d
    ```

5. Check the result:

    ```sh
    docker compose exec php bin/sightpress system:check
    ```

    You should see the limits, the largest file of each kind, and **No problems found.** If something is still
    wrong, it is listed with the setting to change.

If a value is not valid, for example `UPLOAD_MAX_SIZE=lots`, SightPress doesn't start and the terminal says which
value to fix.

## On a hosting provider's server

If someone else runs your server, send them the warning's text. It names the settings, for example
`upload_max_filesize`, `post_max_size` or the web server's limit (`client_max_body_size` for nginx), and the value
each needs. Ask them to tell you when it is done, then open the dashboard again: the warning is gone once the
settings are right.

## Choose how large each website's files can be

To give one website larger or smaller limits than the others, see [Choose upload limits](choose-upload-limits.md).

## Large audio files and videos

The website accepts audio files up to 200 MB and videos up to 1 GB, but for now one upload can't be larger than
`UPLOAD_MAX_SIZE`. A later version will send large files in parts, so they can be larger than the server's limit.
`system:check` mentions this as a note, not a problem.
