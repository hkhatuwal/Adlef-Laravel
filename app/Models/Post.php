<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'subtitle',
        'label',
        'content',
        'type',
        'image',
        'links',
        'category_id',
    ];


    protected $casts=[
        "links"=>"array"
    ];

    public function category(){
       return $this->belongsTo(Category::class);
    }
}
