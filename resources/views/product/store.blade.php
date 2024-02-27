@auth
@if(auth()->user()->role === 'admin')
<div class="card p-1">
    <div class="card-header">{{ __('Add Product') }}</div>

    @if(session('error'))
<div class="card border-danger mb-3 offset-md-2" style="max-width: 18rem; margin-top: 0.25rem;">
  <div class="card-header bg-danger text-white">Error</div>
  <div class="card-body text-danger">
    <p class="card-text">{{ session('error') }}</p>
  </div>
</div>
@endif
@if(session('success'))
<div class="card border-success mb-3 offset-md-2" style="max-width: 18rem; margin-top: 0.25rem;">
  <div class="card-header bg-success text-white">Message</div>
  <div class="card-body text-success">
    <p class="card-text">{{ session('success') }}</p>
  </div>
</div>
@endif

    <form method="POST" class='p-2' action="{{ route('product.store', ['user' => $user]) }}"
        enctype="multipart/form-data">
        @csrf
        <div class="row mb-3">
            <label for="name" class="col-md-4 col-form-label text-md-end">Name</label>
            <div class="col-md-6"> <input type="text" class="form-control @error('name') is-invalid @enderror"
                    name="name" id="name" placeholder="Product Name"></div>
        </div>
        <div class="row mb-3">
            <label for="price" class="col-md-4 col-form-label text-md-end">Price</label>
            <div class="col-md-6"><input type="price" class="form-control @error('price') is-invalid @enderror"
                    name="price" id="price" placeholder="10">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-md-4 col-form-label text-md-end">Description</label>
            <div class="col-md-6">
                <textarea class="form-control @error('info') is-invalid @enderror" id="description" rows="3"
                    name="description"></textarea>
            </div>
        </div>
        <div class="row mb-3">
            <label for="photos" class="col-md-4 col-form-label text-md-end">Photos</label>
            <div class="col-md-6">
                <input class="form-control" type="file" id="photos" name="photos[]" multiple>
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    {{ __('Add Product') }}
                </button>
            </div>
        </div>
    </form>
</div>
@endif
@endauth
