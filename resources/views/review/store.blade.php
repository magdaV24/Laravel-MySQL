<div class="card p-1 container mt-4" style="margin-bottom: 10px;">
    <div class="card-header">{{ __('Write a Review') }}</div>
    <form method="POST" class='p-2'
        action="{{ route('reviews.store', ['productId'=>$product->id, 'product'=>$product]) }}">
        @csrf
        <div class="row mb-3">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    Grade
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" data-value="1">1 star</a></li>
                    <li><a class="dropdown-item" data-value="2">2 stars</a></li>
                    <li><a class="dropdown-item" data-value="3">3 stars</a></li>
                    <li><a class="dropdown-item" data-value="4">4 stars</a></li>
                    <li><a class="dropdown-item" data-value="5">5 stars</a></li>
                </ul>
                <input type="hidden" name="grade" id="gradeInput">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12 d-flex gap-2">
                <textarea class="form-control @error('info') is-invalid @enderror" id="content" rows="3"
                    name="content"></textarea>
                <button type="submit" class="btn btn-primary">
                    {{ __('Submit Review') }}
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var dropdownToggle = document.querySelector('.dropdown-toggle');
        var dropdownMenu = document.querySelector('.dropdown-menu');

        dropdownToggle.addEventListener('click', function () {
            dropdownMenu.classList.toggle('show');
        });

        var dropdownItems = document.querySelectorAll('.dropdown-item');

        dropdownItems.forEach(function (item) {
            item.addEventListener('click', function () {
                var value = item.getAttribute('data-value');
                document.getElementById('gradeInput').value = value;
                dropdownToggle.textContent = item.textContent;
                dropdownMenu.classList.remove('show');
            });
        });
    });
</script>
