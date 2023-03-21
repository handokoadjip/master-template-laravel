@extends('layouts.dashboard')

@section('title', 'Ubah CRUD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => ['crud-one-to-one.update', $data['crud']->crud_one_to_one_pk_id], 'method' => 'put', 'files' => true]) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_one_pk_nama', 'Nama', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_one_pk_nama', $data['crud']->crud_one_to_one_pk_nama, ['placeholder' => 'Nama', 'class' => $errors->has('crud_one_to_one_pk_nama') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_one_pk_nama', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_one_pk_nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_one_fk_telp', 'Telp', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_one_fk_telp', $data['crud']->telp->crud_one_to_one_fk_telp, ['placeholder' => 'Telp', 'class' => $errors->has('crud_one_to_one_fk_telp') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_one_fk_telp', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_one_fk_telp')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{ Form::submit('Simpan', ['class' => 'btn btn-success waitme']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection