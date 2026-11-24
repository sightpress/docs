<?php

/**
 * A SightPress website with the tutorial's module installed, for the accessibility check of "Create a module"
 * (module-a11y.spec.ts). The module's own vendor folder has SightPress core (a dev dependency).
 *
 * Usage: SIGHTPRESS_MODULE=<module folder> php -S 127.0.0.1:8123 tools/tutorial/module-site.php
 *
 * The first request makes the project in a temporary folder: the module installed the way Composer installs it,
 * and a site "demo" made with site:create. `/_test/setup-code` answers with the first admin's setup code.
 */

declare(strict_types=1);

use SightPress\Core\Kernel;
use SightPress\Core\Tenant\TenantConfigLoader;
use Symfony\Component\Console\Tester\ApplicationTester;

$module = getenv('SIGHTPRESS_MODULE');
if (!is_string($module) || !is_file($module . '/composer.json')) {
    http_response_code(500);
    echo "Set SIGHTPRESS_MODULE to the module's folder.\n";

    return;
}
require $module . '/vendor/autoload.php';

$project = sys_get_temp_dir() . '/sightpress-tutorial-module';
$environment = ['APP_ENV' => 'test', 'APP_SECRET' => 'tutorial-secret-0123456789abcdef0123456789'];
$boot = static fn (): Kernel => Kernel::fromConfig(new TenantConfigLoader()->load($project, $environment));

if (!is_file($project . '/setup-code.txt')) {
    @mkdir($project . '/config', 0o777, true);
    @mkdir($project . '/vendor/composer', 0o777, true);
    file_put_contents($project . '/config/tenant.php', '<?php return ' . var_export([
        'log' => ['path' => $project . '/var/log/site.log'],
        'session' => ['path' => $project . '/var/sessions'],
    ], true) . ';');
    file_put_contents($project . '/config/sites.php', '<?php return ' . var_export(['version' => 2, 'sites' => []], true) . ';');
    $package = json_decode((string) file_get_contents($module . '/composer.json'), true, 32, JSON_THROW_ON_ERROR);
    file_put_contents($project . '/vendor/composer/installed.json', json_encode(['packages' => [$package]], JSON_THROW_ON_ERROR));

    $console = $boot()->console();
    $console->setAutoExit(false);
    $tester = new ApplicationTester($console);
    $tester->run([
        'command' => 'site:create',
        'slug' => 'demo',
        '--name' => 'Riverside Library',
        '--domain' => '127.0.0.1',
        '--db' => 'sqlite:///' . $project . '/demo.sqlite',
        '--owner-type' => 'organization',
        '--admin-email' => 'ada@example.org',
        '--admin-first-name' => 'Ada',
        '--admin-last-name' => 'Lovelace',
    ], ['interactive' => false]);
    if (preg_match('/setup code (\d{3} \d{3})/', $tester->getDisplay(), $match) !== 1) {
        http_response_code(500);
        echo $tester->getDisplay();

        return;
    }
    file_put_contents($project . '/setup-code.txt', str_replace(' ', '', $match[1]));
}

$path = (string) parse_url(is_string($_SERVER['REQUEST_URI'] ?? null) ? $_SERVER['REQUEST_URI'] : '/', PHP_URL_PATH);
if ($path === '/_test/setup-code') {
    header('Content-Type: text/plain');
    readfile($project . '/setup-code.txt');

    return;
}

$boot()->run();
