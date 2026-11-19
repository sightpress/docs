# Use the AI assistant

The AI assistant suggests text for your website: summaries, descriptions for search engines, alt text for pictures,
drafts of pages and translations. It also answers questions about using SightPress. It uses an AI service you
choose, with **your own key** from that service, so you pay the service directly for what you use.

The assistant only ever **suggests**. Nothing it writes is used until a person inserts it and saves. Alt text and
translations it writes are marked until a person has checked them, and pages that use them can't be published
before that.

The AI assistant is an optional feature, **off** for new websites. Nothing is sent to any AI service until an admin
sets it up and someone asks for a suggestion.

## Before you start

- You need to be an **admin** of the website to turn the assistant on and set it up.
- You need an **API key** from one of the AI services SightPress works with: Anthropic (Claude), OpenAI (GPT),
  Mistral AI, or a service or your own server that works like OpenAI's. Each service's website explains how to get
  a key and what it costs. Check where the service processes data before you choose it: Mistral AI, for example,
  processes it in the EU.

## Turn the assistant on

1. Sign in to the admin panel, for example at `http://localhost:8080/admin`.
2. In the admin menu, choose **Features**.
3. Find **AI assistant** and choose **Turn on AI assistant**.
4. You should see that its status is now "On". A new screen appears in the admin menu under **Site settings**:
   **AI assistant**.

See [Turn features on or off](turn-features-on-or-off.md) for more about features.

## Choose the service and enter your key

1. In the admin menu, choose **Site settings**, then **AI assistant**.
2. Under **Status** you should see "Not ready yet", followed by what is missing.
3. Under **AI service**, choose a service. Each one says what it is.
4. Choose a **Model**. Bigger models write better and cost more. For an OpenAI-compatible service, type the
   **Model name** and the **Address of the service** instead (it must start with `https://`).
5. Paste your key into **New API key** and choose **Save the AI service**.
6. You should see "The AI service is saved." Under **Status** it now says "Ready", and the page shows only the last
   four characters of your key, for example "Saved API key: ••••1234". The key is stored encrypted and never shown
   again. To change it, paste a new one; to delete it, choose **Remove key**.
7. Choose **Test connection**. You should see "The connection works", or what went wrong (for example a key the
   service didn't accept).

If your organisation chooses the AI service for every website, this screen shows its choice and you can't change
it.

## Choose who may use it, and a budget

1. On the same screen, under **Who may use it, and how much**, tick the roles that may ask the assistant. Admins
   always may.
2. In **Monthly budget in tokens**, enter how many tokens (pieces of words, sent and received) the website may use
   each month. Leave it empty to use your organisation's budget; enter 0 for no limit.
3. Choose **Save who may use it**. The screen shows how much has been used this month.

Months start on the 1st. From 80% of the budget, everyone who asks is warned; at 100% the assistant stops until the
next month. Each person can ask 20 times every 10 minutes.

## Ask a question

1. On the **Dashboard** (or at the end of a page's editor), find **Ask the assistant**.
2. Type your question in **Your question**, for example "How do I add a page to the main menu?", and choose
   **Ask**.
3. You should hear or see "Working on it…", then "Suggestion ready". The answer appears under its own heading,
   which gets focus. It ends with a link to the part of this documentation it used.

The assistant only tells you about screens your role can use. If something needs an admin, it says so.

On a page's editor you can tick **Share this page with the assistant** to ask about the page itself. It reads the
saved page, so save your changes first.

## Get a suggestion for a field

On a page's editor, under **Summary**, **Title in search results** and **Description for search engines**, there is a
**Suggest** button.

1. Save the page first: the assistant reads the saved page.
2. Choose, for example, **Suggest a summary**.
3. The suggestion appears in **Ask the assistant**, under its own heading, which gets focus.
4. Choose **Insert** to put it in the field (focus goes back to the field), **Copy** to copy it, **Try again** for
   another one, or **Discard**.
5. Check the text, change it if needed, and choose **Save page**.

Without JavaScript, the same buttons open a page with the suggestion; **Insert** takes you back to the field with
the text in it.

## Rewrite text, draft a page, or explain a check

- **Rewrite text** (in every **Ask the assistant**): paste some text, choose **Make it** simpler, shorter, friendlier
  or more formal, and choose **Rewrite**. Copy the result into your page.
- **Draft this page** (on a page's editor): say what the page should be about and choose **Draft this page**.
  Choose **Add to the end of the page**: the headings and text are added to the page's blocks, not yet saved.
  Things to check or fill in are in [square brackets].
- **Explain this** (next to each publish check on a page's editor): the assistant explains what the check means
  and how to fix it.

## Alt text for pictures

The assistant can describe pictures only with a model that can see pictures (most listed models can).

1. In the admin menu, choose **Media** and open a picture.
2. Under the alt text field, choose **Suggest alt text**.
3. Choose **Insert**, check the text, and choose **Save details**.
4. You should see "The AI assistant suggested this alt text." Pages that show this text can't be published until
   someone checks it.
5. When the text describes the picture well, tick **I checked the alt text: it describes the picture** and choose
   **Save details**. Changing the text and saving it counts as checking it too.

## Draft a translation

On a website in more than one language:

1. Open a page's editor. In the **Translations** section, choose **Draft a translation into** followed by the
   language, **with the AI assistant**.
2. The new translation opens in the editor, with a notice: "The AI assistant drafted this translation, and nobody
   has checked it yet." Its blocks, pictures and links are the same as the original's; only the words are
   translated.
3. Read it and change what needs changing. Then save the page, or choose **I checked this translation**.
4. Only then can the translation be published. Until then the list of pages and the page's older versions say
   "drafted by the AI assistant, not checked yet".

## What is sent, and what is kept

- Sent to the AI service: your question, and the page or picture you ask a suggestion for. Never lists of people,
  email addresses, the audit log or other pages.
- Kept on your website: when the assistant was used, by whom, for what, with which service and model, and how many
  tokens. Never the question, the page text or the answer.
- The AI service handles what it receives under its own terms.

## Turn the assistant off

On the **Features** screen, choose **Turn off AI assistant**. The panel, the Suggest buttons and the AI assistant
screen disappear. The settings, the key and the record of what was used are kept, so turning it on again brings
everything back.

## From the command line

Whoever runs your SightPress installation can set the assistant up with `site:assistant:set`, test it with
`site:assistant:test`, see what was used with `site:assistant:usage` and turn it off with
`site:assistant:disable`. See the [command line reference](../reference/command-line.md#ai-assistant).
