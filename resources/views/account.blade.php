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
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" style="margin-left: .25rem">
                        Delete Account
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel">Are you sure you want to delete your account?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @include('account.delete', ['user'=>$user])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @include('address.show')
            @include('account.orders', ['orders'=>$orders])
        </div>
        <div class="col-md-4">
            @include('address.store')
            <div class="mt-4"></div>
            @include('product.store')
        </div>
    </div>
@endsection
