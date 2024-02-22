@props(['public_id','width', 'height'])

@php
$imageUrl = cloudinary()->getImage($public_id)->toUrl();
@endphp

<img src="{{ $imageUrl }}" alt="Cloudinary Image" width="{{ $width }}" height="{{ $height }}" />
