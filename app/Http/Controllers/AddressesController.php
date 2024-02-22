<?php

namespace App\Http\Controllers;

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
        $validatedData = $request->validate([
            'city' => ['required', 'string', 'max:50'],
            'street' => ['required', 'string', 'max:100'],
            'number' => ['required', 'numeric', 'min:0'],
            'info' => ['nullable', 'string', 'max:300'],
        ]);

        auth()->user()->addresses()->create($validatedData);
        return redirect()->back()->with('success', 'Address added successfully.');
    }


    public function show(User $user)
    {
        $addresses = $user->addresses()->get();
        return view('address.show', compact('addresses'));
    }

    public function edit($address)
    {
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
    }
    public function update(Request $request, $address)
    {
        $address = Address::findOrFail($address);
        $validData = $request->validate([
            'city' => ['nullable', 'string', 'max:50'],
            'number' => ['nullable', 'numeric', 'min:1'],
            'street' => ['nullable', 'string', 'max:400'],
            'info' => ['nullable', 'string', 'max:400'],
        ]);
        $address->update($validData);
        return redirect()->back()->with('success', 'Address updated successfully!');
    }

    public function delete($address)
    {
        $address = Address::findOrFail($address);
        $address->delete();
        return redirect()->back()->with('success', 'Address deleted successfully!');
    }
}
