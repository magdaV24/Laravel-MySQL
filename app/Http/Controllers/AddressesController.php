<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use App\Models\Address;
use App\Models\User;

class AddressesController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'city' => ['required', 'string', 'max:50'],
                'street' => ['required', 'string', 'max:100'],
                'number' => ['required', 'numeric', 'min:0'],
                'info' => ['nullable', 'string', 'max:300'],
            ]);

            auth()->user()->addresses()->create($validatedData);
            return back()->with('success', 'Address added successfully.');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }


    public function show(User $user)
    {
        try {
            $addresses = $user->addresses()->get();
            return view('address.show', compact('addresses'));
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function edit($address)
    {
        try {
            $user = auth()->user();
            $address = Address::findOrFail($address);
            $favoritesCount = (new FavoritesController())->count();
            $cartCount = (new CartController())->count();
            return view('address.edit', [
                'user' => $user,
                'address' => $address,
                'favoritesCount' => $favoritesCount,
                'cartCount' => $cartCount
            ]);
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
    public function update(Request $request, $address)
    {
        try {
            $address = Address::findOrFail($address);
            $validData = $request->validate([
                'city' => ['nullable', 'string', 'max:50'],
                'number' => ['nullable', 'numeric', 'min:1'],
                'street' => ['nullable', 'string', 'max:400'],
                'info' => ['nullable', 'string', 'max:400'],
            ]);
            $address->update($validData);
            return back()->with('success', 'Address updated successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }

    public function delete($address)
    {
        try {
            $address = Address::findOrFail($address);
            $id = $address->id;
            $order = Orders::where("address_id", $id)->first();
            if($order->exists() && $order->status !== "Delivered"){
                return back()->with('error', "This address is associated to an on-going delivery!");
            }
            $address->delete();
            return back()->with('success', 'Address deleted successfully!');
        } catch (\Exception $ex) {
            return back()->with('error', $ex->getMessage());
        }
    }
}
