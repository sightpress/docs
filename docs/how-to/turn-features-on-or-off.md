# Turn features on or off

Some parts of SightPress are optional **features**, for example site search or visitor statistics. Each website has
its own: an admin can turn a feature off for one website and keep it on for the others. Turning a feature off hides
everything it adds, on the website and in the admin panel, and **keeps its data**, so turning it on again brings it
back as it was.

You need to be an **admin** of the website. Editors, contributors and members don't see the *Features* screen.

!!! note "Optional features in this version"

    This version of SightPress has three optional features of its own: **Site search** (see
    [Search your website](search-your-website.md)), **Analytics** (see
    [See who visits your website](see-who-visits-your-website.md)), both on for new websites, and the
    **AI assistant** (see [Use the AI assistant](use-the-ai-assistant.md)), off for new websites. Features installed
    as extra packages (modules) show up on the screen too: see [Add a module](add-a-module.md).

## See the features of a website

1. Sign in to the admin panel of the website, for example `http://localhost:8080/admin`.
2. In the admin menu, choose **Features**.
3. You should see one section for each optional feature, with its name as a heading, what it does, its **Status**
   ("On" or "Off") and what happens when it is off.

A feature that says **"set by your organisation"** was chosen for every website by whoever runs your SightPress
installation. It has no button, and it can't be changed from the website.

## Turn a feature off

1. On the *Features* screen, find the feature and choose **Turn off** followed by its name, for example
   **Turn off Events**.
2. A page asks you to confirm and says what will happen. Choose **Turn off …** again to go ahead, or
   **Keep … on** to go back without changing anything.
3. You are back on the *Features* screen, at the feature's heading. A message at the top says that it is off and that
   its data is kept. Screen readers read the message out.
4. Check: the pages the feature added now answer "not found", and its screens have left the admin menu.

If another feature needs this one, SightPress refuses and names that feature: turn that one off first.

## Turn a feature on

1. On the *Features* screen, choose **Turn on** followed by the feature's name.
2. You are back at the feature's heading, with the message "… is on."
3. Check: its pages and screens are back, with the data they had before.

If the feature needs another feature that is off, SightPress refuses and names the feature to turn on first.

## Delete a feature's data

This removes everything the feature stored for the website, **for good**. You can only do it while the feature is off.

1. Turn the feature off (see above).
2. Under the feature, choose **Delete the data of …**. The link only appears when there is data to delete.
3. A page asks you to confirm. Choose **Delete the data of …** to go ahead, or **Keep the data** to go back.
4. The message says the data was deleted. Turning the feature on again later starts afresh, with no data.

Every change on this screen is written to the website's **Audit log**, with who made it and when.

## With the command line tool

The same can be done in a terminal, in the `skeleton` folder. Replace `demo` with the short name of your website and
`acme.events` with the feature's id, which the first command shows.

1. List the features of a website and whether each one is on:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:module:list demo
    ```

2. Turn one on, or off:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:module:enable demo acme.events
    docker compose run --rm --no-deps php bin/sightpress site:module:disable demo acme.events
    ```

3. Turn one off and delete its data for good:

    ```sh
    docker compose run --rm --no-deps php bin/sightpress site:module:disable demo acme.events --purge-data
    ```

When you create a website, `site:create demo --without=acme.events` starts it with that feature off.

## For whoever runs the installation

In `config/tenant.php`, the `modules` section changes the features of **every** website:

- `defaults` says which features new websites start with, for example `'defaults' => ['analytics' => false]`.
  Websites that already exist keep what they have.
- `locked` turns a feature on or off for every website, for example `'locked' => ['analytics' => false]`. Admins then
  see "set by your organisation" and can't change it, and neither can the command line tool.
