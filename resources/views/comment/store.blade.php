<div class="card p-1 container mt-4">
    <div class="card-header">{{ __('Reply') }}</div>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form method="POST" class='p-2'
        action="{{ route('comment.store', ['productId'=>$review->product_id, 'reviewId'=>$review->id]) }}">
        @csrf
        <div class="row mb-3">
            <div class="mb-12 d-flex flex-row gap-2">
                <textarea class="form-control @error('info') is-invalid @enderror" id="content" rows="3"
                    name="content"></textarea>

                <button type="submit" class="btn btn-primary">
                    {{ __('Submit Comment') }}
                </button>
            </div>
    </form>
</div>
