<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        try {
            $uuid = Str::uuid();
            $request->validate([
                'name' => ['required', 'string', 'max:50'],
                'price' => ['required', 'numeric', 'min:0'],
                'description' => ['required', 'string', 'max:400'],
                'photos.*' => ['nullable', 'image', 'max:2048'],
                'uuid' => ['nullable', 'string'],
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $uploadedFile = $photo->getRealPath();
                    $uploadResult = Cloudinary::upload($uploadedFile);

                    $publicId = $uploadResult->getPublicId();

                    Photo::create([
                        'url' => $publicId,
                        'uuid' => $uuid,
                    ]);
                }
            } else {
                // If the admin does not upload any photos, this one will be assigned;
                $defaultUrl = 'pexels-pixabay-268349_onacrr';
                Photo::create([
                    'url' => $defaultUrl,
                    'uuid' => $uuid,
                ]);
            }

            Product::create([
                'name' => $request['name'],
                'price' => $request['price'],
                'description' => $request['description'],
                'uuid' => $uuid,
            ]);
            return back()->with('success', 'Product added successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function index($product)
    {
        try {
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
                $review->comments = (new ReviewsController())->fetchReviewComments($review->id);
                $review->verified = (new ReviewsController())->checkIsVerified($review->id);
                $review->avatar = User::where('id', $review->user_id)->value('avatar');
                $review->name = User::where('id', $review->user_id)->value('name');
                $grade += $review->grade;
            }
            $count === 0 ? $product->grade = 0 : $product->grade = $grade / $count;
            $product->favorite = (new FavoritesController)->isInWishlist($product->id); /*checks if the product is in the wishlist;
based on whether or not the product is in the wishlist, the user will get option to remove/add the product from/to the wishlist
*/
            $product->cart = (new CartController())->isInCart($product->id); /*checks if the product is in the cart;*/
            return view('product.index', [
                'user' => $user,
                'product' => $product,
                'photos' => $photos,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount,
                'reviews' => $reviews
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function edit($product)
    {
        try {
            $user = auth()->user();
            $product = Product::findOrFail($product);
            $photos = Photo::where('uuid', $product->uuid)->pluck('url');
            $favoritesCount = (new FavoritesController())->count();
            $cartCount = (new CartController())->count();
            return view('product.update', [
                'product' => $product,
                'user' => $user,
                'photos' => $photos,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, Product $product, $uuid)
    {

        try {
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
            return back()->with('success', 'Product edited successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function delete($product)
    {
        try {
            $product = Product::findOrFail($product);
            if (!$product) {
                return back()->with('error', 'Product not found.');
            }

            $photos = Photo::where('product_id', $product->id)->get();
            foreach ($photos as $photo) {
                (new PhotoController)->delete($photo->url);
            }
            $product->delete();
            $product->reviews()->delete();
            $product->comments()->delete();

            return back()->with('success', 'Product deleted successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
