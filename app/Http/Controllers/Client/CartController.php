<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;

class CartController extends Controller
{
    /**
     * Ipinapakita nito ang shopping cart ng user.
     * Dito makikita ng user ang mga produkto na kanilang idinagdag sa cart.
     */
    public function carts(){
        $data = [
            'shop' => Shop::first(), // Kukunin ang impormasyon ng shop
            'title' => 'Carts' // Ang titulo ng page
        ];

        return view('client.carts', $data); // Ibabalik ang 'carts' view
    }

    /**
     * Idinadagdag nito ang isang produkto sa shopping cart ng user.
     * Sinusuri din nito kung sapat pa ba ang stock ng produkto.
     */
    public function addToCart(Request $request)
    {
        $cart = session()->get('cart'); // Get current cart from session
        $id = $request->product_id;
        $product = Product::where('id', $id)->first(); // Fetch product from DB

        // If product already in cart, update quantity
        if (isset($cart[$id])) {
            $quantityUpdate = $cart[$id]["quantity"] + $request->quantity;

            if ($quantityUpdate > $product->stock) {
                return response()->json([
                    'status' => 'failed',
                    'cartCount' => count((array) session('cart')),
                    'code' => 202
                ], 202);
            }

            $cart[$id]["quantity"] = $quantityUpdate;
            session()->put('cart', $cart);
            return response()->json([
                'status' => 'success',
                'cartCount' => count((array) session('cart')),
                'code' => 201
            ], 201);
        }

        // If new product, add to cart (✅ now includes image_path)
        $cart[$id] = [
            "product_id" => $id,
            "title" => $product->title,
            "quantity" => $request->quantity,
            "price" => $product->price,
            "product_stock" => $product->stock,
            "image_path" => $product->image_path // ✅ Add this line
        ];

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'cartCount' => count((array) session('cart')),
            'code' => 200
        ], 200);
    }

    /**
     * Ina-update nito ang quantity ng isang produkto sa shopping cart.
     * Aayusin din nito ang kabuuang presyo ng cart.
     */
    public function updateCart(Request $request)
    {
        if($request->product_id && $request->quantity){ // Kung may product ID at quantity sa request
            $cart = session()->get('cart'); // Kukunin ang cart
            $cart[$request->product_id]["quantity"] = $request->quantity; // I-update ang quantity
            session()->put('cart', $cart); // I-save sa session

            $total = 0;
            // Kalkulahin ang bagong kabuuang presyo ng cart
            foreach((array) session('cart') as $id => $details){
                $total += $details['price'] * $details['quantity'];
            }

            return response()->json(['message' => 'Success', 'total' => $total]); // Ibabalik ang success message at bagong total
        }
    }

    /**
     * Tinatanggal nito ang isang produkto mula sa shopping cart.
     * Aayusin din nito ang kabuuang presyo at bilang ng items sa cart.
     */
    public function deleteCart(Request $request){
        if($request->id) { // Kung may ID ng produkto sa request
            $cart = session()->get('cart'); // Kukunin ang cart
            if(isset($cart[$request->id])) { // Kung may produkto sa cart na may ganitong ID
                unset($cart[$request->id]); // Tanggalin ang produkto
                session()->put('cart', $cart); // I-save ang updated cart sa session
            }

            $total = 0;
            // Kalkulahin ang bagong kabuuang presyo ng cart
            foreach((array) session('cart') as $id => $details){
                $total += $details['price'] * $details['quantity'];
            }

            return response()->json(['message' => 'Success', 'total' => $total, 'cartCount' => count((array) session('cart'))]); // Ibabalik ang success, total, at bagong count ng cart
        }
    }
}