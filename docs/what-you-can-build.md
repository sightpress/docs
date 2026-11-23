# What you can build with SightPress

SightPress is a content management system (CMS): a program that lets you build and run websites without writing code.
You write pages in an admin panel, and SightPress turns them into a website that everyone can use, including people
who use a screen reader, a keyboard alone or screen magnification.

This page explains what SightPress can do today, then gives you ideas for websites you could build with it.

## What you can do today

SightPress is still at an early stage, and this page follows the newest documentation. Features marked **next
release** are finished but aren't in version 0.3 yet. SightPress already gives you the following.

### Run many websites from one installation

One installation of SightPress can run as many websites as you need. Each website has its own pages, people, files,
menus and settings, and its own main language. A person can be an editor on one website and a contributor on another.
See [Create a website](how-to/create-a-site.md).

### Write pages with blocks

A page is a list of **blocks**, such as a heading, some text or a picture. There are ten kinds:

| Block | What it is for |
| --- | --- |
| Heading | Starting a part of the page. |
| Text | Paragraphs, lists, links, quotes and more. |
| Image | One picture, with its alternative text and a caption. |
| Gallery | A set of up to 50 pictures. |
| Video | A video with captions and a transcript. It never starts by itself. |
| Audio | A sound recording, such as a podcast episode, with a transcript. |
| File download | A file such as a PDF, with its type and size shown in the link. |
| Call to action | A button that leads somewhere, such as "Book a visit". |
| Columns | Two to four columns side by side on wide screens. |
| Contact form | A form visitors use to send you a message, without a puzzle to solve. |

Every block is described in [Blocks](reference/blocks.md).

### Publish with confidence

- **Accessibility checks.** Before a page is published, SightPress checks it. A picture with no alternative text, a
  video with speech but no captions or text that is too faint to read stops the page from being published until it
  is fixed. Smaller problems, such as a link that says "click here", come up as suggestions.
- **Review before publishing.** Contributors write pages and send them for review. Editors and admins approve and
  publish them. See [Write and publish a page](how-to/write-and-publish-a-page.md).
- **Roles for your team.** Admins, editors, contributors and members each see and do only what they need. See
  [Roles and permissions](reference/roles-and-permissions.md).
- **An audit log.** Admins can see who changed what, and when.

### Look after your files and people

- **A media library** for pictures, videos, sound recordings, captions files and documents. Visitors only see the
  files you publish.
- **Invitations.** Invite people by e-mail. They get a 6-digit code, never a link. See
  [Codes we send you](how-to/codes-we-send-you.md).
- **Menus.** A main menu and a footer menu, which you change in the admin panel.
- **Site settings.** The website's name, profile, logo and other details.

### Be found in search engines and AI search

Every public page is ready for search engines and for AI search engines and assistants, with nothing to set up: a
title and description, a sitemap, structured data and a plain-text version of each page. You choose whether AI
services may read your pages. See [Search and AI visibility](reference/search-and-ai.md).

### Do every task with a keyboard and a screen reader

The admin panel, the default theme and every built-in feature aim to meet the Web Content Accessibility Guidelines
(WCAG) 2.2 at level AA. Nothing depends on dragging, on colour alone or on a puzzle to prove you are human. Admins can
also do many tasks with the [command line tool](reference/command-line.md).

### Reach more people and understand your visitors

- **Several languages.** Publish a website in more than one language. See
  [Add a language and translate a page](how-to/add-a-language.md).
- **Site search.** Visitors can search your pages. See [Search your website](how-to/search-your-website.md).
- **Analytics.** See how many people visit and what they read. See
  [See who visits your website](how-to/see-who-visits-your-website.md).
- **An AI assistant.** Turn it on if you want help drafting, with your own API key. A person checks every suggestion.
  See [Use the AI assistant](how-to/use-the-ai-assistant.md).

### Choose how your website looks and where its files live

- **Themes and colours (next release).** Install other themes, and change the colours on the **Appearance** screen.
  See [Install and change a theme](how-to/install-and-change-a-theme.md).
- **Large files (next release).** Send big files in parts, and pause or resume them. See
  [Upload large files](how-to/upload-large-files.md).
- **Cloud storage and a CDN (next release).** Keep a website's files with an S3-compatible service, and deliver public
  files from near each visitor.

## Ideas for websites

These ideas use only what SightPress can do today. Each one lists the blocks and features that help most.

### A website for a small business

A café, a hairdresser, a plumber or a shop that wants people to find it and get in touch.

- Pages: Home, What we offer, Prices, About us, Contact.
- A **Gallery** of your work or your premises, and a **Call to action** such as "Book an appointment" that opens a
  phone number or an e-mail address.
- A **Contact form**, so that customers can write to you without seeing your e-mail address.
- **Search and AI visibility**, so that people asking a search engine or an AI assistant can find you.

### A charity or community group

A charity, a residents' association, a sports club or a support group.

- Pages: Who we are, What we do, Get involved, Annual reports, Contact.
- **File download** blocks for annual reports, minutes and leaflets, with their type and size shown.
- A **Video** about your work, with captions and a transcript, so that everyone can follow it.
- **Roles**, so that volunteers write pages as contributors and a trustee publishes them as an editor.

### A podcast or audio project

A podcast, an audio drama, a radio show or a collection of oral history recordings.

- One page per episode, with an **Audio** block and its transcript. The transcript makes every episode readable and
  searchable.
- A **File download** for show notes or extra material.
- A page for each guest or contributor, with an **Image** and a short biography.

### A portfolio

An artist, photographer, writer, musician or designer who wants to show their work.

- A **Gallery** for each project, with alternative text that describes every picture. Writing good alternative text
  helps blind and low-vision visitors enjoy your work, and helps search engines understand it.
- **Columns** to put a picture next to its description on wide screens.
- **Audio** or **Video** blocks for performances and recordings.
- A **Contact form** for commissions and bookings.

### A school, library or museum

A school, a public library, a museum or a gallery that must be accessible to everyone.

- Pages: Visit us, Opening hours, What's on, Accessibility, Contact.
- **Video** tours with captions, and **Audio** guides with transcripts.
- **File download** blocks for policies, timetables and forms.
- **Roles**, so that staff in different departments write their own pages, and a web editor approves them.
- One installation with a separate **website** for each school, branch or venue.

### An accessibility-first information website

A council service, a health clinic, a disability organisation or a guide to a local service, where clear,
accessible information matters most.

- Short pages with clear **Headings**, so that screen reader users can jump from part to part.
- **File download** blocks for forms and leaflets, plus the same information as a page, so that nobody has to open a
  PDF.
- The **accessibility checks** stop common mistakes, such as a missing caption, before they reach the public.

### A personal website or blog-style journal

A personal homepage, a CV or a journal of your projects, travels or hobbies.

- Pages: About me, Projects, CV, Contact.
- A **File download** for your CV as a PDF.
- One page per project or trip, with **Images**, a **Gallery** and **Text**.

### Several websites for an agency or organisation

A web agency, a federation of clubs or an organisation with several brands.

- One installation of SightPress with a **website** for each client, club or brand, each with its own domain,
  language, people and settings.
- Each website keeps its own **roles**, so that each client or club looks after its own content.
- The **command line tool** to create and list websites quickly.

## Coming later

Later versions of SightPress add more, such as add-on modules and a guide to putting SightPress on a public server.
The [Getting started](getting-started.md) guide and the [how-to guides](how-to/index.md) are updated as each feature
arrives.

## Where to go next

- To decide which options and content suit your website, read [Plan your website](how-to/plan-a-site.md).
- To try SightPress on your own computer, follow [Getting started](getting-started.md).
- To build your first website, follow [Create a website](how-to/create-a-site.md), then
  [Write and publish a page](how-to/write-and-publish-a-page.md).
