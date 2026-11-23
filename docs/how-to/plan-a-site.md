# Plan your website

This guide helps you decide how to set up a SightPress website before you build it. Answer a few questions about what
the website needs. The guide then suggests the settings to choose and the content to write.

You don't need to be technical to do the planning. Some choices are made by whoever runs your SightPress installation,
and this guide says which ones.

!!! note "Which version this guide describes"

    This guide describes version 0.4. If you run 0.3, update first: it has no cloud storage, other themes, extra page
    layouts or per-website upload limits. See [Update SightPress](update-sightpress.md).

## Step 1: Answer these questions

Write down your answers. Each one points to a choice in the next steps.

| Question | Why it matters |
| --- | --- |
| Who is the website for, and what must they be able to do? | Decides your pages and calls to action. |
| Is it public, or only for members? | Decides who can read it and whether search engines can find it. |
| Who will write, check and publish pages? | Decides the roles you give people. |
| Which languages do you need? | Decides how much translation to plan for. |
| Will you publish large videos, audio or many files? | Decides file storage and upload limits. |
| Do you expect a few visitors or a lot? | Decides the database and whether you need a CDN. |
| Will you run one website or several? | Decides what to set for every website and what to set for each one. |
| Do you want visitor figures or an AI assistant? | Decides which optional features to turn on. |

## Step 2: Start from the closest kind of website

Pick the row closest to yours. You can change every choice later.

| Kind of website | Database | Files | Access | People | Features |
| --- | --- | --- | --- | --- | --- |
| Personal website or portfolio | SQLite | On the server | Public | One admin | Search off if there are few pages |
| Small business | SQLite | On the server | Public | One admin, perhaps an editor | Search and analytics |
| Charity or community group | SQLite or MariaDB | On the server | Public, with some members-only pages | Admin, editor, volunteer contributors | Search and analytics |
| Podcast or media project | MariaDB or PostgreSQL | Cloud storage and a CDN | Public | Admin and editors | Search and analytics |
| School, library or museum | MariaDB or PostgreSQL | Server, or cloud storage if there is a lot of media | Public | Web editor, staff as contributors | Search and analytics |
| Public service or health information | MariaDB or PostgreSQL | Server or cloud storage | Public | Editors approve every page | Search, and analytics if your policy allows it |
| Private club or staff website | SQLite or MariaDB | On the server | Members-only | Admin, editors, members | Search |
| Agency or group of websites | MariaDB or PostgreSQL | Cloud storage per website | Set per website | Separate people per website | Defaults set for all websites |

## Step 3: Choose your options

### Database

Each website has its own database. You choose it when you [create the website](create-a-site.md).

| Choose | When |
| --- | --- |
| **SQLite** | One small or medium website on one server. It is a single file and needs nothing else. |
| **MariaDB or MySQL** | Several people edit at once, the website is busy, or your host already provides it. |
| **PostgreSQL** | Your organisation already uses it, or the website is large and busy. |

You can move later, but SightPress doesn't copy the data for you. See `site:db:change` in the
[command line reference](../reference/command-line.md).

### File storage

| Choose | When |
| --- | --- |
| **On the server** (the default) | Pictures and documents, and a server with enough disk space. |
| **Cloud storage** | Lots of media, large files, or more than one server. Works with AWS, Cloudflare and other S3-compatible services. |
| **Cloud storage with a CDN** | Visitors in many countries, or popular audio and video. A CDN delivers public files from near each visitor. |

Whoever runs the installation sets up cloud storage with the command line tool. This example uses AWS in London:

```sh
bin/sightpress site:storage:provision demo --provider=aws --region=eu-west-2 --dry-run
bin/sightpress site:storage:provision demo --provider=aws --region=eu-west-2
bin/sightpress site:storage:s3 demo --bucket=your-bucket --region=eu-west-2
bin/sightpress site:storage:migrate demo
bin/sightpress site:storage:cdn demo --url=https://files.example.org
```

- The first command only shows the plan. It creates nothing and contacts nobody.
- The second needs your provider's keys in the server's environment: `SIGHTPRESS_AWS_ACCESS_KEY_ID` and
  `SIGHTPRESS_AWS_SECRET_ACCESS_KEY`. For Cloudflare, use `--provider=cloudflare` with
  `SIGHTPRESS_CLOUDFLARE_ACCOUNT_ID` and `SIGHTPRESS_CLOUDFLARE_API_TOKEN`.
- The third needs keys limited to this bucket, in `SIGHTPRESS_S3_ACCESS_KEY` and `SIGHTPRESS_S3_SECRET_KEY`. For a
  provider other than AWS, also give its address with `--endpoint`.
- The website keeps using its current files until `site:storage:migrate` has copied them.

### Upload limits

| Kind of website | Suggested setup |
| --- | --- |
| Brochure website, or shared hosting | The **Small or shared hosting** values |
| Most websites | The **Standard** values, which need no change |
| Podcast, video or photography | The **Lots of media** values, with higher audio and video limits for that website |

[Choose upload limits](choose-upload-limits.md) gives the exact values. To add many files at once, or very large ones,
use `site:media:import`, which applies the same safety checks.

### Who can read and join

- **Public** is the default. Search engines and AI services can find the pages.
- **Some pages for members** suits reports or resources for a group. See
  [Make a website or a page members-only](make-a-site-members-only.md).
- **The whole website for members** hides every page from search engines and AI services.
- **Open sign-up** suits communities. Keep new people as **Member**. See [Let people sign up](let-people-sign-up.md).
- **Invitation only** is the default, and suits most teams and private websites.

### Roles

Give each person the lowest role that lets them do their work. Try to have at least two people who can publish, so
that someone can cover absences.

| Role | Give it to |
| --- | --- |
| **Admin** | The one or two people responsible for the website. |
| **Editor** | People who check and publish pages. |
| **Contributor** | People who write, such as volunteers or staff, whose pages need approval. |
| **Member** | People who only read members-only pages. |

See [Roles and permissions](../reference/roles-and-permissions.md).

### Languages

Choose one main language, then add others only when someone can keep them up to date. A page without a translation is
better than an old one. The admin panel is available in British English, Spanish and European Portuguese, and a website
can publish in other languages. See [Add a language](add-a-language.md).

### Look and layout

Start with the default theme. You can install other themes and change the colours on the
**Appearance** screen, and SightPress checks that they stay readable. Choose a layout for each page:

| Layout | Good for |
| --- | --- |
| **Home page** | The first page, with a large introduction. |
| **Standard page** | Most pages. |
| **Landing** | A single campaign or call to action. |
| **Sidebar** | A section with several related pages. |
| **Full width** | Wide galleries and tables. |

See [Choose a page layout](choose-a-page-layout.md) and [Install and change a theme](install-and-change-a-theme.md).

### Optional features

| Feature | New websites | Turn it on when | Think about |
| --- | --- | --- | --- |
| [Site search](search-your-website.md) | On | The website has more than a handful of pages. | Leave private or duplicate pages out of the search. |
| [Analytics](see-who-visits-your-website.md) | On | You will act on the figures. | You can keep them for 1 to 25 months. |
| [AI assistant](use-the-ai-assistant.md) | Off | You want help drafting, and have an API key and a budget. | Check where the service processes data. A person checks every suggestion. |

If you run several websites, set the defaults for all of them in `config/tenant.php`. See
[Turn features on or off](turn-features-on-or-off.md).

### Search engines and AI services

In **Site settings**, under **Search & AI**, choose whether AI services may read your pages:

- **Allow all** suits most websites that want to be found.
- **AI search only** lets AI assistants link to you, and asks crawlers that train AI models to stay out.
- **Block all** asks every AI crawler to stay out.

See [Search and AI visibility](../reference/search-and-ai.md).

## Step 4: Plan your content

Every new website starts with a home page, About, Contact, a privacy notice, terms of use and an accessibility
statement. These are starting points. **Read and rewrite each one**, especially the privacy notice and terms, so they
are true for your organisation.

| Kind of website | Suggested pages | Most useful blocks |
| --- | --- | --- |
| Personal website | Home, About me, Projects, CV, Contact | Text, Image, File download |
| Portfolio | Home, Work, one page per project, About, Commissions | Gallery, Columns, Video, Contact form |
| Small business | Home, What we offer, Prices, About us, Find us, Contact | Call to action, Gallery, Contact form |
| Charity or community group | Home, What we do, Get involved, Donate or volunteer, Annual reports, Contact | Call to action, Video, File download |
| Podcast or media project | Home, Episodes, one page per episode, Guests, About | Audio with a transcript, File download |
| School, library or museum | Home, Visit us, Opening hours, What's on, Accessibility, Policies, Contact | Video, Audio, File download |
| Public service or health information | Home, one page per service, How to apply, Forms, Accessibility, Contact | Heading, Text, File download |
| Private club or staff website | Home, News, Guides, Documents, People, Contact | Text, File download |

For every website, plan to:

- write one clear purpose for each page, and put the most important thing first;
- use short headings in order, so that screen reader users can move through the page;
- write alternative text for every picture that carries meaning;
- add captions to videos and transcripts to audio before you publish;
- use link text that says where the link goes, never "click here";
- offer important PDF content as a page too;
- give every page its own title and description for search engines.

## Step 5: Check before you launch

- [ ] The name, logo, contact details and time zone are right in **Site settings**.
- [ ] Every ready-made page has been read and rewritten.
- [ ] The contact form reaches the right person.
- [ ] Each person has the right role, and at least two people can publish.
- [ ] Every page passes the accessibility checks.
- [ ] You have tried the main tasks with a keyboard alone, and with a screen reader if you can.
- [ ] `site:seo:check` reports no duplicate titles.
- [ ] Whoever runs the installation has a copy of the `sites` folder, the databases and `APP_SECRET`.
- [ ] Someone is responsible for updates, and has read [Update SightPress](update-sightpress.md).

## What to plan around today

SightPress doesn't do the following yet. If your website depends on one of them, plan another way or wait.

- **A guide for a public server.** Getting started covers your own computer. Have someone experienced set up HTTPS,
  backups and updates.
- **Several web servers.** Sign-in sessions are kept in files, so servers must share them, and share the upload folder.
- **Shops and payments, event booking, or a blog with an RSS feed.** Podcasts can publish episodes as pages, but
  SightPress doesn't produce a podcast feed.
- **Custom roles.** Use the four built-in roles.
- **Sign-in by phone or text message.** Sign-in uses an email address and a password.
- **Limiting sign-up to one email domain.** Use invitations instead.
- **Tools to download or delete someone's personal data.** If privacy law applies to you, such as UK or EU GDPR, plan
  how you will answer these requests.

## Where to go next

1. Follow [Getting started](../getting-started.md) to run SightPress.
2. [Create your website](create-a-site.md) with the choices you made.
3. [Write and publish your first page](write-and-publish-a-page.md).
