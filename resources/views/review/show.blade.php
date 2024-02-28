<div class="card">
    <div class="card-header d-flex justify-content-between gap-3 align-items-center">
        <div style="width: 50%; display: flex; justify-content: start; align-items: center; gap: 1rem;">
            <div class="d-flex justify-content-center align-items-center p-1 gap-2"
                style="border-radius: 50%; overflow: hidden; width: 2rem; height: 2rem;">
                @include('components.cld-img', ['public_id'=>$review->avatar, 'width'=>35, 'height'=>35])
            </div>
            <div>{{$review->name}} gave {{$review->grade}} stars</div>
            @if($review->verified)
            <div class='d-flex' style="width: 50%; display: flex; justify-content: start; gap: 0.2rem; align-items: center">(
                <i class="bi bi-star-fill" style="color: #FFC400;"></i>
                <div>Verified purchase!</div> )
            </div>
            @endif
        </div>
        <div style="width: 25%; display: flex; justify-content: end; align-items: center; gap: 1rem;">
            @if($review->user_id === auth()->user()->id)
            <div class="btn btn-outline-info" id="edit-review-toggle-{{$review->id}}" style="display: block"><i
                    class="bi bi-pen"></i></div>
            <div style="display: flex; justify-content: center; align-items: center">
            @include('review.delete', ['review'=> $review])
            </div>
            @endif
        </div>
    </div>

    <div class="card-body" id="review-content-{{$review->id}}" style="display: block">
        <p>{{$review->content}}</p>
    </div>
    <div class="card-body" id="edit-review-form-{{$review->id}}" style="display: none">
        @include('review.edit', ['review'=>$review])
    </div>
    <div class="card-actions p-1 d-flex gap-4">
        <button class="btn btn-success" id='comment-toggle-{{$review->id}}' style="display: block">Comment</button>
    </div>
    <div id="comment-form-{{$review->id}}" style="display: none;">
        @include('comment.store', ['productId'=>$review->product_id, 'reviewId'=>$review->id])
    </div>
</div>

<div class="d-flex flex-column gap-3 justify-content-center align-items-end" style="width: 100%; margin-top: 10px;">
    @foreach($review->comments as $comment)
    @include('comment.show',['comment'=>$comment])
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const commentToggle = document.querySelectorAll('#comment-toggle-{{$review->id}}');

        commentToggle.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const commentForm = document.querySelector('#comment-form-{{$review->id}}');
                if (commentForm) {
                    commentForm.style.display = commentForm.style.display === 'none' ? 'block' : 'none';
                }
            });
        });


        const editReviewToggle = document.querySelectorAll('#edit-review-toggle-{{$review->id}}');

        editReviewToggle.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                // Select the review content and edit form using the comment ID
                const reviewContent = document.querySelector('#review-content-{{$review->id}}');
                const editReviewForm = document.querySelector('#edit-review-form-{{$review->id}}');

                // Toggle between displaying the content and the edit form
                if (reviewContent && editReviewForm) {
                    reviewContent.style.display = reviewContent.style.display === 'none' ? 'block' : 'none';
                    editReviewForm.style.display = editReviewForm.style.display === 'none' ? 'block' : 'none';
                }
            });
        });
    });
</script>
