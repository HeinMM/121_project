<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleImage;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index(){
        $result = Sale::with('saleImages')->latest()->get();

        return response()->json(
            $result
        );
    }
    public function indexByLimit(){
        $result = Sale::inRandomOrder()->take(6)->get();
        return response()->json(
            $result
        );
    }

    public function createSale(Request $request){

        //return $request->image->store('image');
       $request->validate([
            "title"=>"required",
            "normal_price"=>"required",
            "sale_price"=>"required",
            "dec"=>"required",
            "qty"=>"required",
            "waiting_time"=>"required",
        ]);

        $product = new Sale();
        $product->title = $request->title;
        $product->normal_price = $request->normal_price;
        $product->sale_price = $request->sale_price;
        $product->dec = $request->dec;
        $product->qty = $request->qty;
        $product->waiting_time = $request->waiting_time;
        $product->save();


        if(!request()->hasFile('images')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $images = request()->images;
        foreach ($images as $imagefile) {
            $path = $imagefile->store('image');
            $product->saleImages()->create([
            'sale_id' => $product->id,
            'file_path' => asset("storage/$path")
        ]);


        }

        return response()->json([
            'status'=>true,
            'message'=>'Sale Post Created Successfully',
            'token'=>' ',
            'isAdmin'=>true
        ]);
     }

     public function show($id){
        $showById = Sale::find($id);
        return response()->json([
            'id'=>$showById->id,
            'title'=>$showById->title,
            'normal_price'=>$showById->normal_price,
            'sale_price'=>$showById->sale_price,
            'dec'=>$showById->dec,
            'qty'=>$showById->qty,
            'image'=>$showById->image,
            'waiting_time'=>$showById->waiting_time,
            ]
        );
     }

    public function delete($id){
        Sale::destroy($id);

        return response()->json([
           'status'=>true,
           'message'=>'Post Created Successfully',
           'token'=>' ',
           'isAdmin'=>true
       ]);
    }

    public function image(){

        // if(!request()->hasFile('image')) {
        //     return response()->json(['upload_file_not_found'], 400);
        // }

        // $file = request()->file('image')->store('image');
        //     $path = public_path(). '/storage/'  . $file;


        //     return response()->json(compact('path'));

       $name = request()->file('name')->store('name');

        return response()->file(public_path("/storage/$name"));
    }
}
