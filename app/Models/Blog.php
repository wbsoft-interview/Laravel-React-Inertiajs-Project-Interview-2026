<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id', 'title' , 'photo', 'post' , 'solid_post','status'
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function blogCategoryData()
    {
        return $this->belongsTo(BlogCategory::class,'blog_category_id');
    }
}
