<div
  class="container-fluid mobile-nav fixed-bottom px-3 py-1 d-flex d-lg-none justify-content-between bg-white border-top">
  <div @class(['icon-wrapper p-3 home-nav', 'active'=> Route::is('index')])>
    <a class="nav-link d-flex flex-column align-items-center" aria-current="page" href={{route('index')}}>
      <i class="nav-icon ri-home-line"></i>Beranda</a>
  </div>
  <div @class(['icon-wrapper p-3 home-nav', 'active'=> Route::is('schedule')])>
    <a class="nav-link d-flex flex-column align-items-center" aria-current="page" href={{route('schedule')}}>
      <i class="nav-icon ri-calendar-line"></i>Jadwal Kelas</a>
  </div>
  <div class="icon-wrapper p-3 chat-admin-nav">
    <a class="nav-link d-flex flex-column align-items-center" aria-current="page" href="https://wa.me/6285190331512"
      target="_blank">
      <i class="nav-icon ri-chat-3-line"></i>Chat Admin</a>
  </div>
</div>