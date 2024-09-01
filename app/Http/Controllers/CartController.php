<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Product;
use DB;

class CartController extends Controller
{
    public function index()
    {
        // Display the cart items
        
        $cart = DB::table('products')->get();
        //dd($cart);
        return view('cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $product = Product::find($request->id);
        
        // Add item to the cart session
        $cart = session()->get('cart', []);
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "price" => $product->price,
                "quantity" => 1
            ];
        }
        
        session()->put('cart', $cart);
        
        return response()->json(['success' => 'Product added to cart!']);
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->id])) {
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['success' => 'Cart updated!']);
    }

    public function delete(Request $request)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
        }

        return response()->json(['success' => 'Item removed from cart!']);
    }
}
