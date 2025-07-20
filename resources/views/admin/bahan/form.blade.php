<x-modal data-backdrop="static" data-keyboard="false" size="modal-lg">
    <x-slot name="title">
        Tambah Bahan & Variasi Ukuran
    </x-slot>

    @method('POST')

    {{-- Nama Bahan --}}
    <div class="form-group">
        <label for="nama_bahan">Nama Bahan</label>
        <input type="text" class="form-control" name="nama_bahan" id="nama_bahan" autocomplete="off">
    </div>

    {{-- Keterangan --}}
    <div class="form-group">
        <label for="keterangan">Keterangan</label>
        <textarea name="keterangan" id="keterangan" cols="30" rows="3" class="form-control summernote"></textarea>
    </div>

    <hr>

    <h6>Variasi Ukuran & Harga</h6>
    <div id="variant-container">
        <div class="row variant-item">
            <div class="col-md-2">
                <label>Ukuran</label>
                <input type="text" name="variants[0][size]" class="form-control" placeholder="Contoh: M">
            </div>
            <div class="col-md-3">
                <label>Harga</label>
                <input type="number" name="variants[0][price]" class="form-control" placeholder="Harga per pcs">
            </div>
            <div class="col-md-3">
                <label>Min. Qty Diskon</label>
                <input type="number" name="variants[0][min_quantity_discount]" class="form-control"
                    placeholder="Contoh: 12">
            </div>
            <div class="col-md-3">
                <label>Diskon (%)</label>
                <input type="number" name="variants[0][discount_percent]" class="form-control"
                    placeholder="Contoh: 10">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger remove-variant">×</button>
            </div>
        </div>
    </div>

    <div class="mt-2">
        <button type="button" class="btn btn-sm btn-success" id="add-variant">+ Tambah Varian</button>
    </div>

    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i> Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-danger">
            <i class="fas fa-times"></i> Close
        </button>
    </x-slot>
</x-modal>

{{-- Tambahkan script jQuery --}}
@push('scripts')
    <script>
        let variantIndex = 1;
        $('#add-variant').click(function() {
            const html = `
        <div class="row variant-item mt-2">
            <div class="col-md-2">
                 <label>Size</label>
                <input type="text" name="variants[${variantIndex}][size]" class="form-control" placeholder="Ukuran">
            </div>
            <div class="col-md-3">
                   <label>Harga</label>
                <input type="number" name="variants[${variantIndex}][price]" class="form-control" placeholder="Harga">
            </div>
            <div class="col-md-3">
                  <label>Min Qty Diskon</label>
                <input type="number" name="variants[${variantIndex}][min_quantity_discount]" class="form-control" placeholder="Min Qty">
            </div>
            <div class="col-md-3">
                 <label>Diskon (%)</label>
                <input type="number" name="variants[${variantIndex}][discount_percent]" class="form-control" placeholder="Diskon %">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-sm btn-danger remove-variant">×</button>
            </div>
        </div>`;
            $('#variant-container').append(html);
            variantIndex++;
        });

        $(document).on('click', '.remove-variant', function() {
            $(this).closest('.variant-item').remove();
        });
    </script>
@endpush
