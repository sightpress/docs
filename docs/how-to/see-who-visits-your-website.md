# See who visits your website

SightPress counts how many people visit your website, which pages they read, where they come from, what they search
for and which addresses are not found. It also shows which search engines and AI crawlers read the website.
Analytics is an optional feature, on by default.

It is private by design: visits are counted on your own server, with no cookies, no tracking scripts and nothing
sent to other companies. SightPress never stores visitors' IP addresses or details of their browser. This is not
legal advice, but it means most websites don't need a cookie banner for it.

## Open the Analytics screen

1. Sign in to the admin panel as an admin or an editor, for example at `http://localhost:8080/admin`.
2. In the admin menu, choose **Analytics**. (Contributors don't have it.)
3. You should see the last 30 days. Under **Days to show**, the link for the range you are looking at is marked as
   the current one; choose **Last 7 days**, **Last 90 days** or **Last 12 months** to change it.
4. For other dates, fill in **First day** and **Last day** under **Other days** and choose **Show these days**. If a
   date is missing or the last day comes before the first, a box at the top says "There is a problem" and links to
   the field to fix.

Days run from midnight to midnight in your website's time zone, which the screen names, so everyone sees the same
numbers. Today's numbers are counted live.

## Read the numbers

At the top, three sentences sum up the range, for example "1,349 visitors, up 12% on the 30 days before". The arrows
next to them are only decoration: the words say the same.

Each part of the screen has its own heading, so a screen reader can move between them:

- **Visitors over time**: a chart, with a sentence above it that says what it shows, for example "Visitors went up
  12% compared with the previous 30 days, from 1,204 to 1,349. The busiest day was Tuesday 4 November, with 98
  visitors." This period is a solid line with round markers, the period before a dashed line with square markers.
  Choose **Show the data for each day** to see every day in a table.
- **Pages read most**, **Where visitors came from** and **Devices** (phone, tablet or computer): tables, with a
  small bar next to each number.
- **Languages**: only on websites in more than one language.
- **Site search**: what people searched for, and the searches that found nothing. Only while site search is on.
  Searches that look like an email address or a phone number are counted but never shown.
- **Pages not found**: addresses people asked for that don't exist. People who can edit any page see **Add a
  redirect** next to each one: it opens the Redirects screen with the address filled in.
- **Search engines and AI crawlers**: which programs read the website and what they read. They are never counted
  as visitors.

Each part has a **Download … as a CSV file** link, for a spreadsheet. The file has every row, not only the first ten.

## What is counted and what isn't

- A visitor is counted once per day. The same person on two days counts twice; there is no "returning visitors"
  number, on purpose.
- Not counted: people signed in to the website, visitors whose browser asks websites not to track them (Global
  Privacy Control or Do Not Track), the admin panel, pictures and other files, and pages a browser only loads in
  advance.
- Figures are kept for 25 months, so you can compare two full years.

## The dashboard

The dashboard (`/admin`) shows **Last 7 days**: one sentence with the visitors and page views of the last week, and
a link to the Analytics screen.

## Keep analytics for less time, or delete them (admins)

1. On the Analytics screen, go to **Keeping analytics** at the bottom.
2. In **Months to keep analytics for**, type a number from 1 to 25 and choose **Save**. Older figures are deleted
   within the hour. The change is in the audit log.
3. To delete everything, choose **Delete analytics data**. A page asks you to confirm; choose **Delete analytics
   data** again, or **Keep the data** to go back. Counting goes on from then.

## From the command line

In the `skeleton` folder (replace `demo` with your website's short name):

```sh
docker compose run --rm --no-deps php bin/sightpress site:analytics:report demo
```

You should see the visitors, page views and views per visitor of the last 30 days, each with the 30 days before.
Add `--report=pages` (or `sources`, `devices`, `not-found` …) for another report, and `--format=csv` or
`--format=json` for a file. If it warns that analytics were "last rolled up" long ago, SightPress isn't running in
the background (see [Run SightPress in the background](run-in-the-background.md)).

## Turn analytics off

Analytics is a feature: an admin can turn it off on the **Features** screen (see
[Turn features on or off](turn-features-on-or-off.md)). Nothing is counted any more, and the figures already
counted are kept until they are deleted. If your website's **Privacy notice** page was written by SightPress, remove
its "Visitor statistics" section.
