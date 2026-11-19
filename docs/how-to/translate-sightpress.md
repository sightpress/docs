# Translate SightPress

The admin panel, the website screens (sign-in, search, error pages) and the emails can be translated. Each language
is one file. You need to be fluent in the language; you don't need to be a developer.

## What is translated today

- **British English** (`en-GB`): the source language, and the language used when a text has no translation.
- **European Portuguese** (`pt-PT`) and **Spanish** (`es`): complete and reviewed.

Regional variants are separate languages: for example Brazilian Portuguese (`pt-BR`) is not European Portuguese, and
Latin American Spanish (`es-419`) is not Spanish from Spain.

## Translate into a new language, or fix a translation

1. Download the source file,
   [messages.en-GB.xlf](https://github.com/sightpress/core/blob/main/resources/translations/messages.en-GB.xlf), and an
   existing translation as an example, such as
   [messages.es.xlf](https://github.com/sightpress/core/blob/main/resources/translations/messages.es.xlf).
2. Open it in a translation tool that reads XLIFF 2.0 files, such as Poedit, OmegaT or Virtaal, or in a text
   editor. Each text is a `<source>`; write your translation in the `<target>` next to it.
3. Keep the words in curly brackets, such as `{name}` or `{count}`, exactly as they are: SightPress puts a name or a
   number there. Keep any HTML such as `<strong>` too.
4. Use the words people use every day, as the English does. Short sentences are better than long ones.
5. Send the file:
    - by email to <contribute@sightpress.org>, saying which language it is; or
    - as a pull request to the [core repository](https://github.com/sightpress/core), if you know GitHub. The file
      goes in `resources/translations/messages.<code>.xlf`, for example `messages.fr.xlf`.

## What happens next

- A second fluent speaker reviews every new language, and every new text, before it is released.
- Right-to-left languages, such as Arabic and Hebrew, also get a visual check of every screen.
- New texts that still need a review are marked `state="initial"` in the file. Reviewing them is a good first task.

Translating the documentation is not possible yet: it is written in British English only.
