# Open the test e-mail inbox

When SightPress runs on your computer, it never sends real e-mail. Every message goes to a test inbox called
Mailpit instead, so you can read it without anything leaving your computer. This guide shows you how to open it.

1. Start SightPress (see [Getting started](../getting-started.md)).
2. In your web browser, go to <http://localhost:8025>.
3. You should see the Mailpit inbox. It is empty for now, with a message saying there are no messages.

SightPress doesn't send any e-mail yet. Later versions will send e-mail, for example when someone is invited to
edit a site, and those messages will appear here.

If you changed `MAILPIT_UI_PORT` in your `.env` file, use that number instead of `8025`.
