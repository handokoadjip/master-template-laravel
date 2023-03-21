<?php

namespace App\Http\Middleware;

use Closure;
use Harimayco\Menu\Models\Menus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $url = explode('/', request()->path());
        $routes = collect($url)->filter(function ($route) {
            return !Str::isUuid($route) && !in_array($route, ['create', 'edit']);
        });

        $menu = Menus::where('menu_nama', 'Sidebar')
            ->first()
            ->menuItems()
            ->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)
            ->where('menu_item_tautan', '=', '/' . $routes->implode('/', ','))
            ->get();

        try {
            $actiosn = Menus::where('menu_nama', 'Sidebar')
                ->first()
                ->menuItems()
                ->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)
                ->whereRelation('actions', 'aksi_tautan', '=', '/' . $routes->implode('/', ','))
                ->first()
                ->actions
                ->first()
                ->groups
                ->count();
        } catch (\Throwable $th) {
            $actions = 0;
        }

        if ($menu && end($url) == 'create' && $menu[0]->groups[0]->pivot->grup_menu_item_tambah == 'tidak') return abort(404);
        if ($menu && end($url) == 'edit' && $menu[0]->groups[0]->pivot->grup_menu_item_ubah == 'tidak') return abort(404);
        if ($menu || $actions) return $next($request);
        return abort(404);
    }
}
