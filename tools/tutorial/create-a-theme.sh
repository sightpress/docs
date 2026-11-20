#!/bin/sh
# Follows docs/tutorials/create-a-theme.md on a fresh copy of the theme template, so the tutorial keeps working:
# makes the theme "Harbour", checks that a low-contrast colour is refused with the message the tutorial shows, fixes
# it, overrides the footer, adds the sidebar layout, then runs the template's checks and its preview.
#
# Usage: tools/tutorial/create-a-theme.sh <folder with a fresh copy of sightpress/theme-template, dependencies installed>
set -eu
theme="$1"
here="$(cd "$(dirname "$0")" && pwd)"
cd "$theme"

# 2. Make it yours.
php "$here/edit.php" composer
php "$here/edit.php" class
php "$here/edit.php" tokens '#7fb3ff'

# 3. A light blue is refused, with the pair and the ratio.
if vendor/bin/sightpress-theme theme:validate . > validate.txt 2>&1; then
    echo "A primary colour of #7fb3ff was accepted; the tutorial says it is refused." >&2
    exit 1
fi
cat validate.txt
grep -F 'The theme "Harbour" fails with 3 problems:' validate.txt
grep -F '1. In light mode, text "on-primary" (#ffffff) on "primary" (#7fb3ff) has a contrast of 2.14:1; it needs at least 4.50:1.' validate.txt
rm validate.txt
php "$here/edit.php" tokens '#0b4f8a'

# 4. Override the footer; 5. add the sidebar layout.
php "$here/edit.php" footer
php "$here/edit.php" sidebar

# 6. Check it; 7. preview it.
composer check
composer preview
grep -F '<h1>Harbour</h1>' build/theme-preview/acme-harbour/index.html
grep -F 'made with the Harbour theme.' build/theme-preview/acme-harbour/index.html
echo "The tutorial's theme passes."
