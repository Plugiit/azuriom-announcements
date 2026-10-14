<?php

namespace Azuriom\Plugin\PlugiitAnnouncements\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Minimal allow-list HTML sanitizer for announcement messages.
 */
class HtmlSanitizer
{
    /**
     * Allowed tags and their allowed attributes.
     */
    private const ALLOWED = [
        'a' => ['href', 'title', 'target'],
        'b' => [], 'strong' => [], 'i' => [], 'em' => [], 'u' => [], 's' => [],
        'mark' => [], 'small' => [], 'code' => [], 'span' => [], 'br' => [],
        'p' => [], 'ul' => [], 'ol' => [], 'li' => [],
    ];

    /**
     * Tags removed with their content.
     */
    private const DROPPED = ['script', 'style', 'iframe', 'object', 'embed', 'svg', 'math', 'template', 'noscript', 'form', 'textarea', 'select', 'head', 'title'];

    public static function clean(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="UTF-8"><div id="plugiit-ann-root">'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $root = $document->getElementById('plugiit-ann-root');

        if ($root === null) {
            return e(strip_tags($html));
        }

        self::cleanChildren($root);

        $output = '';

        foreach ($root->childNodes as $child) {
            $output .= $document->saveHTML($child);
        }

        return $output;
    }

    private static function cleanChildren(DOMNode $node): void
    {
        foreach (iterator_to_array($node->childNodes) as $child) {
            if ($child->nodeType === XML_TEXT_NODE) {
                continue;
            }

            if (! $child instanceof DOMElement) {
                $node->removeChild($child);

                continue;
            }

            $tag = strtolower($child->tagName);

            if (in_array($tag, self::DROPPED, true)) {
                $node->removeChild($child);

                continue;
            }

            self::cleanChildren($child);

            if (! array_key_exists($tag, self::ALLOWED)) {
                // Unknown tag: keep its (already cleaned) content only.
                while ($child->firstChild !== null) {
                    $node->insertBefore($child->firstChild, $child);
                }

                $node->removeChild($child);

                continue;
            }

            self::cleanAttributes($child, self::ALLOWED[$tag]);
        }
    }

    private static function cleanAttributes(DOMElement $element, array $allowed): void
    {
        foreach (iterator_to_array($element->attributes) as $attribute) {
            if (! in_array(strtolower($attribute->name), $allowed, true)) {
                $element->removeAttribute($attribute->name);
            }
        }

        if ($element->tagName !== 'a') {
            return;
        }

        $href = trim((string) $element->getAttribute('href'));

        if ($href !== '' && ! self::isSafeUrl($href)) {
            $element->removeAttribute('href');
        }

        if ($element->hasAttribute('target')) {
            if ($element->getAttribute('target') !== '_blank') {
                $element->removeAttribute('target');
            } else {
                $element->setAttribute('rel', 'noopener noreferrer');
            }
        }
    }

    public static function isSafeUrl(string $url): bool
    {
        $normalized = strtolower(preg_replace('/[\x00-\x20]+/', '', $url));

        if (preg_match('/^[a-z][a-z0-9+.-]*:/', $normalized) !== 1) {
            return true; // Relative URL
        }

        return str_starts_with($normalized, 'http:')
            || str_starts_with($normalized, 'https:')
            || str_starts_with($normalized, 'mailto:');
    }
}
