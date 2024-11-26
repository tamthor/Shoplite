<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\PetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
    }
    public function index()
    {
        $func = "pcat_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="cat_list";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh mục </li>';
        $categories=PetCategory::orderBy('id','DESC')->paginate( $this->pagesize );
        return view('backend.pet_categories.index',compact('categories','breadcrumb','active_menu'));
        
    }
    public function create()
    {
        $func = "pcat_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $active_menu="cat_add";
        $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item  " aria-current="page"><a href="'.route('pet_category.index').'">Danh mục</a></li>
        <li class="breadcrumb-item active" aria-current="page"> tạo danh mục </li>';
        return view('backend.pet_categories.create',compact('breadcrumb','active_menu'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $func = "pcat_add";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        // return $request->all();
        $this->validate($request,[
            'name'=>'string|required',
            'description'=>'string|nullable',
            'image'=>'string|nullable',
        ]);
        
        $data = $request->all();
        // return $data;
        if($request->photo == null)
            $data['image'] = asset('backend/assets/dist/images/profile-6.jpg');
        $cat = PetCategory::where('name',$data['name'])->first();
        if($cat != null)
        {
            return back()->with('error','Danh mục đã có');
        }
        $status = PetCategory::create($data);
        return redirect()->route('pet_category.index')->with('success','Tạo danh mục thành công!');
    }
    public function edit(string $id)
    {
        $func = "pcat_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $category = PetCategory::find($id);
        $active_menu="cat_list";
        if($category)
        {
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('pet_category.index').'">Danh mục</a></li>
            <li class="breadcrumb-item active" aria-current="page"> điều chỉnh danh mục </li>';
            return view('backend.pet_categories.edit',compact('breadcrumb','category','active_menu'));
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function update(Request $request, string $id)
    {
        // dd($request->all());

        $func = "pcat_edit";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $category = PetCategory::find($id);
        if($category)
        {
            $this->validate($request,[
                'name'=>'string|required',
                'description'=>'string|nullable',
                'image'=>'string|nullable',
                'image_old'=>'string|nullable',
            ]);
            $data = $request->all();
            if($request->image_old == null)
            {
                $data['image_old'] =   $category->image;
            }
            if($data['image'] == null || $data['image']=="")
                $data['image'] = $data['image_old'] ;
            if($data['image'] == null && $data['image_old']=="")
                    $data['image'] = asset('backend/assets/dist/images/profile-6.jpg');
            //check exit name
            $cat = PetCategory::where('name',$data['name'])
                    ->where('id','<>', $category->id)->first();
            if($cat != null)
            {
                return back()->with('error','Danh mục đã có');
            }
            $status = $category->fill($data)->save();
            return redirect()->route('pet_category.index')->with('success','Cập nhật thành công');
        
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    public function destroy(string $id)
    {
        $func = "pcat_delete";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        //
        $category = PetCategory::find($id);
        if($category)
        {
            $status = $category->delete();

            if($status){
                return redirect()->route('pet_category.index')->with('success','Xóa danh mục thành công!');
            }
            else
            {
                return back()->with('error','Có lỗi xãy ra!');
            }    
        }
        else
        {
            return back()->with('error','Không tìm thấy dữ liệu');
        }
    }
    
}
