@extends('layouts.layout')

@section('title', 'Transaksi Penjualan Baru')

@section('content')
    <h1 class="h2 mb-4">Transaksi Penjualan Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('dashboard.sales.store') }}" method="POST" id="sale-form">
                @csrf
                <div class="row g-3">

                    <div class="col-md-12">
                        <label for="product_code" class="form-label">Pilih Produk</label>
                        <select class="form-select @error('product_code') is-invalid @enderror" id="product_code"
                            name="product_code" required>
                            <option value="">-- Pilih Produk --</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->product_code }}" data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}"
                                    {{ old('product_code') == $product->product_code ? 'selected' : '' }}>

                                    {{ $product->product_name }} (Stok: {{ $product->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('product_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity"
                            name="quantity" value="{{ old('quantity', 1) }}" min="1" required>
                        <div class="invalid-feedback" id="quantity-feedback"></div>
                    </div>

                    <div class="col-md-4">
                        <label for="price" class="form-label">Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price', 0) }}" min="0" required readonly>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="subtotal" class="form-label">Subtotal</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control" id="subtotal" value="0" readonly disabled>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <button type="submit" class="btn btn-primary" id="save-button">Simpan Transaksi</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const productSelect = document.getElementById('product_code');
        const quantityInput = document.getElementById('quantity');
        const priceInput = document.getElementById('price');
        const subtotalInput = document.getElementById('subtotal');
        const saveButton = document.getElementById('save-button');
        const quantityFeedback = document.getElementById('quantity-feedback');

        let currentStock = 0;

        function calculateSubtotal() {
            const quantity = parseInt(quantityInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            subtotalInput.value = (quantity * price).toLocaleString('id-ID');

            validateStock();
        }

        function validateStock() {
            const quantity = parseInt(quantityInput.value) || 0;

            quantityInput.classList.remove('is-invalid');

            if (quantity > currentStock) {
                quantityFeedback.textContent = 'Stok tidak cukup! (Maks: ' + currentStock + ')';
                quantityInput.classList.add('is-invalid');
                saveButton.disabled = true;
            } else {
                quantityFeedback.textContent = '';
                quantityInput.classList.remove('is-invalid');
                saveButton.disabled = false;
            }
        }

        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const price = selectedOption.getAttribute('data-price') || 0;
            const stock = selectedOption.getAttribute('data-stock') || 0;

            priceInput.value = price;
            currentStock = parseInt(stock);

            calculateSubtotal();
        });

        quantityInput.addEventListener('input', calculateSubtotal);
        priceInput.addEventListener('input', calculateSubtotal);

        document.addEventListener('DOMContentLoaded', function() {
            if (productSelect.value) {
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                currentStock = parseInt(selectedOption.getAttribute('data-stock') || 0);
                calculateSubtotal();
            }

            if (quantityInput.classList.contains('is-invalid') && quantityFeedback.textContent === '') {
                const serverError = quantityInput.nextElementSibling;
                if (serverError && serverError.classList.contains('invalid-feedback')) {
                    quantityFeedback.textContent = serverError.textContent;
                    serverError.style.display = 'none';
                }
            }
        });
    </script>
@endsection
