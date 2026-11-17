# Write and publish a page

This guide shows you how to start a page, add its content, and take it through review until visitors can read it.
Every step works with a keyboard alone and with a screen reader.

You need an account on the website with the role contributor, editor or admin. See
[Roles and permissions](../reference/roles-and-permissions.md).

## Start a page

1. Sign in to the website, for example at <http://localhost:8080/login>. You should see the **Dashboard**.
2. Choose **Pages** in the admin menu, then **Start a new page**. You should see the heading **Start a new page**.
3. In **Title**, type the page's title, for example `Book club`. You can change it later.
4. In **Put it under**, choose where the page belongs. Choose **None: a top-level page** for a page of its own, or
   another page to put it under that one. A page under "About us" gets an address such as `/about/book-club`.
5. Leave **Layout** on **Standard page**. **Home page** shows the title and summary as a large introduction.
6. Choose **Start the page**. You should see the message "“Book club” has been started as a draft. Add its content,
   then save it." and the heading **Edit “Book club”**.

A new page is a **draft**: nobody else sees it until it is published.

## Add the content

A page is made of **blocks**, shown one after another: a heading, some text, a picture and so on. The
[blocks reference](../reference/blocks.md) describes every kind.

1. Go to the **Content** section. On a new page it says "There are no blocks yet. Add the first one below."
2. In **Type of block to add**, choose a kind of block, for example **Text**.
3. Choose **Add block**. You should hear or see "Added a Text block. It is block 1 of 1. Save the page to keep this
   change." Focus moves to the new block, a group called "Block 1 of 1: Text".
4. Fill in the block's fields. For a Text block, type your text in **Text**. You can use simple HTML, such as
   `<p>We meet every first <strong>Tuesday</strong> of the month.</p>`. **Update the previews** shows how it will
   look.
5. Add more blocks the same way. Each block has the buttons **Move up**, **Move down** and **Remove**. After each
   one, you hear or see what changed, for example "Moved block 2 (Text) up. It is now block 1 of 2." Focus stays on
   the block or on the button.
6. Choose **Save page**. It is at the top and at the bottom of the form; pressing <kbd>Enter</kbd> in a field saves
   too. You should see "Saved “Book club”."

!!! warning "The block buttons don't save"

    Adding, moving and removing blocks changes the form only. Nothing is kept until you choose **Save page**.

If the page can't be saved, a list of problems appears at the top of the page and gets focus. Each problem is a link
to the field it is about, for example "Heading text: Fill in this field." Fix it and save again.

## Help people find the page

The **Search & AI** section decides how the page looks in search engines, in AI search and when it is shared. All
its fields are optional, but a description and a summary help people find the page.

- **Summary**: one or two sentences about the page. Shown in lists of pages and read by AI search engines.
- **Title in search results**: only when it should differ from the page title.
- **Description for search engines**: one or two sentences. Search engines show about 160 characters. A count of
  the characters is read out after a short pause.
- **Ask search engines and AI search engines not to list this page**: tick it to keep the page out of search
  results. It is then also left out of the sitemap and `llms.txt`.

**How a search result could look** shows the title, the address and the description as text. Choose **Update the
previews** after a change. [Search and AI visibility](../reference/search-and-ai.md) explains the rest.

## Check the page before you send it

Under the form, SightPress checks the saved page:

- **Problems that stop publishing**, for example a picture without alt text, a video without captions or a heading
  that skips a level. A page with these can't be published until they are fixed.
- **Suggestions**, for example "Add a description for search engines". The page can be published with these.

Each item links to the field or block it is about. When there are only suggestions, the check says "Ready to
publish, with suggestions".

## Send the page for review

Contributors, editors and admins all send their pages for review.

1. Save the page first. The review buttons use the saved page.
2. Under **Review and publishing**, you can write a **Note for the next person**, for example what you changed.
3. Choose **Submit for review**. You should see "Sent for review. The editors have been told by email."

The page now waits for review. Every editor and admin gets an e-mail with the page's name and a link to it in the
admin panel. A contributor can't change the page while it waits.

## Review, approve and publish a page

You need the role editor or admin.

1. Open the page: choose the link in the e-mail, or find the page under **Waiting for review** on the **Dashboard**.
2. Read the page. **View the saved page**, at the top, shows it as visitors will see it.
3. If it needs changes, choose **Send back to draft**, with a note that says what to change. You should see "Sent
   back to draft. The writer can change it and send it again."
4. If it is ready, choose **Approve**. You should see "Approved. It can be published now."
5. Choose **Publish**. You should see "Published. Visitors can see the page now."

If the page still has a problem that stops publishing, **Publish** is refused, and the list of problems appears at
the top with focus on it.

## Look at the published page

1. Go to the page's address on the website, for example <http://localhost:8080/book-club>. The address is shown
   next to **State** at the top of the page editor.
2. You should see the page with its content. It is now also in the website's sitemap and its `llms.txt` file.

## Take a page off the website

1. Open the page in the admin panel.
2. Choose **Unpublish**. You should see "Unpublished. Visitors can’t see the page any more; it is a draft again."

The page becomes a draft and goes through review again before it is back on the website.
