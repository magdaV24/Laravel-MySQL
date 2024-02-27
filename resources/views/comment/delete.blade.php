<form method="POST" action="{{ route('comment.delete', ['comment' => $comment]) }}">
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
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger review-delete" style="display: block"><i
            class="bi bi-trash"></i></button>
</form>
