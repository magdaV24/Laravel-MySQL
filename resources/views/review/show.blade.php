<div class="card">
    <div class="card-header d-flex justify-content-between gap-3 align-items-center">
        <div class="d-flex">
        <div class="d-flex justify-content-center align-items-center p-1 gap-2"
             style="border-radius: 50%; overflow: hidden; width: 2rem; height: 2rem;">
            @include('components.cld-img', ['public_id'=>$review->avatar, 'width'=>35, 'height'=>35])
        </div>
        <p>{{$review->name}} gave {{$review->grade}} stars</p>
        @if($review->verified)
            <div class='d-flex'>(
                <i class="bi bi-star-fill" style="color: #FFC400;"></i>
                <p>Verified purchase!</p> )
            </div>
        @endif
        </div>
        <div class="d-flex gap-2">
      @if($review->user_id === auth()->user()->id)
            <div class="btn btn-outline-info edit-toggle" style="display: block"><i class="bi bi-pen"></i></div>
            @include('review.delete', ['review'=> $review])

        @endif
      </div>
    </div>
    <div class="card-body review-content" style="display: block">
        <p>{{$review->content}}</p>

    </div>
    <div class="review-edit"  style="display: none">
            @include('review.edit', ['review'=>$review])
        </div>
    <div class="card-actions p-1 d-flex gap-4">
        <button class="btn btn-success comment-toggle" style="display: block">Comment</button>
    </div>
    <div class="comment-form" style="display: none;">
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
        const commentToggle = document.querySelectorAll('.comment-toggle');
        const editToggle = document.querySelectorAll('.edit-toggle');

        commentToggle.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const commentForm = document.querySelector('.comment-form');
                if (commentForm) {
                    commentForm.style.display = commentForm.style.display === 'none' ? 'block' : 'none';
                }
            });
        });


        editToggle.forEach(function (toggle) {
            toggle.addEventListener('click', function () {
                const reviewEdit = document.querySelector('.review-edit');
                const reviewContent = document.querySelector(".review-content")
                if (reviewEdit) {
                    reviewEdit.style.display = reviewEdit.style.display === 'none' ? 'block' : 'none';
                }
                if (reviewContent) {
                    reviewContent.style.display = reviewContent.style.display === 'block' ? 'none' : 'block';
                }
            });
        });
    });
</script>
