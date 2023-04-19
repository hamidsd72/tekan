@extends('layouts.admin')
@section('content')
  <section class="container-fluid">
    {{ Form::open(array('route' => 'admin.potential-list.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req')) }}

      <div class="card card-primary card-outline">
        <div class="card-header"><h5>درج مشخصات کاربری</h5></div>
        <div class="card-body box-profile">
          
          <div class="row mb-0">

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                {{ Form::label('first_name', 'نام *') }}
                {{ Form::text('first_name',null, array('class' => 'form-control' ,'required')) }}
              </div>
            </div>
  
            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                {{ Form::label('last_name', 'نام خانوادگی *') }}
                {{ Form::text('last_name',null, array('class' => 'form-control' ,'required')) }}
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                {{ Form::label('mobile', 'نام کاربری *') }} (شماره موبایل)
                {{ Form::number('mobile',null, array('class' => 'form-control' ,'required')) }}
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                {{ Form::label('whatsapp', 'شماره موبایل دوم *') }}
                {{ Form::number('whatsapp', null, array('class' => 'form-control' , 'required')) }}
              </div>
            </div>

            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                {{ Form::label('password', 'رمز عبور پیشفرض') }}
                {{ Form::text('password','password1234', array('class' => 'form-control' ,'readonly', 'required')) }}
              </div>
            </div>
            
          </div>

          <div id="next" class="row mb-0 d-none">

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

              <input type="hidden" name="present_ta_peresent" id="present_ta_peresent_data" value="خرید اولیه انجام نشده">
            </div>

        </div>
      </div>

      {{ Form::button('ذخیره و مرحله بعد', array('type' => 'submit', 'class' => 'btn btn-success')) }}
    {{ Form::close() }}
  </section>
@endsection
@section('js')
<script>
  function ptp(val) {
    console.log('present_ta_peresent : ',val);
    document.getElementById("present_ta_peresent_data").value = val;
    if(val=='خرید اولیه انجام شده') {
      document.getElementById("present_ta_peresent11").classList.add('d-none');
      document.getElementById("present_ta_peresent1text").classList.remove('d-none');
    }
  }
</script>
@endsection

