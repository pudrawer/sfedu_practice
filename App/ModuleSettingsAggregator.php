<?php

namespace App;

class ModuleSettingsAggregator
{
    public const SETTINGS_WEB_ROUTES     = 'web_routes';
    public const SETTINGS_API_ROUTES     = 'api_routes';
    public const SETTINGS_CONSOLE_ROUTES = 'console_routes';

    static private $originalModules;
    static private $originalRoutes;
    static private $resultRoutes;

    public static function getWebRoutes(): array
    {
        return self::parseRoutes(self::SETTINGS_WEB_ROUTES);
    }

    public static function getApiRoutes(): array
    {
        return self::parseRoutes(self::SETTINGS_API_ROUTES);
    }

    public static function getConsoleRoutes(): array
    {
        return self::parseRoutes(self::SETTINGS_CONSOLE_ROUTES);
    }

    protected static function parseRoutes(string $routesName): array
    {
        self::$resultRoutes = [];

        if (!self::$originalModules) {
            self::$originalModules = include_once APP_ROOT . '/App/modules.php';
        }

        if (!self::$originalRoutes) {
            foreach (self::$originalModules as $module) {
                self::$originalRoutes[] = include_once APP_ROOT . "/App/$module/settings.php";
            }
        }

        foreach (self::$originalRoutes as $route) {
            self::$resultRoutes[] = $route[$routesName] ?? [];
        }

        return self::getMergedRoutes();
    }

    protected static function getMergedRoutes(): array
    {
        $result = [];
        foreach (self::$resultRoutes as $routes) {
            $result += $routes;
        }

        return $result;
    }
}
