<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index(){
        $result = Post::inRandomOrder()->filter(request(['search','category']))->get();
         return response()->json(
            $result
        );

    }

    public function createPost(Request $request){
        $request->validate([
            "category_id"=>"required",
            "title"=>"required",
            "price"=>"required",
            "dec"=>"required",
            "qty"=>"required",
        ]);

        //     $categoryid = (int)($request->input('category_id'));
        // Post::create([
        //     'category_id'=>$categoryid,
        //     'title'=>$request->input('title'),
        //     'price'=>$request->input('price'),
        //     'dec'=>$request->input('dec'),
        //     'qty'=>$request->input('qty'),
        //     'item_number'=>''
        // ]);

        //  /** @var \App\Models\User */
        //  $currentUser = Auth::user();
        //  $token = $currentUser->createToken('Auth')->accessToken;


        $product = new Post();
        $product->title = $request->title;
        $product->price = $request->price;
        $product->dec = $request->dec;
        $product->qty = $request->qty;
        $product->item_number = "";
        $product->category_id = $request->category_id;
        $product->save();

        if(!request()->hasFile('images')) {
            return response()->json(['upload_file_not_found'], 400);
        }else{
            $images = request()->images;

        foreach ($images as $imagefile) {
            $path = $imagefile->store('image');

            $product->postImages()->create([
            'post_id' => $product->id,
            'file_path' => asset("storage/$path")
        ]);
        }

        return response()->json([
            'status'=>true,
            'message'=>' Post Created Successfully',
            'token'=>' ',
            'isAdmin'=>true
        ]);
        }


     }
     public function test(){
        $item_number=new Post();
        $item_number->ite;
        return $item_number;
     }

     public function update(Request $request,$id){
         $request->validate([
            "title"=>"required",
            "price"=>"required",
            "category_id"=>"required",
            "dec"=>"required",
            "qty"=>"required",
         ]);
        Post::find($id)->update([
            'category_id'=>$request->input('category_id'),
            'title'=>$request->input('title'),
            'price'=>$request->input('price'),
            'dec'=>$request->input('dec'),
            'qty'=>$request->input('qty'),
        ]);

        return response()->json([
            'status'=>true,
            'message'=>'Post Updated Successfully',
            'isAdmin'=>true,
            'token'=>' '
        ]);
     }
     public function show($id){
        $showById = Post::find($id);
        return response()->json([
            'id'=>$showById->id,
            'title'=>$showById->title,
            'price'=>$showById->price,
            'dec'=>$showById->dec,
            'qty'=>$showById->qty,
            'category_id'=>$showById->category_id,

            'item_number'=>$showById->item_number
            ]
        );
     }

     public function delete($id){
         Post::destroy($id);

         return response()->json([
            'status'=>true,
            'message'=>'Post Created Successfully',
            'token'=>' ',
            'isAdmin'=>true
        ]);
     }

    //  protected function getPosts(){
    //     // $query = Post::latest();

    //     // $query->when(request('search'),function($query,$search){
    //     //     $query->where('title','LIKE','%'.request($search).'%')
    //     //     ->orWhere('dec','LIKE','%'.request($search).'%');
    //     // });
    //     // return $query->get();
    //     Post::latest()->filter()->get();
    //  }

        public function recommentShow(){
            $result = Post::inRandomOrder()->filter(request(['category']))->take(3)->get();
            return response()->json(
                $result
            );
        }

}

