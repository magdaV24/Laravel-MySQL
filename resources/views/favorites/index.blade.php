@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])
@section('content')
<div class=' d-flex gap-1 container'>
        @foreach ($products as $product)
        <div class="card" style="width: 20rem" href="#">
            <div class="card-header d-flex justify-content-end align-items-center">
                <form action="{{ route('favorites.remove', ['product_id' => $product->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">
                    <i class="bi bi-trash3"></i>
                    </button>
                </form>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center p-2">
                @include('components.cld-img', ['public_id'=>$product->photo_url, 'width'=> 200, 'height'=> 300])
                <h4 style="width: 12.5rem;" class="d-flex justify-content-start align-items-center m-2">
                    {{$product->name}}</h4>
                <h5 style="width: 12.5rem;" class="d-flex justify-content-start align-items-center">{{$product->price}}$
                </h5>
            </div>
            <div class="card-actions p-1 d-flex flex-column justify-content-center align-items-center">
                <button class="btn btn-primary btn-block p-1 m-1" style="width: 12.5rem;">Add to Cart
                </button>

            </div>
        </div>
        @endforeach
    </div>
@endsection

