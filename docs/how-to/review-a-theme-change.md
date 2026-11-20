# Review a theme change

When someone changes a theme, for example the default theme's colours or the layout of its pages, the change arrives
as a pull request on GitHub. You can see exactly what it looks like without installing anything: every theme pull
request comes with a preview you download and open in your browser. You don't need to know anything about code.

## Find the preview

1. Open the pull request on GitHub.
2. Find the comment titled **Theme preview**, near the top of the conversation. A new change to the pull request
   updates the same comment; it never adds another one.
3. The comment says, in words, what the theme looks like, and how many accessibility problems the automatic check
   found in light mode, dark mode and forced colours (Windows contrast themes). "No violations" in all three is the
   goal.
4. Select **Download the preview**. GitHub asks you to sign in first; the download is a zip file and is kept for 30
   days.

## Open it

1. Unzip the file.
2. Open `index.html` in any browser. It works offline: nothing in it loads from the internet, and it needs no server.

The page is the same for every theme, so you can compare one theme with another:

- a top bar with the site name and links to each part of the page;
- a band with the theme's name, its description and a **Download** button;
- one example of every kind of block: headings, text, a button, a picture, a file, two columns, a gallery, a
  recording, a video, and a contact form (it can't send anything: it's a preview);
- the footer.

The zip also holds `screenshot.png` and `screenshot-dark.png`, the top of the page in light and dark mode,
`page.png`, the whole page, and `axe.txt`, the full report of the accessibility check.

## What to check

Go through the page as you would for [Review the interface](review-the-interface.md):

1. **Contrast.** Is any text, link, button or border hard to see? Switch your computer or browser to dark mode and
   look again, and try a contrast theme on Windows.
2. **Focus.** Press <kbd>Tab</kbd> again and again. Can you always see where you are?
3. **Zoom and small screens.** Zoom to 200% and 400%, and make the window very narrow. Is anything cut off, on top of
   something else, or only readable by scrolling sideways?
4. **The description.** Does the description in the comment match what you see? It is what people who can't see
   the screenshots get instead, so it must be right.
5. **The change itself.** The pull request says what it changes. Does the page show that, and nothing else?

## Describe what you see

Write your review as a comment on the pull request. Whoever reads it may be blind, so say it in words:

- where on the page, and in which mode, for example "The Pictures and files part, in dark mode, at 200% zoom";
- what is wrong, for example "The file link is dark blue on the dark background and hard to read";
- what you expected, for example "The link should be as light as the other links in dark mode".

If you add a screenshot, describe it in the same words. If everything looks right, say so too: "Checked in light
and dark mode at 100% and 200% zoom; looks good" helps as much as finding a problem.
