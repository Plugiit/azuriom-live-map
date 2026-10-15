<?php

namespace Azuriom\Plugin\LiveMap\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Plugin\LiveMap\Models\LiveMap;

class LiveMapServiceProvider extends BasePluginServiceProvider
{
    /**
     * Bootstrap any plugin services.
     */
    public function boot(): void
    {
        $this->loadViews();

        $this->loadTranslations();

        $this->loadMigrations();

        $this->registerRouteDescriptions();

        $this->registerAdminNavigation();

        Permission::registerPermissions([
            'live-map.admin' => 'live-map::admin.permission',
        ]);

        ActionLog::registerLogModels([
            LiveMap::class,
        ], 'live-map::admin.logs');

        ActionLog::registerLogs('live-map.settings.updated', [
            'icon' => 'map',
            'color' => 'info',
            'message' => 'live-map::admin.logs.settings',
        ]);
    }

    /**
     * Returns the routes that should be able to be added to the navbar.
     *
     * @return array<string, string>
     */
    protected function routeDescriptions(): array
    {
        return [
            'live-map.index' => trans('live-map::messages.title'),
        ];
    }

    /**
     * Return the admin navigations routes to register in the dashboard.
     *
     * @return array<string, array<string, string>>
     */
    protected function adminNavigation(): array
    {
        return [
            'live-map' => [
                'name' => trans('live-map::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-map',
                'route' => 'live-map.admin.*',
                'permission' => 'live-map.admin',
                'items' => [
                    'live-map.admin.maps.index' => trans('live-map::admin.nav.maps'),
                    'live-map.admin.settings' => trans('live-map::admin.nav.settings'),
                ],
            ],
        ];
    }
}
