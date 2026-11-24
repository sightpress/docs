# Create a custom role

A **role** is a set of things people may do on your website. Everyone has one role. Every website starts with four:
**Admin** (everything), **Editor**, **Contributor** and **Member** (reads members-only pages, nothing more). You can
change Editor and Contributor, and make your own roles, for example "Shop staff" who may upload pictures and change
the menus but not publish pages.

You need to be an **admin**, or have a role that may **Manage roles**.

## Create a role

1. Choose **Roles** in the admin menu. You see every role, how many permissions it has and how many people have it.
2. Choose **Create a role**.
3. In **Name of the role**, type a name people will understand, for example `Shop staff`.
4. Tick what people with this role may do. The permissions are grouped by area (Pages, Media, Menus, People …), and
   each one says what it allows. For example, under **Media** tick **Upload media**, and under **Menus** tick
   **Manage menus**.
5. Choose **Create role**. You should see "Created the role “Shop staff”."

A role with any permission can also open the admin panel: **Use the admin panel** is added for you.

## Give the role to someone

1. Choose **People** in the admin menu, then the person's name.
2. Under **Change role**, choose your new role and then **Change role**. You should see, for example, "Cleo’s role is
   now Shop staff."

The change applies from their next click.

## Change or delete a role

- Open the role from **Roles**, change its name or the ticked boxes, and choose **Save role**.
- To delete it, choose **Delete this role** at the bottom of its page. A role that someone still has can't be
  deleted: give those people another role first.

## What you can't do

These rules keep your website safe. If you try, the page tells you why, next to the box or name concerned, and keeps
what you ticked:

- **Admin** and **Member** can't be changed, renamed or deleted.
- The role people get when they [sign up](let-people-sign-up.md) can only have permissions that are safe for people
  you don't know, such as writing pages and sending them for review.
- You can't take **Manage roles** away from your own role, so you can't lock yourself out. Ask another admin.
- Features that are [turned off](turn-features-on-or-off.md) don't show their permissions. A role keeps them, so
  they come back when you turn the feature on again.

Every change to a role is written to the **Audit log**, under **Roles**.

## On the command line

The same rules apply on the [command line](try-the-command-line-tool.md), where a website is named by its slug:

```bash
docker compose run --rm --no-deps php bin/sightpress site:role:list demo
docker compose run --rm --no-deps php bin/sightpress site:role:create demo shop_staff --label="Shop staff" --permissions=media.upload,menu.manage
docker compose run --rm --no-deps php bin/sightpress site:role:grant demo shop_staff page.create
docker compose run --rm --no-deps php bin/sightpress site:role:revoke demo shop_staff page.create
docker compose run --rm --no-deps php bin/sightpress site:role:delete demo shop_staff
```
