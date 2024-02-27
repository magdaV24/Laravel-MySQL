<form method="POST" class='p-2 review-edit' action="{{ route('comment.update', ['comment'=>$comment]) }}">
    @csrf
    @method("PATCH")
    @if(session('error'))
<div class="card border-danger mb-3 offset-md-2" style="max-width: 18rem;">
  <div class="card-header bg-danger text-white">Error</div>
  <div class="card-body text-danger">
    <p class="card-text">{{ session('error') }}</p>
  </div>
</div>
@endif
@if(session('success'))
<div class="card border-success mb-3 offset-md-2" style="max-width: 18rem;">
  <div class="card-header bg-success text-white">Message</div>
  <div class="card-body text-success">
    <p class="card-text">{{ session('success') }}</p>
  </div>
</div>
@endif
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
