<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;

class Sanitizer
{
    /**
     * Sanitiza o HTML do editor (proteção XSS), permitindo apenas embeds do YouTube.
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $config = HTMLPurifier_Config::createDefault();

        $config->set('HTML.Allowed', implode(',', [
            'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's',
            'h1', 'h2', 'h3', 'h4',
            'ul', 'ol', 'li',
            'blockquote', 'pre', 'code', 'hr',
            'span', 'div',
            'a[href|title|target]',
            'img[src|alt|title|width|height]',
            'iframe[src|width|height|frameborder]',
        ]));

        // iframes: somente YouTube (embed / nocookie).
        $config->set('HTML.SafeIframe', true);
        $config->set('URI.SafeIframeRegexp', '%^https://(www\.)?(youtube\.com/embed/|youtube-nocookie\.com/embed/)%');
        $config->set('Attr.AllowedFrameTargets', ['_blank']);

        // Sem cache em disco (evita exigir diretório gravável na hospedagem).
        $config->set('Cache.DefinitionImpl', null);

        return (new HTMLPurifier($config))->purify($html);
    }
}
