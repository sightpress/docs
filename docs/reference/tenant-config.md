# Tenant configuration reference

`config/tenant.php` holds the settings for **every website** of a SightPress installation. It returns a PHP array.
Every key is optional: a missing key gets the default shown here. SightPress checks the file each time it starts, and
a value it can't use stops it with a message that names the key and says what it should be.

Keys and passwords never go in this file. They come from environment variables (in the `skeleton` folder, from
`.env`), which are listed at the end.

## Where things are

| Key | Default | What it is |
| --- | --- | --- |
| `registry.path` | `config/sites.php` | The site registry: every website, its domains and its database. Only `bin/sightpress site:*` commands write it. |
| `sites_dir` | `sites` | Where websites keep their files, when they are stored locally. |
| `cache_dir` | `var/cache/<APP_ENV>` | The cache. `bin/sightpress cache:clear` empties it. |
| `log.path` | `var/log/<APP_ENV>.log` | The log file. A stream such as `php://stderr` also works. |
| `log.level` | `notice` | The least important messages that are written: `debug`, `info`, `notice`, `warning`, `error` … |
| `session.path` | `var/sessions` | Where sign-in sessions are kept. |
| `session.idle_timeout` | `7200` | Seconds without a request after which someone is signed out (at least 60). |

## Language, time and email

| Key | Default | What it is |
| --- | --- | --- |
| `admin.default_locale` | `en-GB` | The language of the admin panel when nobody chose one. |
| `timezone` | `UTC` | The time zone of new websites, as a name such as `Europe/London`. |
| `mail.from` | `no-reply@localhost` | The address emails come from. |
| `mail.from_name` | `SightPress` | The name emails come from. |

## Features and themes

| Key | Default | What it is |
| --- | --- | --- |
| `modules.defaults` | `[]` | Which optional features (modules) new websites start with, for example `['analytics' => false]`. Websites that already exist keep their choice. |
| `modules.locked` | `[]` | Features turned on (`true`) or off (`false`) for every website; admins see "Set by your organisation". An unknown id stops SightPress. |
| `themes.allowed` | `null` | The theme ids websites may use; `null` means every installed theme. |
| `themes.default` | `default` | The theme of new websites. It must be allowed. |

See [Turn features on or off](../how-to/turn-features-on-or-off.md), [Add a module](../how-to/add-a-module.md) and
[Install and change a theme](../how-to/install-and-change-a-theme.md).

## Files and uploads

| Key | Default | What it is |
| --- | --- | --- |
| `uploads.proxy_body_limit` | none | The web server's request size limit, which PHP can't read. `bin/sightpress system:check` compares it with the other limits. |
| `uploads.temp_path` | `var/uploads` | Where large files wait while their parts arrive. Never under `public/`; several web servers must share it. |
| `uploads.max_chunked_bytes` | `5G` | The largest file that can be sent in parts. |
| `media.limits` | `[]` | The largest file of each kind for every website, for example `['image' => '20M']`, and under `max` the most a website's own limit can be. |

See [Choose upload limits](../how-to/choose-upload-limits.md) and [Upload large files](../how-to/upload-large-files.md).

## Your organisation's domains

| Key | Default | What it is |
| --- | --- | --- |
| `domain_policy` | `any` | `['subdomain_of' => 'example.org']`: websites must use that domain or a name under it. |
| `email_policy` | `any` | `['domains' => ['example.org']]`: only addresses at exactly these domains can be invited, sign up or change to a new address. |
| `domain_registration` | `off` | On members-only websites, people at these domains may make their own account even when sign-up is off: `'on'` (the domains of `email_policy`) or `['domains' => [...]]`. Each website can turn it off. |

Making a policy stricter removes nothing: `bin/sightpress system:check` lists what is outside it. See
[Keep websites to your organisation](../how-to/keep-websites-to-your-organisation.md).

## AI assistant

| Key | Default | What it is |
| --- | --- | --- |
| `assistant.provider` | none | A provider every website may use without a key of its own. Its key is `ASSISTANT_API_KEY`. |
| `assistant.model` | none | The model of that provider. |
| `assistant.base_url` | none | The address of a provider with a custom endpoint. |
| `assistant.site_keys` | `true` | Whether a website's admins may enter a key of their own. |
| `assistant.allow_private_hosts` | `false` | Allow a provider on a private or local network address, such as a model server in the office. |
| `assistant.monthly_budget` | `0` | Tokens a website may use each month; `0` means no limit. |

See [Use the AI assistant](../how-to/use-the-ai-assistant.md).

## Text messages

| Key | Default | What it is |
| --- | --- | --- |
| `sms.provider` | none | A text message provider every website may use: `twilio`, `vonage`, or a module's. Its key is `SMS_API_KEY`. |
| `sms.account` | none | That provider's account or key id. |
| `sms.sender` | none | Who messages come from: a number such as `+447700900123`, or a name of 3 to 11 letters and digits. |
| `sms.region` | none | The provider's region, for providers that have regions. |
| `sms.site_providers` | `false` | Whether a website's admins may set up a provider of their own. |
| `sms.require_for_existing` | `false` | Whether people who already have an account must add a mobile number at their next sign-in, instead of being allowed to skip it. |
| `sms.monthly_budget` | `1000` | Message parts a website may send each month; `0` means no limit. |
| `sms.reserved_for_codes` | `50` | The part of the budget only security codes may use, so a used-up budget never locks people out. Must be smaller than the budget. |
| `sms.countries` | the region of each website's main language | The countries numbers may be in, as two-letter codes such as `['GB', 'IE']`. |
| `sms.limits` | see below | How many codes or messages per hour, per number (`per_number`, 3), per device (`per_client`, 10), per account (`per_account`, 5) and per website (`per_site`, 100), and number checks per day (`lookups_per_day`, 100). |
| `two_step` | `optional` | Two-step confirmation: `optional` (each website's admins choose), `required` (every website with text messages asks for a code before important changes) or `off` (no website can, and the setting is hidden). |

See [Set up text messages](../how-to/set-up-text-messages.md) and
[Confirm important changes](../how-to/confirm-important-changes.md).

## Environment variables

| Variable | What it is |
| --- | --- |
| `APP_ENV` | `production`, `staging`, `test` or `dev`. |
| `APP_SECRET` | The secret that encrypts database addresses and keys. Keep it safe: without it they can't be read. |
| `APP_DEBUG` | Show error details. Never in production. |
| `MAILER_DSN` | How emails are sent, for example `smtp://mail.example.org:25`. Without it no email is sent. |
| `ASSISTANT_API_KEY` | The key of `assistant.provider`. |
| `SMS_API_KEY` | The key of `sms.provider`. |

## An example

```php
<?php

return [
    'timezone' => 'Europe/London',
    'mail' => ['from' => 'web@example.org', 'from_name' => 'Riverside Library'],
    'modules' => ['defaults' => ['analytics' => false], 'locked' => []],
    'email_policy' => ['domains' => ['example.org']],
    'domain_registration' => 'on',
    'sms' => ['provider' => 'twilio', 'account' => 'AC0123…', 'sender' => 'Riverside', 'countries' => ['GB']],
    'two_step' => 'optional',
];
```
