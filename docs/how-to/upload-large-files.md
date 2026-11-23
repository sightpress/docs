# Upload large files

SightPress sends large files in small parts. If your connection drops, or you close the page, you can carry on from
where the upload stopped instead of starting again.

If your website keeps its files in **cloud storage**, large files go straight from your browser to the storage, which
is faster and doesn't load your server. If the storage doesn't allow that, SightPress sends the file through your
server instead and says so. You don't need to do anything different.

## Upload a large file

1. In the admin, open **Media**.
2. Under **Upload a file**, choose your file. The hint under the field says the largest file of each kind.
3. Choose **Upload**.

A progress bar shows how far the upload has gone. Screen readers hear the progress every 10%. When it finishes,
SightPress checks the file and opens its page, so you can describe it.

## Pause, resume or cancel

While a large file is uploading, three buttons work by keyboard or mouse:

- **Pause** stops sending. Focus moves to **Resume**.
- **Resume** carries on from where it stopped.
- **Cancel upload** stops and deletes what was sent so far.

## If the upload stops

- **The connection dropped.** SightPress tries again by itself. If it still can't connect, it pauses and asks you to
  choose **Resume**.
- **You closed or reloaded the page.** Open **Media**, choose **the same file** and choose **Upload**. It carries on
  from where it stopped, on the same computer and browser, for up to 24 hours.

## If the file is refused

SightPress checks what a file really is once it has arrived, whatever its name says. A file of a type it doesn't
accept is deleted, and **Media** explains why.

## For whoever runs the server

- To see which way large files travel, and why, open **Site settings**, then **File storage**. For files to go straight
  to cloud storage, its CORS rules must allow `PUT` from your website's address and expose `ETag`. `site:storage:provision`
  sets this up on AWS and Cloudflare.
- Files that go through the server wait in `var/uploads`. To use another folder, set `uploads.temp_path` in `config/tenant.php`. It
  can't be inside `public`.
- The largest file that can be sent in parts is 5 GB. To change it, set `uploads.max_chunked_bytes`. Each website's own
  limits still apply, see [Choose upload limits](choose-upload-limits.md).
- If your website runs on **several servers**, they must share the upload folder. Otherwise people see a message saying
  the server doesn't have the file's earlier parts.
- Abandoned uploads are removed after 24 hours by the [scheduler](run-in-the-background.md). To see the uploads in
  progress, or remove abandoned ones now:

    ```sh
    docker compose exec php bin/sightpress site:media:uploads demo
    docker compose exec php bin/sightpress site:media:uploads demo --purge-expired
    ```

Without JavaScript, a file is sent in one piece, so the lower server limit applies. See
[Fix upload size limits](fix-upload-size-limits.md).
