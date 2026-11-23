<?php

/**
 * The edits of docs/tutorials/create-a-theme.md, one step at a time, in the current folder (a copy of the theme
 * template). Each one fails loudly when the template no longer has what the tutorial changes.
 *
 * Usage: php edit.php composer|class|tokens <colour>|footer|sidebar
 */

declare(strict_types=1);

function change(string $file, string $from, string $to): void
{
    $text = (string) file_get_contents($file);
    if (!str_contains($text, $from)) {
        fwrite(STDERR, sprintf("%s no longer contains:\n%s\nUpdate the tutorial.\n", $file, $from));
        exit(1);
    }
    file_put_contents($file, str_replace($from, $to, $text));
}

switch ($argv[1] ?? '') {
    case 'composer':
        $composer = json_decode((string) file_get_contents('composer.json'), true, 32, JSON_THROW_ON_ERROR);
        $composer['name'] = 'acme/theme-harbour';
        $composer['description'] = 'Harbour: calm blues and large type, a theme for SightPress.';
        $composer['homepage'] = 'https://example.org/harbour';
        file_put_contents('composer.json', json_encode($composer, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        break;
    case 'class':
        change('src/MyTheme.php', "public const string ID = 'my-theme';", "public const string ID = 'acme-harbour';");
        change('src/MyTheme.php', "name: 'My theme',", "name: 'Harbour',");
        change('src/MyTheme.php', "description: 'Describe your theme in one or two sentences, for the theme picker.',", "description: 'Calm blues and large type, for organisations that publish a lot of reading.',");
        change('src/MyTheme.php', "author: 'Your name or organisation',", "author: 'Acme Ltd',");
        change('src/MyTheme.php', "previewDescription: 'Describe what the top of your preview page looks like, for people who can\\'t see its screenshot: the top bar, the band with the heading and the Download button, the colours.',", "previewDescription: 'A dark blue top bar with the site name in white, a pale blue band with a large heading and a yellow Download button.',");
        change('theme.json', '"name": "my-theme"', '"name": "acme-harbour"');
        change('theme.json', '"label": "My theme"', '"label": "Harbour"');
        break;
    case 'tokens':
        $theme = json_decode((string) file_get_contents('theme.json'), true, 32, JSON_THROW_ON_ERROR);
        $theme['colors']['light']['primary'] = $argv[2];
        file_put_contents('theme.json', json_encode($theme, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . "\n");
        break;
    case 'footer':
        change('templates/master.html.twig', "{% block footer %}<p>{{ site_name }}</p>{% endblock %}", "{% block footer %}<p>{{ site_name }}, made with the Harbour theme.</p>{% endblock %}");
        break;
    case 'sidebar':
        change('src/MyTheme.php', 'layouts: LayoutDefinition::required(),', "layouts: [...LayoutDefinition::required(), new LayoutDefinition(LayoutDefinition::SIDEBAR, 'Sidebar')],");
        $tutorial = (string) file_get_contents(dirname(__DIR__, 2) . '/docs/tutorials/create-a-theme.md');
        if (preg_match('/Create `templates\/layouts\/sidebar\.html\.twig`:\s*```twig\n(.*?)```/s', $tutorial, $match) !== 1) {
            fwrite(STDERR, "The tutorial has no sidebar template.\n");
            exit(1);
        }
        file_put_contents('templates/layouts/sidebar.html.twig', preg_replace('/^    /m', '', $match[1]));
        break;
    default:
        fwrite(STDERR, "Usage: php edit.php composer|class|tokens <colour>|footer|sidebar\n");
        exit(1);
}
