<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class DocumentationCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_category_id',
        'documentation_type',
        'category_name_en',
        'slug_en',
        'details_en',
        'serial_no',
        'status',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function parentCategory()
    {
        return $this->belongsTo(DocumentationCategory::class, 'parent_category_id');
    }

    public static function getCategoryName()
    {
        $data = DocumentationCategory::orderByRaw('ISNULL(serial_no), serial_no ASC, id ASC')->where('serial_no', '!=', null)->where('status', true)->get();
        return $data;
    }
}
