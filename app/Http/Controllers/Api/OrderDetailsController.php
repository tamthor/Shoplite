<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Models\Address;
use App\Models\Pet;
use Illuminate\Http\Request;

class OrderDetailController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'pet_id' => 'required|exists:pets,id',
            'pet_price' => 'required|numeric',
            'shipping_cost' => 'required|numeric',
            'tax' => 'required|numeric',
        ]);

        $total = $request->pet_price + $request->shipping_cost + $request->tax;

        $order = OrderDetail::create([
            'address_id' => $request->address_id,
            'pet_id' => $request->pet_id,
            'pet_price' => $request->pet_price,
            'shipping_cost' => $request->shipping_cost,
            'tax' => $request->tax,
            'total' => $total,
        ]);

        return response()->json([
            'message' => 'Order created successfully!',
            'order' => $order,
        ], 201);
    }
}
