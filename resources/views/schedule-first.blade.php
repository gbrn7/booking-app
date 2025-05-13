@extends('layouts.base')

@section('title', 'Jadwal')

@section('body')
<section class="schedule-wrapper">
  <div class="container pt-3 pt-lg-0">
    <div class="title-wrapper">
      <h4 class="fw-semibold">Jadwal Kelas</h4>
      <div class="schedule mt-3">
        <div class="btn-wrapper row">
          <a href="#" class="col-6 text-decoration-none text-center">
            <div class="wrapper bg-pink text-white fw-semibold py-2 rounded-2">
              Kelas Umum
            </div>
          </a>
          <a href={{route('schedule',['private_schedule'=> true])}} class="col-6 text-decoration-none text-black
            text-center">
            <div class="wrapper border fw-semibold py-2 rounded-2">
              Kelas Privat
            </div>
          </a>
        </div>
        <div class="schedule-list mt-3 d-flex flex-column gap-4">
          @foreach ($schedules as $schedule)
          <div class="schedule-section">
            <div class="day-title fw-semibold">{{$schedule->FormattedDate}}</div>
            <div class="schedule-box-wrapper row row-gap-1">
              @foreach ($schedule->scheduleDetails as $scheduleDetail)
              @if ($scheduleDetail->classes->classType->group_class_type_id == $groupTypeID)
              <a class="col-lg-3 col-6 py-1 rounded text-decoration-none text-black schedule-box"
                data-class-type-id={{$scheduleDetail->classes->classType->id}}
                data-schedule-date="{{$schedule->FormattedDate}}"
                data-schedule-time="{{$scheduleDetail->FormattedTime}}"
                data-schedule-detail-quota={{$scheduleDetail->quota}}
                data-schedule-detail-id={{$scheduleDetail->id}}
                data-title="{{$scheduleDetail->classes->name." -
                ".$scheduleDetail->classes->classType->groupClassType->name." -
                By ". $scheduleDetail->classes->instructure_name}}"
                data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                <div class="status-availability text-pink fw-medium">
                  Sisa Kuota {{$scheduleDetail->quota}}
                </div>
                <div class="time fw-medium">{{$scheduleDetail->FormattedTime}}</div>
                <div class="schedule-desc fw-light">
                  {{$scheduleDetail->classes->name}} - Kelas
                  {{$scheduleDetail->classes->classType->groupClassType->name}} - By
                  {{$scheduleDetail->classes->instructure_name}}
                </div>
              </a>
              @endif
              @endforeach
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<div class="offcanvas offcanvas-end rounded-start rounded-2" tabindex="-1" id="offcanvasExample"
  aria-labelledby="offcanvasExampleLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasExampleLabel">
      Booking Kelas
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body d-flex flex-column gap-3">
    <div class="title-wrapper py-2 px-1 rounded-2 bg-pink text-white">
      <div class="schedule-title h5 fw-bold">
        title
      </div>
      <div class="schedule-day fw-semibold">Friday, 9 May 2025</div>
      <div class="schedule-time">07.00</div>
      <div class="fw-medium">Sisa Kuota <span class="kuota-left">1</span></div>
    </div>

    <div class="content-wrapper">
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active text-black" id="home-tab" data-bs-toggle="tab" data-bs-target="#book-tab-pane"
            type="button" role="tab" data-cy="tab-overview" aria-controls="book-tab-pane" aria-selected="true">
            Booking Baru
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link text-black" id="profile-tab" data-bs-toggle="tab" data-cy="tab-pdf"
            data-bs-target=" #member-tab-pane" type="button" role="tab" aria-controls="member-tab-pane"
            aria-selected="false">
            Member
          </button>
        </li>
      </ul>
      <div class="tab-content" id="myTabContent">
        <form method="post" action={{route('book.class')}} class="tab-pane overview-wrapper fade show active"
          id="book-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
          @csrf
          <div class="packet-wrapper">
            <div class="packget-card-wrapper d-flex flex-wrap gap-2 mt-2">
              <div class="spinner-border text-secondary" role="status">
                <span class="sr-only"></span>
              </div>
            </div>
          </div>

          <div class="form-wrapper mt-3">
            <input type="hidden" name="schedule_detail_id" class="schedule-detail-input">
            <div class="wrapper d-flex flex-wrap gap-2 col-12">
              <div class="mb-1 col-12">
                <label for="exampleInputEmail1" class="form-label">Nama</label>
                <input type="text" name="customer_name" required class="form-control" id="exampleInputEmail1"
                  aria-describedby="emailHelp" />
              </div>
              <div class="mb-1 col-12">
                <label for="exampleInputEmail1" class="form-label">Nomor WhatsApp</label>
                <input type="text" name="phone_num" required class="form-control" id="exampleInputEmail1"
                  aria-describedby="emailHelp" />
              </div>
              <div class="mb-1 col-12">
                <label for="exampleInputEmail1" class="form-label">Email (Opsional)</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1"
                  aria-describedby="emailHelp" />
              </div>
              <button type="submit"
                class="bg-pink text-white w-100 p-2 rounded border-0 btn-submit-book fw-medium mt-2 ">
                Submit
              </button>
            </div>
          </div>
        </form>
        <form method="post" action={{route('redeem.book.code')}} class="tab-pane fade document-link-wrapper"
          data-cy="wrapper-document-link" id="member-tab-pane" role="tabpanel" aria-labelledby="member-tab-pane"
          tabindex="0">
          <input type="hidden" name="schedule_detail_id" class="schedule-detail-input">
          @csrf
          @method('put')
          <div class="mb-1 col-12 mt-2">
            <label for="exampleInputEmail1" class="form-label">Kode Booking</label>
            <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" />
            <div class="form-text">Masukkan kode booking dari admin</div>
          </div>
          <button type="submit" class="bg-pink text-white w-100 p-2 rounded border-0 btn-submit-book fw-medium mt-2 ">
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
  $('.schedule-box').click(function(e) {
    $(".packget-card-wrapper").empty();

    let title = $(this).data('title');
    let classTypeID = $(this).data('class-type-id');
    let scheduleDate = $(this).data('schedule-date');
    let scheduleTime = $(this).data('schedule-time');
    let scheduleDetailID = $(this).data('schedule-detail-id');
    let scheduleDetailQuota = $(this).data('schedule-detail-quota');

    $('.schedule-title').html(title);
    $('.schedule-time').html(scheduleTime);
    $('.schedule-day').html(scheduleDate);
    $('.kuota-left').html(scheduleDetailQuota);
    $('.schedule-detail-input').val(scheduleDetailID);

    $.ajax({
        url:`/getPackagesByClassTypeID`,
        type:"Get",
        data: {
            class_type_id: classTypeID,
        },
        dataType:"json",
        beforeSend: function() {
          $(".packget-card-wrapper").append(`
              <div class="spinner-border col-12 text-center text-secondary" role="status">
                <span class="sr-only"></span>
              </div>`);
         },
        success:function(data){
          $(".packget-card-wrapper").empty();

          $.each(data, function(key, value){
            if(value.duration_unit == 'day'){
              value.duration_unit = 'hari'
            }else if(value.duration_unit == 'week'){
              value.duration_unit = 'minggu'
            }else if(value.duration_unit == 'month'){
              value.duration_unit = 'bulan'
            }else{
              value.duration_unit = 'tahun'
            }

            $(".packget-card-wrapper").append(`
              <div class="packet-card col-5 border py-2 px-2 rounded-2">
                <input type="radio" name="package_id" value=${value.id} />
                <div class="title fw-medium">${value.number_of_session}x ${value.class_type.name}</div>
                <div class="price mt-2">Rp.${value.price.toLocaleString('de-DE')}</div>
                ${value.number_of_session>1 ? `<div class="expired-desc text-secondary mt-2 fw-light">* Kode Booking Aktif selama ${value.duration} ${value.duration_unit}</div>` : ''}
              </div>
              `);
          });

          if(data.length > 0){
          $(".packet-card").click(function () {
          // Find and check the radio inside the clicked card
          const radio = $(this).find('input[type="radio"]');
          if (!radio.prop("checked")) {
            radio.prop("checked", true);
          }

          // Uncheck radios in other cards
          $(".packet-card")
            .not(this)
            .removeClass("active")
            .find('input[type="radio"]')
            .prop("checked", false);

          $(this).addClass("active");
        });
          }
        },
      });

    });
</script>

<script>
  $(document).ready(function () {

      });
</script>
@endpush