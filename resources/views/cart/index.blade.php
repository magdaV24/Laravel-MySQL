@extends('layouts.app', ['favoritesCount'=>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
<div class="container">
    <table class="table">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col">Name</th>
                <th scope="col">Price</th>
                <th scope="col">Total Price</th>
                <th scope="col">Change Quantity</th>
                <th scope="col">Remove Product</th>
            </tr>
        </thead>
        <tbody>
            @php
            $count = 1;
            @endphp
            @foreach($products as $product)
            <tr style="height: 5rem">
                <th scope="row">{{$count}}</th>
                <td>
                    @include('components.cld-img', ['public_id'=>$product->photo_url, 'width'=>50, 'height'=>75])
                </td>
                <td>{{$product->name}}</td>
                <td>{{$product->price}}$</td>
                <td>{{$product->totalPrice}}$</td>
                <td class="d-flex flex-row gap-3 justify-content-center align-items-center" style="height: 6.22rem">
                    <form action="{{ route('cart.decrement', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-info">
                            <i class="bi bi-dash-circle"></i>
                        </button>
                    </form>

                    <span>{{$product->quantity}}</span>
                    <form action="{{ route('cart.increment', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-info">
                            <i class="bi bi-plus-circle"></i>
                        </button>
                    </form>
                </td>
                <td>
                    <form action="{{ route('cart.remove', ['product_id' => $product->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="bi bi-trash3"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @php
            $count++;
            @endphp
            @endforeach
        </tbody>
    </table>
    <div class="d-flex flex-column gap-3 justify-content-end align-items-end">
        <h4>Total: {{$cartTotal}} $</h4>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#makeAnOrder">
            Place an Order
        </button>

        <!-- Modal -->
        <div class="modal fade" id="makeAnOrder" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Place an Order</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        @include('orders.store', ['addresses'=> auth()->user()->addresses])

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
