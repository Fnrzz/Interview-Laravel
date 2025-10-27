@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
    <h1 class="h2 mb-4">Dashboard</h1>

    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0">Produk Terlaris (Top 5)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama Produk</th>
                            <th scope="col" class="text-end">Total Penjualan (Unit)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($topProducts as $index => $product)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td class="text-end fw-bold">{{ $product->total_sales }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada data penjualan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
