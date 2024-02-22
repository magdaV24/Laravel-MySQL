<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Favorites;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Photo;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $products = Product::all();
        foreach ($products as $product) {
            $photo = Photo::where('uuid', $product->uuid)->first();
            if ($photo) {
                $product->photo_url = $photo->url;
            } else {
                $product->photo_url = 'pexels-pixabay-268349_onacrr';
            }

            $favorite = (new FavoritesController)->isInWishlist($product->id);
            $product->favorite = $favorite;

            $cart = (new CartController)->isInCart($product->id);
            $product->cart = $cart;
        }
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();
        return view('home', [
            'user'=> $user,
            'products'=> $products,
            'favoritesCount'=> $favoritesCount,
            'cartCount'=>$cartCount
        ]);
    }
}
