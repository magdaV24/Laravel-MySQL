@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4" style="width: 25rem; height: fit-content">
            @include('photo.show', ['photos'=>$photos])
        </div>
        <div class="col-md-4">
            <h2>{{$product->name}}</h2>
            <h3>{{ number_format($product->grade, 2) }} <i class="bi bi-star-fill" style="color: #FFC400;"></i></h3>
            <p>{{$product->description}}</p>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Price</h4>
                </div>
                <h5 class="p-2">{{$product->price}}$</h5>
                <div class="card-actions d-flex flex-column">
                    @if ($product->cart)
                    <button type="button" class="btn btn-primary p-1 m-1" style="width:98%" disabled>Buy Now</button>
                    @else
                    <form action="{{ route('cart.store', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary p-1 m-1" style="width:98%">Buy Now</button>
                    </form>
                    @endif
                    @if ($product->favorite)
                    <form action="{{ route('favorites.remove', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn">
                            <button type="submit" class="btn btn-outline-danger p-1 m-1" style="width: 98%">Remove from
                                Wishlist</button>
                        </button>
                    </form>
                    @else
                    <form action="{{ route('favorites.store', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn">
                            <button type="submit" class="btn btn-outline-danger p-1 m-1" style="width: 98%">Add To
                                Wishlist</button>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('review.store')
    <div class="d-flex flex-column gap-3" style="margin-top: 15px; margin-bottom: 20px">
        @foreach($reviews as $review)
        @include('review.show', ['review'=>$review])
        @endforeach
    </div>
@endsection


