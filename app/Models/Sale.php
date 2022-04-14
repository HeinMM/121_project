<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleImage;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function getImageAttribute($value)
    {
        return asset("storage/$value");
    }
    public function saleImages(){
        return $this->hasMany(SaleImage::class,'sale_id');
    }
}
