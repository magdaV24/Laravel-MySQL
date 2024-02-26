<div class="card p-1">
    <div class="card-header">{{ __('Add Address') }}</div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" class='p-2' action="{{ route('address.store', ['user' => $user]) }}">
        @csrf
        <div class="row mb-3">
            <label for="city" class="col-md-4 col-form-label text-md-end">City</label>
            <div class="col-md-6">
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name='city'
                    placeholder="New York">
            </div>
        </div>
        <div class="row mb-3">
            <label for="street" class="col-md-4 col-form-label text-md-end">Street</label>
            <div class="col-md-6"> <input type="text" class="form-control @error('street') is-invalid @enderror"
                    name="street" id="street" placeholder="Broadway"></div>
        </div>
        <div class="row mb-3">
            <label for="number" class="col-md-4 col-form-label text-md-end">Number</label>
            <div class="col-md-6"><input type="number" class="form-control @error('number') is-invalid @enderror"
                    name="number" id="number" placeholder="726">
            </div>
        </div>
        <div class="row mb-3">
            <label for="info" class="col-md-4 col-form-label text-md-end">Additional Information</label>
            <div class="col-md-6">
                <textarea class="form-control @error('info') is-invalid @enderror" id="info" rows="3"
                    name="info"></textarea>
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Add Address') }}
                </button>
            </div>
        </div>
    </form>
</div>
