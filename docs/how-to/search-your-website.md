# Search your website

Every page of a website has a search box in its header. It finds the website's published pages in the language you
are reading, by their title, their summary and their text, including the descriptions of pictures, the transcripts
of recordings and the names of downloads. Site search is an optional feature, on by default.

## Search from any page

1. Open any page of your website, for example `http://localhost:8080/`.
2. In the header, find **Search this site**. With a keyboard, press Tab until you reach the search box.
3. Type one or more words, for example `opening hours`, and press Enter (or choose **Search**).
4. You should see the results page. Its title and heading say what you searched for and how many results there are,
   for example "2 results for “opening hours”". Screen readers read this out when the page opens.
5. Each result has its title as a link, where it is on the website ("In: About us › Team") and a few lines of its
   text with the words you searched for in bold and underlined. Choose a title to open the page.

Good to know:

- Capitals and accents don't matter: `cafe` finds "Café".
- Words are matched by their stem: `libraries` finds "library".
- The last word can be the start of a word: `accessib` finds "accessibility".
- Pages with every word come first. When no page has every word, the results page says so and shows pages with some
  of them.
- With ten results or more, links at the bottom ("Search results pages") lead to the next pages.

## When nothing is found

The page says "No results for …" and gives tips: check the spelling, try fewer or other words. On a website in
several languages, the language list at the top leads to the same search in the other languages.

## Pages for members only

Pages for members only are found only by people who are signed in. Sign in, search again, and they appear in the
results.

## Leave a page out of the search

1. Open the page in the admin panel's page editor.
2. In the **Search & AI** section, tick **Leave this page out of the site’s own search**.
3. Choose **Save**.
4. Check: searching for the page's title no longer finds it. Search engines can still list the page, unless you also
   tick **Ask search engines and AI search engines not to list this page**.

The choice is made for each language of the page.

## If new pages don't show up

The search follows every change as it is saved. After a bigger change (adding a language, changing the website's
main language, making the whole website members-only, or turning search back on), SightPress rebuilds the search
within a minute, as long as it runs in the background (see
[Run SightPress in the background](run-in-the-background.md)). To rebuild it straight away, in the `skeleton` folder
type (replace `demo` with your website's short name):

```sh
docker compose run --rm --no-deps php bin/sightpress site:search:reindex demo
```

You should see one line per language, such as `en-GB: 12 documents indexed.`

## Turn search off

Search is a feature: an admin can turn it off on the **Features** screen (see
[Turn features on or off](turn-features-on-or-off.md)). The search box and the results page are then gone.
