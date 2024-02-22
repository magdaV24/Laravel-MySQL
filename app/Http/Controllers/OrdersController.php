<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderedProducts;
use Illuminate\Support\Str;


class OrdersController extends Controller
{
    public function store(){
        $user = Auth::user();
        $uuid = Str::uuid();
        $productIds = Cart::where("user_id", $user->id)->pluck("product_id")->toArray();
        foreach($productIds as $productId){
            OrderedProducts::create([
                "product_id"=> $productId,
                "user_id"=> $user->id,
                "uuid"=> $uuid
            ]);
        }
        $price = (new CartController)->getTotalPrice($productIds);
       $order = Orders::create([
            "user_id"=> $user->id,
            "uuid"=> $uuid,
            'price'=> $price,
            "address_id"=>1,
            "status"=>'pending',
            'paying_method'=>"credit card"
        ]);
        if(!$order->exists()){
            return redirect()->back()->with("error","Something went wrong.");
        };
        foreach($productIds as $productId){
            (new CartController)->remove($productId);
        }
        return redirect()->route("home")->with("success","");
    }
}
