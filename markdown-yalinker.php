<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

class MarkdownYalinkerPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onMarkdownInitialized' => ['onMarkdownInitialized', 0],
        ];
    }

    public function onMarkdownInitialized(Event $event)
    {
        $markdown = $event['markdown'];

        $markdown->addInlineType('[', 'Yalink');
        $markdown->inlineYalink = function($excerpt) {
            // syntax: [[(link)?(|(text)?)?]]
            // * [[]] => <a href="/current">Current Title</a>
            // * [[|]] => <a href="/current">/current</a>
            // * [[.|]] => <a href="/current">.</a>
            // * [[page]] => <a href="/current/page">page</a>
            // * [[page|]] => <a href="/current/page">page</a>
            // * [[page|text]] => <a href="/current/page">text</a>
            // * [[../page]] => <a href="/page">page</a>
            // * [[../page|]] => <a href="/page">../page</a>
            // * [[../page|text]] => <a href="/page">text</a>
            // * [[/folder/slashes//in page title]] => <a href="/folder/slashes-in-page-title">slashes/in page title</a>
            // * [[/folder/slashes//in page title|]] => <a href="/folder/slashes-in-page-title">/folder/slashes/in page title</a>
            // * [[/folder/slashes//in page title|text]] => <a href="/folder/slashes-in-page-title">text</a>
            // * [[url]] => <a href="url">url</a>
            // * [[mailto:...]] => <a href="mailto:...">mailto:...</a>
            // * [[mail:...]] => <a href="mailto:...">...</a>
            if (strpos($excerpt['text'], '[[') === 0 && strpos($excerpt['text'], ']]') !== false && preg_match('/^\[\[(.*?)(?:\|(.+?))?\]\]/', $excerpt['text'], $matches)) {
                $extent = strlen($matches[0]);
                $href = $matches[1];
                $has_text = isset($matches[2]);

                $show_path = false;
                if ($has_text)
                    $text = $matches[2];
                else {
                    $len = strlen($href);
                    if ($len && $href[$len - 1] == '|') {
                        $href = substr($href, 0, $len - 1);
                        $show_path = true;
                    }
                    $text = $href;
                }

                // if url
                if (preg_match('/^(?:https?:\/\/|mail(?:to)?:)/', $href))
                    $text = preg_replace('/\|$/', '', $text);
                else {
                // if page
                    // escape non-folder slashes
                    if (strpos($href, '//') !== false) {
                        $href = preg_replace('/\/{2,}/', '-', $href);
                        if (!$has_text)
                            // for now, // => \x00
                            $text = preg_replace('/\/{2,}/', '\x00', $text);
                    }

                    $current_uri = $this->grav['uri'];
                    $rootUrl = $current_uri->rootUrl(); // /grav
                    $current_route = $current_uri->route(); // /current-page
                    $current_title = $this->grav['page']->title(); // Current Page Title

                    // pre-clean up path in href
                    if (strpos($href, '...') !== false)
                        // a/...../b => a/../b
                        $href = preg_replace('/\.{3,}/', '..', $href);
                    if (strpos($href, '/./') !== false)
                        // a/./././b => a/b
                        $href = preg_replace('/(?:\/\.)+\//', '/', $href);
                    if (strpos($href, './') === 0)
                        // ./a => a
                        $href = substr($href, 2);

                    $path_prefix = $current_route;
                    if (preg_match('/^([\/.]*)\/(.*)$/', $href, $matches)) {
                        if (strlen($matches[1]) && $matches[1][0] != '/')
                            // relative path
                            $path_prefix .= $matches[1];
                        else
                            // absolute path
                            $path_prefix = '';
                        $href = $matches[2];
                    }

                    switch ($href) {
                    case '':
                        if (!$has_text)
                            $text = $show_path ? $path_prefix : $current_title;
                        break;
                    case '.':
                        $href = '';
                        break;
                    }

                    // add back path prefix that may has been removed by the slug() function
                    $href = $path_prefix.($href ? '/'.self::slug($href) : '');

                    // post-clean up path in href
                    $paths = explode('/', $href);
                    $npaths = count($paths);
                    for ($i = 0; $i < $npaths; $i++) {
                        if ($paths[$i] == '..')
                            $paths[$i - 1] = $paths[$i] = '';
                    }
                    $href = join('/', $paths);
                    $href = $rootUrl.preg_replace('/\/\/+/', '/', $href);

                    if (!$has_text) {
                        // handle page path in text
                        if (!$show_path && preg_match('/^(?:[\/.]*\/)?(?:[^\/]+\/)*(.*)$/', $text, $matches))
                            // show page title only if requested (no | at the end)
                            $text = $matches[1];

                        // convert escaped non-folder slashes back to slashes
                        $text = str_replace('\x00', '/', $text);
                    }
                }

                // if mail, hide mailto from text
                if (strpos($href, 'mail:') === 0) {
                    $href = substr_replace($href, 'mailto:', 0, 5);
                    if (!$has_text)
                        $text = substr_replace($text, '', 0, 5);
                }

                return [
                    'extent' => $extent,
                    'element' => [
                        'name' => 'a',
                        'text' => $text,
                        'attributes' => [
                            'href' => $href,
                        ],
                    ],
                ];
            }
        };
    }

    // Adopted from the Admin plugin (user/plugins/admin/classes/utils.php)
    private static function slug(string $str)
    {
        if (function_exists('transliterator_transliterate')) {
            $str = transliterator_transliterate('Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC;', $str);
        } else {
            $str = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        }

        $str = strtolower($str);
        $str = preg_replace('/[\s.]+/', '-', $str);
        // leave slashes as is
        $str = preg_replace('/[^a-z0-9\/-]/', '', $str);
        // page./.slug => page/slug
        $str = preg_replace('/-?\/-?/', '/', $str);
        $str = trim($str, '-');

        return $str;
    }
}
