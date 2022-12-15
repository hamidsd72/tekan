@component('layouts.admin')
    @section('title') {{ $title }} @endsection
    @section('body')
        <style>
            .hide-for-excel{
                display: none !important;
            }
            .popover-content
            {
                text-align: justify;
                text-align-last: center;
                font-size: 12px;
                max-height: 250px;
                overflow-y: auto;
            }
        </style>
        <div class="condition pull-right w-100 mb-2">
            <div class="title">
                <h5><i class="fa fa-trello text-size-large ml-2"></i>{{ $title }}</h5>
            </div>
            <div class="p-3">
                <table class="table table-data table-togglable table-bordered pull-right w-100">
                    <thead>
                    <tr>
                        <th data-toggle="true">نام نام خانوادگی</th>
                        <th data-hide="phone">ایمیل</th>
                        <th data-hide="phone">موبایل</th>
                        <th data-hide="phone">عنوان</th>
                        {{-- <th data-hide="phone">بخش مربوطه</th> --}}
                        <th data-hide="phone">تاریخ ثبت</th>
{{--                        <th data-hide="phone">زبان سایت</th>--}}
                        <th data-hide="phone">متن</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->name }} @if($item->see == 0) <span style="color: red;">(جدید)</span>@endif</td>
                            <td dir="ltr" class="text-left">{{ $item->mobile }}</td>
                            <td dir="ltr" class="text-left">{{ $item->email }}</td>
                            <td>{{ $item->part }}</td>
                            <td>{{ my_jdate($item->created_at, 'Y/m/d') }}</td>
{{--                            <td>{{ $item->lang=='fa'?'فارسی':'انگلیسی' }}</td>--}}
                            <td>
                                <a href="javascript:void(0)" data-toggle="popover" data-placement="left" data-content="{{ $item->text }}"><i class="fa fa-eye ml-2"></i>مشاهده</a>
                            </td>
                        </tr>
                        @if($item->see==0)
                            <?php
                                $item->see = 1;
                                $item->update();
                            ?>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @endsection
@endcomponent
