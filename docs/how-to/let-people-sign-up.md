# Let people sign up

By default only people you invite can have an account on your website. You can also let anyone create their own
account. They confirm their email address with a 6-digit code before they can sign in.

You need to be an **admin**.

## Turn sign-up on

1. Choose **Site settings** in the admin menu, then **Change who can sign up**.
2. Tick **Let anyone create an account**.
3. In **Role of people who sign up**, keep **Member** unless you have a reason to change it. Members can read
   members-only pages and nothing more. Only roles that are safe to give to people you don't know are listed: never
   editor or admin.
4. Choose **Save sign-up settings**. You should see "The sign-up settings have been saved."

The sign-in page now shows "New here?" with a **Create an account** link.

## Try it as a new person

1. Sign out, or open a private browser window, and go to the sign-in page, for example
   <http://localhost:8080/login>.
2. Choose **Create an account**. Fill in your first name, last name, an optional display name, your email address,
   your time zone (your browser's is already chosen) and a password. On a website in several languages, choose your
   language too.
3. Choose **Create my account**. You should see the heading **Confirm your email address**.
4. Open the email. While you try SightPress on your computer, it is in the test inbox: see
   [Open the test e-mail inbox](open-the-test-email-inbox.md). It has a 6-digit code and **no link**.
5. Type the code in **6-digit code** and choose **Confirm my email address**. You should see "Your email address is
   confirmed and your account is ready."
6. Sign in with your email address and password.

The code works once, for 15 minutes. **Send a new code** sends another one, at most once a minute. See
[Codes we send you](codes-we-send-you.md).

## What keeps it safe

- An account can't sign in until its email address is confirmed. Accounts that are never confirmed are removed after
  a week.
- If someone signs up with an address that already has an account, the page looks exactly the same, so nobody can find
  out who has an account. The owner of the address gets an email saying someone tried, with no link.
- A hidden field catches most automated sign-ups, and each place can send at most 10 sign-up forms an hour. There are
  no picture puzzles.
- Every new account and every change to these settings is written to the **Audit log**.

On a members-only website, your organisation may also let people with its email addresses register while sign-up is
off: see [Keep websites to your organisation](keep-websites-to-your-organisation.md).

To turn sign-up off again, untick **Let anyone create an account**. Accounts that were already made are kept.
