@extends('layouts.admin')
@section('css')
    <style>
        .dropdown-menu li a {
            color: rgba(0, 0, 0, 0.774);
        }
        .dropdown-menu li:hover {
            background: #2f665f;
        }
        .dropdown-menu li:hover a {
            color: white;
        }
    </style>
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header">

                            @if (auth()->user()->getRoleNames()->first()=='مدیر')
                                <div class="text-left">
                                    <a href="{{route('admin.package.create')}}" class="btn btn-secondary" >
                                        افزودن
                                    </a>
                                    {{-- <a class="btn btn-info" href="{{route('admin.service.create')}}">افزودن</a> --}}
                                    {{-- <a class="btn btn-info" href="{{route('admin.service.create','مشاوره خصوصی')}}">افزودن مشاوره خصوصی</a>
                                    <a class="btn btn-info" href="{{route('admin.service.create','عریضه نویسی')}}">افزودن عریضه نویسی</a>
                                    <a class="btn btn-info" href="{{route('admin.service.create','عقد قرارداد')}}">عقد قرارداد</a>
                                    <a class="btn btn-info" href="{{route('admin.service.create','استعدادیابی')}}">استعدادیابی</a> --}}
                                </div>
                            @endif
                        </div>
                        <div class="card-body res_table_in pt-0">
                            <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>عنوان</th>
                                    <th>وضعیت</th>
                                    <th>مقدار</th>
                                     <th>ترتیب</th>
                                     <th>تاریخ ایجاد</th>

                                    @if (auth()->user()->getRoleNames()->first()=='مدیر')
                                        <th>عملیات</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($items)>0)
                                    @foreach($items as $index=>$item)
                                        <tr>
                                            <td>{{$item->title}}</td>
                                            <td>{{$item->status}}</td>
                                            <td>{{$item->full_price}}</td>
                                            <td>{{$item->sort_by}}</td>
                                            <td>{{$item->created_at}}</td>

                                            @if (auth()->user()->getRoleNames()->first()=='مدیر')
                                                <td class="text-center">
                                                    <a href="{{route('admin.package.edit',$item->id)}}" class="badge bg-primary ml-1" title="ویرایش"><i class="fa fa-edit"></i> </a>
                                                    <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')" class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i> </a>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-center">موردی یافت نشد</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    <div class="pag_ul">
                        {{ $items->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

{{--    <div class="modal fade" id="filter" tabindex="-1" role="dialog" aria-labelledby="filterLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">فیلتر بر اساس گروه های ایجاد شده</h5>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <div class="dropdown">--}}
{{--                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
{{--                            @if(isset($category_id)) گروه {{App\Model\ServiceCat::where('id',$category_id)->first()->title}} @else فیلتر کردن گروه ها @endif--}}

{{--                        </button>--}}
{{--                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">--}}
{{--                            @foreach($ServiceCats as $ServiceCat)--}}
{{--                                <li style="padding: 6px;"><a class="text-dark" href="{{route('admin.service.list',['category_id'=>$ServiceCat->id])}}" title="Courses">{{$ServiceCat->title}}</a></li>--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="createLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog modal-lg" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title">انتخاب دسته اصلی جهت افزودن خدمت</h5>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    @foreach($ServiceCats as $ServiceCat)--}}
{{--                        <a class="btn btn-danger my-2 m-lg-2" href="{{route('admin.service.create',$ServiceCat->id)}}">--}}
{{--                            <img src="{{url('/').'/'.$ServiceCat->pic}}" alt="{{$ServiceCat->title}}" style="width:50px;height:50px;border-radius:50%;margin: 12px;"> <br>--}}
{{--                            {{$ServiceCat->title}}--}}
{{--                        </a>--}}
{{--                    @endforeach--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">انصراف</button>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

@endsection
@section('js')
    <script>
        function active_row(id,type) {
            if(type=='pending')
            {
                var text_user=' مشاوره آفلاین می شود';
            }
            if(type=='active')
            {
                var text_user=' مشاوره آنلاین می شود';
            }
            Swal.fire({
                title: text_user ,
                text: 'برای تغییر وضعیت مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/service-active/'+id+'/'+type;
                }
            })
        }
        function del_row(id) {
            Swal.fire({
                text: 'برای حذف مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/package-destroy/'+id;
                }
            })
        }
    </script>
@endsection
