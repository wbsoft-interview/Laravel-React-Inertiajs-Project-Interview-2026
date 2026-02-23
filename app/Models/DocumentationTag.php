<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \DateTimeInterface;

class DocumentationTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag_name_en',
        'slug_en',
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
}
