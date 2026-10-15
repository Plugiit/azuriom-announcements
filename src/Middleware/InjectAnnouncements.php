<?php

namespace Azuriom\Plugin\Announcements\Middleware;

use Azuriom\Plugin\Announcements\Support\Announcements;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

/**
 * Insert the announcement bars right after <body> and the popups before </body>.
 */
class InjectAnnouncements
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! $this->shouldInject($request, $response)) {
            return $response;
        }

        try {
            $content = $response->getContent();
            $end = strripos($content, '</body>');

            if ($end === false || str_contains($content, 'id="plugiit-ann-config"')) {
                return $response;
            }

            $announcements = Announcements::forRequest($request, $request->user());
            $hasBars = str_contains($content, 'id="plugiit-ann-bars"');

            if ($announcements['popups'] === [] && ($announcements['bars'] === [] || $hasBars)) {
                return $response;
            }

            $footer = view('announcements::footer', [
                'popups' => $announcements['popups'],
                'withBars' => $hasBars || $announcements['bars'] !== [],
            ])->render();

            $content = substr_replace($content, $footer, $end, 0);

            if (! $hasBars && $announcements['bars'] !== []) {
                $bars = view('announcements::bars', [
                    'bars' => $announcements['bars'],
                ])->render();

                $content = $this->insertAfterBodyTag($content, $bars);
            }

            $response->setContent($content);
        } catch (Throwable $e) {
            report($e);
        }

        return $response;
    }

    protected function insertAfterBodyTag(string $content, string $html): string
    {
        if (preg_match('/<body\b[^>]*>/i', $content, $matches, PREG_OFFSET_CAPTURE) !== 1) {
            return $content;
        }

        $position = $matches[0][1] + strlen($matches[0][0]);

        return substr_replace($content, $html, $position, 0);
    }

    protected function shouldInject(Request $request, $response): bool
    {
        if (! $response instanceof Response || $response->getStatusCode() !== 200) {
            return false;
        }

        if (! $request->isMethod('GET') || $request->ajax() || $request->expectsJson()) {
            return false;
        }

        if ($request->is('admin', 'admin/*', 'api/*', '_debugbar/*')) {
            return false;
        }

        return str_contains((string) $response->headers->get('Content-Type'), 'text/html') && is_installed();
    }
}
