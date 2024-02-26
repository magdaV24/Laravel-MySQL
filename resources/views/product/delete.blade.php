<form method="POST" action="{{ route('product.delete', ['product' => $product]) }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger review-delete" style="display: block">Delete Product</button>
</form>
