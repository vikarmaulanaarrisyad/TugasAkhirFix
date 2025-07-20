@extends('layouts.stisla')

@section('title', 'Daftar Bahan')

@section('content')
    <div class="row">
        <div class="col-12">
            <x-card>
                <x-slot name="header">
                    <button onclick="addForm(`{{ route('admin.bahan.store') }}`)" class="btn btn-sm btn-primary"><i
                            class="fas fa-plus-circle"></i> Tambah
                        Data</button>
                </x-slot>
                <x-table>
                    <x-slot name="thead">
                        <th>No</th>
                        <th>Nama Bahan</th>
                        <th>Harga</th>
                        <th>Ketengan</th>
                        <th>Aksi</th>
                    </x-slot>
                </x-table>
            </x-card>
        </div>
    </div>
    @include('admin.bahan.form')
@endsection

@include('includes.datatables')
@include('includes.summernote')

@push('scripts')
    <script>
        let table;
        let modal = '#modal-form';
        let button = '#submitBtn';

        table = $('.table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: '{{ route('admin.bahan.data') }}'
            },
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'nama_bahan'
                },

                {
                    data: 'variants'
                },

                {
                    data: 'keterangan'
                },
                {
                    data: 'aksi',
                    name: 'aksi',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // fungsi tambah data baru
        function addForm(url, title = 'Tambah Data Bahan') {
            $(modal).modal('show');
            $(`${modal} .modal-title`).text(title);
            $(`${modal} form`).attr('action', url);
            $(`${modal} [name=_method]`).val('post');
            // Hide the filename display
            resetForm(`${modal} form`);
            $("#variant-container").empty(); // kosongkan varian
        }

        // fungsi edit data
        function editForm(url, title = 'Edit Data Bahan') {
            $.get(url) // Perform a GET request to the specified URL
                .done(response => {
                    $(modal).modal('show'); // Show the modal
                    $(`${modal} .modal-title`).text(title); // Set the modal title
                    $(`${modal} form`).attr('action', url); // Set the form action to the URL
                    $(`${modal} [name=_method]`).val('put'); // Set the HTTP method to PUT

                    resetForm(`${modal} form`); // Reset the form fields
                    loopForm(response.data); // Populate the form fields with the response data
                    // Tampilkan variants jika ada
                    if (response.data.variants && Array.isArray(response.data.variants)) {
                        $('#variant-container').empty(); // reset
                        response.data.variants.forEach(variant => {
                            addVariantRow(variant);
                        });
                    }
                })
                .fail(errors => { // Handle any errors from the GET request
                    $('#spinner-border').hide(); // Hide the spinner
                    $(button).prop('disabled', false); // Enable the button
                    Swal.fire({ // Show an error message
                        icon: 'error',
                        title: 'Oops! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        $('#spinner-border').hide();
                        $(button).prop('disabled', false);
                        loopErrors(errors.responseJSON.errors); // Handle validation errors
                    }
                });
        }

        function addVariantRow(variant = {}) {
            $('#variant-container').append(`
        <div class="variant-item row mb-2">
            <div class="col-md-2">
                <label>Size</label>
                <input type="text" name="variants[size][]" value="${variant.size ?? ''}" class="form-control" required>
            </div>
            <div class="col-md-2">
                <label>Harga</label>
                <input type="number" name="variants[price][]" value="${variant.price ?? ''}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Min Qty Diskon</label>
                <input type="number" name="variants[min_quantity_discount][]" value="${variant.min_quantity_discount ?? ''}" class="form-control">
            </div>
            <div class="col-md-2">
                <label>Diskon (%)</label>
                <input type="number" name="variants[discount_percent][]" value="${variant.discount_percent ?? ''}" class="form-control">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <label>&nbsp;</label>
                <button type="button" class="btn btn-sm btn-danger remove-variant" onclick="$(this).closest('.variant-item').remove()">×</button>
            </div>
        </div>
    `);
        }

        // fungsi delete data
        function deleteData(url, name) {
            Swal.fire({
                title: 'Hapus Data!',
                text: 'Apakah Anda yakin ingin menghapus ' + name + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batalkan',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false,
                            });
                            table.ajax.reload(); // Refresh tabel
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan.',
                            });
                        }
                    });
                }
            });
        }

        // fungsi kirim data inputan
        function submitForm(originalForm) {
            const submitBtn = $('#submitBtn'); // Reference to the button
            $(button).prop('disabled', true);
            $('#spinner-border').show();
            submitBtn.addClass('btn-progress');

            $.post({
                    url: $(originalForm).attr('action'),
                    data: new FormData(originalForm),
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false
                })
                .done(response => {
                    $(modal).modal('hide');
                    if (response.status = 200) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            $(button).prop('disabled', false);
                            $('#spinner-border').hide();
                            submitBtn.removeClass('btn-progress');
                            table.ajax.reload();
                        })
                    }
                })
                .fail(errors => {
                    $('#spinner-border').hide();
                    submitBtn.removeClass('btn-progress');
                    $(button).prop('disabled', false);
                    Swal.fire({
                        icon: 'error',
                        title: 'Opps! Gagal',
                        text: errors.responseJSON.message,
                        showConfirmButton: true,
                    });
                    if (errors.status == 422) {
                        $('#spinner-border').hide()
                        submitBtn.removeClass('btn-progress')
                        $(button).prop('disabled', false);
                        loopErrors(errors.responseJSON.errors);
                        return;
                    }
                });
        }
    </script>
@endpush
