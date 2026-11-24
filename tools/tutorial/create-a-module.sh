#!/bin/sh
# Follows docs/tutorials/create-a-module.md on a fresh copy of the module template, so the tutorial keeps working:
# makes the "Events" module from the code the tutorial shows, runs the template's checks (code style, PHPStan, the
# unit tests with the 100% coverage gate, the integration test in SightPress), then signs in to a SightPress website
# with the module installed and checks its Events screen and an event page with axe, by keyboard.
#
# Usage: tools/tutorial/create-a-module.sh <folder with a fresh copy of sightpress/module-template, dependencies installed>
#        Needs `playwright` (from @playwright/test) on PATH, @playwright/test and @axe-core/playwright on NODE_PATH,
#        and Playwright's Chromium.
set -eu
module="$1"
here="$(cd "$(dirname "$0")" && pwd)"
cd "$module"

# 1. The template passes as it is.
composer check

# 2. Make it yours; 3. the libraries the module uses.
php "$here/module-edit.php" make-it-yours
composer require --no-interaction --no-progress doctrine/dbal:^4 doctrine/migrations:^3 psr/http-factory:^1
composer require --dev --no-interaction --no-progress nyholm/psr7:^1

# 3–9. Every file the tutorial shows.
php "$here/module-edit.php" files
composer dump-autoload

# 10. Test it.
composer check

# 11. Install it on a website: the Events screen and an event page pass axe.
mkdir -p "$module/build"
SIGHTPRESS_MODULE="$module" php -S 127.0.0.1:8123 "$here/module-site.php" > "$module/build/site.log" 2>&1 &
server=$!
trap 'kill $server' EXIT
sleep 1
playwright test --config "$here/module-a11y.config.ts"
echo "The tutorial's module passes."
