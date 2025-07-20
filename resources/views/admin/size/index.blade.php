@extends('layouts.stisla')

@section('title', 'Daftar Ukuran')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    {{-- Ganti route ke admin.size.store --}}
                    <button onclick="addForm(`{{ route('admin.size.store') }}`)" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle"></i> Tambah Data
                    </button>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        {{-- Sesuaikan header tabel --}}
                        <th>No</th>
                        <th>Nama Ukuran</th>
                        <th>Penyesuaian Harga</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    {{-- Ganti include ke admin.size.form --}}
    @include('admin.size.form')
@endsection

@include('includes.datatables')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        $(function() {
            table = $('.table').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: false,
                responsive: true,
                ajax: {
                    // Ganti route ke admin.size.data
                    url: '{{ route('admin.size.data') }}'
                },
                columns: [
                    // Sesuaikan kolom data
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name' },
                    { data: 'price_adjustment' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false },
                ]
            });
        });

        // fungsi tambah data baru
        function addForm(url, title = 'Tambah Data Ukuran') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            resetForm(`${modal} form`);
        }

        // fungsi edit data
        function editForm(url, title = 'Edit Data Ukuran') {
            $.get(url)
                .done(response => {
                    $(modal).modal('show');
                    $(`${modal} .modal-title`).text(title);
                    $(`${modal} form`).attr('action', url);
                    $(`${modal} [name=_method]`).val('put');
                    resetForm(`${modal} form`);
                    loopForm(response.data);
                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                });
        }

        // fungsi delete data
        function deleteData(url, name) {
            Swal.fire({
                title: 'Hapus Data!',
                text: `Apakah Anda yakin ingin menghapus ukuran ${name}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post(url, {
                            '_method': 'delete'
                        })
                        .done(response => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            table.ajax.reload();
                        })
                        .fail(errors => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: 'Tidak dapat menghapus data',
                            });
                            return;
                        });
                }
            });
        }
        
        // fungsi submit form (asumsi sudah ada di template utama Anda)
        function submitForm(originalForm) {
            // ... (kode submitForm Anda) ...
        }
    </script>
@endpush