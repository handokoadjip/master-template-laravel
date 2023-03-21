<?php

use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Permission CRUD
 */
function PermissionMenu()
{
    $menu = Menus::where('menu_nama', 'Sidebar')
        ->first()
        ->menuItems()
        ->where('menu_item_tautan', '=', '/' . request()->path())
        ->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)
        ->get();

    return $menu;
}

/**
 * Permission Action Button
 *
 * @param $url
 *
 */
function PermissionAction($permission)
{
    $routesUuid = collect(explode('/', parse_url($permission, PHP_URL_PATH)));
    $routes = $routesUuid->filter(function ($route) {
        return !Str::isUuid($route);
    });

    try {
        $actions = Menus::where('menu_nama', 'Sidebar')
            ->first()
            ->menuItems()
            ->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)
            ->whereRelation('actions', 'aksi_tautan', '=', $routes->implode('/', ','))
            ->first()
            ->actions
            ->first()
            ->groups
            ->count();
    } catch (\Throwable $th) {
        $actions = 0;
    }

    if ($actions) return true;
    return false;
}
