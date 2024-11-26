<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong cơ sở dữ liệu
     *
     * @var string
     */
    protected $table = 'pets';

    /**
     * Các thuộc tính có thể gán giá trị hàng loạt
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'category_id',
        'description',
        'price',
        'image',
    ];

    /**
     * Quan hệ: Một thú cưng thuộc về một danh mục
     */
    public function category()
    {
        return $this->belongsTo(PetCategory::class, 'category_id');
    }

    /**
     * Các phương thức tĩnh
     */

    // Phương thức tạo thú cưng mới
    public static function createPet($data)
    {
        return Pet::create($data);
    }

    // Phương thức xóa thú cưng
    public static function deletePet($pet_id)
    {
        $pet = Pet::find($pet_id);
        if ($pet) {
            $pet->delete();
            return true;
        }
        return false;
    }

    // Phương thức cập nhật thông tin thú cưng
    public static function updatePet($pet_id, $data)
    {
        $pet = Pet::find($pet_id);
        if ($pet) {
            $pet->update($data);
            return $pet;
        }
        return null;
    }
}
