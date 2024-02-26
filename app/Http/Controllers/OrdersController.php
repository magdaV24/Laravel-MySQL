<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Photo;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderedProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class OrdersController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();
        $uuid = Str::uuid();
        $productIds = Cart::where("user_id", $user->id)->pluck("product_id")->toArray();
        foreach ($productIds as $productId) {
            $product = Cart::where("product_id", $productId)->first();
            OrderedProducts::create([
                "product_id" => $productId,
                "user_id" => $user->id,
                "uuid" => $uuid,
                'quantity' => $product->quantity,
            ]);
        }
        $price = (new CartController)->getTotalPrice($productIds);
        $order = Orders::create([
            "user_id" => $user->id,
            "uuid" => $uuid,
            'price' => $price,
            "address_id" => $request['address'],
            "status" => 'Pending',
            'paying_method' => $request['paying-method']
        ]);
        if (!$order->exists()) {
            return redirect()->back()->with("error", "Something went wrong.");
        }
        ;
        foreach ($productIds as $productId) {
            (new CartController)->remove($productId);
        }
        return redirect()->route("home")->with("success", "");
    }
    public function show()
    {
        $user = auth()->user();
        $orders = Orders::all();
        $favoritesCount = (new FavoritesController)->count();
        $cartCount = (new CartController)->count();
        foreach ($orders as $order) {
            $order->products = (new OrdersController)->getProducts($order->id);
            $order->address = Address::where("id", $order->address_id)->first();
        }
        // dd($orders);
        return view("orders.show", [
            "orders" => $orders,
            "favoritesCount" => $favoritesCount,
            "cartCount" => $cartCount,
            'user' => $user
        ]);
    }

    public function update(Orders $order)
    {
        $order = Orders::find($order->id);
        if ($order->status === 'Pending') {
            $order->status = 'Processing';
        } else if ($order->status === 'Processing') {
            $order->status = 'In transit';
        } else {
            $order->status = "Delivered";
        }
        $order->save();
        return redirect()->back()->with("success","Order updated successfully!");
    }

    public function getProducts($orderId)
    {
        $order = Orders::find($orderId);
        $productIds = OrderedProducts::where("uuid", $order->uuid)->pluck('product_id')->toArray();
        $products = [];
        foreach ($productIds as $productId) {
            $product = Product::find($productId);
            if ($product) {
                $product->photos = Photo::where('uuid', $product->uuid)->pluck('url')->toArray();
                $product->quantity = OrderedProducts::where("product_id", $product->id)->value("quantity");
                $products[] = $product;
            }
        }
        return $products;
    }
}
