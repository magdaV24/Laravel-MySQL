<div class="card p-1 container mt-4" style="margin-bottom: 10px;">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('order.store', ['user' => $user]) }}" method="POST" class="card d-flex flex-column">
        @csrf
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <label for="paying-method">Choose your preferred paying method:</label>
            <input type="radio" class="btn-check paying-method-value" id="btn-radio1" name="paying-method" value="Cash" autocomplete="off">
            <label class="btn btn-outline-primary" for="btn-radio1">Cash</label>

            <input type="radio" class="btn-check paying-method-value" id="btn-radio2" name="paying-method" value="Credit Card" autocomplete="off">
            <label class="btn btn-outline-primary" for="btn-radio2">Credit Card</label>
            <!-- No need for hidden input here -->
        </div>

        <div class="btn-group d-flex flex-column" role="group" aria-label="Basic checkbox toggle button group">
            @foreach($addresses as $address)
            <div style="display: flex; gap: 5px; ">
                <div>
                    <input type="checkbox" class="btn-check address-checkbox" id="btn-check-{{$address->id}}" name="address" value="{{$address->id}}" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btn-check-{{$address->id}}">Address</label>
                </div>
                <div>
                    <p>{{$address->city}}, {{$address->street}}, number {{$address->number}}</p>
                </div>
            </div>
            @endforeach
        </div>
        <button class="btn btn-outline-info" type="submit">Submit Order</button>
        <!-- Hidden inputs to store selected values -->
        <input type="hidden" id="selected-address" name="selected-address">
        <input type="hidden" id="selected-paying-method" name="selected-paying-method">
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Handling paying method selection
        var payingMethodItems = document.querySelectorAll('.paying-method-value');
        payingMethodItems.forEach(function (item) {
            item.addEventListener('click', function () {
                document.getElementById('selected-paying-method').value = item.value;
            });
        });

        // Handling address selection
        var addressCheckboxes = document.querySelectorAll('.address-checkbox');
        addressCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('click', function () {
                document.getElementById('selected-address').value = checkbox.value;
            });
        });
    });
</script>
