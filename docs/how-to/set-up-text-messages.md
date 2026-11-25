# Set up text messages

A website can send text messages (SMS): codes that check a mobile number is yours, notifications people choose, codes
to sign in or reset a password, and codes for [important changes](confirm-important-changes.md). Text messages are
**off** on every website until whoever runs your SightPress installation sets them up, because they cost money and
send phone numbers to another company.

You need a text message account at **Twilio** or **Vonage** (other providers can come as modules), and the command
line tool (see [Try the command line tool](try-the-command-line-tool.md)).

## 1. Choose who sends

Get these from your provider's dashboard:

| Provider | Account | Key | Sender |
| --- | --- | --- | --- |
| Twilio | Account SID (starts with `AC`) | Auth token | A number such as `+447700900123`, or a name of 3 to 11 letters and digits, such as `Riverside` |
| Vonage | API key | API secret, then `|`, then the signature secret | A number or a name |

Names as senders are not allowed in every country. Check your provider's rules for the countries you send to.

## 2. Give them to the website

The key is read from the keyboard, never typed on the command line, so it doesn't end up in your shell history:

```sh
printf %s "$TWILIO_AUTH_TOKEN" | bin/sightpress site:sms:set riverside --provider=twilio \
    --account=AC0123… --sender=Riverside --key-stdin
```

You should see `The site "riverside" now sends text messages with Twilio.` and `Key: ••••` followed by its last 4
characters. The key is stored encrypted and never shown again.

To give every website the same provider instead, put it in `config/tenant.php` (see below) and the key in the
`SMS_API_KEY` environment variable.

## 3. Turn them on and test

```sh
bin/sightpress site:sms:enable riverside
bin/sightpress site:sms:test riverside --to=+447700900123
```

The test message arrives within a minute: "Test message from Riverside Library: text messages work." If it doesn't,
the command says why in plain words, for example that the key was refused or that the country isn't allowed.

Admins see **Text messages** under **Site settings** in the admin panel: whether the website can send, how much of
this month's budget is used, the latest messages (numbers partly hidden), and **Send a test message**.

## 4. Tell the provider where to report

In your provider's dashboard, set the delivery reports and incoming messages address to
`https://<your website>/_sms/webhook`. Then a STOP reply stops notifications to that number, and the screen shows
which messages were delivered. Every report must be signed by your provider; others are refused.

## Limits that protect you

Anyone can type a number into a sign-up form, and some people do that to make websites send expensive texts. So
SightPress:

- only sends to **mobile numbers**, never to premium-rate, shared-cost or toll-free numbers, and checks each new
  number with your provider first;
- only sends to numbers in **your country** (the country of the website's main language), unless you list others;
- sends at most a few codes to one number, one computer and one account each hour, and at most 100 texts a website an
  hour;
- stops at a **monthly budget** (1,000 message parts a website by default), with a warning at 80 %. A small part of
  it is kept for security codes, so people can still sign in when notifications have used the rest.

Change them in `config/tenant.php`:

```php
'sms' => [
    'monthly_budget' => 1000,
    'reserved_for_codes' => 50,
    'countries' => ['GB', 'IE'],
    'limits' => ['per_number' => 3, 'per_client' => 10, 'per_account' => 5, 'per_site' => 100, 'lookups_per_day' => 100],
],
```

## Turn them off

`bin/sightpress site:sms:disable riverside` stops everything: no texts, no phone sign-in, no codes for important
changes. Numbers already on accounts and the record of messages are kept. `site:sms:remove riverside` also forgets the
website's provider and key.

## Next

- [Add your mobile number](add-your-mobile-number.md)
- [Get notifications by text](text-message-notifications.md)
- Add a *Text messages* section to your Privacy Policy if your website was made before text messages were on.
