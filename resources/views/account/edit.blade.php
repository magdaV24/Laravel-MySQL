@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
<div class="container">
    @if(session('error'))
    <div class="card border-danger mb-3 offset-md-2" style="max-width: 18rem;">
        <div class="card-header bg-danger text-white">Error</div>
        <div class="card-body text-danger">
            <p class="card-text">{{ session('error') }}</p>
        </div>
    </div>
    @endif
    @if(session('success'))
    <div class="card border-success mb-3 offset-md-2" style="max-width: 18rem;">
        <div class="card-header bg-success text-white">Message</div>
        <div class="card-body text-success">
            <p class="card-text">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Profile</div>

                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card-body">
                    <form method="POST" action="{{ route('account.update', ['user'=>$user,'userId'=> $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="row mb-3">
                            <label for="new_name" class="col-md-4 col-form-label text-md-end">New Name</label>

                            <div class="col-md-6">
                                <input id="new_name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder={{$user->name}}>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_email" class="col-md-4 col-form-label text-md-end">New Email Address</label>

                            <div class="col-md-6">
                                <input id="new_email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    placeholder={{$user->email}}>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_phone_number" class="col-md-4 col-form-label text-md-end">New Phone Number</label>

                            <div class="col-md-6">
                                <input id="new_phone-number" type="string"
                                pattern="0[0-9]{9}"
                                    class="form-control @error('phone-number') is-invalid @enderror" name="phone-number"
                                    placeholder={{$user->phone-number}}>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_password" class="col-md-4 col-form-label text-md-end">New Password</label>

                            <div class="col-md-6">
                                <input id="new_password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">Confirm New
                                Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="confirm_new_password">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="old_password" class="col-md-4 col-form-label text-md-end">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="old_password"
                                    required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="new_avatar" class="col-md-4 col-form-label text-md-end">New Avatar</label>
                            <div class="col-md-6">
                                <input class="form-control" type="file" id="new_avatar" name="avatar">
                            </div>
                        </div>
                        <div class="mb-12 d-flex" style="margin-top: 2rem;">
                            <div class="col-md-3 offset-md-4">
                                <button type="submit" class="btn btn-secondary">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
