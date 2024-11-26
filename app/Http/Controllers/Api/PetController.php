<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pet;
use App\Models\PetCategory;

class PetController extends Controller
{
    /**
     * Lấy danh sách tất cả thú cưng
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPets()
    {
        try {
            // Lấy danh sách tất cả thú cưng
            $pets = Pet::with('category')->get();

            return response()->json([
                'success' => true,
                'data' => $pets,
            ], 200);
        } catch (\Exception $e) {
            // Xử lý lỗi
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy danh sách thú cưng.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy danh sách tất cả danh mục thú cưng
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories()
    {
        try {
            $categories = PetCategory::all();

            return response()->json([
                'success' => true,
                'data' => $categories,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy danh sách danh mục.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Lấy chi tiết thú cưng theo ID
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPetDetail($id)
    {
        try {
            $pet = Pet::with('category')->find($id);
            
            if (!$pet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thú cưng với ID này.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $pet,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lấy chi tiết thú cưng.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
