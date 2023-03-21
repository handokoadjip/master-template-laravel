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
                    {{ Form::open(['route' => 'crud.store', 'method' => 'post', 'files' => true]) }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('crud_nama', 'Nama', ['class' => 'form-label']) }}
                                {{ Form::text('crud_nama', null, ['placeholder' => 'Nama', 'class' => $errors->has('crud_nama') ? 'form-control is-invalid' : 'form-control', 'autocomplete'=>'crud_nama', 'autofocus'=>'autofocus']) }}
                                @error('crud_nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-4">
                                {{ Form::label('crud_foto', 'Foto', ['class' => 'form-label']) }}
                                {{ Form::file('crud_foto', ['placeholder' => 'Foto', 'class' => $errors->has('crud_foto') ? 'form-control dropify is-invalid' : 'form-control dropify', 'autocomplete'=>'crud_foto', 'autofocus'=>'autofocus']) }}
                                @error('crud_foto')
                                <div class="mt-1 text-danger">
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