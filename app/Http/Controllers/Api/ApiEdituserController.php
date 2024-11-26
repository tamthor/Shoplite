<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class ApiEdituserController extends Controller
{
    //Hiển thị danh sách user
    public function apiuserList(Request $request)
    {
        $query = User::query();

        // Sorting
        $sort = $request->input('sort', 'id');
        $order = $request->input('order', 'asc');
        $query->orderBy($sort, $order);

        // Pagination
        $perPage = $request->input('limit', 10);
        $page = $request->input('page', 1);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'users' => $users->items(),
            'current_page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
        ]);
    }
}
