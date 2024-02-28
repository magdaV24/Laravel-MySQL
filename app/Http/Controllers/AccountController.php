<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Api\Admin\AdminApi;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware("auth");
    }
    public function index($user)
    {
        try {
            $user = User::findOrFail($user);
            $public_id = $user->avatar;
            $addresses = $user->addresses;
            $orders = $user->orders;
            foreach ($orders as $order) {
                $order->products = (new OrdersController())->getProducts($order->id);
                $address = Address::where("id", $order->address_id)->first();
                if($address){
                    $order->address = $address;
                }
            }
            $favoritesCount = (new FavoritesController())->count();
            $cartCount = (new CartController())->count();
            return view('account', [
                'user' => $user,
                'public_id' => $public_id,
                'addresses' => $addresses,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount,
                'orders' => $orders
            ]);
        } catch (\Exception $ex) {
            return back()->with('error', 'Error updating your account: ' . $ex->getMessage());
        }
    }

    public function edit($user)
    {
        try {
            $user = User::findOrFail($user);
            $favoritesCount = (new FavoritesController())->count();
            $cartCount = (new CartController())->count();
            return view('account.edit', [
                'user' => $user,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $ex) {
            return back()->with('error', 'Error while trying to fetch this account: ' . $ex->getMessage());
        }
    }

    public function update(Request $request, $userId)
    {
        try {
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
        } catch (\Exception $ex) {
            return back()->with('error', 'Error updating your account: ' . $ex->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            if (!$user) {
                return back()->with('error', 'User not found.');
            }
            if (Hash::check($request['password'], $user->password)) {
                $publicId = $user->avatar;
                (new AdminApi())->deleteAssets(
                    $publicId,
                    ["resource_type" => "image", "type" => "upload"]
                );
                $user->delete();
                return back()->with('success', 'Account deleted successfully.');
            } else {
                return back()->with('error', 'Incorrect password!');
            }
        } catch (\Exception $ex) {
            return back()->with('error', 'Error deleting account: ' . $ex->getMessage());
        }
    }
}
