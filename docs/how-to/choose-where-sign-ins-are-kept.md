# Choose where sign-ins are kept

When someone signs in, SightPress remembers it until they sign out or stop using the website for a while. It also
remembers the short messages shown on the next page (such as "Your password has been changed") and the security
tokens of the forms they fill in. Together these are called **sessions**. This guide shows you where sessions are
kept, and how to choose another place.

!!! note "Who this guide is for"

    The person who runs SightPress: you change a setting on the server. People who use the websites see nothing,
    apart from being signed out once when you change it.

## The three places

| Setting    | Where sessions are kept                  | Choose it when                                                   |
|------------|------------------------------------------|------------------------------------------------------------------|
| `database` | In each website's own database (default) | Almost always. It works with one web server or many.             |
| `redis`    | In a Redis server                        | You already run Redis, or your websites are very busy.           |
| `files`    | In files on the web server               | You have exactly one web server and want nothing in the database. |

With more than one web server (for example behind a load balancer), use `database` or `redis`. With `files`, each
server only knows its own files, so people are signed out whenever another server answers.

Whichever you choose, SightPress never keeps the session codes from the browser's cookie as they are, only a scrambled
form of them (a hash), so someone who reads the database or Redis can't use them to sign in.

## See where sessions are kept now

1. In the `skeleton` folder, type:

    ```sh
    docker compose exec php bin/sightpress system:check
    ```

2. Look for the line that starts with `Sessions:`, for example
   `Sessions: Sign-ins are kept in each site's database.`

## Change where sessions are kept

Changing the place signs **everyone** out once, on every website: the new place doesn't know the old sessions.
Choose a quiet moment, and tell your website's admins first.

1. In the `skeleton` folder, check whether a file called `.env` exists. If it doesn't, create it by copying the
   example file:

    ```sh
    cp .env.example .env
    ```

2. Open `.env` in a text editor and find the line `SESSION_STORE=database`.
3. Change `database` to `files` or `redis`. For Redis, also follow the steps in the next section.
4. Save the file, then restart SightPress:

    ```sh
    docker compose up --detach --wait
    ```

5. Check the result with `system:check`, as above.

On a server without the skeleton's Docker setup, set `'session' => ['store' => 'redis']` (or `'files'`) in
`config/tenant.php` instead. The [tenant configuration reference](../reference/tenant-config.md) lists every
session setting.

## Use Redis

1. Start Redis with SightPress. In the `skeleton` folder, type:

    ```sh
    docker compose --profile redis up --detach --wait
    ```

    The first time, Docker downloads Redis, which can take a minute.

2. In `.env`, set `SESSION_STORE=redis` and remove the `#` at the start of this line:

    ```sh
    SESSION_REDIS_DSN=redis://redis:6379
    ```

3. Restart SightPress with the same command as in step 1.
4. Type `docker compose exec php bin/sightpress system:check`. You should see
   `Sessions: Sign-ins are kept in Redis at redis:6379.` and no error.

If SightPress can't reach Redis, pages show an error and `system:check` says
"SightPress could not reach the Redis server at …". Check that Redis runs
(`docker compose --profile redis ps`) and that `SESSION_REDIS_DSN` is right.

With your own Redis server, use its address, for example `redis://cache.example.org:6379`. For a password, write
`redis://:your-password@cache.example.org:6379`; for an encrypted connection, start with `rediss://`. PHP on your
server needs its redis extension: the skeleton's Docker image already has it.

## How long someone stays signed in

People are signed out after **2 hours** without using the website. To change this, open `config/tenant.php`, find
the `'session'` block and change the number after `'idle_timeout' =>`. It is in seconds, at least 60. For example,
for 30 minutes:

```php
'idle_timeout' => 1800,
```

People who ticked *Keep me signed in* stay signed in for longer on that device, whichever place you chose.

Ended sessions are removed once a minute by the skeleton's `scheduler` service (`bin/sightpress schedule:run`).
Redis removes them by itself.
