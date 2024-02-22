@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="width: 18rem;">
                @include('components.cld-img', ['public_id'=>$public_id, 'width' => 286, 'height' => 350])
                <div class="card-body">
                    <h5 class="card-title">{{$user->name}}</h5>
                    <p class="card-text">{{$user->email}}</p>
                    <a href="{{ route('account.edit', ['user'=> $user])}}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('address.show')
        </div>
        <div class="col-md-4">
            @include('address.store')
            <div class="mt-4"></div>
            @include('product.store')
        </div>

    </div>
</div>
@endsection
