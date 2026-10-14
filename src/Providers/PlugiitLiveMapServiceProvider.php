<?php

namespace Azuriom\Plugin\PlugiitLiveMap\Providers;

use Azuriom\Extensions\Plugin\BasePluginServiceProvider;
use Azuriom\Models\ActionLog;
use Azuriom\Models\Permission;
use Azuriom\Plugin\PlugiitLiveMap\Models\LiveMap;

class PlugiitLiveMapServiceProvider extends BasePluginServiceProvider
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
            'plugiit-live-map.admin' => 'plugiit-live-map::admin.permission',
        ]);

        ActionLog::registerLogModels([
            LiveMap::class,
        ], 'plugiit-live-map::admin.logs');

        ActionLog::registerLogs('plugiit-live-map.settings.updated', [
            'icon' => 'map',
            'color' => 'info',
            'message' => 'plugiit-live-map::admin.logs.settings',
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
            'plugiit-live-map.index' => trans('plugiit-live-map::messages.title'),
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
            'plugiit-live-map' => [
                'name' => trans('plugiit-live-map::admin.nav.title'),
                'type' => 'dropdown',
                'icon' => 'bi bi-map',
                'route' => 'plugiit-live-map.admin.*',
                'permission' => 'plugiit-live-map.admin',
                'items' => [
                    'plugiit-live-map.admin.maps.index' => trans('plugiit-live-map::admin.nav.maps'),
                    'plugiit-live-map.admin.settings' => trans('plugiit-live-map::admin.nav.settings'),
                ],
            ],
        ];
    }
}
