<table id="example2" class="table table-bordered table-hover">
    <thead>
    <tr>
        <th>نام و نام خانوادگی</th>
        <th>موبایل</th>
        <th>تعداد زیرمجموعه</th>
        {{-- <th>سطح</th> --}}
        @if(auth()->user()->hasRole(['مدیر','نماینده مستقل']) )
        <th>ایجاد</th>
        @endif
        <th>معرف</th>
        <th>مشتری معرف</th>
        @if(isset($action) && $action == 'true')
            <th>عملیات</th>
        @endif
    </tr>
    </thead>
    <tbody>
    @if(isset($items) && $items && $items->count() > 0)
        @foreach($items as $item)

            <tr>
                <td>
                    <a href="{{route('admin.customer.show',$item->id)}}">
                        @item($item->first_name) @item($item->last_name)
                    </a>
                    <br>
                    {{$item->getRoleNames()->first()}}
                </td>

                <td>@item($item->mobile)</td>
                <td>
                    {{$item->getSubCustomers()->count()}}
                </td>
               {{-- <th>{{$item->getUserLevelWithMe()}}</th> --}}

                @if(auth()->user()->hasRole(['مدیر','نماینده مستقل']) )
                    <td>
                        <a href="{{route('admin.user.show',$item->creator_id)}}">
                            {{ $item->creator ? $item->creator->full_name : '-' }}
                        </a>

                    </td>
                @endif

                <td>
                    @if(in_array(auth()->user()->id , [$item->reagent_id , $item->creator_id]))
                        خودم
                    @else
                        @if(auth()->user()->hasRole(['مدیر','نماینده مستقل']) && $item->reagent_id)

                            <a href="{{route('admin.user.show',$item->reagent_id)}}">
                                {{ $item->reagent ? $item->reagent->full_name : '-' }}
                            </a>

                        @else
                            {{ $item->reagent ? $item->reagent->full_name : '-' }}
                        @endif
                    @endif
                </td>

                <td>
                    @if($item->from_customer)
                        <a href="{{route('admin.user.show',$item->from_customer_id)}}">
                            {{  $item->from_customer->full_name  }}
                        </a>
                     @endif
                </td>
            @if(isset($action) && $action == 'true')
                    <td class="text-center">
                        <a href="{{route('admin.customer.show',$item->id)}}"
                           class="badge bg-info ml-1" title="پروفایل"><i class="fa fa-eye"></i>
                        </a>
                        <a href="{{route('admin.customer.edit',$item->id)}}"
                           class="badge bg-primary ml-1" title="ویرایش"><i
                                    class="fa fa-edit"></i> </a>
                        <a href="javascript:void(0);" onclick="del_row('{{$item->id}}')"
                           class="badge bg-danger ml-1" title="حذف"><i class="fa fa-trash"></i>
                        </a>


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