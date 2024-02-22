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
        $request->validate([
            'name' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'string', 'email', 'max:100', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'avatar' => ['nullable', 'file', 'image', 'max:2048'],
        ]);
        if (Hash::check($request['old_password'], $user->password)) {
            if ($request['password'] !== '' && $request['password'] === $request['confirm_new_password']) {
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
            // if ($request['name']) {
            //     $user->name = $request['name'];
            // }
            // if ($request['email']) {
            //     $user->email = $request['email'];
            // }
            $user->update($user);
            return back()->with("success", "Profile updated successfully!");
        } else {
            return back()->with("error", "Wrong password.");
        }
    }
}
