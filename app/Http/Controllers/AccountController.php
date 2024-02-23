<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Api\Admin\AdminApi;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    public function index($user)
    {
        $user = User::findOrFail($user);
        $public_id = $user->avatar;
        $addresses = $user->addresses;
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();
        return view('account', [
            'user' => $user,
            'public_id' => $public_id,
            'addresses' => $addresses,
            'favoritesCount' => $favoritesCount,
            'cartCount' => $cartCount
        ]);
    }

    public function edit($user)
    {
        $user = User::findOrFail($user);
        $favoritesCount = (new FavoritesController())->count();
        $cartCount = (new CartController())->count();
        return view('account.edit', [
            'user' => $user,
            'favoritesCount' => $favoritesCount,
            'cartCount' => $cartCount
        ]);
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }
        if (Hash::check($request['old_password'], $user->password)) {
            if ($request['password'] !== null && $request['password'] === $request['confirm_new_password']) {
                $user->password = Hash::make($request->input('password'));
            }
            if (isset($request['avatar'])) {
                $uploadedFile = $request['avatar']->getRealPath();
                $oldPublicId = $user->avatar;
                $uploadResult = Cloudinary::upload($uploadedFile);

                (new AdminApi())->deleteAssets(
                    $oldPublicId,
                    ["resource_type" => "image", "type" => "upload"]
                );

                // Extract the public ID from the upload result
                $publicId = $uploadResult->getPublicId();
                $user->avatar = $publicId;
            }
            if ($request['name'] !== null) {
                $user->name = $request['name'];
            }
            if ($request['email'] !== null) {
                $user->email = $request['email'];
            }
            $user->save();
            return back()->with("success", "Profile updated successfully!");
        } else {
            return back()->with("error", "Wrong password.");
        }
    }

    public function delete()
    {
        try {
            $user = User::findOrFail(auth()->user()->id);
            $user->delete();
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('error', 'User not found.');
        }
    }
}
