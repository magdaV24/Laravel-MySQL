<div id="carouselExample" class="carousel slide">
    <div class="carousel-inner">

        @foreach($photos as $photo)
        <div class="carousel-item active">
            @include('components.cld-img', ['public_id'=> $photo, 'width' => 400, 'height' => 500])
        </div>
        @endforeach

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>
