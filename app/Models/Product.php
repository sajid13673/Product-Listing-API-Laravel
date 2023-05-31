<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku',
        'name',
        'price',
        'status',
        'image_id'
    ];
    
    public function images()
    {
        return $this->belongsTo(Image::class,'image_id','id');
    }
    
}
