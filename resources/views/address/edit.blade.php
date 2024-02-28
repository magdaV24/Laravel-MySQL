@extends('layouts.app', ['favoritesCount' =>$favoritesCount, 'cartCount'=>$cartCount])

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-1">
                <div class="card-header">{{ __('Edit Address') }}</div>
                <div class="card-body p-2">
                    <form method="POST" action="{{ route('address.update', ['user' => $user, 'address'=>$address]) }}">
                        @csrf
                        @method("PATCH")
                        <div class="row mb-3">
                            <label for="city" class="col-md-4 col-form-label text-md-end">City</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                                    name='city' value="{{$address->city}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="street" class="col-md-4 col-form-label text-md-end">Street</label>
                            <div class="col-md-6"> <input type="text"
                                    class="form-control @error('street') is-invalid @enderror" name="street" id="street"
                                    value="{{$address->street}}"></div>
                        </div>
                        <div class="row mb-3">
                            <label for="number" class="col-md-4 col-form-label text-md-end">Number</label>
                            <div class="col-md-6"><input type="number"
                                    class="form-control @error('number') is-invalid @enderror" name="number" id="number"
                                    value="{{$address->number}}">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="info" class="col-md-4 col-form-label text-md-end">Additional Information</label>
                            <div class="col-md-6">
                                <textarea class="form-control @error('info') is-invalid @enderror" id="info" rows="3"
                                    name="info" value="{{$address->info}}"></textarea>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Edit Address') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
