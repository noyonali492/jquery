<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Contracts\Validation\Rule;
class ProductController extends Controller
{
    public function products(){
        return view('products');
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
    }
}
