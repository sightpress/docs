# Open the test e-mail inbox

When SightPress runs on your computer, it never sends real e-mail. Every message goes to a test inbox called
Mailpit instead, so you can read it without anything leaving your computer. This guide shows you how to open it.

1. Start SightPress (see [Getting started](../getting-started.md)).
2. In your web browser, go to <http://localhost:8025>.
3. You should see the Mailpit inbox. It is empty for now, with a message saying there are no messages.

SightPress sends e-mail when:

- someone forgets their password and asks for a code;
- someone is invited to help with a website;
- a page is sent for review: every editor and admin is told;
- a visitor sends a message with a contact form.

Each of these messages appears here a moment later. Choose a message to read it.

The inbox only gets messages when your `.env` file has the line `MAILER_DSN=smtp://mailpit:1025`. The file you copied
from `.env.example` in [Getting started](../getting-started.md) already has it. Without it, SightPress sends nothing.

If you changed `MAILPIT_UI_PORT` in your `.env` file, use that number instead of `8025`.
