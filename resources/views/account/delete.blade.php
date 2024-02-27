<form method="POST" action="{{ route('account.delete', ['user' => $user,]) }}">
    @csrf
    @method('DELETE')
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
        <label for="password" class="col-md-4 col-form-label text-md-end">Password</label>

        <div class="col-md-6">
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required>
        </div>
    </div>
    <button type="submit" class="btn btn-danger review-delete offset-md-4" style="display: block">Delete
        Account</button>
</form>
