<?php

namespace Azuriom\Plugin\Announcements\Models;

use Azuriom\Models\Traits\Loggable;
use Azuriom\Plugin\Announcements\Support\Announcements;
use Azuriom\Plugin\Announcements\Support\HtmlSanitizer;
use Azuriom\Support\Markdown;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $message
 * @property string $format
 * @property string $type
 * @property string $style
 * @property string|null $color
 * @property string|null $text_color
 * @property string|null $button_label
 * @property string|null $button_url
 * @property \Carbon\Carbon|null $starts_at
 * @property \Carbon\Carbon|null $ends_at
 * @property bool $has_countdown
 * @property string $audience
 * @property array|null $roles
 * @property string $pages
 * @property string|null $page_patterns
 * @property bool $is_dismissible
 * @property string $frequency
 * @property int $priority
 * @property int $version
 * @property bool $is_enabled
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Announcement extends Model
{
    use Loggable;

    public const TYPES = ['bar', 'popup'];

    public const FORMATS = ['markdown', 'html'];

    public const STYLES = ['info', 'success', 'warning', 'danger', 'custom'];

    public const AUDIENCES = ['all', 'guests', 'users', 'roles'];

    public const PAGES = ['all', 'home', 'shop', 'pattern'];

    public const FREQUENCIES = ['once', 'session', 'always'];

    /**
     * Changing one of these attributes displays the announcement again to
     * visitors who closed it.
     */
    private const CONTENT_ATTRIBUTES = ['title', 'message', 'format', 'button_label', 'button_url', 'type'];

    protected $table = 'announcements';

    protected $fillable = [
        'title', 'message', 'format', 'type', 'style', 'color', 'text_color',
        'button_label', 'button_url', 'starts_at', 'ends_at', 'has_countdown',
        'audience', 'roles', 'pages', 'page_patterns', 'is_dismissible',
        'frequency', 'priority', 'is_enabled',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'has_countdown' => 'boolean',
        'roles' => 'array',
        'is_dismissible' => 'boolean',
        'priority' => 'integer',
        'version' => 'integer',
        'is_enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::updating(function (self $announcement) {
            if ($announcement->isDirty(self::CONTENT_ATTRIBUTES)) {
                $announcement->version = $announcement->getOriginal('version') + 1;
            }
        });

        static::saved(fn () => Announcements::clearCache());
        static::deleted(fn () => Announcements::clearCache());
    }

    #[Scope]
    protected function enabled(Builder $query): void
    {
        $query->where('is_enabled', true);
    }

    /**
     * The safe HTML of the message.
     */
    public function renderMessage(): string
    {
        if ($this->format === 'html') {
            return HtmlSanitizer::clean($this->message);
        }

        return Markdown::parse($this->message, true);
    }

    public function status(): string
    {
        if (! $this->is_enabled) {
            return 'disabled';
        }

        if ($this->ends_at !== null && $this->ends_at->isPast()) {
            return 'expired';
        }

        if ($this->starts_at !== null && $this->starts_at->isFuture()) {
            return 'scheduled';
        }

        return 'active';
    }
}
