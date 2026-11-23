# Choose upload limits

This guide helps whoever runs a SightPress installation decide how large a file each website accepts. A brochure
website might keep small pictures and no large videos, while a podcast needs long audio files.

## The two kinds of limit

- **Website limits** say how large a picture, video, audio file or document can be. Each website can have its own.
- **Server limits** say how much the server takes in one upload. They are the same for every website. If you need to
  change them, see [Fix upload size limits](fix-upload-size-limits.md).

People can upload the **smaller** of the two. Website admins see the result in **Site settings** and next to each
upload field, but they can't change it.

## Recommended values

| Kind of installation       | `UPLOAD_MAX_SIZE` | `PHP_MEMORY_LIMIT` | Website limits                                                    |
|----------------------------|-------------------|--------------------|-------------------------------------------------------------------|
| **Standard** (the default) | `64M`             | `256M`             | Pictures 20 MB, documents 50 MB, audio 200 MB, video 1 GB         |
| **Small or shared hosting** | `32M`            | `256M`             | Pictures 20 MB, documents 20 MB, audio and video 32 MB            |
| **Lots of media**          | As large as your largest file | `512M` | Larger audio and video, and pictures up to 50 million pixels |

Until SightPress can send files in parts, a file can't be larger than `UPLOAD_MAX_SIZE`.

## See a website's limits

Type this in the `skeleton` folder, replacing `demo` with the website's short name:

```sh
docker compose exec php bin/sightpress site:media:limits demo
```

You should see each kind of file, its limit, and whether it is **this site**'s own value or the **install default**.

## Change a website's limits

1. Lowering a limit needs no preparation. To **raise** one above the default, first set the most any website may
   use in `config/tenant.php`:

    ```php
    'media' => [
        'limits' => [
            'max' => ['audio' => '500M'],
        ],
    ],
    ```

2. Set the website's limit:

    ```sh
    docker compose exec php bin/sightpress site:media:limits demo --audio=500M
    ```

    You should see **The upload limits of "demo" have been saved.** A value above the maximum is refused, and the
    message says what the maximum is.

3. Find out which server values that limit needs:

    ```sh
    docker compose exec php bin/sightpress system:check --site=demo --recommend
    ```

    It prints lines ready to copy into `.env`, or into the settings of PHP, nginx, Apache or Caddy. Leave out
    `--site=demo` to get the values that suit all your websites.

4. If the values differ from yours, change them and restart, as in
   [Fix upload size limits](fix-upload-size-limits.md).

To go back to the installation's limits:

```sh
docker compose exec php bin/sightpress site:media:limits demo --reset
```

## Check what the web server really accepts

SightPress can't read the web server's limit, so it can test it by sending empty files of growing sizes. Nothing is
stored. In the standard Docker setup:

```sh
docker compose exec php bin/sightpress system:check --probe-url=http://nginx
```

You should see a line such as **Probe: the web server accepts 65 MB and refuses 66 MB.** If that is less than
SightPress was told, the command says which setting to correct.

## If something goes wrong

**"The audio limit of 600 MB is above the most this install allows"**
:   Raise `max` in `config/tenant.php` (step 1), or choose a lower value.

**The new limit doesn't show on the upload form**
:   The server's limit is probably lower. Run the command in step 3 and change the values it prints.

**"The probe got the answer 404"**
:   The address doesn't reach this SightPress. In Docker, use `http://nginx` and run the command with
    `docker compose exec php`.
