<form method="POST" action="{{ route('account.delete', ['user' => $user,]) }}">
    @csrf
    @method('DELETE')
    <div class="row mb-3">
        <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-danger review-delete" style="display: block">Delete Account</button>
</form>
