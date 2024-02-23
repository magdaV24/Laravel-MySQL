<div class="card p-2" style="width: 90%; border-radius: 1;">
    <div class="card-header d-flex justify-content-between gap-3 align-items-center">
        <div class="d-flex gap-2">
            <div class="d-flex justify-content-center align-items-center p-1"
                style="border-radius: 50%; overflow: hidden; width: 2rem; height: 2rem;">
                @include('components.cld-img', ['public_id'=>$comment->avatar, 'width'=>35, 'height'=>35])
            </div>
            <p>{{$comment->name}} said:</p>
        </div>
        <div class="d-flex gap-2">
            @if($comment->user_id === auth()->user()->id)
            <div class="btn btn-outline-info " id="edit-comment-toggle-{{$comment->id}}"
                style="display: block"><i class="bi bi-pen"></i></div>
            @include('comment.delete', ['comment'=> $comment])
            @endif
        </div>
    </div>
    <div class="card-body" id="comment-content-{{$comment->id}}" style="display: block">
        <p>{{$comment->content}}</p>
    </div>
    <div class="card-body" id="edit-comment-form-{{$comment->id}}" style="display: none">
        @include('comment.edit', ['comment'=>$comment])
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const editCommentToggle = document.querySelectorAll('#edit-comment-toggle-{{$comment->id}}');

    editCommentToggle.forEach(function (toggle) {
        toggle.addEventListener('click', function () {

            // Select the comment content and edit form using the comment ID
            const commentContent = document.querySelector('#comment-content-{{$comment->id}}');
            const editCommentForm = document.querySelector('#edit-comment-form-{{$comment->id}}');

            // Toggle between displaying the content and the edit form
            if (commentContent && editCommentForm) {
                commentContent.style.display = commentContent.style.display === 'none' ? 'block' : 'none';
                editCommentForm.style.display = editCommentForm.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});

</script>
