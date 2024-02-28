<form method="POST" action="{{ route('reviews.delete', ['review' => $review]) }}">
       @csrf
       @method('DELETE')
       <div >
        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
        </div>
</form>
