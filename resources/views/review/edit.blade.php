<form method="POST" class='p-2 review-edit' action="{{ route('reviews.update', ['review'=>$review]) }}">
    @csrf
    @method("PATCH")
    <div class="row mb-3">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                Grade
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item-edit" href="#" data-value="1">1 star</a></li>
                <li><a class="dropdown-item-edit" href="#" data-value="2">2 stars</a></li>
                <li><a class="dropdown-item-edit" href="#" data-value="3">3 stars</a></li>
                <li><a class="dropdown-item-edit" href="#" data-value="4">4 stars</a></li>
                <li><a class="dropdown-item-edit" href="#" data-value="5">5 stars</a></li>
            </ul>
            <input type="hidden" name="grade" id="gradeInput">
        </div>
    </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dropdownItems = document.querySelectorAll('.dropdown-item-edit');

        dropdownItems.forEach(function (item) {
            item.addEventListener('click', function () {
                // Get the value from the data attribute
                var value = item.getAttribute('data-value');

                // Update the value of the hidden input field
                document.getElementById('gradeInput').value = value;
            });
        });
    });
</script>
