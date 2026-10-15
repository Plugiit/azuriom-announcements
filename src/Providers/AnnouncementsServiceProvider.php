<?php

namespace Azuriom\Plugin\Announcements\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Plugin\Announcements\Middleware\InjectAnnouncements;
use Azuriom\Plugin\Announcements\Models\Announcement;

class AnnouncementsServiceProvider extends BasePluginServiceProvider
{
    /**
     * The plugin's global HTTP middleware stack.
     */
    protected array $middleware = [
        InjectAnnouncements::class,
    ];

    /**
     * Register any plugin services.
     */
    public function register(): void
    {
        $this->registerMiddleware();
    }

    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerAdminNavigation();

        Permission::registerPermissions([
            'announcements.admin' => 'announcements::admin.permission',
        ]);

        ActionLog::registerLogModels([
            Announcement::class,
        ], 'announcements::admin.logs');

        ActionLog::registerLogs('announcements.settings.updated', [
            'icon' => 'megaphone',
            'color' => 'info',
            'message' => 'announcements::admin.logs.settings',
        ]);
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'announcements' => [
                'name' => trans('announcements::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-megaphone',
                'route' => 'announcements.admin.*',
                'permission' => 'announcements.admin',
                'items' => [
                    'announcements.admin.announcements.index' => trans('announcements::admin.nav.announcements'),
                    'announcements.admin.settings' => trans('announcements::admin.nav.settings'),
                ],
            ],
        ];
    }
}
