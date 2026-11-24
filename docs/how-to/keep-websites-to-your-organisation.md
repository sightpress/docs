# Keep websites to your organisation

If your organisation runs SightPress for its own teams, you can make sure every website uses one of your domains,
that only people with your email addresses join, and let your colleagues register on members-only websites without
an invitation.

These settings apply to **every website** of your SightPress installation, so whoever runs it sets them in
`config/tenant.php`. Nothing changes until you set them. Admins of each website can only turn registration with an
organisation email address **off** for their own website.

## Websites only on your domain

Add to `config/tenant.php`:

```php
'domain_policy' => ['subdomain_of' => 'example.org'],
```

From then on, [Create a website](create-a-site.md) only accepts `example.org` or a name under it, such as
`news.example.org`. A name like `evil-example.org` or `example.org.other.test` is refused, with a message saying
which names are allowed.

## Only your email addresses join

```php
'email_policy' => ['domains' => ['example.org', 'example.com']],
```

Only addresses at exactly these domains can be invited, accept an invitation, [sign up](let-people-sign-up.md),
change to a new address in **My account**, or be the first admin of a new website. `ada@mail.example.org` only
counts if you list `mail.example.org` too. Capital letters don't matter, and international names such as
`bücher.example` work.

## Let colleagues register on members-only websites

```php
'email_policy' => ['domains' => ['example.org', 'example.com']],
'domain_registration' => 'on',
```

`'on'` uses every domain of `email_policy`. To use only some, write `['domains' => ['example.org']]` instead.

On every [members-only website](make-a-site-members-only.md), the sign-in page now shows **Register with your
organisation email**, even while **Let anyone create an account** is off. To try it:

1. Open a private browser window and go to the sign-in page of a members-only website.
2. Choose **Register with your organisation email**. The page says which addresses can register.
3. Fill in the form with an address at one of your domains and choose **Create my account**. An address elsewhere is
   refused, and the message names your domains.
4. Type the 6-digit code from the email (see [Codes we send you](codes-we-send-you.md)). The account can't sign in
   before that.
5. Sign in. The new account has the website's sign-up role, **Member** unless an admin chose another safe one.

Public websites, and websites where anyone can already sign up, are not affected. The **Audit log** says these
accounts registered with an organisation email address.

### Turn it off for one website

Admins of a members-only website choose **Site settings**, then **Change who can sign up**. Under **Registration with
an organisation email address**, choose **Off for this site**, then **Save sign-up settings**. The link disappears
from the sign-in page. You can also create a website with it off:

```bash
docker compose run --rm --no-deps php bin/sightpress site:create … --domain-registration=off
```

## After you make a policy stricter

Nothing is removed: websites keep answering on their domains, and people keep signing in with the addresses they
have. The policy applies to new websites, new people and changed addresses. To see what is outside it, run:

```bash
docker compose exec php bin/sightpress system:check
```

It prints a note for each website with a domain outside `domain_policy`, and how many accounts of each website have
an address outside `email_policy`.

If a setting is written wrongly, SightPress doesn't start and says what to fix, for example
`Tenant setting "domain_registration" is 'on', which uses the domains of "email_policy", but "email_policy" is 'any'.`
