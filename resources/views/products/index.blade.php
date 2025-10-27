@extends('layouts.layout')

@section('title', 'Produk')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 mb-0">Produk</h1>
        <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
            + Tambah Produk Baru
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Nama Produk</th>
                            <th class="text-end">Harga</th>
                            <th class="text-end">Stok</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="fw-bold">{{ $product->product_code }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-end">{{ number_format($product->stock, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('dashboard.products.edit', $product) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>
                                    <form action="{{ route('dashboard.products.destroy', $product) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada data produk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
