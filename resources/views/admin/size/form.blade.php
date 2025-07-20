<x-modal data-backdrop="static" data-keyboard="false">
    <x-slot name="title">
        Tambah Data Ukuran
    </x-slot>

    @method('POST')

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Nama Ukuran</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Contoh: XXL" autocomplete="off" required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="price_adjustment">Penyesuaian Harga</label>
                <input type="number" class="form-control" name="price_adjustment" id="price_adjustment" placeholder="Contoh: 10000" required>
                <small class="form-text text-muted">
                    Isi `0` jika tidak ada biaya tambahan. Angka ini adalah **biaya tambahan** dari harga dasar.
                </small>
            </div>
        </div>
    </div>

    {{-- Bagian footer dengan tombol Simpan dan Close --}}
    <x-slot name="footer">
        <button type="button" onclick="submitForm(this.form)" class="btn btn-sm btn-primary" id="submitBtn">
            <i class="fas fa-save mr-1"></i>
            Simpan
        </button>
        <button type="button" data-dismiss="modal" class="btn btn-sm btn-danger">
            <i class="fas fa-times"></i>
            Close
        </button>
    </x-slot>
</x-modal>