# Blocks

A page's content is a list of **blocks**, shown one after another. Each kind of block holds one kind of content, such
as a heading, some text or a picture. SightPress comes with ten kinds. Add-on modules can add more.

This page lists every kind, its fields and their limits, and what SightPress checks for accessibility. To add, move
and remove blocks, see [Write and publish a page](../how-to/write-and-publish-a-page.md).

## How blocks are checked

- **When you save**, every block is checked. Every problem is listed at once, at the top of the page, with a link to
  the field, for example "Heading text: Fill in this field." Nothing is saved until every block is fine.
- **When you publish**, SightPress checks the page for accessibility. Problems marked "stops publishing" below must be
  fixed first. Problems marked "suggestion" don't stop publishing.
- **Files** come from the website's media library and must be the right kind, for example a picture for an Image
  block. Visitors only see files that are published in the media library; a page that uses an unpublished file
  can't be published.
- **Every block also has a text version**, which AI search engines and other readers get as Markdown. See
  [Search and AI visibility](search-and-ai.md).

Fields marked *required* must be filled in. All the others are optional.

## Heading

A heading that starts a part of the page.

| Field | Limits |
| --- | --- |
| **Heading text** (required) | Up to 200 characters. |
| **Level** (required) | 2 to 6. Level 2 starts a main part of the page, 3 a part of that, and so on. The page title is the only level 1. |

- Stops publishing: a heading that skips a level, such as a level 4 straight after a level 2.

## Text

Formatted text: paragraphs, lists, links and more.

| Field | Limits |
| --- | --- |
| **Text** (required) | Up to 100,000 characters, written as HTML. |

You can use paragraphs `<p>`, headings `<h2>` to `<h6>`, lists `<ul>`, `<ol>` and `<li>`, links `<a href="…">`,
`<strong>` and `<em>`. Quotes, code, abbreviations and description lists work too, and a text or background colour
written as `style="color: #1a1a1a"` or `style="background-color: #ffeb3b"`. Anything else is removed when you save.
Scripts, embedded frames and pictures are always removed; use an Image block for pictures. Text pasted from other
editors keeps its words.

- Stops publishing: a link with no text; a level 1 heading or a heading that skips a level; a colour that is too
  faint to read (a contrast below 4.5 to 1, in light or dark mode); a language code that isn't real.
- Suggestion: link text that doesn't say where it goes, such as "click here", "read more" or a bare web address.

## Image

One picture from the media library.

| Field | Limits |
| --- | --- |
| **Image** (required) | A picture from the media library. |
| **Alternative text** | Up to 1,000 characters. What the picture shows, for people who can't see it. |
| **The picture is only decoration: screen readers skip it** | Tick it only for a picture that adds nothing to the page. |
| **Caption** | Up to 2,000 characters. Shown under the picture, for everyone. |

- Stops publishing: no alternative text, when the picture isn't marked as decoration.
- Suggestion: alternative text that looks like a file name, starts with "image of", or is longer than 250 characters.

## Gallery

A set of pictures, shown as a list so that screen readers say how many there are.

| Field | Limits |
| --- | --- |
| **Pictures** (required) | 1 to 50 pictures. Each has **Picture**, **Alternative text**, the decoration tick box and **Caption**, as in an Image block. |
| **Caption of the gallery** | Up to 2,000 characters. Shown under all the pictures. |

Every picture is checked like an Image block.

## Video

A video from the media library, with captions and a transcript. It never starts by itself.

| Field | Limits |
| --- | --- |
| **Video** (required) | A video from the media library. |
| **Title** (required) | Up to 200 characters. Names the video for screen readers and search engines. |
| **Captions** | A WebVTT captions file (`.vtt`) from the media library. |
| **Language of the captions** | A language tag such as `en-GB`. Leave it empty for the page's language. |
| **The video has no speech or important sounds, so it needs no captions** | A tick box. |
| **Poster picture** | A picture shown before the video plays. |
| **Transcript** | Up to 100,000 characters. Everything said and shown, as text. Shown under the video behind a **Transcript** button. |

- Stops publishing: a video with speech but no captions; a video marked as having no speech, but with no transcript
  that describes what it shows.
- Suggestion: a video without a transcript.

A captions file is a plain text file whose first line is `WEBVTT`. Upload it to the media library like any other
file.

## Audio

A sound recording from the media library, with a transcript. It never starts by itself.

| Field | Limits |
| --- | --- |
| **Audio** (required) | A sound file from the media library. |
| **Title** (required) | Up to 200 characters. Names the recording for screen readers and search engines. |
| **Transcript** | Up to 100,000 characters. Everything said, as text. Shown under the player behind a **Transcript** button. |

- Stops publishing: no transcript.

## File download

A link to a file from the media library, such as a PDF.

| Field | Limits |
| --- | --- |
| **File** (required) | A file from the media library. |
| **Link text** (required) | Up to 200 characters. What the file is, for example "Annual report 2026". |

SightPress adds the file's type and size to the link, written for the page's language: "Annual report 2026 (PDF,
2.3 MB)".

- Suggestion: link text that says nothing on its own, such as "here" or "details".

## Call to action

A button that leads somewhere.

| Field | Limits |
| --- | --- |
| **Button text** (required) | Up to 80 characters. Say what happens, for example "Book a visit". |
| **Link address** (required) | Up to 2,000 characters. A page of this website, such as `/contact`, a full address such as `https://example.org`, or a `mailto:` or `tel:` address. |
| **Style** | Primary for the main action of the page, secondary for others. |

- Suggestion: button text that doesn't say what happens, such as "click here".

## Columns

Two to four columns side by side on wide screens, one after another on narrow screens and when zoomed in. The
columns are read in order: the first column, then the second.

| Field | Limits |
| --- | --- |
| **Columns** (required) | 2 to 4 columns. |
| **Blocks** in each column | Up to 50 blocks of any kind except Columns. |

Every block in a column is checked as if it were on the page.

## Contact form

A form visitors use to send you a message. It has fields for their name, e-mail address and message.

| Field | Limits |
| --- | --- |
| **Send messages to** (required) | The e-mail address that gets the messages. It is never shown on the page. |
| **Email subject** | Up to 100 characters. The subject of the e-mails you get. |
| **Button text** | Up to 40 characters. |
| **Message after sending** | Up to 300 characters. The thank-you message visitors see. |

- Messages arrive from the website's own address. Reply to them as usual: the reply goes to the visitor.
- Spam is kept out without a puzzle: a hidden field that people never see, and at most 5 messages per visitor every
  10 minutes.
- When something is missing, the visitor comes back to the same page with a list of what to fix, which gets focus,
  and what they typed is kept. After sending, the thank-you message gets focus.
- The form only works on a published, public page.

## For developers

A module adds a kind of block with `$registrar->blockTypes()->add(MyBlock::class)`, a class that implements
`SightPress\Module\Block\BlockType`. Names of other modules' kinds need a vendor prefix, such as `acme.event_card`.

The fields come from the block's JSON Schema (draft 2020-12). Give every field a `title` (its label) and a
`description` (its hint). Core enforces only these keywords, and refuses a schema with any other, so no rule is ever
skipped without notice: `type`, `properties`, `required`, `additionalProperties`, `enum`, `minLength`, `maxLength`,
`pattern`, `minimum`, `maximum`, `items`, `minItems`, `maxItems`, `format` and `contentMediaType: text/html` (rich
text, cleaned on save). The formats are `link`, `email`, `media`, `media-image`, `media-video`, `media-audio`,
`media-document` and `media-captions`.

Core's own kinds are named `heading`, `rich_text`, `image`, `gallery`, `video`, `audio`, `file_download`,
`call_to_action`, `columns` and `contact_form`.
