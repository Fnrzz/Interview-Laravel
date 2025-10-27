<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $topProducts = DB::select('CALL get_top_product()');
        return view('dashboard', compact('topProducts'));
    }
}
