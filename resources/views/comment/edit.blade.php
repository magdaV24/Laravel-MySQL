<form method="POST" class='p-2 review-edit' action="{{ route('comment.update', ['comment'=>$comment]) }}">
    @csrf
    @method("PATCH")
    <div class="row mb-3">
        <div class="row mb-12">
            <div class="col-md-12 d-flex gap-2">
                <textarea class="form-control @error('info') is-invalid @enderror" id="content" rows="3" name="content"
                    value="{{$review->content}}"></textarea>
            <button type="submit" class="btn btn-primary">
                    {{ __('Submit Edit') }}
                </button>
            </div>
        </div>
    </div>
</form>
