<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostImage;

class Post extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    // protected $fillable = [
    //     'title',
    //     'price',
    //     'dec',
    //     'qty',
    // ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function scopeFilter($query,$filter){//eg . Post::latest()->filter()
        $query->when( $filter['search'] ?? false , function($query,$search) {
            $query->where(function ($query) use ($search) {
                $query->where('title','LIKE','%'.$search.'%')
                  ->orWhere('dec','LIKE','%'.$search.'%');
            });
         });
         $query->when( $filter['category'] ?? false , function($query,$slug) {
                $query->whereHas('category',function ($query) use ($slug){
                    $query->where('slug',$slug);
                });
    });
}
public function getCategoryIdAttribute($value)
    {
        return (string)$value;
    }
public function setItemNumberAttribute()
    {
        $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
       $this->attributes['item_number'] =  substr(str_shuffle($str_result), 0, 5);
    }

    public function postImages(){
        return $this->hasMany(PostImage::class,'post_id');
    }


}
