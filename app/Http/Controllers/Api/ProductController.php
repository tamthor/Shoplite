<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAllProductList()
    {
        // $products = \App\Models\Product::where('status', 'active')->get();
        $products = DB::select("select a.id, a.title, a.photo, a.price from products a where status = 'active'");
        return response()->json([
            'success' => true,
            'products' => json_encode($products),
        ], 200);
    }

    public function getAllProductCat(){
        $cat = Category::where('status', 'active')->get();
        return response()->json([
            'success' => true,
            'cats' => json_encode($cat),
        ], 200);
    }

    public function getProductCat(Request $request){
        $id =$request->cat_id;
        $products = \App\Models\Product::where('cat_id', $id)->get();
        return response()->json([
            'success' => true,
            'cats' => json_encode($products),
        ], 200);
    }
}