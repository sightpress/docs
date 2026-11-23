# Open the file storage console

SightPress comes with a file storage program called MinIO, which has its own web console. This guide shows you how
to sign in to it.

1. Start SightPress (see [Getting started](../getting-started.md)).
2. In your web browser, go to <http://localhost:9001>.
3. You should see the MinIO sign-in page.
4. Sign in with the user name `sightpress` and the password `sightpress-secret`.
5. You should see the MinIO console. There are no files yet.

By default, each website keeps its uploaded files, such as pictures and documents, in its own folder inside the
`sites` folder of the `skeleton`, so MinIO stays empty. From the next release, the `site:storage:s3` command can move
a website's storage to MinIO or another S3-compatible service.

If you changed `MINIO_ROOT_USER`, `MINIO_ROOT_PASSWORD` or `MINIO_CONSOLE_PORT` in your `.env` file, use your
values instead. These settings only take effect the first time the storage starts.
