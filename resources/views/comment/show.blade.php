<div class="car p-2" style="width: 90%; border-radius: 1;">
    <div class="card-header d-flex justify-content-between gap-3 align-items-center">
        <div class="d-flex gap-2">
            <div class="d-flex  justify-content-center align-items-center p-1"
                style="border-radius: 50%; overflow: hidden; width: 2rem; height: 2rem;">
                @include('components.cld-img', ['public_id'=>$comment->avatar, 'width'=>35, 'height'=>35])
            </div>
            <p>{{$comment->name}} said:</p>
        </div>
        <div class="d-flex gap-2">
            @if($review->user_id === auth()->user()->id)
            <div class="btn btn-outline-info edit-toggle" style="display: block"><i class="bi bi-pen"></i></div>
            @include('comment.delete', ['comment'=> $comment])
            @endif
        </div>
    </div>
    <div class="card-body">
        <p>{{$comment->content}}</p>
    </div>
</div>
