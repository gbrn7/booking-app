@extends('layouts.base')

@section('title', 'Beranda')

@section('custom-header')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />
@endsection

@section('body')
<section class="body-wrapper container mt-4">
  <div class="container d-flex flex-column align-items-center gap-4">
    <div class="col-12 img-wrapper">
      <img src="{{asset('img/PINKLATES.jpg')}}" class="img-fluid rounded-4" />
    </div>

    @if (count($galleryImages) > 0)
    <!-- Swiper -->
    <div class="swiper-wrapper col-12">
      <div class="swiper home-slider">
        <div class="swiper-wrapper">
          @foreach ($galleryImages as $image)
          <div class="swiper-slide">
            <img class="slide-img" src="{{asset('storage/'.$image->file_name)}}" />
          </div>
          @endforeach
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
    @endif

    <div class="cta-wrapper col-12 d-flex justify-content-center">
      <a href={{route('schedule')}}
        class="text-center p-2 py-2 text-decoration-none rounded-3 btn-book text-white">Booking
        Sekarang</a>
    </div>
    <!-- contact -->
    <div class="col-12 mb-4 btn-wrapper d-flex flex-wrap gap-2 justify-content-center mt-2">
      <a href="https://g.co/kgs/KonV2mZ" target="_blank"
        class="location text-decoration-none text-white bg-grey p-2 col-12 col-lg-2 text-center rounded">
        <i class="text-pink ri-map-pin-fill"></i>
        Lokasi</a>
      <a target="_blank" href="https://www.instagram.com/studiopinklates.id"
        class="location col-12 col-lg-2 text-decoration-none text-white bg-grey p-2 text-center rounded">
        <i class="text-pink ri-instagram-fill"></i>
        Instagram</a>
      <a target="_blank" href="https://wa.me/6285190331512"
        class="location text-decoration-none text-white bg-grey p-2 text-center col-lg-2 rounded col-12">
        <i class="text-pink ri-whatsapp-fill"></i>
        Chat Admin</a>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".home-slider", {
        effect: "coverflow",
        grabCursor: true,
        centeredSlides: true,
        slidesPerView: "auto",
        coverflowEffect: {
          rotate: 10,
          stretch: 0,
          depth: 100,
          modifier: 2,
          slideShadows: true,
        },
        loop: true,
        autoplay: {
          delay: 2000,
          disableOnInteraction: false,
        },
      });
</script>
@endsection