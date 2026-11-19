# Move or rename a page without breaking links

When a published page gets a new address, or moves somewhere else on the website, SightPress remembers its old
address. Anyone who follows an old link or bookmark is taken to the new address, and search engines are told that the
page has moved for good. You don't need to do anything for this to happen.

You need to be an **editor** or **admin**.

## Give a page a new address

1. Choose **Pages** in the admin menu, then the page.
2. In **Address**, type the new last part of the address, for example `opening-times` instead of `hours`. Use
   lower-case letters, digits and hyphens.
3. Choose **Save page**.
4. Check: open the old address, for example <http://localhost:8080/hours>. You should land on
   <http://localhost:8080/opening-times>.

Pages under it move with it: `/hours/holidays` becomes `/opening-times/holidays`, and its old address leads there
too.

## Move a page somewhere else on the website

In the page editor, the **Place in the site** section has these buttons:

- **Move up** and **Move down** change the order of pages at the same level, for example in a menu of pages.
- **Move under the page before it** puts the page one level down, under the page just above it.
- **Move out to the level above** puts the page one level up.
- **Move to**: choose **The top level of the site** or any other page in the list, then choose **Move the page**.

After each move, a message says where the page is now, and focus stays in the section. Pages under it move with it,
and every old address leads to the new one.

## See and change the redirects

1. Choose **Redirects** in the admin menu. You should see the table **Old addresses and where they lead**, with each
   **Old address**, its **Language** and where it **Leads to**. A page that was deleted for good shows "Gone: the
   page was deleted".
2. To stop an old address leading anywhere, choose **Remove the redirect from** followed by that address.
3. To add your own, for example for a page from an old website, fill in **Old address** (such as `/old-news`) and
   **New address** (such as `/news`) under **Add a redirect**, then choose **Add redirect**. The new address must be
   in the same language.

A few rules keep this tidy:

- If a page moves twice, its oldest address leads straight to the newest one, never through a chain.
- A redirect that would go round in a circle is refused.
- A new page that uses an old address wins: the redirect stops and visitors see the new page.

The **Pages not found** report on the *Analytics* screen lists addresses people asked for that don't exist, with a
link to add a redirect for each. See [See who visits your website](see-who-visits-your-website.md).
