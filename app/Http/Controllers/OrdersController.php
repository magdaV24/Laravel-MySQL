<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Orders;
use App\Models\Photo;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderedProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class OrdersController extends Controller
{
    public function store(Request $request)
    {
        try {
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
                return back()->with("error", "Something went wrong.");
            }
            ;
            foreach ($productIds as $productId) {
                (new CartController)->remove($productId);
            }
            return back()->with("success", "Order placed successfully!");
        } catch (\Exception $e) {
            return back()->with("error", $e->getMessage());
        }
    }
    public function show()
    {
        try {
            $user = auth()->user();
            $orders = Orders::all();
            $favoritesCount = (new FavoritesController)->count();
            $cartCount = (new CartController)->count();
            foreach ($orders as $order) {
                $order->products = (new OrdersController)->getProducts($order->id);
                $order->phoneNumber = User::where('id', $order->user_id)->pluck('phone-number')->first();
                $order->name = User::where('id', $order->user_id)->pluck('name')->first();
                $order->email = User::where('id', $order->user_id)->pluck('email')->first();
                $address = Address::where("id", $order->address_id)->first();
                if($address)
                {
                    $order->address = $address;
                }
            }
            return view("orders.show", [
                "orders" => $orders,
                "favoritesCount" => $favoritesCount,
                "cartCount" => $cartCount,
                'user' => $user
            ]);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function update(Orders $order)
    {
        try {
            $order = Orders::find($order->id);
            if (!$order) {
                return back()->with('error', 'Order not found.');
            }
            if ($order->status === 'Pending') {
                $order->status = 'Processing';
            } else if ($order->status === 'Processing') {
                $order->status = 'In transit';
            } else {
                $order->status = "Delivered";
            }
            $order->save();
            return back()->with("success", "Order updated successfully!");
        } catch (\Exception $ex) {
            return back()->with("error", $ex->getMessage());
        }
    }

    public function getProducts($orderId)
    {
        try {
            return $this->getOrderProducts($orderId);
        } catch (\Exception $ex) {
            return back()->with("error", $ex->getMessage());
        }
    }

    private function getOrderProducts($orderId)
    {
        try {
            $order = Orders::find($orderId);
            if (!$order) {
                return back()->with("error", "Order not found.");
            }
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
        } catch (\Exception $ex) {
            return back()->with("error", $ex->getMessage());
        }
    }
}
