<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    //
    public function create()
    {
        $products = Product::orderBy('product_name')->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'product_code' => 'required|string|exists:products,product_code',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $salesReference = 'SALE-' . strtoupper(Str::random(10));
        $productCode = $request->product_code;
        $quantity = $request->quantity;
        $price = $request->price;

        try {
            //code...
            DB::select(
                'CALL create_sale(?, ?, ?, ?)',
                [
                    $salesReference,
                    $productCode,
                    $quantity,
                    $price
                ]
            );

            return redirect()->route('dashboard')->with('success', 'Penjualan berhasil dibuat.');
        } catch (\Throwable $th) {
            throw $th;
            if (str_contains($th->getMessage(), 'Stok tidak mencukupi')) {
                return back()
                    ->withErrors(['quantity' => 'Stok tidak mencukupi. Sisa stok saat ini.'])
                    ->withInput();
            }

            return back()->with('error', 'Penjualan gagal dibuat.');
        }
    }
}
