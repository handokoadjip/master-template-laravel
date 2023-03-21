@extends('layouts.dashboard')

@section('title', 'Hak Akses Grup')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('grup_nama', 'Grup', ['class' => 'form-label']) }}
                                {{ Form::text('grup_nama', $data['group']->grup_nama, ['placeholder' => 'Grup', 'class' => $errors->has('grup_nama') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'grup_nama', 'disabled'=>'disabled']) }}
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('grup_deskripsi', 'Deskripsi', ['class' => 'form-label']) }}
                                {{ Form::text('grup_deskripsi', $data['group']->grup_deskripsi, ['placeholder' => 'Deskripsi', 'class' => $errors->has('grup_deskripsi') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'grup_deskripsi', 'disabled'=>'disabled']) }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{ Form::open(['route' => ['grup.permissionSync', $data['group']->grup_id], 'method' => 'post']) }}
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Menu</h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        @forelse($data['groupMenuItems'] as $key => $groupMenuItem)
                        <div class="col-lg-4 mb-4">
                            <div class="accordion h-100" id="accordion-{{ $groupMenuItem->menu_item_id }}">
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-{{ $groupMenuItem->menu_item_id }}">
                                        <button class="accordion-button {{ @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_menu_item_id == $groupMenuItem->menu_item_id ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $groupMenuItem->menu_item_id }}" aria-expanded="{{ @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_menu_item_id == $groupMenuItem->menu_item_id ? 'true' : 'false' }}" aria-controls="collapse-{{ $groupMenuItem->menu_item_id }}">
                                            <div class="form-check">
                                                {{ Form::checkbox('grup_menu_item[' . $key. ' ]', $groupMenuItem->menu_item_id, false, ['class' => 'form-check-input', 'id' => $groupMenuItem->menu_item_id, 'checked' => @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_menu_item_id == $groupMenuItem->menu_item_id ]) }}
                                                {{ Form::label($groupMenuItem->menu_item_id, $groupMenuItem->menu_item_label, ['class' => 'form-check-label']) }}
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse-{{ $groupMenuItem->menu_item_id }}" class="accordion-collapse collapse {{ @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_menu_item_id == $groupMenuItem->menu_item_id ? 'show' : '' }}" aria-labelledby="heading-{{ $groupMenuItem->menu_item_id }}" data-bs-parent="#accordion-{{ $groupMenuItem->menu_item_id }}">
                                        <div class="accordion-body">
                                            <div class="ms-3 form-check">
                                                {{ Form::checkbox('grup_menu_crud[' . $key . '][tambah]', 'ya', false, ['class' => 'form-check-input', 'id' => 'tambah|' . $groupMenuItem->menu_item_id, 'checked' => @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_tambah == 'ya']) }}
                                                {{ Form::label('tambah|' . $groupMenuItem->menu_item_id, 'Tambah', ['class' => 'form-check-label']) }}
                                            </div>
                                            <div class="ms-3 form-check">
                                                {{ Form::checkbox('grup_menu_crud[' . $key . '][ubah]', 'ya', false, ['class' => 'form-check-input', 'id' => 'ubah|' . $groupMenuItem->menu_item_id, 'checked' => @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_ubah == 'ya']) }}
                                                {{ Form::label('ubah|' . $groupMenuItem->menu_item_id, 'Ubah', ['class' => 'form-check-label']) }}
                                            </div>
                                            <div class="ms-3 form-check">
                                                {{ Form::checkbox('grup_menu_crud[' . $key . '][hapus]', 'ya', false, ['class' => 'form-check-input', 'id' => 'hapus|' . $groupMenuItem->menu_item_id, 'checked' => @$groupMenuItem->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_menu_item_hapus == 'ya']) }}
                                                {{ Form::label('hapus|' . $groupMenuItem->menu_item_id, 'Hapus', ['class' => 'form-check-label']) }}
                                            </div>
                                            @foreach ($groupMenuItem->actions as $key => $action)
                                            <div class="ms-3 form-check">
                                                {{ Form::checkbox('grup_aksi[' . $action->aksi_id . ']', $action->aksi_id, false, ['class' => 'form-check-input', 'id' => $groupMenuItem->menu_item_id, 'checked' => @$groupMenuItem->actions[$key]->groups->filter(function($item)use ($data){ return $item->grup_id == $data['group']->grup_id; })->flatten()[0]->pivot->grup_aksi_aksi_id == $action->aksi_id]) }}
                                                <a class="form-check-label text-primary" href="{{ route('aksi.edit', ['action' => $action->aksi_id, 'menuItemId' => $groupMenuItem->menu_item_id, 'groupId' => $data['group']->grup_id]) }}">
                                                    {{$action->aksi_label}}
                                                </a>
                                            </div>
                                            @endforeach
                                            <div class="ms-3 mt-3">
                                                <a class="btn btn-sm btn-primary" href="{{ route('aksi.create', ['menuItemId' => $groupMenuItem->menu_item_id, 'groupId' => $data['group']->grup_id]) }}">Tambah Aksi</i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-lg-12">
                            <p>Tidak ada Menu</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">Unit Kerja</h3>
                </div>
                <div class="card-body">
                    @forelse($data['groupWorkUnits'] as $key => $groupWorkUnit)
                    <div class="mb-2">
                        <div class="form-check">
                            {{ Form::checkbox('groupWorkUnit[]', $groupWorkUnit->unit_kerja_id, false, ['class' => 'form-check-input', 'id' => $groupWorkUnit->unit_kerja_id, 'checked' => @$groupWorkUnit->groups[0]->pivot->grup_unit_kerja_unit_kerja_id == $groupWorkUnit->unit_kerja_id]) }}
                            {{ Form::label($groupWorkUnit->unit_kerja_id, $groupWorkUnit->unit_kerja_nama, ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                    @empty
                    <p>Tidak ada Unit Kerja</p>
                    @endforelse
                </div>
            </div>
        </div> -->
    </div>
    {{ Form::submit('Simpan', ['class' => 'btn btn-success waitme w-100']) }}

    {{ Form::close() }}

</div>
@endsection