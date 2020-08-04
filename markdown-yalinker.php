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
            // syntax: [[link(|(text)?)?]]
            // * [[page]] => <a href="page">page</a>
            // * [[page|]] => <a href="page">page</a>
            // * [[page|text]] => <a href="page">text</a>
            // * [[../page]] => <a href="../page">page</a>
            // * [[../page|]] => <a href="../page">../page</a>
            // * [[../page|text]] => <a href="../page">text</a>
            // * [[folder/slashes//in page title]] => <a href="folder/slashes-in-page-title">slashes/in page title</a>
            // * [[folder/slashes//in page title|]] => <a href="folder/slashes-in-page-title">folder/slashes/in page title</a>
            // * [[folder/slashes//in page title|text]] => <a href="folder/slashes-in-page-title">text</a>
            // * [[url]] => <a href="url">url</a>
            // * [[mailto:...]] => <a href="mailto:...">mailto:...</a>
            // * [[mail:...]] => <a href="mailto:...">...</a>
            if (strpos($excerpt['text'], '[[') === 0 && strpos($excerpt['text'], ']]') !== false && preg_match('/^\[\[(.*?)(?:\|(.+?))?\]\]/', $excerpt['text'], $matches)) {
                $extent = strlen($matches[0]);

                $show_path = false;
                if ($matches[2]) {
                    $href = $matches[1];
                    $text = $matches[2];
                    $has_text = true;
                } else {
                    $href = $matches[1];
                    $len = strlen($href);
                    if ($len > 0 && $href[$len - 1] == '|') {
                        $href = substr($href, 0, $len - 1);
                        $show_path = true;
                    }
                    $text = $href;
                    $has_text = false;
                }

                // if url
                if (preg_match('/^(?:https?:\/\/|mail(?:to)?:)/', $href))
                    $text = preg_replace('/\|$/', '', $text);
                else {
                // if page
                    // escape non-folder slashes
                    if (strpos($href, '//') !== false) {
                        $href = preg_replace('/\/+/', '-', $href);
                        if (!$has_text)
                            // for now, // => \x00
                            $text = preg_replace('/\/{2,}/', '\x00', $text);
                    }

                    $href = self::slug($href);

                    if (!$has_text) {
                        // handle page path
                        if (preg_match('/^(([\/.]*)\/)?(?:[^\/]+\/)*(.*?)$/', $text, $matches)) {
                            if ($matches[2])
                                // add back path prefix that may has been removed by the slug() function
                                $href = $matches[2].$href;
                            if (!$show_path)
                                // show page title only if requested (no | at the end)
                                $text = $matches[3];
                        }

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

    // Adopted from the Admin plugin (admin/classes/utils.php)
    public static function slug(string $str)
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
