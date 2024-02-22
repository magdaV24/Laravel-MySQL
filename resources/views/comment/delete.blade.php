<form method="POST" action="{{ route('comment.delete', ['comment' => $comment]) }}">
       @csrf
       @method('DELETE')
       <button type="submit" class="btn btn-outline-danger review-delete" style="display: block"><i class="bi bi-trash"></i></button>
   </form>
