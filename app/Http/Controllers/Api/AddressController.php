<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        // Thêm debug để kiểm tra
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'User not authenticated',
                'user_id' => Auth::id(),
                'user' => Auth::user()
            ], 401);
        }
        
        // Validate đầu vào
        $validator = Validator::make($request->all(), [
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'phone_number' => 'nullable|string|max:20',
            'is_default' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Kiểm tra xem user đã có địa chỉ nào chưa
            $hasAddresses = Address::where('user_id', Auth::id())->exists();
            
            // Nếu chưa có địa chỉ nào, set địa chỉ này làm mặc định
            if (!$hasAddresses) {
                $request->merge(['is_default' => true]);
            }

            // Nếu địa chỉ mới là mặc định, cập nhật tất cả địa chỉ khác
            if ($request->is_default) {
                Address::where('user_id', Auth::id())
                    ->update(['is_default' => false]);
            }

            // Tạo địa chỉ mới
            $address = Address::create([
                'user_id' => Auth::id(),
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'phone_number' => $request->phone_number,
                'is_default' => $request->is_default ?? false
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Address created successfully',
                'data' => $address
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create address',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserAddresses($user_id)
    {
        try {
            // Lấy danh sách địa chỉ dựa trên user_id
            $addresses = Address::where('user_id', $user_id)->get();

            if ($addresses->isEmpty()) {
                return response()->json([
                    'message' => 'No addresses found for this user.',
                    'data' => []
                ], 404);
            }

            return response()->json([
                'message' => 'Addresses retrieved successfully.',
                'data' => $addresses
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
