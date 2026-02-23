<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'published_by_id',
        'documentation_category_id',
        'documentation_tag_id',
        'title_en',
        'post_en',
        'photo_text_en',
        'photo',
        'publish_date',
        'publish_time',
        'layout_format',
        'slug_en',
        'permalink_slug',
        'views',
        'is_published',
    ];


    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d-m-Y H:i:s');
    }

    public function userData()
    {
        return $this->belongsTo(User::class,'user_id');
    }
   
    public function publishedByData()
    {
        return $this->belongsTo(User::class,'published_by_id');
    }

    public function documentationCategoryData()
    {
        return $this->belongsTo(DocumentationCategory::class,'documentation_category_id');
    }
}
