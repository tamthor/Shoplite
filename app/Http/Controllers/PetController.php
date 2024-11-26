<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pet;
use App\Models\PetCategory;

class PetController extends Controller
{
    protected $pagesize;

    public function __construct()
    {
        $this->pagesize = env('NUMBER_PER_PAGE', '20');
        $this->middleware('auth');
    }

    /**
     * Hiển thị danh sách thú cưng.
     */
    public function index()
    {
        $func = "pet_list";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        $active_menu = "pro_list";
        // $pets = Pet::orderBy('id', 'desc')->paginate($this->pagesize);
        $pets = Pet::orderBy('id', 'desc')->paginate($this->pagesize);
        $categories = PetCategory::get();
        $cat_id = 0;

        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page">Danh sách thú cưng</li>';

        return view('backend.pets.index', compact('pets', 'categories', 'active_menu', 'cat_id', 'breadcrumb'));
    }

    /**
     * Hiển thị form thêm thú cưng mới.
     */
    public function create()
    {
        $func = "pet_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
    
        $active_menu = "pro_list";
        $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="' . route('pets.index') . '">Thú cưng</a></li>
            <li class="breadcrumb-item active" aria-current="page"> Thêm thú cưng </li>';
    
        // Lấy danh sách danh mục thú cưng (PetCategory)
        $categories = PetCategory::orderBy('id', 'ASC')->get();
        return view('backend.pets.create', compact('breadcrumb', 'active_menu', 'categories'));
    }
    

    /**
     * Lưu thông tin thú cưng mới vào cơ sở dữ liệu.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $func = "pet_add";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $this->validate($request, [
            'name' => 'string|required',
            'type' => 'string|required',
            'category_id' => 'integer|required|exists:pets_category,id',
            'description' => 'string|nullable',
            'price' => 'numeric|required|min:0',
            'image' => 'string|nullable',
        ]); 

        $data = $request->all();

        // Xử lý hình ảnh mặc định
        if (empty($data['image'])) {
            $data['image'] = asset('backend/assets/dist/images/default-pet.jpg');
        }

        // Sử dụng phương thức tĩnh từ model Pet
        $status = Pet::createPet($data);

        if ($status) {
            return redirect()->route('pets.index')->with('success', 'Thêm thú cưng thành công!');
        } else {
            return back()->with('error', 'Có lỗi xảy ra!');
        }
    }

    /**
     * Xóa thú cưng khỏi cơ sở dữ liệu.
     */
    public function destroy($id)
    {
        $func = "pet_delete";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $status = Pet::deletePet($id);

        if ($status) {
            return redirect()->route('pets.index')->with('success', 'Xóa thú cưng thành công!');
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu hoặc không thể xóa!');
        }
    }

    /**
     * Hiển thị form chỉnh sửa thú cưng.
     */
    public function edit($id)
    {
        $func = "pet_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }

        $pet = Pet::find($id);

        if ($pet) {
            $active_menu="pro_list";
            $categories = PetCategory::orderBy('id', 'ASC')->get();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="' . route('pets.index') . '">Thú cưng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa thú cưng</li>';

            return view('backend.pets.edit', compact('pet', 'categories', 'breadcrumb', 'active_menu'));
        } else {
            return back()->with('error', 'Không tìm thấy dữ liệu!');
        }
    }
    /**
     * Cập nhật thông tin thú cưng.
     */
    public function update(Request $request, $id)
    {
        $func = "pet_edit";
        if (!$this->check_function($func)) {
            return redirect()->route('unauthorized');
        }
        // dd($request->all());
        $pet = Pet::find($id);
        if ($pet){
            $this->validate($request, [
                'name' => 'string|required',
                'type' => 'string|required',
                'category_id' => 'integer|required|exists:pets_category,id',
                'description' => 'string|nullable',
                'price' => 'numeric|required|min:0',
                'image' => 'string|nullable',
                'image_old'=>'string|nullable',
            ]);
            $data = $request->all();
        
            // Xử lý hình ảnh mặc định
            if($request->image_old == null)
            {
                $data['image_old'] =   $pet->image;
            }
                
            if($data['image'] == null || $data['image']=="")
                $data['image'] = $data['image_old'] ; 
            else
                $data['image'] = $data['image'].",". $data['image_old']   ;
    
            $status = Pet::updatePet($id, $data);
            if($status){
                return redirect()->route('pets.index')->with('success', 'Cập nhật thông tin thú cưng thành công!');
            }else{
                return back()->with('error', 'Cập nhật thất bại!');
            }
        }
        else{
            return back()->with('error', 'Cập nhật thất bại!');
        }
    }
}
