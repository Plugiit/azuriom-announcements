<?php

namespace Azuriom\Plugin\PlugiitAnnouncements\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Plugin\PlugiitAnnouncements\Middleware\InjectAnnouncements;
use Azuriom\Plugin\PlugiitAnnouncements\Models\Announcement;

class PlugiitAnnouncementsServiceProvider extends BasePluginServiceProvider
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
            'plugiit-announcements.admin' => 'plugiit-announcements::admin.permission',
        ]);

        ActionLog::registerLogModels([
            Announcement::class,
        ], 'plugiit-announcements::admin.logs');

        ActionLog::registerLogs('plugiit-announcements.settings.updated', [
            'icon' => 'megaphone',
            'color' => 'info',
            'message' => 'plugiit-announcements::admin.logs.settings',
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
            'plugiit-announcements' => [
                'name' => trans('plugiit-announcements::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-megaphone',
                'route' => 'plugiit-announcements.admin.*',
                'permission' => 'plugiit-announcements.admin',
                'items' => [
                    'plugiit-announcements.admin.announcements.index' => trans('plugiit-announcements::admin.nav.announcements'),
                    'plugiit-announcements.admin.settings' => trans('plugiit-announcements::admin.nav.settings'),
                ],
            ],
        ];
    }
}
