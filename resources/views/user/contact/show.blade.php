@extends('layouts.user')
@section('css_style') @endsection
@section('body')

<link rel="stylesheet" type="text/css" href="{{asset('user/front/index.css')}}"/>
<link rel="stylesheet" type="text/css" href="{{asset('user/front/contact.css')}}"/>

<nav aria-label="topbreadcrumb" class="topbreadcrumb">
    <div class="container">
        <ul class="breadcrumb p-2" style="background-color: #f8f8f8;">
            <li class="breadcrumb-item"><a href="{{url('/')}}"> {{__('text.page_name.home')}}</a></li>
            <li class="breadcrumb-item active" aria-current="page"> {{__('text.page_name.contact')}}</li>
        </ul>
    </div>
</nav>

<div class="sec_middle_title">
    <div class="py-4">
        <div class="d-flex justify-content-center" >
            <h1> {{__('text.page_name.contact')}}</h1>
        </div>
    </div>
</div>

    <section class="contact_us_area mt-lg-4">
        <div class="container">
            <div class="contact_us_inner container-fluid">
                <h4 >شرکت تکان انرژی</h4>
                <div class="row">
                    <div class="col-lg my-auto">
                        <iframe class="w-100 border-0" src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d1144.4793120349605!2d51.37950321709661!3d35.773499675383086!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1z2LPYudin2K_YqiDYotio2KfYryDYriDYudmE2KfZhdmHINis2YbZiNio24wg!5e0!3m2!1sfa!2sfr!4v1668922999913!5m2!1sfa!2sfr" allowfullscreen="allowfullscreen"></iframe>
                    </div>
                    <div class="col-lg p-3">
                        <div class="contact_us_details">
                            <div class="link_widget">
                                <ul>
                                    <li><a href="#"><i class="fas fa-map-marker-alt"></i>  تهران،سعادت اباد ،خ علامه جنوبی،کوچه غدیری،پ۷۹،واحد۴</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="far fa-envelope"></i>   info@tekan.com</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-fax"></i> ۸۸۵۷۷۴۳۹ </a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-phone"></i>۸۸۵۷۷۴۳۹ </a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-clock"></i>{{__('text.contact_1.clock')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-6 p-3">
                        <div class="contact_us_details">
                            <h4>{{__('text.contact_2.title')}}</h4>
                            <div>
                                <iframe class="w-100 border-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3239.410566971344!2d51.12159781525965!3d35.716119980186264!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8dee391cf04ae9%3A0xa83974de7a129e3d!2z2YbYp9ioINii2YHYsduM2YbYp9mG!5e0!3m2!1sen!2sfr!4v1575376910405!5m2!1sen!2sfr" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                            <div class="link_widget">
                                <ul>
                                    <li><a href="#"><i class="fas fa-map-marker-alt"></i>{{__('text.contact_2.address')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-fax"></i> {{__('text.contact_2.phone')}} | {{__('text.contact_2.fax')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="far fa-envelope"></i>  {{__('text.contact_2.email')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-map-marked-alt"></i> {{__('text.contact_2.postal')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-clock"></i>{{__('text.contact_2.clock')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 p-3">
                        <div class="contact_us_details">
                            <h4>{{__('text.contact_3.title')}}</h4>
                            <div>
                                <iframe class="w-100 border-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3239.3157456597155!2d51.41695121525991!3d35.71845248018563!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x8f65de239b0104ad!2zMzXCsDQzJzA2LjQiTiA1McKwMjUnMDguOSJF!5e0!3m2!1sen!2snl!4v1659366799392!5m2!1sen!2snl" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                            <div class="link_widget">
                                <ul>
                                    <li><a href="#"><i class="fas fa-map-marker-alt"></i>{{__('text.contact_3.address')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-phone"></i>{{__('text.contact_3.phone')}} | {{__('text.contact_3.fax')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="far fa-envelope"></i>{{__('text.contact_3.phone')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 p-3">
                        <div class="contact_us_details">
                            <h4>{{__('text.contact_4.title')}}</h4>
                            <div>
                                <iframe class="w-100 border-0" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1620.2334828453977!2d51.42625814201031!3d35.69012479504809!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3f8e018ec8397be7%3A0xa5dda3ea8df03e55!2z2YHYsdmI2LTar9in2Ycg2KrZiNin2YYg2YHZhtin2YjYsQ!5e0!3m2!1sfa!2sfr!4v1575456054053!5m2!1sfa!2sfr" allowfullscreen="allowfullscreen"></iframe>
                            </div>
                            <div class="link_widget">
                                <ul>
                                    <li><a href="#"><i class="fas fa-map-marker-alt"></i>{{__('text.contact_4.address')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="fas fa-phone"></i>{{__('text.contact_4.phone')}} | {{__('text.contact_4.fax')}}</a></li>
                                    <li><a href="#"><i aria-hidden="true" class="far fa-envelope"></i>{{__('text.contact_4.email')}}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>--}}

                </div>
                
                <form id="hafez_form" action="{{route('user.contact.store')}}" method="post" novalidate="novalidate">
                    @csrf
                    <h4 class="my-4"> {{__('text.page_name.contact')}}</h4>

                    <div class="row">

                        <div class="form-group col-lg-6">
                            <input type="text" style="height: 50px" class="form-control {{$errors->has('name')?'error_border':''}}" id="name" name="name"
                                    placeholder="* {{__('text.contact.frm_name')}}" value="{{old('name')??''}}">
                            <label class="err">{{$errors->has('name')?$errors->first('name'):''}}</label>
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="email" style="height: 50px" class="form-control d-ltr {{$errors->has('email')?'error_border':''}}" id="email" name="email"
                                    placeholder="{{__('text.contact.frm_email')}} *" value="{{old('email')??''}}">
                            <label class="err">{{$errors->has('email')?$errors->first('email'):''}}</label>
                        </div>
                        <div class="form-group col-lg-6">
                            <input type="text" style="height: 50px" pattern="[0-9]" class="form-control d-ltr {{$errors->has('mobile')?'error_border':''}}" id="mobile" name="mobile"
                                    placeholder="{{__('text.contact.frm_mobile')}} *" value="{{old('mobile')??''}}">
                            <label class="err">{{$errors->has('mobile')?$errors->first('mobile'):''}}</label>
                        </div>
                        <div class="form-group col-lg-6">
                            {{-- <select style="height: 50px" class="form-control {{$errors->has('part')?'error_border':''}}" id="part" name="part">
                                <option value="">* {{__('text.contact.frm_part')}}</option>
                                <option value="{{__('text.contact.frm_part_1')}}" {{old('part') && old('part')==__('text.contact.frm_part_1')?'selected':''}}>{{__('text.contact.frm_part_1')}}</option>
                                <option value="{{__('text.contact.frm_part_2')}}" {{old('part') && old('part')==__('text.contact.frm_part_2')?'selected':''}}>{{__('text.contact.frm_part_2')}}</option>
                                <option value="{{__('text.contact.frm_part_3')}}" {{old('part') && old('part')==__('text.contact.frm_part_3')?'selected':''}}>{{__('text.contact.frm_part_3')}}</option>
                                <option value="{{__('text.contact.frm_part_4')}}" {{old('part') && old('part')==__('text.contact.frm_part_4')?'selected':''}}>{{__('text.contact.frm_part_4')}}</option>
                            </select> --}}
                            <input type="text" style="height: 50px" class="form-control {{$errors->has('part')?'error_border':''}}" id="part" name="part"
                                    placeholder="{{__('text.contact.frm_subject')}}" value="{{old('part')}}">
                            <label class="err">{{$errors->has('part')?$errors->first('part'):''}}</label>
                        </div>

                    </div>

                    <div class="form-group col-12 px-0">
                        <textarea rows="6" class="form-control {{$errors->has('text')?'error_border':''}}" name="text" id="text" rows="1"
                                    placeholder="* {{__('text.contact.frm_msg')}}" >{{old('text')??''}}</textarea>
                        <label class="err">{{$errors->has('text')?$errors->first('text'):''}}</label>
                    </div>
                    <div class="form-group col-12 px-0 text-right">
                        <button class="g-recaptcha btn btn-gold px-4" data-sitekey="{{\App\Setting::find(1)->site_key}}" data-callback='onSubmit' data-action='submit'>{{__('text.contact.frm_btn')}}</button>
                        {{-- <button style="color: #ffffff;" class="btn btn-gold px-4" type="submit" value="submit">{{__('text.contact.frm_btn')}}</button> --}}
                    </div>
                </form>

            </div>
        </div>
    </section>

@endsection
