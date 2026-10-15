<?php

namespace Azuriom\Plugin\Announcements\Support;

use Azuriom\Models\User;
use Azuriom\Plugin\Announcements\Models\Announcement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Announcements
{
    public const CACHE_KEY = 'announcements.active';

    public const BAR_MODES = ['stacked', 'rotate'];

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * The enabled and not yet ended announcements, ready to display.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function all(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(10), function () {
            return Announcement::enabled()
                ->where(fn ($query) => $query->whereNull('ends_at')->orWhere('ends_at', '>', now()))
                ->orderByDesc('priority')
                ->orderByDesc('id')
                ->get()
                ->map(fn (Announcement $announcement) => [
                    'id' => $announcement->id,
                    'version' => $announcement->version,
                    'type' => $announcement->type,
                    'title' => $announcement->title,
                    'html' => $announcement->renderMessage(),
                    'style' => $announcement->style,
                    'color' => $announcement->color,
                    'text_color' => $announcement->text_color,
                    'button_label' => $announcement->button_label,
                    'button_url' => $announcement->button_url,
                    'starts_at' => $announcement->starts_at?->toIso8601String(),
                    'ends_at' => $announcement->ends_at?->toIso8601String(),
                    'countdown' => $announcement->has_countdown && $announcement->ends_at !== null,
                    'audience' => $announcement->audience,
                    'roles' => array_map('intval', $announcement->roles ?? []),
                    'pages' => $announcement->pages,
                    'patterns' => self::parsePatterns($announcement->page_patterns),
                    'dismissible' => $announcement->is_dismissible,
                    'frequency' => $announcement->frequency,
                ])
                ->all();
        });
    }

    /**
     * The announcements to display for the given request.
     *
     * @return array{bars: array, popups: array}
     */
    public static function forRequest(Request $request, ?User $user = null): array
    {
        $now = now();
        $bars = [];
        $popups = [];

        foreach (self::all() as $announcement) {
            if ($announcement['starts_at'] !== null && Carbon::parse($announcement['starts_at'])->isAfter($now)) {
                continue;
            }

            if ($announcement['ends_at'] !== null && Carbon::parse($announcement['ends_at'])->isBefore($now)) {
                continue;
            }

            if (! self::matchesAudience($announcement, $user) || ! self::matchesPage($announcement, $request)) {
                continue;
            }

            if ($announcement['type'] === 'popup') {
                $popups[] = $announcement;
            } else {
                $bars[] = $announcement;
            }
        }

        return ['bars' => $bars, 'popups' => $popups];
    }

    protected static function matchesAudience(array $announcement, ?User $user): bool
    {
        return match ($announcement['audience']) {
            'guests' => $user === null,
            'users' => $user !== null,
            'roles' => $user !== null && in_array((int) $user->role_id, $announcement['roles'], true),
            default => true,
        };
    }

    protected static function matchesPage(array $announcement, Request $request): bool
    {
        $path = trim($request->path(), '/');

        return match ($announcement['pages']) {
            'home' => $path === '' || $request->routeIs('home'),
            'shop' => $request->routeIs('shop.*') || $path === 'shop' || Str::startsWith($path, 'shop/'),
            'pattern' => collect($announcement['patterns'])->contains(
                fn (string $pattern) => Str::is($pattern === '/' ? '' : $pattern, $path)
            ),
            default => true,
        };
    }

    /**
     * @return string[]
     */
    public static function parsePatterns(?string $patterns): array
    {
        return collect(preg_split('/[\r\n,]+/', (string) $patterns))
            ->map(function (string $pattern) {
                $pattern = trim($pattern);

                if ($pattern === '') {
                    return null;
                }

                $pattern = (string) parse_url($pattern, PHP_URL_PATH) ?: $pattern;
                $pattern = trim($pattern, '/');

                return $pattern === '' ? '/' : $pattern;
            })
            ->filter()
            ->values()
            ->all();
    }

    public static function barMode(): string
    {
        $mode = setting('announcements.bar_mode', 'stacked');

        return in_array($mode, self::BAR_MODES, true) ? $mode : 'stacked';
    }

    public static function rotationDelay(): int
    {
        return min(max((int) setting('announcements.rotation_delay', 6), 3), 60);
    }

    /**
     * Inline CSS variables for the custom style.
     */
    public static function styleAttribute(array $announcement): string
    {
        if ($announcement['style'] !== 'custom') {
            return '';
        }

        $background = $announcement['color'] ?? '#5b5bd6';
        $text = $announcement['text_color'] ?? '#ffffff';

        return "--plugiit-ann-bg: {$background}; --plugiit-ann-fg: {$text};";
    }
}
