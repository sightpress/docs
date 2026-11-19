# Review the interface

SightPress is led by blind people. Sighted reviewers help us check what is hard to check without sight. You don't
need to know anything about design or code: if something looks wrong to you, it probably is.

## What to check

Go through a few screens of the admin panel and of a website (see [Getting started](../getting-started.md) to run
your own) and look for these:

1. **Contrast.** Is any text, icon or border hard to see? Check in light mode, in dark mode, and with a high
   contrast theme (on Windows, *Settings → Accessibility → Contrast themes*).
2. **Focus.** Press <kbd>Tab</kbd> again and again. Can you always see where you are?
3. **Zoom.** Zoom the browser to 200% and to 400% (<kbd>Ctrl</kbd> and <kbd>+</kbd>, or <kbd>Cmd</kbd> and
   <kbd>+</kbd> on a Mac). Is any text cut off or on top of other text? Do you have to scroll sideways to read a line?
4. **Small screens.** Make the window very narrow, about the width of a small phone. Does everything still fit?
5. **Spacing.** Is anything squashed together or badly lined up?
6. **Pictures.** Do the icons, the generated profile pictures, the sharing images and the charts look right? Are
   the screenshots in the documentation up to date?
7. **Labels.** Does the text you see on a button or a field match what it does? A screen reader announces the same
   words, so they must make sense on their own.
8. **Colour alone.** Is any information shown only by colour, such as red for an error, without words?

## How to describe a screenshot

A screenshot helps, but whoever reads your report may be blind. Always add a text description:

- say which screen it is and what you were doing, for example "The Analytics screen, in dark mode, at 200% zoom";
- say what is wrong and where, for example "The legend of the visits chart overlaps the last bar label";
- say what you expected, for example "The legend should be below the chart".

If you can't describe it, say so: we'll ask someone sighted to look.

## Send your review

On GitHub, [open an issue](https://github.com/sightpress/core/issues/new/choose) and choose **Interface review**.
The form asks for the screen, your browser, zoom level and colour mode, what looks wrong, and a description of any
screenshot. Without a GitHub account, email the same to <contribute@sightpress.org>.
