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

        <div class="row mb-0">
            <div class="col-md-6 offset-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    {{ __('Edit Product') }}
                </button>
            </div>
        </div>
    </form>
