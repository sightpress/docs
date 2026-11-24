# Create a module

In this tutorial you make a SightPress module of your own: **Events**. Each event gets a page on the website, with
structured data search engines understand and a Markdown version for AI readers. Admins add events on an Events
screen, editors can put an event card on any page, and events appear in the sitemap, in `llms.txt` and in site search.
You start from the module template, write the module, test it, install it on a website and publish it.

You need some experience with PHP and the command line, and:

- PHP 8.5 and Composer 2;
- a SightPress project to try the module in (see [Getting started](../getting-started.md)).

A module only uses `sightpress/module-interface`, the contract between SightPress and its modules. It never depends
on SightPress itself, so it keeps working when SightPress changes inside.

## 1. Create your module from the template

```sh
composer create-project sightpress/module-template events
cd events
composer check
```

`composer check` runs the code style, static analysis, the unit tests (every line of `src/` must be tested) and an
integration test that installs the module in SightPress and turns it off and on. Each check should pass.

On GitHub, you can instead open the [module template](https://github.com/sightpress/module-template) and choose
**Use this template**, then clone your new repository and run `composer install`.

## 2. Make it yours

A module has an id: lower-case words starting with your vendor name. Ours is `acme.events`. Its templates and texts
use a shorter name, `acme_events`.

1. In `composer.json`, change `name` to `acme/events`, write your own `description`, `homepage` and `support`, and
   change the namespace `Acme\\MyModule\\` to `Acme\\Events\\` in `autoload` and `autoload-dev`.
2. In `src/Migrations.php`, change `Acme\MyModule` to `Acme\Events` everywhere it appears.
3. Rename `translations/my_module.en-GB.xlf` to `translations/acme_events.en-GB.xlf`.
4. Delete `src/MyModule.php` and the template's tests, `tests/Unit/MyModuleTest.php` and
   `tests/Integration/InstallTest.php`: you write your own below.

In `composer.json`, replace `extra` with:

```json
"extra": {
    "sightpress": {
        "module": "Acme\\Events\\EventsModule",
        "features": {
            "acme.events": {
                "name": "Events",
                "description": "A page for each event, with its date and time, and an Events screen to add them.",
                "when-off": "Event pages answer “not found”, the Events screen leaves the admin panel and event cards are hidden. Events are kept."
            }
        }
    }
}
```

`module` names the class SightPress creates. Under `features`, the name, description and "when it is off" text are
what site admins read on the **Features** screen, so write them in plain words.

## 3. Store events

Events live in the site's database, in a table of the module's own. Add the libraries the module uses:

```sh
composer require doctrine/dbal:^4 doctrine/migrations:^3 psr/http-factory:^1
composer require --dev nyholm/psr7:^1
```

A migration creates the table. SightPress runs it when a site turns the module on, and undoes it (with `down()`) when
an admin deletes the module's data. It must work on every database SightPress supports (SQLite, MySQL, MariaDB and
PostgreSQL), so it describes the table rather than writing SQL.

Create `migrations/Version20261201000000.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Migrations;

use Doctrine\DBAL\Platforms\AbstractMySQLPlatform;
use Doctrine\DBAL\Schema\PrimaryKeyConstraint;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * The events table. Prefix your tables with your module's id, so they never clash with another module's.
 */
final class Version20261201000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Events: a table of events.';
    }

    public function up(Schema $schema): void
    {
        $table = $schema->createTable('acme_events_event');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('slug', 'string', ['length' => 100]);
        $table->addColumn('title', 'string', ['length' => 200]);
        $table->addColumn('starts_at', 'string', ['length' => 16]);
        $table->addColumn('updated_at', 'datetime_immutable');
        $table->addPrimaryKeyConstraint(PrimaryKeyConstraint::editor()->setUnquotedColumnNames('id')->create());
        $table->addUniqueIndex(['slug'], 'acme_events_event_slug');
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('acme_events_event');
    }

    public function isTransactional(): bool
    {
        // MySQL and MariaDB commit schema changes on their own.
        return !$this->platform instanceof AbstractMySQLPlatform;
    }
}
```

One event, and the store that reads and writes them:

Create `src/Event.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use DateTimeImmutable;

/**
 * One event: its address (`/events/<slug>`), its name, and when it starts (local time, `2026-12-05T10:00`).
 */
final readonly class Event
{
    public function __construct(
        public string $slug,
        public string $title,
        public string $startsAt,
        public DateTimeImmutable $updatedAt,
    ) {
    }

    public function path(): string
    {
        return '/events/' . $this->slug;
    }
}
```

Create `src/EventStore.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;

/**
 * The site's events, in the module's own table, through the site's database connection.
 */
final readonly class EventStore
{
    public function __construct(private Connection $connection)
    {
    }

    /**
     * Adds an event; its address comes from its name.
     */
    public function add(string $title, string $startsAt): Event
    {
        $slug = trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower($title)), '-');
        $event = new Event($slug === '' ? 'event' : $slug, $title, $startsAt, new DateTimeImmutable('now', new DateTimeZone('UTC')));
        $this->connection->insert('acme_events_event', [
            'slug' => $event->slug,
            'title' => $event->title,
            'starts_at' => $event->startsAt,
            'updated_at' => $event->updatedAt,
        ], ['updated_at' => Types::DATETIME_IMMUTABLE]);

        return $event;
    }

    /**
     * @return list<Event> soonest first
     */
    public function all(): array
    {
        $rows = $this->connection->createQueryBuilder()
            ->select('slug', 'title', 'starts_at', 'updated_at')
            ->from('acme_events_event')
            ->orderBy('starts_at')
            ->fetchAllAssociative();

        return array_values(array_map(self::event(...), $rows));
    }

    public function find(string $slug): ?Event
    {
        $row = $this->connection->createQueryBuilder()
            ->select('slug', 'title', 'starts_at', 'updated_at')
            ->from('acme_events_event')
            ->where('slug = :slug')
            ->setParameter('slug', $slug)
            ->fetchAssociative();

        return $row === false ? null : self::event($row);
    }

    /**
     * @param array<string, mixed> $row
     */
    private static function event(array $row): Event
    {
        $text = static fn (string $column): string => is_string($row[$column] ?? null) ? $row[$column] : '';

        return new Event($text('slug'), $text('title'), $text('starts_at'), new DateTimeImmutable($text('updated_at'), new DateTimeZone('UTC')));
    }
}
```

## 4. A permission, an admin screen and a menu item

The **Events** screen lists the events and has a form to add one. SightPress checks the permission before the
handler runs, puts the screen in the admin panel with its heading, and checks the form's security token. Errors are
shown next to their field, with the field marked as invalid, so screen reader users hear what to fix.

Create `src/AdminHandler.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SightPress\Module\Http\AdminScreen;
use SightPress\Module\Http\Screens;
use SightPress\Module\Text\Message;

/**
 * `/admin/events`: the list of events and a form to add one. SightPress checks the `events.manage` permission before
 * this runs (see the routes in {@see EventsModule}), draws the admin panel around it and checks the form's CSRF token.
 */
final readonly class AdminHandler implements RequestHandlerInterface
{
    public const string PATH = '/admin/events';

    public function __construct(private Screens $screens, private EventStore $events)
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $errors = [];
        $values = ['title' => '', 'starts_at' => ''];
        if ($request->getMethod() === 'POST') {
            $body = (array) $request->getParsedBody();
            foreach (array_keys($values) as $field) {
                $values[$field] = is_string($body[$field] ?? null) ? trim($body[$field]) : '';
            }
            if ($values['title'] === '') {
                $errors['title'] = 'Enter the name of the event.';
            }
            if (preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/', $values['starts_at']) !== 1) {
                $errors['starts_at'] = 'Enter the date and time it starts.';
            }
            if ($errors === []) {
                $event = $this->events->add($values['title'], $values['starts_at']);

                return $this->screens->redirect($request, self::PATH, new Message('Added “{title}”.', ['title' => $event->title], EventsModule::NAMESPACE));
            }
        }

        return $this->screens->admin($request, new AdminScreen(
            new Message('Events', domain: EventsModule::NAMESPACE),
            '@acme_events/admin/events.html.twig',
            ['events' => $this->events->all(), 'errors' => $errors, 'values' => $values],
            $errors === [] ? 200 : 422,
        ));
    }
}
```

Create `templates/admin/events.html.twig`:

```twig
{#- The Events screen. SightPress draws the admin panel and the page's <h1> ("Events") around it. -#}
{%- if events is empty %}
<p>{{ t('No events yet. Add the first one below.', {}, 'acme_events') }}</p>
{%- else %}
<table>
    <caption>{{ t('Events, soonest first', {}, 'acme_events') }}</caption>
    <thead>
        <tr><th scope="col">{{ t('Name', {}, 'acme_events') }}</th><th scope="col">{{ t('Starts', {}, 'acme_events') }}</th></tr>
    </thead>
    <tbody>
        {%- for event in events %}
        <tr><td><a href="{{ event.path }}">{{ event.title }}</a></td><td><time datetime="{{ event.startsAt }}">{{ event.startsAt|replace({'T': ' '}) }}</time></td></tr>
        {%- endfor %}
    </tbody>
</table>
{%- endif %}
<h2>{{ t('Add an event', {}, 'acme_events') }}</h2>
<form method="post" action="/admin/events" novalidate>
    {{ csrf_fields() }}
    {%- for field, label in {title: t('Name', {}, 'acme_events'), starts_at: t('Starts', {}, 'acme_events')} %}
    <p>
        <label for="{{ field }}">{{ label }}</label>
        {%- if errors[field] is defined %}
        <span id="{{ field }}-error" class="field-error">{{ t('Error:') }} {{ t(errors[field], {}, 'acme_events') }}</span>
        {%- endif %}
        <input id="{{ field }}" name="{{ field }}" type="{{ field == 'starts_at' ? 'datetime-local' : 'text' }}" value="{{ values[field] }}" required
            {%- if errors[field] is defined %} aria-invalid="true" aria-describedby="{{ field }}-error"{% endif %}>
    </p>
    {%- endfor %}
    <button type="submit" class="button">{{ t('Add event', {}, 'acme_events') }}</button>
</form>
```

## 5. A public page with structured data and Markdown

Each event has a page at `/events/<address>` in the site's theme. The `SeoEntry` gives it a description, its
canonical address and `Event` structured data; adding `.md` to the address gives the same page in Markdown.

Create `src/PublicHandler.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SightPress\Module\Http\PublicScreen;
use SightPress\Module\Http\Screens;
use SightPress\Module\Text\Message;

/**
 * `/events/{slug}`: the event's page in the site's theme, described for search engines by {@see SeoEntries}
 * (description, canonical address and `Event` structured data). `/events/{slug}.md` is the same page in Markdown,
 * for AI readers.
 */
final readonly class PublicHandler implements RequestHandlerInterface
{
    public function __construct(
        private Screens $screens,
        private EventStore $events,
        private SeoEntries $seo,
        private ResponseFactoryInterface $responses,
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $slug = $request->getAttribute('slug');
        $slug = is_string($slug) ? $slug : '';
        $markdown = str_ends_with($slug, '.md');
        $event = $this->events->find($markdown ? substr($slug, 0, -3) : $slug);
        if ($event === null) {
            return $this->screens->public($request, new PublicScreen(
                new Message('Event not found', domain: EventsModule::NAMESPACE),
                '@acme_events/missing.html.twig',
                status: 404,
            ));
        }
        if ($markdown) {
            $response = $this->responses->createResponse()->withHeader('Content-Type', 'text/markdown; charset=utf-8');
            $response->getBody()->write(sprintf("# %s\n\nStarts on %s.\n", $event->title, str_replace('T', ' at ', $event->startsAt)));

            return $response;
        }

        return $this->screens->public($request, new PublicScreen(
            $event->title,
            '@acme_events/event.html.twig',
            ['event' => $event],
            seo: $this->seo->entry($event),
        ));
    }
}
```

Create `templates/event.html.twig`:

```twig
{#- An event's page. SightPress draws the site's theme around it. -#}
<h1>{{ event.title }}</h1>
<p>{{ t('Starts', {}, 'acme_events') }}: <time datetime="{{ event.startsAt }}">{{ event.startsAt|replace({'T': ' '}) }}</time></p>
```

Create `templates/missing.html.twig`:

```twig
<h1>{{ t('Event not found', {}, 'acme_events') }}</h1>
<p>{{ t('There is no event at this address. It may have been removed.', {}, 'acme_events') }}</p>
```

## 6. The sitemap and site search

The same entries list every event in the sitemap and in `llms.txt`. SightPress leaves them out on websites that
aren't live yet, on members-only websites and where the module is off.

Create `src/SeoEntries.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use SightPress\Module\Seo\SeoEntry;
use SightPress\Module\Seo\SeoProvider;

/**
 * Every event in the sitemap and in `llms.txt`, and each event page's description and `Event` structured data. Events
 * are written in one language: the one this provider is asked for first is the site's default.
 */
final readonly class SeoEntries implements SeoProvider
{
    public const string LOCALE = 'en-GB';

    public function __construct(private EventStore $events)
    {
    }

    public function entries(string $locale): iterable
    {
        if ($locale !== self::LOCALE) {
            return;
        }
        foreach ($this->events->all() as $event) {
            yield $this->entry($event);
        }
    }

    public function entry(Event $event): SeoEntry
    {
        return new SeoEntry(
            $event->path(),
            self::LOCALE,
            $event->title,
            $event->updatedAt,
            sprintf('%s starts on %s.', $event->title, str_replace('T', ' at ', $event->startsAt)),
            $event->path() . '.md',
            structuredData: [['@type' => 'Event', 'name' => $event->title, 'startDate' => $event->startsAt]],
        );
    }
}
```

Site search finds events by their name:

Create `src/SearchDocuments.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use SightPress\Module\Search\SearchDocument;
use SightPress\Module\Search\SearchDocumentProvider;

/**
 * Events in site search. SightPress asks for every document when the index is rebuilt (`site:search:reindex`).
 */
final readonly class SearchDocuments implements SearchDocumentProvider
{
    public function __construct(private EventStore $events)
    {
    }

    public function source(): string
    {
        return EventsModule::ID;
    }

    public function documents(string $locale): iterable
    {
        foreach ($locale === SeoEntries::LOCALE ? $this->events->all() : [] as $event) {
            yield $this->toDocument($event);
        }
    }

    public function document(string $ref, string $locale): ?SearchDocument
    {
        $event = $locale === SeoEntries::LOCALE ? $this->events->find($ref) : null;

        return $event === null ? null : $this->toDocument($event);
    }

    private function toDocument(Event $event): SearchDocument
    {
        return new SearchDocument(
            $this->source(),
            $event->slug,
            SeoEntries::LOCALE,
            $event->path(),
            $event->title,
            $event->updatedAt,
            body: 'Starts on ' . str_replace('T', ' at ', $event->startsAt) . '.',
        );
    }
}
```

## 7. A block type

Editors can put an **Event card** on any page: a link to an event. SightPress checks the block's data against its
schema before it saves the page, and asks the block for its HTML, its Markdown and its structured data.

Create `src/EventCardBlock.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use SightPress\Module\Block\BlockContext;
use SightPress\Module\Block\BlockType;

/**
 * The "Event card" block: editors put a link to an event on any page. Its data is checked against schema() before it
 * is saved; render() makes the HTML, renderMarkdown() the Markdown version, and structuredData() adds the event to
 * the page's structured data.
 */
final class EventCardBlock implements BlockType
{
    public function name(): string
    {
        return 'acme_events.event_card';
    }

    public function label(): string
    {
        return 'Event card';
    }

    public function version(): int
    {
        return 1;
    }

    public function schema(): array
    {
        return [
            'type' => 'object',
            'required' => ['title', 'slug'],
            'additionalProperties' => false,
            'properties' => [
                'title' => ['type' => 'string', 'minLength' => 1, 'maxLength' => 200, 'title' => 'Name of the event'],
                'slug' => ['type' => 'string', 'pattern' => '^[a-z0-9-]+$', 'maxLength' => 100, 'title' => 'Last part of the event’s address'],
            ],
        ];
    }

    public function upgradeData(int $fromVersion, array $data): array
    {
        return $data;
    }

    public function render(array $data, BlockContext $context): string
    {
        return sprintf(
            '<p class="event-card"><a href="%s">%s</a></p>',
            htmlspecialchars($this->url($data, $context), ENT_QUOTES | ENT_HTML5),
            htmlspecialchars(self::text($data, 'title'), ENT_QUOTES | ENT_HTML5),
        );
    }

    public function renderMarkdown(array $data, BlockContext $context): string
    {
        return sprintf("[%s](%s)\n", str_replace(['[', ']'], ['\[', '\]'], self::text($data, 'title')), $this->url($data, $context));
    }

    public function structuredData(array $data, BlockContext $context): array
    {
        return ['@type' => 'Event', 'name' => self::text($data, 'title'), 'url' => $this->url($data, $context)];
    }

    public function a11yRules(): array
    {
        return [];
    }

    /**
     * @param array<string, mixed> $data
     */
    private function url(array $data, BlockContext $context): string
    {
        return $context->absoluteUrl('/events/' . self::text($data, 'slug'));
    }

    /**
     * @param array<string, mixed> $data
     */
    private static function text(array $data, string $key): string
    {
        return is_string($data[$key] ?? null) ? $data[$key] : '';
    }
}
```

## 8. Register everything

The module class tells SightPress what the module adds. `register()` only describes things: SightPress builds the
services when a request needs them.

Create `src/EventsModule.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events;

use SightPress\Module\Access\Permission;
use SightPress\Module\Admin\AdminMenuItem;
use SightPress\Module\ModuleInterface;
use SightPress\Module\ModuleManifest;
use SightPress\Module\ModuleRegistrar;
use SightPress\Module\Routing\Route;

/**
 * Events for SightPress: a page for each event, an Events screen to add them, an event card block, and events in the
 * sitemap, `llms.txt` and site search.
 */
final class EventsModule implements ModuleInterface
{
    public const string ID = 'acme.events';

    public const string VERSION = '0.1.0'; // x-release-please-version

    public const string NAMESPACE = 'acme_events';

    public const string PERMISSION = 'events.manage';

    public function manifest(): ModuleManifest
    {
        return new ModuleManifest(self::ID, self::VERSION, enabledByDefault: true);
    }

    public function register(ModuleRegistrar $registrar): void
    {
        $root = dirname(__DIR__);
        $registrar->views()->add(self::NAMESPACE, $root . '/templates');
        $registrar->translations()->add($root . '/translations');
        $registrar->migrations()->add(new Migrations());

        // Services: SightPress builds them with what their constructors ask for (the site's database connection,
        // Screens …).
        foreach ([EventStore::class, AdminHandler::class, PublicHandler::class, SeoEntries::class, SearchDocuments::class, EventCardBlock::class] as $class) {
            $registrar->services()->autowire($class);
        }

        // Who may manage events: admins and editors to start with; admins can change it on the roles screen.
        $registrar->permissions()->add(new Permission(self::PERMISSION, 'Manage events', 'Add events and change them.', ['admin', 'editor']));

        // The Events screen, only for people with the permission, and its place in the admin menu.
        $registrar->routes()->add(new Route('acme.events.admin', AdminHandler::PATH, AdminHandler::class, ['GET', 'POST'], permission: self::PERMISSION));
        $registrar->adminMenu()->add(new AdminMenuItem('acme.events', 'Events', 'acme.events.admin', permission: self::PERMISSION, position: 45));

        // The public page of each event, for everyone.
        $registrar->routes()->add(new Route('acme.events.show', '/events/{slug}', PublicHandler::class));

        $registrar->blockTypes()->add(EventCardBlock::class);
        $registrar->seo()->addProvider(SeoEntries::class);
        $registrar->search()->addProvider(SearchDocuments::class);
    }
}
```

## 9. Texts

Every text people read goes in the translations file, in British English first. A translator adds a file per
language, for example `acme_events.pt-PT.xlf`.

Create `translations/acme_events.en-GB.xlf`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<xliff xmlns="urn:oasis:names:tc:xliff:document:2.0" version="2.0" srcLang="en-GB" trgLang="en-GB">
  <file id="acme_events">
    <unit id="events"><segment><source>Events</source><target>Events</target></segment></unit>
    <unit id="none"><segment><source>No events yet. Add the first one below.</source><target>No events yet. Add the first one below.</target></segment></unit>
    <unit id="caption"><segment><source>Events, soonest first</source><target>Events, soonest first</target></segment></unit>
    <unit id="name"><segment><source>Name</source><target>Name</target></segment></unit>
    <unit id="starts"><segment><source>Starts</source><target>Starts</target></segment></unit>
    <unit id="add-heading"><segment><source>Add an event</source><target>Add an event</target></segment></unit>
    <unit id="add-button"><segment><source>Add event</source><target>Add event</target></segment></unit>
    <unit id="added"><segment><source>Added “{title}”.</source><target>Added “{title}”.</target></segment></unit>
    <unit id="title-missing"><segment><source>Enter the name of the event.</source><target>Enter the name of the event.</target></segment></unit>
    <unit id="start-missing"><segment><source>Enter the date and time it starts.</source><target>Enter the date and time it starts.</target></segment></unit>
    <unit id="not-found"><segment><source>Event not found</source><target>Event not found</target></segment></unit>
    <unit id="not-found-text"><segment><source>There is no event at this address. It may have been removed.</source><target>There is no event at this address. It may have been removed.</target></segment></unit>
  </file>
</xliff>
```

## 10. Test it

Unit tests check each class on its own, with a database in memory. They must run every line of `src/`.

Create `tests/Unit/TestDatabase.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\Migrations\Version20261201000000;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;
use Psr\Log\NullLogger;

/**
 * An SQLite database in memory with the module's migration applied, for unit tests.
 */
final class TestDatabase
{
    public static function connection(): Connection
    {
        $connection = DriverManager::getConnection(['driver' => 'pdo_sqlite', 'memory' => true]);
        $schema = new Schema();
        new Version20261201000000($connection, new NullLogger())->up($schema);
        foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
            $connection->executeStatement($sql);
        }

        return $connection;
    }
}
```

Create `tests/Unit/EventStoreTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\Event;
use Acme\Events\EventStore;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(EventStore::class)]
#[CoversClass(Event::class)]
final class EventStoreTest extends TestCase
{
    public function testAddsAndFindsEventsSoonestFirst(): void
    {
        $events = new EventStore(TestDatabase::connection());

        $fair = $events->add('Book fair!', '2026-12-06T10:00');
        $events->add('Open day', '2026-12-05T10:00');
        $events->add('***', '2026-12-07T10:00');

        self::assertSame('book-fair', $fair->slug);
        self::assertSame('/events/book-fair', $fair->path());
        self::assertSame(['open-day', 'book-fair', 'event'], array_map(static fn (Event $event): string => $event->slug, $events->all()));
        $open = $events->find('open-day');
        self::assertNotNull($open);
        self::assertSame(['Open day', '2026-12-05T10:00'], [$open->title, $open->startsAt]);
        $found = $events->find('book-fair');
        self::assertNotNull($found);
        self::assertSame($fair->updatedAt->format(DATE_ATOM), $found->updatedAt->format(DATE_ATOM));
        self::assertNull($events->find('nothing'));
    }
}
```

Create `tests/Unit/AdminHandlerTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\AdminHandler;
use Acme\Events\EventStore;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SightPress\Module\Http\AdminScreen;
use SightPress\Module\Http\Screens;
use SightPress\Module\Text\Message;

#[CoversClass(AdminHandler::class)]
#[UsesClass(EventStore::class)]
#[UsesClass(\Acme\Events\Event::class)]
final class AdminHandlerTest extends TestCase
{
    public function testListsTheEvents(): void
    {
        $events = new EventStore(TestDatabase::connection());
        $events->add('Open day', '2026-12-05T10:00');
        $screens = $this->createMock(Screens::class);
        $screens->expects(self::once())->method('admin')->willReturnCallback(static function ($request, AdminScreen $screen): Response {
            self::assertSame('@acme_events/admin/events.html.twig', $screen->template);
            self::assertInstanceOf(Message::class, $screen->title);
            self::assertCount(1, (array) $screen->context['events']);

            return new Response($screen->status);
        });

        self::assertSame(200, new AdminHandler($screens, $events)->handle(new ServerRequest('GET', '/admin/events'))->getStatusCode());
    }

    public function testAFormWithMistakesComesBackWithTheErrorsAndWhatWasTyped(): void
    {
        $screens = $this->createMock(Screens::class);
        $screens->expects(self::once())->method('admin')->willReturnCallback(static function ($request, AdminScreen $screen): Response {
            self::assertSame(['title' => 'Enter the name of the event.', 'starts_at' => 'Enter the date and time it starts.'], $screen->context['errors']);
            self::assertSame(['title' => '', 'starts_at' => 'tomorrow'], $screen->context['values']);

            return new Response($screen->status);
        });
        $request = new ServerRequest('POST', '/admin/events')->withParsedBody(['title' => ' ', 'starts_at' => 'tomorrow']);

        self::assertSame(422, new AdminHandler($screens, new EventStore(TestDatabase::connection()))->handle($request)->getStatusCode());
    }

    public function testAddsAnEventAndGoesBackToTheList(): void
    {
        $events = new EventStore(TestDatabase::connection());
        $screens = $this->createMock(Screens::class);
        $screens->expects(self::once())->method('redirect')
            ->with(self::anything(), '/admin/events', new Message('Added “{title}”.', ['title' => 'Open day'], 'acme_events'))
            ->willReturn(new Response(303));
        $request = new ServerRequest('POST', '/admin/events')->withParsedBody(['title' => 'Open day', 'starts_at' => '2026-12-05T10:00']);

        self::assertSame(303, new AdminHandler($screens, $events)->handle($request)->getStatusCode());
        self::assertSame('Open day', $events->find('open-day')?->title);
    }
}
```

Create `tests/Unit/PublicHandlerTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\EventStore;
use Acme\Events\PublicHandler;
use Acme\Events\SeoEntries;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use SightPress\Module\Http\PublicScreen;
use SightPress\Module\Http\Screens;

#[CoversClass(PublicHandler::class)]
#[UsesClass(EventStore::class)]
#[UsesClass(SeoEntries::class)]
#[UsesClass(\Acme\Events\Event::class)]
final class PublicHandlerTest extends TestCase
{
    private EventStore $events;

    protected function setUp(): void
    {
        $this->events = new EventStore(TestDatabase::connection());
        $this->events->add('Open day', '2026-12-05T10:00');
    }

    public function testShowsTheEventWithItsSeoEntry(): void
    {
        $screens = $this->createMock(Screens::class);
        $screens->expects(self::once())->method('public')->willReturnCallback(static function ($request, PublicScreen $screen): Response {
            self::assertSame('Open day', $screen->title);
            self::assertSame('/events/open-day', $screen->seo?->path);

            return new Response($screen->status);
        });

        self::assertSame(200, $this->handler($screens)->handle($this->request('open-day'))->getStatusCode());
    }

    public function testHasAMarkdownVersion(): void
    {
        $response = $this->handler(self::createStub(Screens::class))->handle($this->request('open-day.md'));

        self::assertSame('text/markdown; charset=utf-8', $response->getHeaderLine('Content-Type'));
        self::assertSame("# Open day\n\nStarts on 2026-12-05 at 10:00.\n", (string) $response->getBody());
    }

    public function testAnUnknownEventIsNotFound(): void
    {
        $screens = $this->createMock(Screens::class);
        $screens->expects(self::exactly(2))->method('public')->willReturnCallback(static fn ($request, PublicScreen $screen): Response => new Response($screen->status));

        self::assertSame(404, $this->handler($screens)->handle($this->request('nothing'))->getStatusCode());
        self::assertSame(404, $this->handler($screens)->handle(new ServerRequest('GET', '/events/'))->getStatusCode());
    }

    private function handler(Screens $screens): PublicHandler
    {
        return new PublicHandler($screens, $this->events, new SeoEntries($this->events), new Psr17Factory());
    }

    private function request(string $slug): ServerRequest
    {
        return new ServerRequest('GET', '/events/' . $slug)->withAttribute('slug', $slug);
    }
}
```

Create `tests/Unit/SeoAndSearchTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\EventStore;
use Acme\Events\SearchDocuments;
use Acme\Events\SeoEntries;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SeoEntries::class)]
#[CoversClass(SearchDocuments::class)]
#[UsesClass(EventStore::class)]
#[UsesClass(\Acme\Events\Event::class)]
final class SeoAndSearchTest extends TestCase
{
    public function testEveryEventIsInTheSitemapWithEventStructuredData(): void
    {
        $events = new EventStore(TestDatabase::connection());
        $events->add('Open day', '2026-12-05T10:00');
        $seo = new SeoEntries($events);

        $entries = [...$seo->entries('en-GB')];
        self::assertCount(1, $entries);
        self::assertSame('/events/open-day.md', $entries[0]->markdownPath);
        self::assertSame('Open day starts on 2026-12-05 at 10:00.', $entries[0]->description);
        self::assertSame([['@type' => 'Event', 'name' => 'Open day', 'startDate' => '2026-12-05T10:00']], $entries[0]->structuredData);
        self::assertSame([], [...$seo->entries('pt-PT')]);
    }

    public function testEventsCanBeFoundWithSiteSearch(): void
    {
        $events = new EventStore(TestDatabase::connection());
        $events->add('Open day', '2026-12-05T10:00');
        $search = new SearchDocuments($events);

        self::assertSame('acme.events', $search->source());
        $documents = [...$search->documents('en-GB')];
        self::assertSame('Open day', $documents[0]->title);
        self::assertSame('Starts on 2026-12-05 at 10:00.', $documents[0]->body);
        self::assertSame('/events/open-day', $search->document('open-day', 'en-GB')?->path);
        self::assertNull($search->document('open-day', 'pt-PT'));
        self::assertNull($search->document('nothing', 'en-GB'));
        self::assertSame([], [...$search->documents('pt-PT')]);
    }
}
```

Create `tests/Unit/EventCardBlockTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\EventCardBlock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SightPress\Module\Block\BlockContext;

#[CoversClass(EventCardBlock::class)]
final class EventCardBlockTest extends TestCase
{
    public function testLinksToTheEventInHtmlMarkdownAndStructuredData(): void
    {
        $block = new EventCardBlock();
        $context = self::createStub(BlockContext::class);
        $context->method('absoluteUrl')->willReturnCallback(static fn (string $path): string => 'https://example.org' . $path);
        $data = ['title' => 'Fish & [chips]', 'slug' => 'fish-and-chips'];

        self::assertSame('acme_events.event_card', $block->name());
        self::assertSame('Event card', $block->label());
        self::assertSame(1, $block->version());
        self::assertSame(['title', 'slug'], $block->schema()['required']);
        self::assertSame($data, $block->upgradeData(1, $data));
        self::assertSame([], $block->a11yRules());
        self::assertSame('<p class="event-card"><a href="https://example.org/events/fish-and-chips">Fish &amp; [chips]</a></p>', $block->render($data, $context));
        self::assertSame("[Fish & \\[chips\\]](https://example.org/events/fish-and-chips)\n", $block->renderMarkdown($data, $context));
        self::assertSame(['@type' => 'Event', 'name' => 'Fish & [chips]', 'url' => 'https://example.org/events/fish-and-chips'], $block->structuredData($data, $context));
        self::assertSame('<p class="event-card"><a href="https://example.org/events/"></a></p>', $block->render([], $context));
    }
}
```

Create `tests/Unit/EventsModuleTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Unit;

use Acme\Events\EventsModule;
use Acme\Events\Migrations;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use SightPress\Module\Access\Permission;
use SightPress\Module\ModuleManifest;
use SightPress\Module\ModuleRegistrar;
use SightPress\Module\Registrar\PermissionRegistrar;

#[CoversClass(EventsModule::class)]
#[CoversClass(Migrations::class)]
final class EventsModuleTest extends TestCase
{
    public function testItsManifestIsValid(): void
    {
        $manifest = new EventsModule()->manifest();

        self::assertMatchesRegularExpression(ModuleManifest::ID_PATTERN, $manifest->id);
        self::assertSame(EventsModule::VERSION, $manifest->version);
        self::assertTrue($manifest->enabledByDefault);
    }

    public function testItRegistersItsPermission(): void
    {
        $permissions = $this->createMock(PermissionRegistrar::class);
        $permissions->expects(self::once())->method('add')->with(self::callback(
            static fn (Permission $permission): bool => $permission->name === 'events.manage' && $permission->defaultRoles === ['admin', 'editor'],
        ));
        $registrar = self::createStub(ModuleRegistrar::class);
        $registrar->method('permissions')->willReturn($permissions);

        // The rest is checked by the integration test, in SightPress itself.
        new EventsModule()->register($registrar);
    }

    public function testItsMigrationsAreInTheirOwnNamespaceAndFolder(): void
    {
        self::assertSame('Acme\Events\Migrations', new Migrations()->namespace());
        self::assertFileExists(new Migrations()->directory() . '/Version20261201000000.php');
    }
}
```

The integration test installs the module in SightPress, makes a website, adds an event and checks its page, its
Markdown, the sitemap, `llms.txt` and site search, then turns the module off and on again:

Create `tests/Integration/InstallTest.php`:

```php
<?php

declare(strict_types=1);

namespace Acme\Events\Tests\Integration;

use Acme\Events\EventsModule;
use Acme\Events\EventStore;
use Doctrine\DBAL\DriverManager;
use Nyholm\Psr7\ServerRequest;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;
use SightPress\Core\Kernel;
use SightPress\Core\Tenant\TenantConfigLoader;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * The module installed in a SightPress project the way Composer installs it, with SightPress core from the dev
 * dependencies: a new site starts with it on and runs its migration; an event gets a page with `Event` structured data,
 * a Markdown version, a place in the sitemap, `llms.txt` and site search; the Events screen asks people to sign in;
 * turned off, its pages are gone and its events kept.
 */
#[CoversNothing]
final class InstallTest extends TestCase
{
    private const string SECRET = 'a-test-secret-of-at-least-32-characters';

    private string $project;

    protected function setUp(): void
    {
        $this->project = sys_get_temp_dir() . '/acme-events-' . bin2hex(random_bytes(6));
        mkdir($this->project . '/config', 0o777, true);
        mkdir($this->project . '/vendor/composer', 0o777, true);
        mkdir($this->project . '/sites', 0o777, true);
        file_put_contents($this->project . '/config/tenant.php', '<?php return ' . var_export(['log' => ['path' => $this->project . '/var/log/test.log']], true) . ';');
        file_put_contents($this->project . '/config/sites.php', '<?php return ' . var_export(['version' => 2, 'sites' => []], true) . ';');
        $package = json_decode((string) file_get_contents(dirname(__DIR__, 2) . '/composer.json'), true, 32, JSON_THROW_ON_ERROR);
        file_put_contents($this->project . '/vendor/composer/installed.json', json_encode(['packages' => [$package]], JSON_THROW_ON_ERROR));
    }

    protected function tearDown(): void
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->project, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST);
        foreach ($files as $file) {
            \assert($file instanceof \SplFileInfo);
            $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
        }
        rmdir($this->project);
    }

    public function testEventsWorkInSightPress(): void
    {
        $kernel = $this->kernel('test');
        self::assertTrue($kernel->modules()->has(EventsModule::ID));
        [$status, $output] = $this->console($kernel, [
            'command' => 'site:create',
            'slug' => 'demo',
            '--domain' => 'demo.example',
            '--db' => 'sqlite:///' . $this->project . '/sites/demo.sqlite',
            '--owner-type' => 'organization',
            '--admin-email' => 'ada@example.org',
            '--admin-first-name' => 'Ada',
            '--admin-last-name' => 'Lovelace',
        ]);
        self::assertSame(0, $status, $output);
        new EventStore(DriverManager::getConnection(['driver' => 'pdo_sqlite', 'path' => $this->project . '/sites/demo.sqlite']))->add('Open day', '2026-12-05T10:00');

        $page = $this->get($kernel, '/events/open-day');
        self::assertSame(200, $page[0]);
        self::assertStringContainsString('<h1>Open day</h1>', $page[1]);
        self::assertStringContainsString('"@type":"Event","name":"Open day"', $page[1]);
        self::assertSame("# Open day\n\nStarts on 2026-12-05 at 10:00.\n", $this->get($kernel, '/events/open-day.md')[1]);
        self::assertSame(404, $this->get($kernel, '/events/nothing')[0]);
        self::assertSame(303, $this->get($kernel, '/admin/events')[0], 'the Events screen asks people to sign in');

        self::assertSame(0, $this->console($kernel, ['command' => 'site:search:reindex', 'slug' => 'demo'])[0]);
        self::assertStringContainsString('Open day', $this->console($kernel, ['command' => 'site:search', 'slug' => 'demo', 'text' => 'open day'])[1]);

        // The sitemap and llms.txt list pages only on a live website.
        $live = $this->kernel('production');
        self::assertStringContainsString('<loc>https://demo.example/events/open-day</loc>', $this->get($live, '/sitemap.xml')[1]);
        self::assertStringContainsString('(https://demo.example/events/open-day.md): Open day starts on 2026-12-05 at 10:00.', $this->get($live, '/llms.txt')[1]);

        self::assertSame(0, $this->console($kernel, ['command' => 'site:module:disable', 'slug' => 'demo', 'module' => EventsModule::ID])[0]);
        self::assertSame(404, $this->get($this->kernel('test'), '/events/open-day')[0]);
        self::assertSame(0, $this->console($kernel, ['command' => 'site:module:enable', 'slug' => 'demo', 'module' => EventsModule::ID])[0]);
        self::assertSame(200, $this->get($this->kernel('test'), '/events/open-day')[0], 'its events were kept');
    }

    private function kernel(string $environment): Kernel
    {
        return Kernel::fromConfig(new TenantConfigLoader()->load($this->project, ['APP_ENV' => $environment, 'APP_SECRET' => self::SECRET]));
    }

    /**
     * @return array{int, string} status and body
     */
    private function get(Kernel $kernel, string $path): array
    {
        $response = $kernel->handle(new ServerRequest('GET', 'https://demo.example' . $path));

        return [$response->getStatusCode(), (string) $response->getBody()];
    }

    /**
     * @param array<string, string> $input
     *
     * @return array{int, string} exit status and everything it wrote
     */
    private function console(Kernel $kernel, array $input): array
    {
        $application = $kernel->console();
        $application->setAutoExit(false);
        $tester = new ApplicationTester($application);
        $status = $tester->run($input, ['interactive' => false]);

        return [$status, $tester->getDisplay()];
    }
}
```

Run every check:

```sh
composer dump-autoload
composer check
```

Then go through the Events screen with the keyboard alone (step 11): Tab reaches each field and the button, an empty
form says what to fix next to each field, and the new event is in the table.

## 11. Install it on a website

In your SightPress project, add the module as a Composer path repository while you work on it:

```sh
composer config repositories.events '{"type": "path", "url": "../events", "options": {"symlink": true}}'
composer require acme/events:@dev
bin/sightpress cache:clear
bin/sightpress site:module:list <site>
```

`site:module:list` shows `acme.events (Events): on` for new websites. On an existing website, turn it on with

```sh
bin/sightpress site:module:enable <site> acme.events
```

or, as an admin, on the **Features** screen (**Turn on Events**). Turning it on runs its migration. Then open
**Events** in the admin panel and add the first event.

If something is wrong with the module (a typo in `composer.json`, a class that doesn't load), SightPress keeps
running: the Features screen, `site:module:list` and `system:check` list it as unavailable, with the reason.

## 12. Publish it

1. Put the module in its own Git repository and push it to GitHub (or any Git host).
2. Tag a version, for example `0.1.0`. With the template's release workflow, merging the release pull request does it.
3. Sign in to [Packagist](https://packagist.org/), choose **Submit** and give your repository's address. Keep the
   `sightpress-module` keyword, so people can find it.

Anyone can then install it with `composer require acme/events`.

## Next steps

- Add a setting with `$registrar->settings()`, a scheduled job with `$registrar->schedule()`, or listen to SightPress
  events (a page was published, a module was turned on) with `$registrar->subscribers()`.
- Translate the texts: copy `acme_events.en-GB.xlf` to another language and change each `target`.
- Read the README of the module template for the rules every module follows: accessible screens, plain words,
  portable migrations and as little personal data as possible.
