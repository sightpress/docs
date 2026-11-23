# Keep your files in the cloud

By default, a website keeps its pictures, videos and other files on your server. You can move them to **cloud
storage** instead, and let a **CDN** (content delivery network) deliver public files from near each visitor. Both are
optional. Nothing is sent to another company unless you set it up.

This is done by whoever runs your SightPress installation, with the command line tool. Start each command with
`docker compose run --rm --no-deps php`, as in [Try the command line tool](try-the-command-line-tool.md). The examples
use a website called `demo`.

!!! warning "Before you start"

    - **It may cost money.** AWS and Cloudflare charge for storage and traffic. Check their prices first.
    - **Your files move to another company.** Files can contain personal data, so say so in your privacy notice. If
      the location is outside your country, that may be an international transfer of personal data.
    - **Keep a copy** of the `sites` folder until you have checked the website.

## Which service to use

SightPress works with any S3-compatible service, such as AWS S3, Cloudflare R2, DigitalOcean Spaces or MinIO.

SightPress can create what you need at **AWS** or **Cloudflare**. For another service, create a private bucket in its
control panel and go to [Connect the bucket](#connect-the-bucket).

## Create the bucket and CDN

1. Put your provider's keys in the environment for this one run. SightPress never saves them.
    - AWS: `SIGHTPRESS_AWS_ACCESS_KEY_ID` and `SIGHTPRESS_AWS_SECRET_ACCESS_KEY`.
    - Cloudflare: `SIGHTPRESS_CLOUDFLARE_ACCOUNT_ID` and `SIGHTPRESS_CLOUDFLARE_API_TOKEN`. To use your own domain with
      `--domain`, also set `SIGHTPRESS_CLOUDFLARE_ZONE_ID`.
2. Read the plan. This contacts nobody and changes nothing:

    ```sh
    bin/sightpress site:storage:provision demo --provider=aws --dry-run
    ```

    You should see each item that will be created and its location.
3. Run the same command without `--dry-run` to create them. It is safe to run it twice.

**What is created.** At AWS: a private bucket, a CloudFront CDN that alone can read it, permission for browsers to
upload to it from your website, and rules that clear unfinished uploads. At Cloudflare: an R2 bucket with the same
upload permission and rules, and a public address if you use `--domain`.

**Where it is created.** The location follows the website's time zone:

| Website's time zone | AWS | Cloudflare R2 |
| --- | --- | --- |
| United Kingdom | London (`eu-west-2`) | EU, because R2 has no UK location |
| EU, EEA or Switzerland | Frankfurt (`eu-central-1`) | EU |
| Anywhere else | You choose with `--region` | You choose with `--region` (`eu`, `us` or `default`) |

Creating these doesn't change your website yet.

To let the website's admins do this under **Site settings**, then **File storage**, set
`SIGHTPRESS_PROVISION_FROM_ADMIN=1` and the provider's keys in the web server's environment. They can then create
things that cost money in that account, so only do this if you trust them with it.

## Connect the bucket

1. Create keys limited to this bucket in your provider's control panel, and set them as `SIGHTPRESS_S3_ACCESS_KEY` and
   `SIGHTPRESS_S3_SECRET_KEY`. SightPress stores them encrypted.
2. Connect the bucket. For a provider other than AWS, add its address with `--endpoint`. MinIO also needs
   `--path-style`.

    ```sh
    bin/sightpress site:storage:s3 demo --bucket=my-bucket --region=eu-west-2
    ```

    You should see that the bucket works and has been saved. The website still uses its local files.

## Move the files

```sh
bin/sightpress site:storage:migrate demo
```

SightPress copies each file, checks it arrived intact, and then switches the website. If it stops, run it again and it
goes on from where it was. Your local copies stay until you are sure. To delete them after the check, run the command
again with `--delete-source`. This can't be undone.

Open the website and check that its pictures and files appear.

## Turn on the CDN

1. Set the CDN to fetch from your website, forward its host name, include the query string in its cache key, and not
   forward cookies.
2. Turn it on, with an address that starts with `https://`, and publish the theme's files:

    ```sh
    bin/sightpress site:storage:cdn demo --url=https://cdn.example.org
    bin/sightpress theme:publish-assets
    ```

To stop using it, run `bin/sightpress site:storage:cdn demo --remove`.

Private files stay protected. Unpublished files and every file of a members-only website use signed addresses that
stop working after a short time. After you unpublish a file or make a website members-only, a copy on the CDN can last
up to five minutes.

## Check it

Website admins can see where files are kept under **Site settings**, then **File storage**: the provider, the bucket,
the region and the CDN address. Keys are never shown.

## If something goes wrong

**The command says the bucket can't be reached or the keys are wrong**
:   Nothing was changed. Check the bucket name, the region, the address and the keys, then run it again.

**Large uploads say they're using the server instead**
:   The bucket doesn't allow uploads from your website's address. Files still upload, through your server. Add your
    website's address to the bucket's CORS rules, allowing `PUT` and exposing `ETag`.

**A website is deleted**
:   `site:purge` also removes that website's folder in the bucket.
