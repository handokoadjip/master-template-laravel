<?php

namespace App\Http\Controllers\Example;

use App\Http\Controllers\Controller;
use App\Http\Requests\Example\CrudOneToManyRequest;
use App\Models\Example\CrudOneToManyPk;
use Illuminate\Http\Request;
use DataTables;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;

class CrudOneToManyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CrudOneToManyPk::with('phones');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = "<div class='text-center'>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya') $btn .= "<a class='btn btn-sm btn-primary w-100 mb-1 waitme' href='" . route('crud-one-to-many.edit', $data->crud_one_to_many_pk_id) . "'><i class='fas fa-edit'></i></a>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya') $btn .= "<button type='submit' class='btn btn-sm btn-danger w-100 mb-1 destroy' id='" . route('crud-one-to-many.destroy', $data->crud_one_to_many_pk_id) . "'><i class='fa fa-trash destroy' id='" . route('crud-one-to-many.destroy', $data->crud_one_to_many_pk_id) . "'></i></button>";
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

        return view('example.crud_one_to_many.index', compact('data'));
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

        return view('example.crud_one_to_many.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrudOneToManyRequest $request)
    {
        $data = $request->all();

        $phone = [];
        foreach ($data['crud_one_to_many_fk_telp'] as $key => $value) {
            $phone[] = [
                'crud_one_to_many_fk_telp' => $value,
            ];
        }

        $identitas = CrudOneToManyPk::create($data);
        $identitas->phones()->createMany($phone);

        return redirect()->route('crud-one-to-many.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(CrudOneToManyPk $crud_one_to_many)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'crud' => $crud_one_to_many
        ];

        return view('example.crud_one_to_many.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrudOneToManyRequest $request, CrudOneToManyPk $crud_one_to_many)
    {
        $data = $request->all();

        $identitas = CrudOneToManyPk::findOrFail($crud_one_to_many->crud_one_to_many_pk_id);
        $identitas->update($data);

        foreach ($data['crud_one_to_many_fk_id'] as $key => $value) {
            $identitas->phones()->where('crud_one_to_many_fk_id', $value)->update(['crud_one_to_many_fk_telp' => $data['crud_one_to_many_fk_telp'][$key]]);
        }

        return redirect()->route('crud-one-to-many.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crud_one_to_many = CrudOneToManyPk::findOrFail($id);
        $crud_one_to_many::destroy($id);

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
