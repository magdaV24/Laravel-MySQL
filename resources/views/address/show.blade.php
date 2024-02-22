@if($addresses->isNotEmpty())
<div class='card'>
    <h2 class="card-header">Your Addresses</h2>
    <div class="list-group list-group-flush">
        @foreach($addresses as $address)
        <div class="list-group-item">{{ $address->city }}, {{ $address->street }}, {{ $address->number }}</div>
        <div class="list-group-item">{{ $address->info }}</div>
        <div class="card-footer d-flex gap-1">
            <div class="col-md-6">
                <a href="{{ route('address.edit', ['address' => $address]) }}" class="btn btn-outline-info btn-block col-md-12">Edit</a>
            </div>
            <div class="col-md-6">
                <form action="{{ route('address.delete', ['address' => $address]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-block col-md-12" type="submit">Delete</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<p>No addresses found.</p>
@endif
