<?php

namespace App\Http\Controllers;

use App\Models\Address;
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
        try {
            $user = User::findOrFail($user);
            $public_id = $user->avatar;
            $addresses = $user->addresses;
            $orders = $user->orders;
            foreach ($orders as $order) {
                $order->products = (new OrdersController())->getProducts($order->id);
                $order->address = Address::where("id", $order->address_id)->first();
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
            return redirect()->back()->with('error', 'Error updating your account: ' . $ex->getMessage());
        }
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
            return redirect()->back()->with('error', 'Error updating your account: ' . $ex->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            $user = User::find(auth()->user()->id);
            if ($user) {
                if (Hash::check($request['password'], $user->password)) {
                    $user->delete();
                    return redirect()->route('/login')->with('success', 'Account deleted successfully.');
                } else {
                    return back()->with('error', 'Incorrect password!');
                }
            } else {
                return redirect()->back()->with('error', 'User not found.');
            }
        } catch (\Exception $ex) {
            return redirect()->back()->with('error', 'Error deleting account: ' . $ex->getMessage());
        }
    }

}
