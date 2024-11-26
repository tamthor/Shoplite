<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetCategory extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'pets_category';

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    /**
     * Các phương thức tĩnh
     */

    // Phương thức tạo danh mục mới
    public static function createCategory($data)
    {
        return PetCategory::create($data);
    }

    // Phương thức xóa danh mục
    public static function deleteCategory($category_id)
    {
        $category = PetCategory::find($category_id);
        if ($category) {
            $category->delete();
            return true;
        }
        return false;
    }

    // Quan hệ: Một danh mục có nhiều thú cưng
    public function pets()
    {
        return $this->hasMany(Pet::class, 'category_id');
    }
}
