<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\GroupRequest;
use App\Models\Group;
use App\Models\WorkUnit;
use Illuminate\Http\Request;
use DataTables;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Group::select('grup_id', 'grup_nama', 'grup_deskripsi');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($data) {
                    $btn = "<div class='text-center'>";
                    if ($data->grup_id <> 'a6d315d6-c86d-44c6-bc60-2f6f83c8ce2f') {
                        if (PermissionMenu()[0]->groups->filter(function ($item) {
                            return $item->grup_id == Auth::user()->groups[0]->grup_id;
                        })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya') $btn .= "<a class='btn btn-sm btn-primary w-100 mb-1 waitme' href='" . route('grup.edit', $data->grup_id) . "'><i class='fas fa-edit'></i></a>";
                        if (PermissionMenu()[0]->groups->filter(function ($item) {
                            return $item->grup_id == Auth::user()->groups[0]->grup_id;
                        })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya') $btn .= "<button type='button' class='btn btn-sm btn-danger w-100 mb-1 destroy' id='" . route('grup.destroy', $data->grup_id) . "'><i class='fa fa-trash destroy' id='" . route('grup.destroy', $data->grup_id) . "'></i></button>";
                    }
                    if (PermissionAction(route('grup.permissionCreate', $data->grup_id))) $btn .= "<a class='btn btn-sm btn-success w-100 mb-1 waitme' href='" . route('grup.permissionCreate', $data->grup_id) . "'>Hak Akses</a>";
                    if ($btn == "<div class='text-center'>") $btn .= '-';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get()
        ];

        return view('backoffice.group.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get()
        ];

        return view('backoffice.group.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GroupRequest $request)
    {
        $data = $request->all();

        Group::create($data);
        return redirect()->route('grup.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'group' => $group
        ];

        return view('backoffice.group.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GroupRequest $request, Group $group)
    {
        $data = $request->all();

        Group::findOrFail($group->grup_id)
            ->update($data);

        return redirect()->route('grup.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Group::destroy($id);

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function permissionCreate(Group $group)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'group' => $group,
            'groupMenuItems' =>  @Menus::where('menu_nama', 'Sidebar')->first()->menuItems ?? [],
            'groupWorkUnits' =>  @WorkUnit::all() ?? []
        ];

        return view('backoffice.group.permission', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function permissionSync(Request $request, Group $group)
    {
        $dataMenu = [];
        $dataAction = [];
        $dataWorkUnits = [];
        foreach (@$request->grup_menu_item ?? [] as $key => $groupMenuItem) {
            $dataMenu[$groupMenuItem] = [
                'grup_menu_item_id' => Str::uuid(),
                'grup_menu_item_tambah' => @$request->grup_menu_crud[(int)$key]['tambah'] ?? 'tidak',
                'grup_menu_item_ubah' => @$request->grup_menu_crud[(int)$key]['ubah'] ?? 'tidak',
                'grup_menu_item_hapus' => @$request->grup_menu_crud[(int)$key]['hapus'] ?? 'tidak',
            ];
        }

        foreach (@$request->grup_aksi ?? [] as $key => $grup_aksi) {
            $dataAction[$grup_aksi] = [
                'grup_aksi_id' => Str::uuid(),
                'grup_aksi_grup_id' => $group->grup_id,
            ];
        }

        // foreach (@$request->grup_unit_kerja ?? [] as $key => $grup_unit_kerja) {
        //     $dataWorkUnits[$grup_unit_kerja] = [
        //         'grup_unit_kerja_id' => Str::uuid(),
        //         'grup_unit_kerja_grup_id' => $group->grup_id,
        //     ];
        // }

        $group = Group::findOrFail($group->grup_id);
        $group->menuItems()->sync($dataMenu);
        $group->actions()->sync($dataAction);
        $group->unitKerjas()->sync($dataWorkUnits);

        return redirect()->route('grup.permissionCreate', $group->grup_id)->with('success', 'Data berhasil ditambahkan!');
    }
}
