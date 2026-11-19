# Make a website or a page members-only

A **members-only** page can only be read by people who have an account on the website and are signed in. You can
make a single page members-only, or the whole website.

You need to be an **admin** to change the whole website, and an **editor** or **admin** to change a page.

## Make one page members-only

1. Choose **Pages** in the admin menu, then the page.
2. In **About the page**, under **Who can read this page**, choose **Members only: people with an account on the
   site, once they sign in**.
3. Choose **Save page**.
4. Check: sign out, or open a private browser window, and go to the page. You should see the sign-in page. After
   signing in, you are taken back to the page.

A members-only page is left out of search engines, the sitemap, `llms.txt` and site search for visitors who aren't
signed in. In the menus, it only appears for people who are signed in.

To show a menu entry only to members, for example a link to a members' forum, choose **Menus**, then **Change** next
to the entry, and tick **Only show this entry to members who are signed in**.

## Make the whole website members-only

1. Choose **Site settings** in the admin menu.
2. Under **Who can read the site**, choose **Only people with an account, once they sign in. Search engines and AI
   services can’t read any page.**
3. Choose **Save settings**. You should see "The settings have been saved."
4. Check: in a private browser window, go to any page of the website. You should see the sign-in page with the
   message "This site is for its members. Sign in to read it."

On a members-only website:

- People who aren't signed in can still sign in, create an account (if you let them, see
  [Let people sign up](let-people-sign-up.md)), reset a forgotten password and accept an invitation.
- Search engines and AI services can't read anything: the sitemap and `llms.txt` are empty, and every page asks not
  to be listed.
- Every signed-in person, whatever their role, can read every published page.

## Who can read what

| | Visitors who aren't signed in | Members (signed in) | Editors and admins |
| --- | --- | --- | --- |
| Pages for everyone, on a public website | Yes | Yes | Yes |
| Members-only pages | No: asked to sign in | Yes | Yes |
| Any page of a members-only website | No: asked to sign in | Yes | Yes |
| Drafts, older versions, archived pages | No | No | Yes |
| The admin panel | No | No | Yes |

To give people accounts, invite them from **Invite someone** in the admin menu, or let them sign up themselves.
