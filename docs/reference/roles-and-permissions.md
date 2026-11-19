# Roles and permissions

Everyone who signs in to a SightPress website has one **role**. A role is a set of **permissions**: each permission
lets a person do one thing, such as publish pages. Each website keeps its own people and roles, so someone can be an
editor on one website and a contributor on another.

## The four roles

Every new website has these roles. The admin panel describes each one in the same words when you invite someone or
change their role.

| Role | What the admin panel says | In short |
| --- | --- | --- |
| **Admin** | Can do everything, including inviting people, changing roles and site settings. | Runs the website. |
| **Editor** | Can write, review and publish any page, and invite contributors. | Looks after the content. |
| **Contributor** | Can write pages and send them for review, but not publish them. | Writes. |
| **Member** | Can sign in and read the site, but not use the admin panel. | Reads. |

The admin and member roles can't be renamed or deleted. The member role never has any permission. Every new website
has one admin: the person named when it was created (see [Create a website](../how-to/create-a-site.md)).

## What each role can do

| Permission | What it lets you do | Admin | Editor | Contributor | Member |
| --- | --- | --- | --- | --- | --- |
| Use the admin panel | Sign in to the admin panel. | yes | yes | yes | no |
| Create pages | Start new pages. | yes | yes | yes | no |
| Edit own pages | Change pages they wrote. | yes | yes | yes | no |
| Edit any page | Change pages anyone wrote. | yes | yes | no | no |
| Submit pages for review | Send a page to the editors. | yes | yes | yes | no |
| Publish pages | Approve, publish and unpublish pages. | yes | yes | no | no |
| Archive pages | Take pages off the site and keep them. | yes | yes | no | no |
| Delete pages | Delete pages for good. | yes | yes | no | no |
| Upload media | Add pictures, video, audio and files. | yes | yes | yes | no |
| Publish media | Make uploaded files public. | yes | yes | no | no |
| Manage menus | Change the main and footer menus. | yes | yes | no | no |
| Invite people | Invite people to the site with a role they are allowed to give. | yes | yes | no | no |
| Manage users | Change the role of any user, and deactivate accounts. | yes | no | no | no |
| Manage roles | Create roles and change what they allow. | yes | no | no | no |
| Manage site settings | Change the site's name, profile, branding and other settings. | yes | no | no | no |
| Manage features | Turn the site's optional features on and off, and delete their data ([how](../how-to/turn-features-on-or-off.md)). | yes | no | no | no |
| Apply themes | Choose the site's theme. | yes | no | no | no |
| View the audit log | See who changed what, and when. | yes | no | no | no |
| View analytics | See how many people visit the site, what they read and where they come from ([how](../how-to/see-who-visits-your-website.md)). | yes | yes | no | no |
| Manage analytics | Choose how long analytics are kept, and delete them. | yes | no | no | no |
| Use the AI assistant | Ask the AI assistant for suggestions, which they check before using ([how](../how-to/use-the-ai-assistant.md)). People who sign up may have it. | yes | yes | yes | no |
| Manage the AI assistant | Choose the AI service, its key, the monthly budget and who may use the assistant. | yes | no | no | no |

Archiving pages, deleting pages, more themes and editing roles in the admin panel come in later versions; the
permissions already exist so that roles are ready for them.

## Pages and roles

Every page goes through the same steps before visitors can see it. Editors' own pages go through review too.

| Step | From | To | Who can take it |
| --- | --- | --- | --- |
| **Submit for review** | Draft | Waiting for review | The person who wrote the page, or anyone who can edit any page |
| **Send back to draft** | Waiting for review | Draft | Editors and admins |
| **Approve** | Waiting for review | Approved | Editors and admins |
| **Publish** | Approved | Published | Editors and admins |
| **Unpublish** | Published | Draft | Editors and admins |

Only published pages are on the public website. When a page is submitted, every editor and admin (except the person
who submitted it) gets an e-mail about it. Contributors see their own pages and published pages in the admin panel,
and can't change a page while it waits for review.

## Who can invite whom

- **Admins** can invite someone with any role.
- **Editors** can invite **contributors** only. Their form shows just that role.
- Contributors and members can't invite anyone.

The invited person gets an e-mail with a 6-digit code, never a link. See [Codes we send you](../how-to/codes-we-send-you.md).

## Change someone's role

Admins do this in the admin panel:

1. Choose **People** in the admin menu. You should see a table of everyone with an account.
2. Choose the person's name.
3. Under **Change role**, choose the new role, then choose **Change role**. You should hear or see, for example,
   "Cleo’s role is now Editor." The new role applies from the person's next page.

On the same page, **Deactivate account** stops someone signing in straight away; their pages stay. **Reactivate**
lets them sign in again with their old password. Nobody can change their own role or deactivate themselves, so a
website always keeps someone who can manage people.

## For developers

Permission names, for modules and the command line: `admin.access`, `page.create`, `page.edit.own`, `page.edit.any`,
`page.submit`, `page.publish`, `page.archive`, `page.delete`, `media.upload`, `media.publish`, `menu.manage`,
`user.invite`, `user.manage`, `role.manage`, `settings.manage`, `module.manage`, `theme.apply` and `audit.view`.

- A role that has any permission also has `admin.access`.
- A module adds permissions with `$registrar->permissions()->add(...)`. They are copied into every website and given
  to their default roles, and always to the admin role.
- The role people get when they register themselves (site setting `registration_role`, default `member`) may only
  hold permissions that are safe for strangers: `admin.access`, `page.create`, `page.edit.own`, `page.submit` and
  `media.upload`. Self-registration arrives in a later version.
