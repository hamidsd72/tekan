@extends('layouts.admin')
@section('content')
    <section class="container-fluid">
        <div class="col-md-10 col-lg-6 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header">{{' ویرایش '.$title2.' '.$id}}</div>
                <div class="card-body box-profile">
                    {{ Form::open(array('route' => 'admin.target.store', 'method' => 'POST', 'files' => true , 'id' => 'form_req' )) }}
                        @if ($id=='سیستمی')
                            <div class="form-group">
                                {{ Form::label('level', 'هدف لول ماه *') }}
                                {{ Form::select('level',
                                ['نمایده ۲ ستاره'=>'نمایده ۲ ستاره','نمایده ۳ ستاره'=>'نمایده ۳ ستاره','نمایده ۴ ستاره'=>'نمایده ۴ ستاره','نمایده مستقل'=>'نمایده مستقل'
                                ,'حامی نقرهای'=>'حامی نقرهای','حامی طلایی'=>'حامی طلایی','حامی پلاتین'=>'حامی پلاتین','حامی الماس'=>'حامی الماس'
                                ,'شبکه ساز نقره ای'=>'شبکه ساز نقره ای','شبکه ساز طلایی'=>'شبکه ساز طلایی'], $item?$item->level:null, array('class' => 'form-control')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('personal', 'هدف درآمد از فروش شخصی *') }}
                                {{ Form::number('personal',$item?$item->personal:null, array('class' => 'form-control','onkeyup'=>'number_price(this.value)', 'required')) }}
                                <span id="price_span" class="span_p"><span id="pp_price"></span> تومان </span>
                            </div>
                            <div class="form-group">
                                {{ Form::label('network', 'هدف درآمد از شبکه سازی *') }}
                                {{ Form::number('network',$item?$item->network:null, array('class' => 'form-control','onkeyup'=>'number_price2(this.value)', 'required')) }}
                                <span id="price_span" class="span_p"><span id="pp_price2"></span> تومان </span>
                            </div>
                        @else
                            <div class="form-group">
                                {{ Form::label('burning', 'هدف سوزان *') }}
                                {{ Form::text('burning',$item?$item->burning:null, array('class' => 'form-control' ,'required')) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('other', 'سایر اهداف *') }}
                                {{ Form::textarea('other',$item?$item->other:null, array('class' => 'form-control')) }}
                            </div>
                        @endif    
                            
                        {{ Form::button('افزودن یا ویرایش', array('type' => 'submit', 'class' => 'btn btn-success mt-3')) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </section>
@endsection
@section('js')
@endsection
