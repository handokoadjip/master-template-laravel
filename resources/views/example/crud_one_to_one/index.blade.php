@extends('layouts.dashboard')

@section('title', 'CRUD')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center w-100">
                    <h3 class="card-title">Data @yield('title')</h3>
                    <!-- ------------------------------------------------ -->
                    <!-- Cek apakah crud-one-to-one dapat akses menu -->
                    <!-- ------------------------------------------------ -->
                    @if(PermissionMenu()[0]->groups->filter(function($item){ return $item->grup_id == Auth::user()->groups[0]->grup_id; })->flatten()[0]->pivot->grup_menu_item_tambah == 'ya')
                    <a class="btn btn-success waitme" href="{{ route('crud-one-to-one.create') }}">Tambah CRUD</a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;">No</th>
                                    <th>Nama</th>
                                    <th>Telp</th>
                                    <th class="text-center" style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('script')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var table = $('#table').DataTable({
        serverSide: true,
        fnInitComplete: function() {
            $("#overlay").hide();
        },
        ajax: "{{ route('crud-one-to-one.index') }}",
        columns: [{
                "data": null,
                "sortable": false,
                searchable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'crud_one_to_one_pk_nama',
                name: 'crud_one_to_one_pk_nama'
            },
            {
                data: 'telp.crud_one_to_one_fk_telp',
                name: 'telp.crud_one_to_one_fk_telp',
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
    });
</script>
@endpush