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
    <form method="POST" class='p-2' action="{{ route('product.update', ['product'=>$product, 'uuid'=>$product->uuid]) }}"
        enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
            <div class="col-md-6"> <input type="text" class="form-control @error('name') is-invalid @enderror"
                    name="name" id="name" value="{{ $product->name }}"></div>
        </div>
        <div class="row mb-3">
            <label for="price" class="col-md-4 col-form-label text-md-end">Price</label>
            <div class="col-md-6"><input type="price" class="form-control @error('price') is-invalid @enderror"
                    name="price" id="price" value="{{ $product->price }}">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
            <div class="col-md-6">
                <textarea class="form-control @error('info') is-invalid @enderror" id="description" rows="3"
                    name="description"value="{{ $product->description }}"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label for="photos" class="col-md-4 col-form-label text-md-end">Add Photos</label>
            <div class="col-md-6">
                <input class="form-control" type="file" id="photos" name="photos[]" multiple>
            </div>
        </div>
        <div class='d-flex flex-row gap-1 align-items-center justify-content-center' style="width: 100%; margin-top: 10px; margin-bottom: 10px; margin-left: 20px">
        @foreach($photos as $photo)
            @include('photo.delete', ['public_id'=>$photo])
        @endforeach
        </div>
        <div class="row mb-0">
            <div class="col-md-6 offset-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Edit Product') }}
                </button>
                @include('product.delete', ['product'=>$product])
            </div>
        </div>
    </form>
</div>
</div>
@endif
@endauth

@endsection
