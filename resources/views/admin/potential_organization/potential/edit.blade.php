@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    {{ Form::model( $item , array('route' => array('admin.potential-list.update', $item->id), 'method' => 'PATCH', 'files' => true , 'id' => 'form_req')) }}
      
      <div class="card card-primary card-outline">
        <div class="card-header"><h5>درج مشخصات کاربری</h5></div>
        <div class="card-body box-profile">
            <div class="row my-0">

              <div class="col-md-6 col-lg-3">
                <div class="form-group">
                  {{ Form::label('first_name', 'نام *') }}
                  {{ Form::text('first_name',$item->user->first_name, array('class' => 'form-control' ,'readonly')) }}
                </div>
              </div>
    
              <div class="col-md-6 col-lg-3">
                <div class="form-group">
                  {{ Form::label('last_name', 'نام خانوادگی *') }}
                  {{ Form::text('last_name',$item->user->last_name, array('class' => 'form-control' ,'readonly')) }}
                </div>
              </div>

              <div class="col-md-6 col-lg-3">
                <div class="form-group">
                  {{ Form::label('mobile', 'نام کاربری *') }} (شماره موبایل)
                  {{ Form::number('mobile',$item->user->mobile, array('class' => 'form-control' ,'readonly')) }}
                </div>
              </div>

              <div class="col-md-6 col-lg-3">
                <div class="form-group">
                  {{ Form::label('whatsapp', 'شماره موبایل دوم *') }}
                  {{ Form::number('whatsapp',$item->user->whatsapp, array('class' => 'form-control' ,'readonly')) }}
                </div>
              </div>
              
            </div>
            <div class="row my-0 d-none" id="others">

              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('hadaf_gozari_shakhsi', 'هدف گذاری فروش شخصی') }}
                  {{ Form::select('hadaf_gozari_shakhsi', [''=>'انتخاب کنید','1500000'=>'+1,500,000','3000000'=>'+3,000,000','6000000'=>'+6,000,000','10000000'=>'+10,000,000'], null, array('class' => 'form-control')) }}
                </div>
              </div>
              
              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('hadaf_gozari_level', 'هدف گذاری لول ماه') }}
                  {{ Form::select('hadaf_gozari_level',
                    [''=>'انتخاب کنید','نمایده ۲ ستاره'=>'نمایده ۲ ستاره','نمایده ۳ ستاره'=>'نمایده ۳ ستاره','نمایده ۴ ستاره'=>'نمایده ۴ ستاره','نمایده مستقل'=>'نمایده مستقل'
                    ,'حامی نقرهای'=>'حامی نقرهای','حامی طلایی'=>'حامی طلایی','حامی پلاتین'=>'حامی پلاتین','حامی الماس'=>'حامی الماس'
                    ,'شبکه ساز نقره ای'=>'شبکه ساز نقره ای','شبکه ساز طلایی'=>'شبکه ساز طلایی'], null, array('class' => 'form-control')) }}
                </div>
              </div>

              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('kasb_o_kar_kochak_ya_bozorg', 'کسب و کار کوچک یا بزرگ') }}
                  {{ Form::select('kasb_o_kar_kochak_ya_bozorg', [''=>'انتخاب کنید','کوچک'=>'کوچک','بزرگ'=>'بزرگ'], null, array('class' => 'form-control')) }}
                </div>
              </div>
            
              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('folowe_ya_4eqdam', 'فالویی یا چهار اقدام') }}
                  {{ Form::select('folowe_ya_4eqdam', [''=>'انتخاب کنید','فالویی'=>'فالویی','چهار اقدام'=>'چهار اقدام'], null, array('class' => 'form-control')) }}
                </div>
              </div>

              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('hadaf_jam_daramad_mah', 'هدف جمع درآمد') }}
                  {{ Form::number('hadaf_jam_daramad_mah', null, array('class' => 'form-control','digits','onkeyup'=>'number_price(this.value)' )) }}
                  <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                </div>
              </div>
            
              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('candid_shabakesazi', 'کاندید تندیس شبکه سازی') }}
                  {{ Form::select('candid_shabakesazi', [''=>'انتخاب کنید','برنزی'=>'برنزی','نقره ای'=>'نقره ای','طلایی'=>'طلایی'], null, array('class' => 'form-control')) }}
                </div>
              </div>

              <div class="col-md-8 col-lg-4">
                <div class="form-group">
                  {{ Form::label('candid_forosh', 'کاندیس تندیس فروش') }}
                  {{ Form::select('candid_forosh', [''=>'انتخاب کنید','برنزی'=>'برنزی','نقره ای'=>'نقره ای','طلایی'=>'طلایی'], null, array('class' => 'form-control')) }}
                </div>
              </div>

            </div>
        </div>
      </div>

      <div class="card card-primary card-outline">
        <div class="card-header">فرآیند پرزنت تا پرزنت</div>
        <div class="card-body box-profile">
            
            <div class="row my-0">
              <div class="col-md-8 col-lg-4" id="present_ta_peresent1">
                <div class="form-group">
                  {{ Form::label('present_ta_peresent', 'خرید اولیه') }}
                  {{ Form::select('ptop', [''=>'خرید اولیه انجام نشده','خرید اولیه انجام شده'=>'خرید اولیه انجام شده'],
                   null, array('class' => 'form-control', 'onchange' => 'ptp(this.value)', 'id' => 'present_ta_peresent11')) }}
                  {{ Form::text('present_ta_peresent','خرید اولیه انجام شده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_peresent1text')) }}
                </div>
              </div>

              <div class="col-md-8 col-lg-4 d-none" id="present_ta_peresent2">
                <div class="form-group">
                  {{ Form::label('present_ta_peresent', 'آموزش fast start') }}
                  {{ Form::select('ptop', [''=>'آموزش فست استارت ندیده','آموزش فست استارت دیده'=>'آموزش فست استارت دیده'],
                   null, array('class' => 'form-control', 'onchange' => 'ptp(this.value)', 'id' => 'present_ta_peresent12')) }}
                  {{ Form::text('present_ta_peresent','آموزش فست استارت دیده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_peresent2text')) }}
                </div>
              </div>

              <div class="col-md-8 col-lg-4 d-none" id="present_ta_peresent3">
                <div class="form-group">
                  {{ Form::label('present_ta_peresent', 'ست شدن اولین پرزنت') }}
                  {{ Form::select('ptop', ['آموزش فست استارت دیده'=>'اولین پرزنت ست نشده','اولین پرزنت ست شده'=>'اولین پرزنت ست شده'],
                   null, array('class' => 'form-control', 'onchange' => 'ptp(this.value)', 'id' => 'present_ta_peresent13')) }}
                  {{ Form::text('present_ta_peresent','اولین پرزنت ست شده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_peresent3text')) }}
                </div>
              </div>

              <input type="hidden" name="present_ta_peresent" id="present_ta_peresent_data" value="خرید اولیه انجام نشده">
            </div>

        </div>
      </div>

      <div class="card card-primary card-outline d-none" id="nextSection">
        <div class="card-body box-profile">فرآیند پرزنت تا استیج</div>
        <div class="card-body box-profile">
          <div class="row my-0">

            <div class="col-md-8 col-lg-4" id="present_ta_estage0">
              <div class="form-group">
                {{ Form::label('present_ta_peresent', 'اولین ورودی') }} <span class="h5">شبکه ساز</span>
                {{ Form::select('present_ta_estage', [''=>'اولین ورودی گرفته نشده','اولین ورودی گرفته شده'=>'اولین ورودی گرفته شده'],
                 null, array('class' => 'form-control ', 'onchange' => 'pts(this.value)', 'id' => 'present_ta_estage10')) }}
                {{ Form::text('present_ta_estage','اولین ورودی گرفته شده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_estage0text')) }}
              </div>
            </div>

            <div class="col-md-8 col-lg-4 d-none" id="present_ta_estage1">
              <div class="form-group">
                {{ Form::label('present_ta_estage', 'آموزش پشتیبان') }}
                {{ Form::select('present_ta_estage', ['اولین ورودی گرفته شده'=>'آموزش پشتیبان ندیده','آموزش پشتیبان دیده'=>'آموزش پشتیبان دیده'],
                 null, array('class' => 'form-control ', 'onchange' => 'pts(this.value)', 'id' => 'present_ta_estage11')) }}
                {{ Form::text('present_ta_estage','آموزش پشتیبان دیده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_estage1text')) }}
              </div>
            </div>

            <div class="col-md-8 col-lg-4 d-none" id="present_ta_estage2">
              <div class="form-group">
                {{ Form::label('present_ta_estage', 'دومین ورودی') }} <span class="h5">شبکه ساز</span>
                {{ Form::select('present_ta_estage', ['آموزش پشتیبان دیده'=>'دومین ورودی گرفته نشده','دومین ورودی گرفته شده'=>'دومین ورودی گرفته شده'],
                 null, array('class' => 'form-control ', 'onchange' => 'pts(this.value)', 'id' => 'present_ta_estage12')) }}
                {{ Form::text('present_ta_estage','دومین ورودی گرفته شده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_estage2text')) }}
              </div>
            </div>

            <div class="col-md-8 col-lg-4 d-none" id="present_ta_estage3">
              <div class="form-group">
                {{ Form::label('present_ta_estage', 'آموزش همانندسازی') }}
                {{ Form::select('present_ta_estage', ['دومین ورودی گرفته شده'=>'آموزش همانندسازی ندیده','آموزش همانندسازی دیده'=>'آموزش همانندسازی دیده'],
                 null, array('class' => 'form-control ', 'onchange' => 'pts(this.value)', 'id' => 'present_ta_estage13')) }}
                {{ Form::text('present_ta_estage','آموزش همانندسازی دیده', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_estage3text')) }}
              </div>
            </div>

            <div class="col-md-8 col-lg-4 d-none" id="present_ta_estage4">
              <div class="form-group">
                {{ Form::label('present_ta_estage', '۴ تا تیم اکتیو') }} <span class="h6">شبکه ساز</span>
                {{ Form::select('present_ta_estage', ['آموزش همانندسازی دیده'=>'۴ تا تیم اکتیو ندارد','۴ تا تیم اکتیو دارد'=>'۴ تا تیم اکتیو دارد'],
                 null, array('class' => 'form-control ', 'onchange' => 'pts(this.value)', 'id' => 'present_ta_estage14')) }}
                {{ Form::text('present_ta_estage','۴ تا تیم اکتیو دارد', array('class' => 'form-control d-none' ,'readonly', 'id' => 'present_ta_estage4text')) }}
              </div>
            </div>

            <input type="hidden" name="present_ta_estage" id="present_ta_estage_data" value="">
          </div>
          
        </div>
      </div>

      <div class="mt-4">
        <p class="text-info my-2 d-none" id="msg">با کلیک بر روی دکمه پایین این بخش تکمیل میشود و قابل ویرایش نیست</p>
        {{ Form::button('ذخیره', array('type' => 'submit', 'class' => 'btn btn-success mx-2')) }}
        <a href="{{route('admin.potential-list.index')}}" class="btn btn-secondary my-0">رفتن به صفحه قبلی</a>
      </div>
    {{ Form::close() }}

  </section>
@endsection
@section('js')
<script>
  function number_price(a){
      $('#pp_price').text(a);
      $('#pp_price_1').text(a);
      $('#pp_price').text(function (e, n) {
          var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return lir1;
      })
  }
  $(document).ready(function () {
      var a=$('#price').val();
      $('#pp_price').text(a);
      $('#pp_price_1').text(a);
      $('#pp_price').text(function (e, n) {
          var lir1= n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return lir1;
      })
  });

  function ptp(val) {
    console.log('present_ta_peresent : ',val);
    document.getElementById("present_ta_peresent_data").value = val;

    if (val=='اولین پرزنت ست شده') {
      document.getElementById("present_ta_peresent2").classList.remove('d-none');
      document.getElementById("present_ta_peresent3").classList.remove('d-none');
      document.getElementById("present_ta_peresent1text").classList.remove('d-none');
      document.getElementById("present_ta_peresent2text").classList.remove('d-none');
    } else if (val=='آموزش فست استارت دیده') {
      document.getElementById("present_ta_peresent12").classList.add('d-none');
      document.getElementById("present_ta_peresent2text").classList.remove('d-none');
      document.getElementById("present_ta_peresent3").classList.remove('d-none');
      document.getElementById("msg").classList.remove('d-none');
    } else if(val=='خرید اولیه انجام شده') {
      document.getElementById("present_ta_peresent11").classList.add('d-none');
      document.getElementById("present_ta_peresent1text").classList.remove('d-none');
      document.getElementById("present_ta_peresent2").classList.remove('d-none');
      document.getElementById("others").classList.remove('d-none');
    }
  }

  function pts(val) {
    console.log('present_ta_estage : ',val);
    document.getElementById("present_ta_estage_data").value = val;

    if(val=='آموزش همانندسازی دیده') {
      document.getElementById("present_ta_estage13").classList.add('d-none');
      document.getElementById("present_ta_estage3text").classList.remove('d-none');
      document.getElementById("present_ta_estage4").classList.remove('d-none');
      document.getElementById("msg").classList.remove('d-none');
    } else if(val=='دومین ورودی گرفته شده') {
      document.getElementById("present_ta_estage12").classList.add('d-none');
      document.getElementById("present_ta_estage2text").classList.remove('d-none');
      document.getElementById("present_ta_estage3").classList.remove('d-none');
    } else if(val=='آموزش پشتیبان دیده') {   
      document.getElementById("present_ta_estage11").classList.add('d-none');
      document.getElementById("present_ta_estage1text").classList.remove('d-none');
      document.getElementById("present_ta_estage2").classList.remove('d-none');
    } else if(val=='اولین ورودی گرفته شده') {
      document.getElementById("present_ta_estage10").classList.add('d-none');
      document.getElementById("present_ta_estage0text").classList.remove('d-none');
      document.getElementById("present_ta_estage1").classList.remove('d-none');
    }
  }     

  let step = @json($step);
  console.log('step: ',step);
  if (step>1) { ptp('خرید اولیه انجام شده'); }
  if (step>2) { ptp('آموزش فست استارت دیده'); }
  if (step>3) {
    ptp('اولین پرزنت ست شده');
    document.getElementById("present_ta_peresent13").classList.add('d-none');
    document.getElementById("present_ta_peresent3text").classList.remove('d-none');
    document.getElementById("nextSection").classList.remove('d-none');
    document.getElementById("present_ta_estage_data").value = 'اولین ورودی گرفته نشده';
    document.getElementById("msg").classList.add('d-none');
  }
  if (step>4) { pts('اولین ورودی گرفته شده'); }
  if (step>5) { pts('آموزش پشتیبان دیده'); }
  if (step>6) { pts('دومین ورودی گرفته شده'); }
  if (step>7) { pts('آموزش همانندسازی دیده'); }
  if (step>8) { 
    document.getElementById("msg").classList.add('d-none');
    document.getElementById("present_ta_estage14").classList.add('d-none');
    document.getElementById("present_ta_estage4text").classList.remove('d-none');
  }
</script>
@endsection


