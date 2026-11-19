# Add a language and translate a page

A website can be written in several languages. Each page then has one **translation** per language, with its own
title, address and content. Visitors read the page in their language, and search engines are told which pages are
translations of each other.

You need to be an **admin** to add a language, and an **editor** or **admin** to publish a translation. Contributors
can write translations; an editor publishes them.

## Add a language

1. Sign in to the admin panel, for example at <http://localhost:8080/admin>.
2. Choose **Site settings** in the admin menu, then **Change the languages**. You should see the heading
   **Languages** and a table of the languages of the site, the main language first.
3. Under **Add a language**, in **Language to add**, choose the language, for example **Spanish (es)**. You can type
   the first letters of its name to move through the list.
4. Choose **Add language**. You should see the message "Added Spanish. Translate pages into it from each page’s
   editor." and Spanish in the table.

Pages in the **main language** have addresses without a language part, such as `/about`. Pages in the other languages
start with the language code in lower case, such as `/es/about`.

### Start with the ready-made pages

New websites come with six starter pages (home, About, Contact, Privacy, Terms and Accessibility). To get them in the
new language too:

1. On the **Languages** screen, choose **Create the starter pages in Spanish**.
2. Each starter page gets a Spanish translation, as a **draft**. Nothing is published until you publish each
   translation (see below).

When SightPress has no ready-made text in that language, the drafts are written in British English for you to
translate, and a message says so.

## Translate a page

1. Choose **Pages** in the admin menu, then the page, for example **About us**.
2. In the page editor, go to the **Translations** section. It lists each language of the site with the state of the
   page in it: *Not written yet*, *Draft*, *Draft translation*, *Published*, or *Drafted by the AI assistant, not
   checked yet*.
3. Next to Spanish, choose **Translate into Spanish from British English**. This copies the title, address and
   blocks into a new Spanish draft and opens it. The British English page doesn't change.
4. Translate the title, the address (for example `sobre-nosotros`), the summary and every block, then choose
   **Save page**.

!!! tip "Let the AI assistant draft it"

    If the AI assistant is on, **Draft a translation into Spanish with the AI assistant** writes a first draft for
    you, block by block. It is marked *Drafted by the AI assistant, not checked yet* until a person saves a change or
    chooses **I checked this translation**, and can't be published before that. See
    [Use the AI assistant](use-the-ai-assistant.md).

## Publish the translation

1. In the Spanish translation's editor, choose **Publish this translation**. The same checks run as for any page:
   for example, every picture needs a description in Spanish.
2. If a check fails, a list at the top says what to fix. Fix it, save, and publish again.
3. You should see the message "Published the Spanish translation. Visitors can see it now." and **Published** next
   to Spanish in the **Translations** section.

Visitors see a translation only when both the page and the translation are published. A draft translation is not
in the menus, the sitemap or the language links that search engines read.

## Check it on the website

1. Go to the page in its new language, for example <http://localhost:8080/es/sobre-nosotros>.
2. You should see the page in Spanish. The menus use the Spanish titles, and the language switcher lists each
   language in its own words, such as "Español".
3. Someone whose browser asks for a language when they open the home page is taken to the home page in that
   language, or the closest one: for example Brazilian Portuguese to European Portuguese, and otherwise the main
   language.

Pictures can have their description, caption and transcript in each language: open the picture in **Media** and
fill in the fields for each language. When a language has none, the main language's text is used, never nothing.

## Change the main language or remove a language

- **Make Spanish the main language** changes the addresses of the pages in the old and the new main language. Links
  to their old addresses stop working, so do this early, before the website is public.
- **Remove Spanish** asks you to confirm on its own page. The Spanish translations are **kept** but can't be read.
  Adding Spanish again brings them back. People who chose Spanish for their emails get the main language instead.

## With the command line tool

In the `skeleton` folder, replace `demo` with the short name of your website:

```sh
docker compose run --rm --no-deps php bin/sightpress site:locale:add demo es
docker compose run --rm --no-deps php bin/sightpress site:locale:default demo es
docker compose run --rm --no-deps php bin/sightpress site:locale:remove demo es --yes
```

Without `--yes`, `site:locale:remove` only explains what would happen. To start a website in several languages, give
`site:create` a list, such as `--locale=en-GB,pt-PT,es`: every starter page is then written in each of them.
