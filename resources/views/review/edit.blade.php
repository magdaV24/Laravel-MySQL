<form method="POST" class='p-2 review-edit' action="{{ route('reviews.update', ['review'=>$review]) }}">
    @csrf
    @method("PATCH")
    <div class="row mb-3">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                Grade
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item dropdown-item-edit" data-value="1">1 star</a></li>
                <li><a class="dropdown-item dropdown-item-edit" data-value="2">2 stars</a></li>
                <li><a class="dropdown-item dropdown-item-edit" data-value="3">3 stars</a></li>
                <li><a class="dropdown-item dropdown-item-edit" data-value="4">4 stars</a></li>
                <li><a class="dropdown-item dropdown-item-edit" data-value="5">5 stars</a></li>
            </ul>
            <input type="hidden" name="grade" id="newGradeInput" value="{{ $review->grade }}">
        </div>
    </div>
    <div class="row mb-3">
        <div class="row mb-12">
            <div class="col-md-12 d-flex gap-2">
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" rows="3" name="content">{{ old('content', $review->content) }}</textarea>
                <button type="submit" class="btn btn-primary">
                    {{ __('Submit Edit') }}
                </button>
            </div>
        </div>
    </div>
</form>

<script>
     document.addEventListener('DOMContentLoaded', function () {
        var dropdownToggle = document.getElementById('dropdownMenuButton');
        var dropdownMenu = document.querySelector('.dropdown-menu');
        var gradeInput = document.getElementById('newGradeInput');

        dropdownToggle.addEventListener('click', function () {
            dropdownMenu.classList.toggle('show');
        });

        var dropdownItems = document.querySelectorAll('.dropdown-item-edit');

        dropdownItems.forEach(function (item) {
            item.addEventListener('click', function () {
                var value = item.getAttribute('data-value');
                gradeInput.value = value;
                dropdownToggle.textContent = item.textContent;
                dropdownMenu.classList.remove('show');
            });
        });
    });
</script>
