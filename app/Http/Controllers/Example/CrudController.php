<?php

namespace App\Http\Controllers\Example;

use App\Http\Controllers\Controller;
use App\Http\Requests\Example\CrudRequest;
use App\Models\Example\Crud;
use Illuminate\Http\Request;
use DataTables;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CrudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Crud::select('crud_id', 'crud_nama', 'crud_foto');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($data) {
                    $btn = "<div class='text-center'>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya') $btn .= "<a class='btn btn-sm btn-primary w-100 mb-1 waitme' href='" . route('crud.edit', $data->crud_id) . "'><i class='fas fa-edit'></i></a>";
                    if (PermissionMenu()[0]->groups->filter(function ($item) {
                        return $item->grup_id == Auth::user()->groups[0]->grup_id;
                    })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya') $btn .= "<button type='submit' class='btn btn-sm btn-danger w-100 mb-1 destroy' id='" . route('crud.destroy', $data->crud_id) . "'><i class='fa fa-trash destroy' id='" . route('crud.destroy', $data->crud_id) . "'></i></button>";
                    if ($btn == "<div class='text-center'>") $btn .= '-';
                    $btn .= '</div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
        ];

        return view('example.crud.index', compact('data'));
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
        ];

        return view('example.crud.create', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CrudRequest $request)
    {
        $data = $request->all();

        if ($request->file('crud_foto')) {
            $filename = time() . '.' . $request->crud_foto->extension();
            $request->crud_foto->move(public_path('example/crud'), $filename);
            $data['crud_foto'] = $filename;
        }

        Crud::create($data);
        return redirect()->route('crud.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Crud $crud)
    {
        $data = [
            'menus' => Menus::where('menu_nama', 'Sidebar')->first()->items()->whereRelation('groups', 'grup_menu_item_grup_id', '=', Auth::user()->groups[0]->grup_id)->get(),
            'crud' => $crud
        ];

        return view('example.crud.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CrudRequest $request, Crud $crud)
    {
        $data = $request->all();

        if ($request->file('crud_foto')) {
            if (File::exists(public_path("example/crud/{$crud->crud_foto}")) && $crud->crud_foto != 'default.jpg') File::delete(public_path("example/crud/{$crud->crud_foto}"));

            $filename = time() . '.' . $request->crud_foto->extension();
            $request->crud_foto->move(public_path('example/crud'), $filename);
            $data['crud_foto'] = $filename;
        }

        Crud::findOrFail($crud->crud_id)
            ->update($data);

        return redirect()->route('crud.index')->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $crud = Crud::findOrFail($id);
        if (File::exists(public_path("example/crud/{$crud->crud_foto}")) && $crud->crud_foto != 'default.jpg') File::delete(public_path("example/crud/{$crud->crud_foto}"));

        $crud::destroy($id);

        return response()->json(['success' => 'Data berhasil dihapus!']);
    }
}
