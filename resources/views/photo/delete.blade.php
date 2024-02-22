<div class='d-flex flex-column gap-1' style="width: 9.4rem;">
   @include('components.cld-img', ['pubic_id'=> $public_id, 'width'=>150, 'height'=>200])
   <form method="POST" action="{{ route('photo.delete', ['photo' => $public_id]) }}">
       @csrf
       @method('DELETE')
       <button type="submit" class="btn btn-outline-danger" style="width: 100%">Delete</button>
   </form>
</div>
