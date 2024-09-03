<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Product;

class ProductController extends Controller
{
    public function products(){
        $products = Product::latest()->paginate(3);
        return view('products',compact('products'));
    }

    //add product
    public function addProduct(Request $request){
        
        $request->validate(
            [
                'name'=>'required|unique:products',
                'price'=>'required',
            ],
            [
                'name.required'=>'Name is Required',
                'name.unique'=>'Product Already Exists',
                'price.required'=>'Price is Required',
            ]

        );
        $product = new Product();
        $product->name = $request->name;
        $product->price = $request->price;
        
        $product->save();
        return response()->json([
            'status'=>'success',
        ]);
    }
}
