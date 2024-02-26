@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
@auth
@if(auth()->user()->role === 'admin')
<div class="container">
    <div class="card p-1 d-flex justify-content-center p-2">
        <div class="card-header">{{ __('Edit Product') }}</div>
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
            @include('product.edit', ['product'=>$product])
            <div class='d-flex flex-row gap-1 align-items-center justify-content-center'
                style="width: 100%; margin-top: 10px; margin-bottom: 10px; margin-left: 20px">
                @foreach($photos as $photo)
                @include('photo.delete', ['public_id'=>$photo])
                @endforeach
            </div>
            <div class="row mb-0">
            <div class="col-md-6 offset-md-4 d-flex gap-2">
            @include('product.delete', ['product'=>$product])
            </div>
        </div>

        </div>
    </div>
</div>
@endif
@endauth

@endsection
