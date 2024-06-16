<?php

use EasyPanel\Contracts\CRUDComponent;
use EasyPanel\Models\CRUD;
use EasyPanel\Services\ACLService;

if (!function_exists('getRouteName')) {
    function getRouteName()
    {
        $routeName = config('easy_panel.route_prefix');
        $routeName = trim($routeName, '/');
        $routeName = str_replace('/', '.', $routeName);
        return $routeName;
    }
}

if (!function_exists('getCrudConfig')) {
    function getCrudConfig($name)
    {
        $className = ucwords($name);
        $namespace = "\\App\\CRUD\\{$className}Component";
        $appFilePath = app_path("/CRUD/{$name}Component.php");
        $nsExist = class_exists($namespace);
        $filePathExist = file_exists($appFilePath);

        if (!$filePathExist or !$nsExist) {
            abort(403, "Class with {$namespace}  namespace or {$appFilePath} doesn't exist, ");
        }

        $instance = app()->make($namespace);

        if (!$instance instanceof CRUDComponent) {
            abort(403, "{$namespace} should implement CRUDComponent interface");
        }

        return $instance;
    }
}

if (!function_exists('crud')) {
    function crud($name)
    {
        return CRUD::query()->where('name', $name)->first();
    }
}

if (!function_exists('hasPermission')) {
    function hasPermission($routeName, $withAcl = false): bool
    {
        $showButton = true;
        if ($withAcl && !ACLService::hasPermission(auth()->user()->roles()->get(), $routeName)) {
            $showButton = false;
        }
        return $showButton;
    }
}
