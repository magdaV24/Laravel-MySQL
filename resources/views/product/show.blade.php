<div>
    <h1>All Products</h1>
    <div class=' d-flex gap-1'>
        @foreach ($products as $product)
        <div class="card" style="width: 20rem" href="#">
            <div class="card-header d-flex justify-content-end align-items-center">
                @if ($product->favorite)
                <form action="{{ route('favorites.remove', ['product_id' => $product->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">
                        <i class="bi bi-balloon-heart-fill text-danger fs-4"></i>
                    </button>
                </form>
                @else
                <form action="{{ route('favorites.store', ['product_id' => $product->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">
                        <i class="bi bi-balloon-heart text-danger fs-4"></i>
                    </button>
                </form>
                @endif
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center p-2">
                @include('components.cld-img', ['public_id'=>$product->photo_url, 'width'=> 200, 'height'=> 300])
                <a href="{{route('product', ['product'=>$product])}}" style="width: 12.5rem; size: 2.2rem; text-decorations:none; color: inherit; font-size: 1.5rem" class="d-flex justify-content-start align-items-center m-2 font-weight-bold">
                    {{$product->name}}</a>
                <h5 style="width: 12.5rem;" class="d-flex justify-content-start align-items-center">{{$product->price}}$
                </h5>
            </div>
            <div class="card-actions p-1 d-flex flex-column justify-content-center align-items-center">
                @if ($product->cart)
                <button disabled class="btn btn-primary btn-block p-1 m-1" style="width: 12.5rem;"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                    data-bs-title="This product is already in your cart. Go there to change it's quantity.">
                    Add to Cart
                </button>
                @else

                <form action="{{ route('cart.store', ['product_id' => $product->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block p-1 m-1" style="width: 12.5rem;">
                        Add to Cart
                    </button>
                </form>
                @endif
                @if(auth()->user()->role === "admin")
                <a href="{{ route('product.edit', ['product'=> $product->id])}}"
                    class="btn btn-outline-success btn-block p-1 m-1" style="width: 12.5rem;">
                    Edit Product
                </a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
