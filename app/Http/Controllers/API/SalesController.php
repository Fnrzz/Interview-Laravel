<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $perPage = $request->query('per_page', 10);
        $salesRefrence = $request->query('sales_refrence');

        $query = Sale::query();

        if ($salesRefrence) {
            $query->where('sales_refrence', 'like', '%' . $salesRefrence . '%');
        }

        $sales = $query->paginate($perPage);
        if ($sales->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No sales found',
                'data' => [],
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sales found successfully',
            'data' => $sales,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'sales_refrence' => 'required|string|unique:sales',
            'sales_date' => 'required|date',
            'product_code' => 'required|string|exists:products,product_code',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 422);
        }

        $sales = Sale::create($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Sales created successfully',
            'data' => $sales,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        //
        if (!$sale) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sales not found',
            ], 404);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sales found successfully',
            'data' => $sale,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        //
        if (!$sale) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sales not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'sales_refrence' => ['required', 'string', Rule::unique('sales')->ignore($sale->id_sales, 'id_sales')],
            'sales_date' => 'required|date',
            'product_code' => 'required|string|exists:products,product_code',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors(),
            ], 400);
        }

        $sale->update($request->all());
        return response()->json([
            'status' => 'success',
            'message' => 'Sales updated successfully',
            'data' => $sale,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        //
        if (!$sale) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sales not found',
            ], 404);
        }

        $sale->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Sales deleted successfully',
        ], 200);
    }
}
