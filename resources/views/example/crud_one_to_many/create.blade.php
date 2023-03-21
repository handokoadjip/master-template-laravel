@extends('layouts.dashboard')

@section('title', 'Tambah CRUD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">@yield('title')</h3>
                </div>
                <div class="card-body">
                    {{ Form::open(['route' => 'crud-one-to-many.store', 'method' => 'post', 'files' => true]) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_many_pk_nama', 'Nama', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_many_pk_nama', null, ['placeholder' => 'Nama', 'class' => $errors->has('crud_one_to_many_pk_nama') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_many_pk_nama', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_many_pk_nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_many_fk_telp', 'Telp 1', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_many_fk_telp[0]', null, ['placeholder' => 'Telp 1', 'class' => $errors->has('crud_one_to_many_fk_telp.0') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_many_fk_telp', 'autofocus'=>'autofocus']) }}
                                @if ($errors->has('crud_one_to_many_fk_telp.0'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('crud_one_to_many_fk_telp.0') }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_many_fk_telp', 'Telp 2', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_many_fk_telp[1]', null, ['placeholder' => 'Telp 2', 'class' => $errors->has('crud_one_to_many_fk_telp.1') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_many_fk_telp', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_many_fk_telp.1')
                                <div class="invalid-feedback">
                                    {{ $errors->first('crud_one_to_many_fk_telp.1') }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_many_fk_telp', 'Telp 3', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_many_fk_telp[2]', null, ['placeholder' => 'Telp 3', 'class' => $errors->has('crud_one_to_many_fk_telp.2') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_many_fk_telp', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_many_fk_telp.2')
                                <div class="invalid-feedback">
                                    {{ $errors->first('crud_one_to_many_fk_telp.2') }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                {{ Form::label('crud_one_to_many_fk_telp', 'Telp 4', ['class' => 'form-label']) }}
                                {{ Form::text('crud_one_to_many_fk_telp[3]', null, ['placeholder' => 'Telp 4', 'class' => $errors->has('crud_one_to_many_fk_telp.3') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_one_to_many_fk_telp', 'autofocus'=>'autofocus']) }}
                                @error('crud_one_to_many_fk_telp.3')
                                <div class="invalid-feedback">
                                    {{ $errors->first('crud_one_to_many_fk_telp.3') }}
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