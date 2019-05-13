<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace App\Services\Markdown;

/**
 * BaseMarkdown provides concrete implementation for [[Markdown]].
 *
 * Do not use BaseMarkdown. Use [[Markdown]] instead.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class Markdown
{
    /**
     * @var array a map of markdown flavor names to corresponding parser class configurations.
     */
    public static $flavors = [
        'original' => [
            'class' => 'cebe\markdown\Markdown',
            'html5' => true,
        ],
        'gfm' => [
            'class' => 'cebe\markdown\GithubMarkdown',
            'html5' => true,
        ],
        'gfm-comment' => [
            'class' => 'cebe\markdown\GithubMarkdown',
            'html5' => true,
            'enableNewlines' => true,
        ],
        'extra' => [
            'class' => 'cebe\markdown\MarkdownExtra',
            'html5' => true,
        ],
    ];
    /**
     * @var string the markdown flavor to use when none is specified explicitly.
     * Defaults to `original`.
     * @see $flavors
     */
    public static $defaultFlavor = 'original';


    /**
     * Converts markdown into HTML.
     *
     * @param string $markdown the markdown text to parse
     * @param string $flavor the markdown flavor to use. See [[$flavors]] for available values.
     * Defaults to [[$defaultFlavor]], if not set.
     * @return string the parsed HTML output
     */
    public static function process($markdown, $flavor = null)
    {
        $parser = static::getParser($flavor);

        return $parser->parse($markdown);
    }

    /**
     * Converts markdown into HTML but only parses inline elements.
     *
     * This can be useful for parsing small comments or description lines.
     *
     * @param string $markdown the markdown text to parse
     * @param string $flavor the markdown flavor to use. See [[$flavors]] for available values.
     * Defaults to [[$defaultFlavor]], if not set.
     * @return string the parsed HTML output
     */
    public static function processParagraph($markdown, $flavor = null)
    {
        $parser = static::getParser($flavor);

        return $parser->parseParagraph($markdown);
    }

    /**
     * @param string $flavor the markdown flavor to use. See [[$flavors]] for available values.
     * Defaults to [[$defaultFlavor]], if not set.
     * @return \cebe\markdown\Parser
     */
    protected static function getParser($flavor)
    {
        return (new Highlighter());
    }
}
