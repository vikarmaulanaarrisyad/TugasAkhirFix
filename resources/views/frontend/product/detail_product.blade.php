@extends('frontend.main_master')

@section('title', 'Detail Produk')

@section('content')
    <div class="breadcrumb">
        {{-- Breadcrumb content remains the same --}}
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline list-unstyled">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li class='active'>{{ $product->product_name }}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="body-content outer-top-xs">
        <div class='container'>
            <div class='row single-product'>
                <div class='col-md-12 sidebar'>
                    {{-- Hot Deals include remains the same --}}
                    @include('frontend.common.hotdeals_product')
                </div>

                <div class='col-md-12'>
                    <div class="detail-block">
                        <div class="row wow fadeInUp">
                            <div class="col-xs-12 col-sm-6 col-md-5 gallery-holder">
                                {{-- Image gallery remains the same --}}
                                <div class="product-item-holder size-big single-product-gallery small-gallery">
                                    <div id="owl-single-product">
                                        @foreach ($multiImg as $img)
                                            <div class="single-product-gallery-item" id="slide{{ $img->id }}">
                                                <img class="img-responsive" alt="" src="{{ Storage::url($img->photo_name) }}" data-echo="{{ Storage::url($img->photo_name) }}" />
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="single-product-gallery-thumbs gallery-thumbs">
                                        <div id="owl-single-product-thumbnails">
                                            @foreach ($multiImg as $img)
                                                <div class="item">
                                                    <a class="horizontal-thumb active" data-target="#owl-single-product" data-slide="1" href="#slide{{ $img->id }}">
                                                        <img class="img-responsive" width="85" alt="" src="{{ Storage::url($img->photo_name) }}" data-echo="{{ Storage::url($img->photo_name) }}" />
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
<div class='col-sm-6 col-md-7 product-info-block'>
    <div class="product-info">
        <h1 class="name" id="pname">{{ $product->product_name }}</h1>

        {{-- Tampilan Stok --}}
        <div class="stock-container info-container m-t-10">
            <div class="row">
                <div class="col-sm-2">
                    <div class="stock-box"><span class="label">Tersedia :</span></div>
                </div>
                <div class="col-sm-9">
                    <div class="stock-box"><span id="stock-status" class="value"></span></div>
                </div>
            </div>
        </div>

        {{-- Deskripsi Singkat --}}
        <div class="description-container m-t-20">{{ $product->short_descp }}</div>

        {{-- Tampilan Harga --}}
        <div class="price-container info-container m-t-20">
            <div class="row">
                <div class="col-sm-12">
                    <div class="price-box" id="price-container">
                        <span class="price">Pilih ukuran untuk melihat harga</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pilihan Varian --}}
        <div class="price-container info-container m-t-20">
            <div class="row">
                {{-- KOLOM UKURAN --}}
                <div class="col-sm-6">
                    <div class="form-group">
                        @if ($product->variants->isNotEmpty())
                            <label>Ukuran</label>
                            <select name="size" id="size" class="form-control" required>
                                <option value="" selected disabled>Pilih Ukuran</option>
                                @foreach ($product->variants as $variant)
                                    <option value="{{ $variant->size }}"
                                        data-variant-id="{{ $variant->id }}"
                                        data-price="{{ $variant->price }}"
                                        data-price-after-discount="{{ $variant->price_after_discount }}"
                                        data-stock="{{ $variant->quantity }}">
                                        {{ $variant->size }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                {{-- KOLOM WARNA --}}
                <div class="col-sm-6">
                    <div class="form-group">
                        @if ($product->product_color)
                            <label>Warna</label>
                            <select name="color" id="color" class="form-control" required>
                                <option value="" selected disabled>Pilih Warna</option>
                                @foreach (explode(',', $product->product_color) as $color)
                                    <option value="{{ trim($color) }}">{{ trim($color) }}</option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Kuantitas & Tombol Add to Cart --}}
        <div class="quantity-container info-container">
            <div class="row">
                <div class="col-sm-2">
                    <span class="label">Jumlah :</span>
                </div>
                <div class="col-sm-2">
                    <div class="cart-quantity">
                        <div class="quant-input">
                            <div class="arrows">
                                <div class="arrow plus gradient"><span class="ir"><i class="icon fa fa-sort-asc"></i></span></div>
                                <div class="arrow minus gradient"><span class="ir"><i class="icon fa fa-sort-desc"></i></span></div>
                            </div>
                            <input type="text" value="1" id="qty" min="1">
                        </div>
                    </div>
                </div>
                
                {{-- Input tersembunyi yang PENTING --}}
                <input type="hidden" id="product_id" value="{{ $product->id }}">
                <input type="hidden" id="variant_id" name="variant_id">

                <div class="col-sm-7">
                    {{-- Tombol DENGAN onclick --}}
                    <button type="button" id="addToCartBtn" onclick="addToCart()" class="btn btn-primary" disabled>
                        <i class="fa fa-shopping-cart inner-right-vs"></i> ADD TO CART
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>

                    {{-- Product Tabs and Related Products sections remain the same --}}
                    <div class="product-tabs inner-bottom-xs wow fadeInUp">
                        <div class="row">
                            <div class="col-sm-3">
                                <ul id="product-tabs" class="nav nav-tabs nav-tab-cell">
                                    <li class="active"><a data-toggle="tab" href="#description">DESCRIPTION</a></li>
                                </ul>
                            </div>
                            <div class="col-sm-9">
                                <div class="tab-content">
                                    <div id="description" class="tab-pane in active">
                                        <div class="text">
                                            {!! nl2br(e($product->long_descp)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                                        <section class="section featured-product wow fadeInUp">
                        <h3 class="section-title">Related products</h3>
                        <div class="owl-carousel home-owl-carousel upsell-product custom-carousel owl-theme outer-top-xs">

                            @if ($relatedProduct->isNotEmpty())
                                @foreach ($relatedProduct as $product)
                                @endforeach
                                <div class="item item-carousel">
                                    <div class="products">

                                        <div class="product">
                                            <div class="product-image">
                                                <div class="image">
                                                    <a
                                                        href="{{ url('/detail/' . $product->id . '/' . $product->product_slug) }}"><img
                                                            src="{{ Storage::url($product->product_thumbnail) }}"
                                                            alt=""></a>
                                                </div><!-- /.image -->



                                                @if ($product->discount_price != 0)
                                                    <div class="tag sale"><span>{{ $product->discount_price }} %<br>
                                                            off</span></div>
                                                @else
                                                    <div class="tag sale"><span>New</span></div>
                                                @endif
                                            </div><!-- /.product-image -->


                                            <div class="product-info text-left">
                                                <h3 class="name"><a
                                                        href="{{ url('/detail/' . $product->id . '/' . $product->product_slug) }}">{{ $product->product_name }}</a>
                                                </h3>

                                                <div class="description">{{ $product->short_descp }}</div>

                                                @if ($product->discount_price == 0)
                                                    <div class="product-price"> <span class="price">
                                                            Rp. {{ format_uang($product->selling_price) }}
                                                        </span>

                                                    </div>
                                                @else
                                                    <div class="product-price"> <span class="price">
                                                            Rp.
                                                            {{ format_uang($product->price_after_discount) }}
                                                        </span>
                                                        <span class="price-before-discount">Rp.
                                                            {{ format_uang($product->selling_price) }}</span>
                                                    </div>
                                                @endif

                                            </div><!-- /.product-info -->
                                            <div class="cart clearfix animate-effect">
                                                <div class="action">
                                                    <ul class="list-unstyled">
                                                        <li class="add-cart-button btn-group">
                                                            <button data-toggle="modal" id="{{ $product->id }}"
                                                                onclick="productView(this.id)" data-target="#staticBackdrop"
                                                                class="btn btn-primary icon" type="button"
                                                                title="Add Cart"> <i class="fa fa-shopping-cart"></i>
                                                            </button>
                                                            <button class="btn btn-primary cart-btn" type="button">Add to
                                                                cart</button>

                                                        </li>


                                                        <li class="add-cart-button btn-group"> <button type="submit"
                                                            onclick="addToWislist(this.id)" id="{{ $product->id }}"
                                                            data-toggle="tooltip" class="btn btn-primary icon"
                                                            title="Wishlist">
                                                            <i class="icon fa fa-heart"></i>
                                                        </button>
                                                    </li>

                                                        <li class="lnk">
                                                            <a class="add-to-cart" href="detail.html" title="Compare">
                                                                <i class="fa fa-signal"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div><!-- /.action -->
                                            </div><!-- /.cart -->
                                        </div><!-- /.product -->

                                    </div><!-- /.products -->
                                </div><!-- /.item -->
                            @endif

                        </div><!-- /.home-owl-carousel -->
                    </section><!-- /.section -->

                    {{-- @include('frontend.common.related_product') --}}

                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>

{{-- ========== JAVASCRIPT (MODIFIED) ========== --}}
<script type="text/javascript">
    // ===================================================================
    // FUNGSI GLOBAL UNTUK ONCLICK TOMBOL ADD TO CART
    // ===================================================================
    function addToCart() {
        // Mengambil semua data yang diperlukan saat tombol diklik
        const productId = document.getElementById("product_id").value;
        const variantId = document.getElementById("variant_id").value;
        const quantity = document.getElementById("qty").value;
        const colorSelect = document.getElementById("color");
        const color = colorSelect ? colorSelect.value : null;
        const sizeSelect = document.getElementById("size"); // Untuk validasi
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Validasi client-side
        if ((sizeSelect && sizeSelect.value === "") || (!variantId && sizeSelect)) {
            Swal.fire({ title: "Gagal", text: "Silakan pilih ukuran terlebih dahulu.", icon: "error" });
            return;
        }
        if (colorSelect && colorSelect.value === "") {
            Swal.fire({ title: "Gagal", text: "Silakan pilih warna terlebih dahulu.", icon: "error" });
            return;
        }

        const formData = new FormData();
        formData.append('variant_id', variantId);
        formData.append('qty', quantity);
        formData.append('color', color);

        fetch('/cart/data/store/' + productId, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ title: "Berhasil!", text: data.success, icon: "success", showConfirmButton: false, timer: 2000 });
                // Panggil fungsi update minicart jika ada, pastikan fungsi miniCart() tersedia secara global
                if (typeof miniCart === "function") {
                    miniCart();
                }
            } else {
                Swal.fire({ title: "Gagal", text: data.error || "Terjadi kesalahan.", icon: "error" });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({ title: "Error", text: "Tidak dapat memproses permintaan.", icon: "error" });
        });
    }


    // ===================================================================
    // LOGIKA UNTUK UPDATE TAMPILAN (HARGA, STOK, DLL)
    // ===================================================================
    document.addEventListener("DOMContentLoaded", function () {
        const sizeSelect = document.getElementById("size");
        const colorSelect = document.getElementById("color");
        const addToCartBtn = document.getElementById("addToCartBtn");
        const priceContainer = document.getElementById("price-container");
        const stockStatus = document.getElementById("stock-status");
        const qtyInput = document.getElementById("qty");
        const variantIdInput = document.getElementById("variant_id");

        function formatCurrency(number) {
            if (isNaN(number)) return "";
            return 'Rp. ' + new Intl.NumberFormat('id-ID').format(number);
        }

        function updateVariantDetails() {
            if (!sizeSelect || sizeSelect.value === "") return;
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            variantIdInput.value = selectedOption.getAttribute('data-variant-id');
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            const priceAfterDiscount = parseFloat(selectedOption.getAttribute('data-price-after-discount'));
            const stock = parseInt(selectedOption.getAttribute('data-stock'));

            priceContainer.innerHTML = (priceAfterDiscount > 0 && priceAfterDiscount < price)
                ? `<span class="price">${formatCurrency(priceAfterDiscount)}</span> <span class="price-strike">${formatCurrency(price)}</span>`
                : `<span class="price">${formatCurrency(price)}</span>`;

            if (stock > 0) {
                stockStatus.innerHTML = `<strong>${stock}</strong> Tersedia`;
                qtyInput.max = stock;
            } else {
                stockStatus.innerHTML = `<span class="text-danger">Habis</span>`;
                qtyInput.max = 0;
            }
            if (parseInt(qtyInput.value) > stock) qtyInput.value = 1;
        }

        function checkSelectionAndStock() {
            const sizeSelected = sizeSelect ? sizeSelect.value !== "" : true;
            const colorSelected = colorSelect ? colorSelect.value !== "" : true;
            let stock = 0;
            if (sizeSelect && sizeSelect.value !== "") {
                const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                stock = parseInt(selectedOption.getAttribute('data-stock'));
            } else if (!sizeSelect) {
                stock = {{ $product->product_qty > 0 ? 1 : 0 }};
            }
            if (sizeSelected && colorSelected && stock > 0) {
                addToCartBtn.removeAttribute("disabled");
            } else {
                addToCartBtn.setAttribute("disabled", "true");
            }
        }

        if (sizeSelect) {
            sizeSelect.addEventListener("change", () => {
                updateVariantDetails();
                checkSelectionAndStock();
            });
        }
        if (colorSelect) {
            colorSelect.addEventListener("change", checkSelectionAndStock);
        }
        checkSelectionAndStock();
    });
</script>

@endsection