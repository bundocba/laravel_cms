<?php

namespace App\Helpers;

use Route;

class AuthItemHelper {

    public static function getRoutes() {
        $controllers = [];

        foreach (Route::getRoutes()->getRoutes() as $route) {
            $action = $route->getAction();

            if (array_key_exists('as', $action)) {
                $list = explode('@', $action['controller']);
                $length = count($list);

                $list = explode('\\', $list[$length - 2]);
                $length = count($list);

                $controllerName = preg_replace('/Controller/', '', $list[$length - 1]);

                $list = explode('.', $action['as']);
                $length = count($list);

                $actionName = strtolower(preg_replace('/(get|post)/', '', $list[$length - 1]));
                //$controller = preg_replace('/' . $list[$length - 1] . '/', $actionName, $action['as']);

                if (!isset($controllers[$controllerName]) || !in_array($actionName, $controllers[$controllerName])) {
                    $as = $action['as'];
                    $controllers[$controllerName][$as] = $actionName;
                }
            }
        }

        return $controllers;
    }

    public static function canDo($listPermissions, $permission) {
        $list = explode('.', $permission);
        $length = count($list);

        $actionName = preg_replace('/(post)/', 'get', $list[$length - 1]);

        $finalPermission = '';

        foreach ($list as $index => $element) {
            if ($index == $length - 1) {
                $finalPermission .= $actionName;
            } else {
                $finalPermission .= $element . '.';
            }
        }
        
        if (!isset($listPermissions[$finalPermission])) {
            return false;
        }
        
        return true;
    }

}
