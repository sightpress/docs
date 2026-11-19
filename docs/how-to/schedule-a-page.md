# Schedule a page

A page can be published at a set time, and taken off the website again at another time, without anyone being there
to press a button. This is useful for an event page that should appear on Monday morning, or a notice that should
disappear when an offer ends.

You need to be an **editor** or **admin**: people who may publish pages.

## Before you start

SightPress does what is scheduled once a minute, with a helper called the **scheduler**. When you start SightPress
with `docker compose up` (see [Getting started](../getting-started.md)), the scheduler starts too. To see what it
does, type `docker compose logs -f scheduler` in the `skeleton` folder.

## Publish a page at a set time

1. Write the page and take it through review until it is **Approved** (see
   [Write and publish a page](write-and-publish-a-page.md)). Only an approved page can be published on schedule.
2. In the page editor, go to the **Schedule** section. It says "Nothing is scheduled." and shows **your time zone**
   above the fields. Change your time zone on *My account* if it is wrong.
3. In **Publish at**, choose the day and time.
4. Choose **Save the schedule**. You should see a message such as "Scheduled: the page is published at 1 Dec 2026,
   09:00 (Europe/London time)."
5. In the page list, the page shows "Approved · To be published at 1 Dec 2026, 09:00".

Within a minute of that time, the page is published.

## Take a page off the website at a set time

1. In the **Schedule** section, choose a time in **Take off the site at**.
2. Choose **Save the schedule**.

At that time the page becomes a draft again, if it is published then.

## Change or clear the schedule

Change the times and choose **Save the schedule** again, or choose **Clear the schedule** to remove both times.
Archiving a page or putting it in the trash clears its schedule too.

## If something goes wrong

- **A time in the past**, an end before the start, or a time that doesn't exist (the night the clocks go forward) is
  explained, and the form keeps what you typed.
- The page is published **as the person who scheduled it**: the same publish checks run. If a check fails, or that
  person may no longer publish, nothing is published, the audit log says why, and the time is cleared so that
  you can fix the page and schedule it again.
- If the computer or server was off at the time, the page is published as soon as the scheduler runs again.

Whoever runs SightPress on a server sets the scheduler up once; see the [command line reference](../reference/command-line.md#things-that-happen-at-a-set-time).
