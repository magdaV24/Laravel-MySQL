@if(count($orders) > 0)
<div style="margin-top: 2rem">
    <h5>Your Orders:</h5>
    @foreach($orders as $order)
    <div class="card">
        <div class="card-header">
            Order ID: {{$order->uuid}}
        </div>
        <div class="card-body">
            <div>
                @php
                $count=1;
                @endphp
                @foreach($order->products as $product)
                <div class="d-flex gap-4 p-2 justify-content-start align-items-center"
                    style="border: 1px solid #B6AFB5; margin-bottom: 5px">
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
           <h5>
                Delivery Address:
            </h5>
            <div>
                City: {{$order->address->city}}, <br />
                Street: {{$order->address->street}} <br />
                Number: {{$order->address->number}} <br />
                Additional Information: {{$order->address->info}} <br />
            </div>
            @else
            <p>Address was deleted</p>
            @endif
            <div class="d-flex gap-2">
                <h5>Status:</h5>
                <div>{{$order->status}}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<p style="margin-top: 2rem">You placed no orders.</p>
@endif
