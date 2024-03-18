<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['string', 'in:user,admin'],
            'avatar' => ['nullable', 'file', 'image', 'max:2048'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        // Checking if the user provided a file for the avatar field;
        if (isset($data['avatar'])) {
            // Upload the avatar to Cloudinary
            $uploadedFile = $data['avatar']->getRealPath();
            $uploadResult = Cloudinary::upload($uploadedFile);

            // Extract the public ID from the upload result;
            $publicId = $uploadResult->getPublicId();
        } else {
            // If the user does not want to upload an avatar, they will have a default one assigned;
            $defaultAvatar = 'uz8dp4kowe3riee7gbmf';
            $publicId = $defaultAvatar;
        }

        $role = 'user'; // Gives the new user the role of "user";

       return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'avatar' => $publicId,
            'role' => $role,
            'password' => Hash::make($data['password']), // The password is stored in a hashed form;
        ]);

    }
}
