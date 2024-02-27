<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();

        $productIds = Cart::where("user_id", $user->id)->pluck("product_id")->toArray();
        $products = [];
        $cartTotal = (new CartController())->getTotalPrice($productIds);
        foreach ($productIds as $id) {
            $product = Product::find($id);
            $quantity = (new CartController())->getQuantity($id);
            $photo = Photo::where('uuid', $product->uuid)->first();
            $product->photo_url = $photo->url;
            $product->quantity = $quantity;
            $product->totalPrice = $product->quantity * $product->price;
            $products[] = $product;
        }
        return view("cart.index", [
            "user" => $user,
            "favoritesCount" => $favoritesCount,
            'cartCount' => $cartCount,
            'products' => $products,
            'cartTotal' => $cartTotal
        ]);
    }

    public function store($productId)
    {
        $userId = Auth::user()->id;
        if (!$userId) {
            return redirect()->to("/login");
        }
        Cart::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => 1,
        ]);
        return redirect()->back()->with('success', 'Product added to the cart successfully.');
    }

    public function increment($productId)
    {
        $user = Auth::user();
        $product = Cart::where('product_id', $productId)->where('user_id', $user->id)->first();
        if (!$product) {
            return back()->withErrors(['Product not found in cart.']);
        }
        $product->quantity += 1;
        $product->save();
        return redirect()->back()->with('success', 'Increased the quantity successfully!');

    }

    public function decrement($productId)
    {
        $user = Auth::user();
        $product = Cart::where('product_id', $productId)->where('user_id', $user->id)->first();
        if (!$product) {
            return back()->withErrors(['Product not found in cart.']);
        }
        if ($product->quantity == 1) {
            return back();
        }
        $product->quantity -= 1;
        $product->save();
        return redirect()->back()->with('success', 'Decreased the quantity successfully!');
    }

    public function remove($product_id)
    {
        $user_id = Auth::user()->id;

        Cart::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->delete();
        return redirect()->back()->with('success', 'Product eliminated from the wishlist');
    }

    public function count(): int
    {
        $user = Auth::user();
        $cartCount = Cart::where("user_id", $user->id)->count();
        return $cartCount;
    }

    public function getQuantity($productId)
    {
        try {
            $user = Auth::user();
            $product = Product::findOrFail($productId);
            $quantity = Cart::where("user_id", $user->id)
                ->where("product_id", $product->id)
                ->value("quantity");
            return $quantity ?? 0;
        } catch (\Exception $e) {
            \Log::error('Error in getQuantity function: ' . $e->getMessage());
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function getTotalPrice(array $productIds)
    {
        try {
            $cartTotal = 0;
            foreach ($productIds as $productId) {
                $product = Product::find($productId);
                if (!$product) {
                    continue;
                }
                $productQuantity = (new CartController)->getQuantity($product->id);
                $cartTotal += $product->price * $productQuantity;
            }
            return $cartTotal;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function isInCart($productId)
    {
        try {
            $userId = auth()->user()->id;
            if (!$userId) {
                return redirect()->to('/login');
            }
            $favorite = Cart::where('product_id', $productId)->where('user_id', $userId)
                ->exists();
            return $favorite;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
