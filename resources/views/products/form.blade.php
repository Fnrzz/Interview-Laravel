@extends('layouts.layout')

@section('title', (isset($product) ? 'Edit' : 'Tambah') . ' Produk')

@section('content')
    <h1 class="h2 mb-4">{{ isset($product) ? 'Edit' : 'Tambah' }} Produk</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form
                action="{{ isset($product) ? route('dashboard.products.update', $product) : route('dashboard.products.store') }}"
                method="POST">
                @csrf
                @if (isset($product))
                    @method('PUT')
                @endif

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="product_code" class="form-label">Kode Produk</label>
                        <input type="text" class="form-control @error('product_code') is-invalid @enderror"
                            id="product_code" name="product_code"
                            value="{{ old('product_code', $product->product_code ?? '') }}" required>
                        @error('product_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="product_name" class="form-label">Nama Produk</label>
                        <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                            id="product_name" name="product_name"
                            value="{{ old('product_name', $product->product_name ?? '') }}" required>
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label">Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price"
                                name="price" value="{{ old('price', $product->price ?? '') }}" required min="0">
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="stock" class="form-label">Stok</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock"
                            name="stock" value="{{ old('stock', $product->stock ?? '') }}" required min="0">
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <hr class="my-4">

                <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Simpan' }}</button>
                <a href="{{ route('dashboard.products.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
