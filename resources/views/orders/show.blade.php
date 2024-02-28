@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])
@section('content')
<div class="container">
    @if(auth()->user()->role === "admin")
    <div class="d-flex flex-column  gap-3">
        @foreach($orders as $order)

        <div class="card">
            <div class="card-header">
                <h6>Order ID: {{$order->id}}</h3>
            </div>
            <div class="card-body d-flex justify-content-between p-3 ap-3">
                <div>
                    @php
                    $count=1;
                    @endphp

                    @foreach($order->products as $product)
                    <div class="d-flex gap-2 p-2 justify-content-between align-items-center">
                        <div>{{$count}}</div>
                        <div>
                            @include('components.cld-img', ['public_id'=>$product->photos[0], 'width'=>50,
                            'height'=>75])
                        </div>
                        <div>
                            {{$product->name}} X {{$product->quantity}}
                        </div>

                    </div>
                    @php
                    $count++
                    @endphp
                    @endforeach
                </div>
                @if($order->address)
                <div class="d-flex flex-column gap-3">
                    <h5>
                        Address:
                    </h5>
                    <div>
                        City: {{$order->address->city}}, <br />
                        Street: {{$order->address->street}} <br />
                        Number: {{$order->address->number}} <br />
                        Additional Information: {{$order->address->info}} <br />
                    </div>
                </div>
                @else
                <p>Address was deleted</p>
                @endif
                <div>
                    <h5>Paying Method: </h5>
                    <div>{{$order->paying_method}}</div>
                </div>
                <div class="d-flex flex-column gap-3">
                    <h5>Status: {{$order->status}}</h5>
                    @php
                    $status="";
                    if($order->status === 'Pending'){
                    $status="Processing";
                    } else if($order->status === 'Processing'){
                    $status = "In Transit";
                    }else{
                    $status = "Delivered";
                    };
                    @endphp
                    <form method="POST" action="{{ route('order.update', ['order' => $order]) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-outline-danger review-delete" style="display: block">
                            Change status to {{$status}}
                        </button>
                    </form>

                </div>
            </div>
        </div>

        @endforeach
    </div>
    @endif
</div>
@endsection
