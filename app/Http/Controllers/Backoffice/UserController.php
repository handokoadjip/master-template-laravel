<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backoffice\UserRequest;
use App\Models\Group;
use App\Models\WorkUnit;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('groups')->select('pengguna_id', 'pengguna_nik', 'pengguna_nama', 'pengguna_email', 'pengguna_telp')
                ->where('pengguna_id', '<>', '1d711ed8-2873-4e45-b57c-2e9ce87bb50a');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('actions', function ($data) {
                    $btn = "<div class='text-center'>";
                    if (PermissionAction(route('pengguna.show', $data->pengguna_id))) $btn .= "<a class='btn btn-sm btn-info text-white w-100 mb-1 waitme' href='" . route('pengguna.show', $data->pengguna_id) . "'><i class='fas fa-eye'></i></a>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya') $btn .= "<a class='btn btn-sm btn-primary w-100 mb-1 waitme' href='" . route('pengguna.edit', $data->pengguna_id) . "'><i class='fas fa-edit'></i></a>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya') $btn .= "<button type='submit' class='btn btn-sm btn-danger w-100 mb-1 destroy' id='" . route('pengguna.destroy', $data->pengguna_id) . "'><i class='fa fa-trash destroy' id='" . route('pengguna.destroy', $data->pengguna_id) . "'></i></button>";
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

        return view('backoffice.user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'groups' => Group::where('grup_id', '<>', '854272fd-b56f-483e-b089-ecfce38a8b4b')->pluck('grup_nama', 'grup_id'),
            'workUnits' => WorkUnit::pluck('unit_kerja_nama', 'unit_kerja_id'),
            'userWorkUnits' =>  @WorkUnit::all() ?? []
        ];

        return view('backoffice.user.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->all();
        $data['pengguna_password'] = Hash::make($data['pengguna_password']);

        $user = User::create($data);

        $dataWorkUnits = [];
        foreach (@$request->pengguna_unit_kerja ?? [] as $key => $userWorkUnit) {
            $dataWorkUnits[$userWorkUnit] = [
                'pengguna_unit_kerja_id' => Str::uuid(),
                'pengguna_unit_kerja_pengguna_id' => $user->pengguna_id,
            ];
        }

        $user->groups()->sync([$data['grup_id'] => ['pengguna_grup_id' => Str::uuid()]]);
        $user->workUnits()->sync($dataWorkUnits);

        return redirect()->route('pengguna.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'user' => $user,
        ];

        return view('backoffice.user.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'groups' => Group::where('grup_id', '<>', '854272fd-b56f-483e-b089-ecfce38a8b4b')->pluck('grup_nama', 'grup_id'),
            'workUnits' => WorkUnit::pluck('unit_kerja_nama', 'unit_kerja_id'),
            'userWorkUnits' =>  @WorkUnit::all() ?? [],
            'user' => $user,
        ];

        return view('backoffice.user.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $data['pengguna_password'] = Hash::make($data['pengguna_password']);
        if ($data['pengguna_password'] == '') unset($data['pengguna_password']);

        $user = User::findOrFail($user->pengguna_id);
        $user->update($data);

        $dataWorkUnits = [];
        foreach (@$request->pengguna_unit_kerja ?? [] as $key => $pengguna_unit_kerja) {
            $dataWorkUnits[$pengguna_unit_kerja] = [
                'pengguna_unit_kerja_id' => Str::uuid(),
                'pengguna_unit_kerja_pengguna_id' => $user->pengguna_id,
            ];
        }

        $user->groups()->sync([$data['grup_id'] => ['pengguna_grup_id' => Str::uuid()]]);
        $user->workUnits()->sync($dataWorkUnits);

        return redirect()->route('pengguna.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
