# Search and AI visibility

Every public page of a SightPress website is ready for search engines and for AI search engines and assistants, with
nothing to set up. This page explains what SightPress does for each page, and the settings that change it.

## Which pages are shown

Search engines and AI readers are only ever offered pages that are:

- **published** (drafts and pages waiting for review never are);
- **public**;
- **not marked** "Ask search engines and AI search engines not to list this page";
- **not a copy** of a page on another website (a page with an "Address of the original").

While SightPress runs anywhere other than production (the `APP_ENV` setting, for example on your own computer), every
page asks search engines not to list it, and `robots.txt` keeps crawlers out of the whole website.

## What every page has

- **A title**: the page's "Title in search results" if it has one, otherwise its title followed by the website's
  name, such as "About us - Riverside Library".
- **A description**: the page's "Description for search engines", otherwise its summary, otherwise its first words.
  Longer text is cut at a whole word, at 160 characters. Social networks get the same description.
- **One address** (the canonical address). An address ending in `/` moves permanently to the one without it, and the
  website's other domains move permanently to its first domain.
- **Structured data** that search engines read: the page, where it sits in the website, who runs the website (from
  the site settings) and the pictures, videos and recordings on it.
- **"Last updated"** under the content, in the reader's time zone.
- **A Markdown version** for AI readers: add `.md` to the page's address, such as `/about.md`, or `/index.md` for the
  home page. It has the title, the summary, every block as plain text (pictures with their alternative text, videos
  and recordings with their transcripts, files with their type and size) and the page's address and date. Contact
  forms are left out.

## Files for search engines and AI readers

| Address | What it is |
| --- | --- |
| `/sitemap.xml` | Every page search engines may list, in every language, with the date it last changed. |
| `/robots.txt` | Keeps crawlers out of the admin panel, accounts and sign-in pages, points them to the sitemap, and names the AI crawlers the website keeps out. |
| `/llms.txt` | The website's name and summary, then a link to the Markdown version of every page that may be listed, with its description. |
| `/llms-full.txt` | The same pages in full, as Markdown, up to 500 KB. If it stops early, it says how many pages were left out. |

The files are made again each time a page is created, saved, moved, published or unpublished.

## Settings

Admins change these under **Site settings**, in the **Search & AI** section.

### AI crawlers

Whether AI services may read the public pages, to answer people's questions with a link back (AI search and
assistants) and to train their models.

| Choice | What `robots.txt` asks |
| --- | --- |
| **Allow all** (the default) | Nothing extra: every crawler may read the public pages. |
| **AI search only** | Crawlers that train AI models, such as GPTBot, ClaudeBot, Google-Extended and CCBot, stay out. AI search and assistants may read the pages and link to them. |
| **Block all** | Every AI crawler stays out: those that train models, and AI search and assistant crawlers such as OAI-SearchBot, ChatGPT-User, Claude-SearchBot and PerplexityBot. |

Search engines are never affected. `robots.txt` is a request that well-behaved crawlers follow, not a lock.

### Search console codes

To prove to a search engine that the website is yours, it gives you a code. Paste only the code (the part in
`content="…"`) into **Google Search Console verification code**, **Bing Webmaster Tools verification code** or
**Yandex Webmaster verification code**. SightPress adds it to the home page.

### IndexNow

**Tell search engines at once when a page is published or unpublished (IndexNow)** is off by default. When it is on,
the website tells the search engines that use IndexNow, such as Bing and Yandex, each time a page is published or
unpublished.

### Who runs the site

The **Who runs the site** section (a description, full name, contact details and profiles on other websites) goes
into the structured data, and the description also goes at the top of `llms.txt`. Leave a field empty to keep it
private.

## Settings on each page

In the page editor's **Search & AI** section:

| Field | What it does |
| --- | --- |
| **Summary** | One or two sentences, shown in lists of pages, used as the description when there is none, and read by AI search engines. |
| **Title in search results** | Replaces the title in search results only. |
| **Description for search engines** | Shown under the title in search results. About 160 characters are shown. |
| **Picture shown when the page is shared** | A picture from the media library. Leave it empty to use the website's share image. Shown only when the media library has pictures. |
| **Ask search engines and AI search engines not to list this page** | Keeps the page out of search results, the sitemap, `llms.txt` and `llms-full.txt`. |
| **Address of the original** | Only for a copy of a page on another website: its full address. The copy then points search engines to the original, and is left out of the sitemap. |

Before publishing, the page's checks suggest a description and a summary when they are missing, and warn about a
description longer than 160 characters or a title another page already has.

## Check a website from the command line

```sh
docker compose run --rm --no-deps php bin/sightpress site:seo:check demo
```

It lists titles longer than 60 characters, missing or too long descriptions, pages left out of search and why, and
whether SightPress runs in production. Two pages with the same title count as a problem, and the command ends with an
error.
