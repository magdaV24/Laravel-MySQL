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
        $user = Auth::user();
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
    }
    public function store($productId)
    {
        $userId = Auth::user()->id;
        if (!$userId) {
            return redirect()->to("/login");
        }
        Favorites::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
        return redirect()->back()->with('success', 'Product added to the wishlist successfully.');

    }

    public function remove($product_id)
    {
        $user_id = Auth::user()->id;

        Favorites::where('user_id', $user_id)
            ->where('product_id', $product_id)
            ->delete();
        return redirect()->back()->with('success', 'Product eliminated from the wishlist');
    }

    public function count()
    {
        $userId = Auth::user()->id;
        $favoritesCount = Favorites::where('user_id', $userId)->count();
        return $favoritesCount;
    }

    public function isInWishlist($productId){
        $userId = auth()->user()->id;
        if(!$userId){
            return redirect()->to('/login');
        }
        $favorite = Favorites::where('product_id', $productId)->where('user_id', $userId)
        ->exists();
        return $favorite;
    }
}
