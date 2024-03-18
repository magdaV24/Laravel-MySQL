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
        try {
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
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store($productId)
    {
        try {
            $userId = Auth::user()->id;
            if (!$userId) {
                return redirect()->to("/login");
            }
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => 1,
            ]);
            return back()->with('success', 'Product added to the cart successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function increment($productId)
    {
        try {
            return $this->incrementQuantity($productId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function incrementQuantity($productId)
    {
        try {
            $user = Auth::user();
            $product = Cart::where('product_id', $productId)->where('user_id', $user->id)->first();
            if (!$product) {
                return back()->with('error', 'Product not found in cart.');
            }
            $product->quantity += 1;
            $product->save();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function decrement($productId)
    {
        try {
            return $this->decrementQuantity($productId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function decrementQuantity($productId)
    {
        try {
            $user = Auth::user();
            $product = Cart::where('product_id', $productId)->where('user_id', $user->id)->first();
            if (!$product) {
                return back()->with('error', 'Product not found in cart.');
            }
            if ($product->quantity == 1) {
                return back();
            }
            $product->quantity -= 1;
            $product->save();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function remove($product_id)
    {
        try {
            $user_id = Auth::user()->id;

            Cart::where('user_id', $user_id)
                ->where('product_id', $product_id)
                ->delete();
            return back()->with('success', 'Product eliminated from the wishlist');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function count()
    {
        try {
            return $this->cartCount();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function cartCount()
    {
        try {
            $user = Auth::user();
            $cartCount = Cart::where("user_id", $user->id)->count();
            return $cartCount;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function getQuantity($productId)
    {
        try {
            return $this->productQuantity($productId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function productQuantity($productId)
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
            return back()->with("error", $e->getMessage());
        }
    }

    public function getTotalPrice(array $productIds)
    {
        try {
            return $this->totalPrice($productIds);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private function totalPrice(array $productIds)
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
            return $this->inCart($productId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function inCart($productId)
    {
        try {
            $userId = auth()->user()->id;
            if (!$userId) {
                return redirect()->to('/login');
            }
            $product = Cart::where('product_id', $productId)->where('user_id', $userId)
                ->exists();
            return $product;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
