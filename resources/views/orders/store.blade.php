<div class="card container mt-4" style="max-width: 600px; margin-bottom: 10px;">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('order.store', ['user' => $user]) }}" method="POST" class="card-body">
        @csrf
        <div class="form-group" style="margin-bottom: 1rem">
            <label for="paying-method" style="margin-bottom: .75rem">Choose your preferred paying method:</label><br>
            <div class="btn-group btn-group-toggle"  data-toggle="buttons">
                <label class="btn btn-outline-primary">
                    <input type="radio" name="paying-method" value="Cash" autocomplete="off"> Cash
                </label>
                <label class="btn btn-outline-primary">
                    <input type="radio" name="paying-method" value="Credit Card" autocomplete="off"> Credit Card
                </label>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 1rem">
           @if(count($addresses)===0)
           <label>No addresses found. Go to your account page and add one!</label><br>
           @else
           <label style="margin-bottom: .75rem">Select Address:</label><br>
           @foreach($addresses as $address)
            <div class="form-check">
                <input type="radio" class="form-check-input" id="address-{{$address->id}}" name="address" value="{{$address->id}}">
                <label class="form-check-label" for="address-{{$address->id}}">{{$address->city}}, {{$address->street}}, number {{$address->number}}</label>
            </div>
            @endforeach
           @endif

        </div>
@if(count($addresses) > 0)
<button class="btn btn-outline-info" type="submit">Submit Order</button>
@endif
        <!-- Hidden inputs to store selected values -->
        <input type="hidden" id="selected-address" name="selected-address">
        <input type="hidden" id="selected-paying-method" name="selected-paying-method">
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handling paying method selection
        var payingMethodItems = document.querySelectorAll('input[name="paying-method"]');
        payingMethodItems.forEach(function (item) {
            item.addEventListener('change', function () {
                document.getElementById('selected-paying-method').value = item.value;
            });
        });

        // Handling address selection
        var addressRadios = document.querySelectorAll('input[name="address"]');
        addressRadios.forEach(function (radio) {
            radio.addEventListener('change', function () {
                document.getElementById('selected-address').value = radio.value;
            });
        });
    });
</script>
