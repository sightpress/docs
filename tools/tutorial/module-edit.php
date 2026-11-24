<?php

/**
 * The edits of docs/tutorials/create-a-module.md, in the current folder (a fresh copy of the module template). Each
 * one fails loudly when the template no longer has what the tutorial changes.
 *
 * Usage: php module-edit.php make-it-yours|files
 *   make-it-yours  step 2: the package name, the namespace, the id and the texts for the Features screen
 *   files          writes every file the tutorial shows after "Create `<path>`:"
 */

declare(strict_types=1);

function fail(string $message): never
{
    fwrite(STDERR, $message . "\nUpdate the tutorial.\n");
    exit(1);
}

function change(string $file, string $from, string $to): void
{
    $text = (string) file_get_contents($file);
    if (!str_contains($text, $from)) {
        fail(sprintf('%s no longer contains: %s', $file, $from));
    }
    file_put_contents($file, str_replace($from, $to, $text));
}

$tutorial = (string) file_get_contents(dirname(__DIR__, 2) . '/docs/tutorials/create-a-module.md');

switch ($argv[1] ?? '') {
    case 'make-it-yours':
        foreach (['src/MyModule.php', 'src/Migrations.php', 'tests/Unit/MyModuleTest.php', 'tests/Integration/InstallTest.php', 'translations/my_module.en-GB.xlf'] as $file) {
            if (!is_file($file)) {
                fail(sprintf('The template no longer has %s.', $file));
            }
        }
        $composer = json_decode((string) file_get_contents('composer.json'), true, 32, JSON_THROW_ON_ERROR);
        if (preg_match('/In `composer\.json`, replace `extra` with:\s*```json\n(.*?)```/s', $tutorial, $match) !== 1) {
            fail('The tutorial has no "extra" for composer.json.');
        }
        $composer['name'] = 'acme/events';
        $composer['description'] = 'Events for SightPress: a page for each event, an Events screen and an event card block.';
        $composer['homepage'] = 'https://example.org/events';
        $composer['support'] = ['issues' => 'https://example.org/events/issues'];
        $composer['keywords'] = ['sightpress-module', 'events', 'accessibility'];
        $composer['autoload']['psr-4'] = ['Acme\\Events\\' => 'src/', 'Acme\\Events\\Migrations\\' => 'migrations/'];
        $composer['autoload-dev']['psr-4'] = ['Acme\\Events\\Tests\\' => 'tests/'];
        $composer['extra'] = json_decode('{' . $match[1] . '}', true, 32, JSON_THROW_ON_ERROR)['extra'];
        file_put_contents('composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        change('src/Migrations.php', 'namespace Acme\MyModule;', 'namespace Acme\Events;');
        change('src/Migrations.php', "return 'Acme\\MyModule\\Migrations';", "return 'Acme\\Events\\Migrations';");
        change('src/Migrations.php', '`Acme\MyModule\Migrations`', '`Acme\Events\Migrations`');
        rename('translations/my_module.en-GB.xlf', 'translations/acme_events.en-GB.xlf');
        foreach (['src/MyModule.php', 'tests/Unit/MyModuleTest.php', 'tests/Integration/InstallTest.php'] as $file) {
            unlink($file);
        }
        break;
    case 'files':
        if (preg_match_all('/^Create `([^`]+)`:\s*\n\s*```[a-z]*\n(.*?)^```/ms', $tutorial, $matches, PREG_SET_ORDER) < 10) {
            fail('The tutorial shows fewer files than expected.');
        }
        foreach ($matches as [, $path, $code]) {
            if (str_contains($path, '..') || str_starts_with($path, '/')) {
                fail(sprintf('Not a path in the module: %s', $path));
            }
            @mkdir(dirname($path), 0o777, true);
            file_put_contents($path, $code);
            echo 'Wrote ', $path, "\n";
        }
        break;
    default:
        fwrite(STDERR, "Usage: php module-edit.php make-it-yours|files\n");
        exit(1);
}
