<?php

namespace App\Http\Controllers;

use App\Models\User;
use Egulias\EmailValidator\Parser\Comment;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Photo;
use App\Models\Reviews;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function store(Request $request)
    {
        $uuid = Str::uuid();
        $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:400'],
            'photos.*' => ['nullable', 'image', 'max:2048'],
            'uuid' => ['nullable', 'string', 'max:100']
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $uploadedFile = $photo->getRealPath();
                $uploadResult = Cloudinary::upload($uploadedFile);

                $publicId = $uploadResult->getPublicId();

                Photo::create([
                    'url' => $publicId,
                    'uuid' => $uuid
                ]);
            }
        } else {
            // If the user does not upload any avatars, they will get a default one;
            $defaultUrl = 'pexels-pixabay-268349_onacrr';
            Photo::create([
                'url' => $defaultUrl,
                'uuid' => $uuid
            ]);
        }

        Product::create([
            'name' => $request['name'],
            'price' => $request['price'],
            'description' => $request['description'],
            'uuid' => $uuid,
        ]);
        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function index($product)
    {
        $user = auth()->user();
        $product = Product::findOrFail($product);
        $photos = Photo::where('uuid', $product->uuid)->pluck('url');
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();
        $grade = 0;

        // Retrieve the product's reviews;
        $reviews = Reviews::where('product_id', $product->id)->get();
        $count = Reviews::where('product_id', $product->id)->count();
        foreach ($reviews as $review) {
            $review->comments = (new ReviewsController())->getComments($review->id);
            $review->verified = (new ReviewsController())->isVerified($review->id);
            $review->avatar = User::where('id', $review->user_id)->value('avatar');
            $review->name = User::where('id', $review->user_id)->value('name');
            $grade += $review->grade;
        }
        $count === 0 ? $product->grade = 0 : $product->grade = $grade / $count;
        $product->favorite = (new FavoritesController)->isInWishlist($product->id);
        $product->cart = (new CartController())->isInCart($product->id);
        return view('product.index', [
            'user' => $user,
            'product' => $product,
            'photos' => $photos,
            'favoritesCount' => $favoritesCount,
            'cartCount' => $cartCount,
            'reviews' => $reviews
        ]);
    }

    public function edit($product)
    {
        $user = auth()->user();
        $product = Product::findOrFail($product);
        $photos = Photo::where('uuid', $product->uuid)->pluck('url');
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();
        return view('product.edit', [
            'product' => $product,
            'user' => $user,
            'photos' => $photos,
            'favoritesCount' => $favoritesCount,
            'cartCount' => $cartCount
        ]);
    }

    public function update(Request $request, Product $product, $uuid)
    {

        $validData = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string', 'max:400'],
            'photos.*' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $uploadedFile = $photo->getRealPath();
                $uploadResult = Cloudinary::upload($uploadedFile);

                $publicId = $uploadResult->getPublicId();

                Photo::create([
                    'url' => $publicId,
                    'uuid' => $uuid
                ]);
            }
        }
        $product->update($validData);
        return redirect()->back()->with('success', 'Product edited successfully.');
    }

    public function delete($product)
    {
        $product = Product::findOrFail($product);
        if(!$product)
        {
            return redirect()->back()->with('error','Product not found.');
        }

        $photos = Photo::where('product_id', $product->id)->get();
        foreach($photos as $photo)
        {
            (new PhotoController)->delete($photo->url);
        }
        $product->delete();
        $product->reviews()->delete();
        $product->comments()->delete();

        return redirect()->back()->with('success','Product deleted successfully!');
    }
}
