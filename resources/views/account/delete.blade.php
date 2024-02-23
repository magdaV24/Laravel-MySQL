<form method="POST" action="{{ route('account.delete', ['user' => $user]) }}">
       @csrf
       @method('DELETE')
       <button type="submit" class="btn btn-outline-danger review-delete" style="display: block">Delete Account</button>
</form>
