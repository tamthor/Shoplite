<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $request->validate([
            'pet_id' => 'required|exists:pets,id', // Kiểm tra pet_id thay vì product_id
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        // Kiểm tra nếu sản phẩm đã có trong giỏ hàng
        $cartItem = Cart::where('user_id', $userId)
            ->where('pet_id', $request->pet_id)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'pet_id' => $request->pet_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pet added to cart successfully',
        ], 200);
    }
}
