<?php

namespace App\Http\Controllers\Example;

use App\Http\Controllers\Controller;
use App\Http\Requests\Example\CrudOneToOneRequest;
use App\Models\Example\CrudOneToOnePk;
use Illuminate\Http\Request;
use DataTables;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;

class CrudOneToOneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CrudOneToOnePk::with('telp');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = "<div class='text-center'>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya') $btn .= "<a class='btn btn-sm btn-primary w-100 mb-1 waitme' href='" . route('crud-one-to-one.edit', $data->crud_one_to_one_pk_id) . "'><i class='fas fa-edit'></i></a>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya') $btn .= "<button type='submit' class='btn btn-sm btn-danger w-100 mb-1 destroy' id='" . route('crud-one-to-one.destroy', $data->crud_one_to_one_pk_id) . "'><i class='fa fa-trash destroy' id='" . route('crud-one-to-one.destroy', $data->crud_one_to_one_pk_id) . "'></i></button>";
                    if ($btn == "<div class='text-center'>") $btn .= '-';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get()
        ];

        return view('example.crud_one_to_one.index', compact('data'));
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

        return view('example.crud_one_to_one.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrudOneToOneRequest $request)
    {
        $data = $request->all();

        $identitas = CrudOneToOnePk::create($data);
        $identitas->telp()->create([
            'crud_one_to_one_fk_telp' => $data['crud_one_to_one_fk_telp'],
        ]);

        return redirect()->route('crud-one-to-one.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudOneToOnePk $crud_one_to_one)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'crud' => $crud_one_to_one
        ];

        return view('example.crud_one_to_one.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrudOneToOneRequest $request, CrudOneToOnePk $crud_one_to_one)
    {
        $data = $request->all();

        $identitas = CrudOneToOnePk::findOrFail($crud_one_to_one->crud_one_to_one_pk_id);
        $identitas->update($data);
        $identitas->telp()->update([
            'crud_one_to_one_fk_telp' => $data['crud_one_to_one_fk_telp'],
        ]);

        return redirect()->route('crud-one-to-one.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crud_one_to_one = CrudOneToOnePk::findOrFail($id);
        $crud_one_to_one::destroy($id);

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
