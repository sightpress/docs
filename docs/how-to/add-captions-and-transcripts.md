# Add captions and transcripts

**Captions** show the words and important sounds of a video as it plays. A **transcript** gives the same information
as text that anyone can read, including people using a screen reader or a braille display. SightPress asks for them
before a page with a video or recording can be published.

## Add captions to a video

Captions are a WebVTT file, with a name ending in `.vtt`. Many video and subtitle tools can export one.

1. In the admin menu, choose **Media**, then open the video.
2. Find the **Captions** heading. There is one file field per language of the website.
3. Choose the file under **Captions file in …**, then choose **Upload captions in …**.
4. You should see "The captions have been saved." If the file has a mistake, it is refused with the line to fix, for
   example "line 3: the cue must end after it starts".

Visitors get a captions choice per language in the video player. It starts on the language of the page, or else the
website's main language.

## Write a transcript

1. In **Media**, open the video or recording.
2. Write the transcript for each language. Include who is speaking and any important sounds.
3. Choose **Save details**.

Visitors open it with the **Transcript** button under the player, by mouse or keyboard. Transcripts of public files
are also in each page's Markdown version and in `llms-full.txt`, for AI readers.

!!! note "A video with no speech"

    A video with no speech still needs a description of what happens in its transcript.

## Tidy a transcript with the AI assistant

If the [AI assistant](use-the-ai-assistant.md) is on, it can add punctuation, paragraphs and speaker labels to a saved
transcript of up to 6,000 characters. It can't change the words: an answer that does is refused.

1. Choose **Tidy up the transcript**, then **Insert**.
2. Read the result against the recording, and correct it.
3. Tick **I checked the transcript against the recording**, then choose **Save details**.

Until someone has checked it, pages that show it can't be published.

## Privacy

Captions and transcripts can name or quote people. They are deleted with the file.
