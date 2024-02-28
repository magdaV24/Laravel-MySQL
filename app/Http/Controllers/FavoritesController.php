<?php

namespace App\Http\Controllers;

use App\Models\Favorites;
use App\Models\Product;
use App\Models\Photo;
use Illuminate\Support\Facades\Auth;


class FavoritesController extends Controller
{

    public function index()
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return redirect()->to("/login");
            }
            $productId = Favorites::where("user_id", $user->id)->pluck("product_id")->toArray();
            $products = [];
            foreach ($productId as $id) {
                $product = Product::find($id);
                $photo = Photo::where('uuid', $product->uuid)->first();
                $product->photo_url = $photo->url;
                $products[] = $product;
            }
            $favoritesCount = (new FavoritesController())->count();
            $cartCount = (new CartController())->count();
            return view("favorites.index", [
                "user" => $user,
                "products" => $products,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount
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
            Favorites::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return back()->with('success', 'Product added to the wishlist successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

    }

    public function remove($product_id)
    {
        try {
            $user_id = Auth::user()->id;

            Favorites::where('user_id', $user_id)
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
            return $this->favoritesCount();
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function favoritesCount()
    {
        try {
            $userId = Auth::user()->id;
            $favoritesCount = Favorites::where('user_id', $userId)->count();
            return $favoritesCount;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function isInWishlist($productId)
    {
        try {
            return $this->inWishlist($productId);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    private function inWishlist($productId)
    {
        try {
            $userId = auth()->user()->id;
            if (!$userId) {
                return redirect()->to('/login');
            }
            $favorite = Favorites::where('product_id', $productId)->where('user_id', $userId)
                ->exists();
            return $favorite;
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
