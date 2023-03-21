<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\ActionRequest;
use App\Models\Action;
use App\Models\Group;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($menuItemId, $groupId)
    {
        $menuItems = MenuItems::findOrFail($menuItemId);
        $group = Group::findOrFail($groupId);

        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'menuItems' => $menuItems,
            'group' => $group,
        ];

        return view('backoffice.action.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActionRequest $request)
    {
        $data = $request->all();

        Action::create($data);
        return redirect()->route('grup.permissionCreate', $data['grup_id'])->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Action $action, $menuItemId, $groupId)
    {
        $menuItems = MenuItems::findOrFail($menuItemId);
        $group = Group::findOrFail($groupId);

        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'menuItems' => $menuItems,
            'group' => $group,
            'action' => $action
        ];

        return view('backoffice.action.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ActionRequest $request, Action $action)
    {
        $data = $request->all();

        Action::findOrFail($action->aksi_id)
            ->update($data);

        return redirect()->route('grup.permissionCreate', $data['grup_id'])->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $groupId)
    {
        Action::destroy($id);

        return redirect()->route('grup.permissionCreate', $groupId)->with('success', 'Data berhasil dihapus!');
    }
}
