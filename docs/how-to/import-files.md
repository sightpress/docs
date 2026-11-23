# Import files from a folder

If you have many files, or files too large for the browser, you can add them to a website's media library from the
server. This is done by whoever runs your SightPress installation. Website limits on file size don't apply, but every
safety check does.

Start each command with `docker compose run --rm --no-deps php`, as in
[Try the command line tool](try-the-command-line-tool.md). The folder must be one the command can read: with Docker,
put it inside the `skeleton` folder.

1. Import a folder into the website `demo`, including the folders inside it:

    ```sh
    bin/sightpress site:media:import demo imports/ --recursive
    ```

2. Read the result. Each file gets a line, then a summary such as `Imported: 12. Already present: 1. Refused: 1.`
3. Open **Media** in the admin panel. New files are unpublished until you describe and publish them.

## Options

| Option | What it does |
| --- | --- |
| `--recursive` | Also imports the files inside folders. |
| `--publish` | Publishes the new files straight away. |
| `--alt="…"` | Gives every new picture this description. Use it only if it is true of all of them. |
| `--locale=pt-PT` | The language of that description. The default is the website's main language. |
| `--memory-limit=2G` | Gives this run more memory, for very large pictures. |

## What happens to each file

- **Imported:** the file passed the checks and is in the library.
- **Already present:** the library already has exactly this file, so it isn't stored twice.
- **Refused:** the line says why, for example that a file isn't really the type its name says. The import carries on
  with the other files, and the command ends with an error so that scripts notice.

A very large picture is refused with the memory it needs. Run the command again with `--memory-limit=` and that value.
