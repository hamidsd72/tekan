@extends('layouts.admin')
@section('css')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-12">
                    <div class="card res_table">
                        <div class="card-header">
                            <h3 class="card-title float-right">{{$title2}}</h3>

                            @if(request()->route()->getName() == 'admin.user.potential.list' && auth()->user()->hasRole(['مدیر','نماینده مستقل']) )
                                <a data-toggle="modal" data-target="#add_new_user_to_potential" class="float-left btn btn-primary btn-sm text-white"><i
                                            class="fa fa-circle-o mtp-1 ml-1"></i>افزودن</a>
                            @else
                                <a href="{{route('admin.user.create')}}" class="float-left btn btn-info btn-sm"><i
                                            class="fa fa-circle-o mtp-1 ml-1"></i>افزودن</a>
                            @endif

                        </div>
                        <!-- /.card-header -->
                        <div class="card-body res_table_in">
                            @include('admin.user._table',['items'=>$items,'action'=>true])
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <div class="pag_ul">
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(count($items) >0)
        @foreach($items as $item)

            <div class="modal" id="role{{$item->id}}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{ Form::model($item,array('route' => array('admin.user-role.update'), 'method' => 'POST', 'files' => true)) }}
                        <div class="modal-header">
                            <h4 class="modal-title">تغییر رول کاربر</h4>
                            <button type="button" class="close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="{{$item->id}}">
                            <div class="form-group">
                                <label for="role_name">نوع رول</label>
                                <select id="role_name" name="role_name" class="form-control col-lg-6 col-8">
                                    @foreach (\App\Model\Role::roles() as $item)
                                        <option value="{{$item->name}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer row">
                            <div class="col">
                                {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12 ')) }}
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-danger col-12" data-dismiss="modal">بستن</button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>




        @endforeach

        @if(isset($potential) && isset($potentials) &&  isset($potentials) && auth()->user()->hasRole(['مدیر','نماینده مستقل']) )

            <div class="modal" id="add_new_user_to_potential">
                <div class="modal-dialog">
                    <div class="modal-content">
                        {{ Form::model($item,array('route' => array('admin.user-potential.update'), 'method' => 'POST', 'files' => true)) }}
                        <input type="hidden" name="potentials_id" value="{{$potential->id}}">
                        <input type="hidden" name="redirect_url" value="{{url()->current()}}">

                        <div class="modal-header">
                            <h4 class="modal-title"> افزودن کاربر به لیست
                            '{{$potential->name}}'
                            </h4>
                            <button type="button" class="close" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="id">انتخاب اعضاء</label>
                                <select id="id" name="id" class=" select2 ">
                                    @foreach ($users as $user)
                                        <option value="{{$user->id}}" >{{$user->full_name}} - {{$user->reagent_code}} - {{$user->mobile}} </option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="modal-footer row">
                            <div class="col">
                                {{ Form::button('افزودن', array('type' => 'submit', 'class' => 'btn btn-success col-12 ')) }}
                            </div>
                            <div class="col">
                                <button type="button" class="btn btn-danger col-12" data-dismiss="modal">بستن
                                </button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>

            @foreach($items as $item)




{{--                <div class="modal" id="tag{{$item->id}}">--}}
{{--                    <div class="modal-dialog">--}}
{{--                        <div class="modal-content">--}}
{{--                            {{ Form::model($item,array('route' => array('admin.user-potential.update'), 'method' => 'POST', 'files' => true)) }}--}}
{{--                            <div class="modal-header">--}}
{{--                                <h4 class="modal-title">تغییر لیست پتانسیل کاربر</h4>--}}
{{--                                <button type="button" class="close" data-dismiss="modal"></button>--}}
{{--                            </div>--}}
{{--                            <div class="modal-body">--}}
{{--                                <input type="hidden" name="id" value="{{$item->id}}">--}}
{{--                                <div class="form-group">--}}
{{--                                    <label for="potentials_id">لسیت پتانسیل</label>--}}
{{--                                    <select id="potentials_id" name="potentials_id[]" multiple--}}
{{--                                            class="form-control col-lg-6 col-8">--}}
{{--                                        @if(isset($potentials))--}}
{{--                                            @foreach ($potentials as $item)--}}
{{--                                                <option value="{{$item->id}}">{{$item->name}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}
{{--                                    </select>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="modal-footer row">--}}
{{--                                <div class="col">--}}
{{--                                    {{ Form::button('ویرایش', array('type' => 'submit', 'class' => 'btn btn-success col-12 ')) }}--}}
{{--                                </div>--}}
{{--                                <div class="col">--}}
{{--                                    <button type="button" class="btn btn-danger col-12" data-dismiss="modal">بستن</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            {{ Form::close() }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            @endforeach

        @endif
    @endif

@endsection
@section('js')
    <script>
        function active_row(id, type) {
            if (type == 'blocked') {
                var text_user = 'پنل کاربر مسدود می شود';
            }
            if (type == 'active') {
                var text_user = 'پنل کاربر فعال می شود';
            }
            Swal.fire({
                title: text_user,
                text: 'برای تغییر وضعیت کاربر مطمئن هستید؟',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    location.href = '{{url('/')}}/admin/user-active/' + id + '/' + type;
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
                    location.href = '{{url('/')}}/admin/user-destroy/' + id;
                }
            })
        }



    </script>
@endsection
