@component('layouts.admin')
    @section('title') صفحه اصلی @endsection
    @section('body')


        <div class="condition table pull-right w-100">
            <div class="title">
                <h5><i class="fa fa-trello text-size-large ml-2"></i>آخرین فعالیت ها</h5>
            </div>
            <div class="p-3">
                <a href="{{ route('admin-activities') }}" class="btn btn-success mb-2"><i class="fa fa-eye ml-2"></i>مشاهده
                    تمام فعالیت ها</a>
                <table class="table-data table-togglable table-bordered pull-right w-100">
                    <thead>
                    <tr>
                        <th data-toggle="true">نام کاربر</th>
                        <th data-hide="phone">فعالیت</th>
                        <th data-hide="phone">تاریخ ثبت</th>
                    </tr>
                    </thead>
                    <tbody> 
                    @foreach($activities as $item)
                        <tr>
                            <td>{{ $item->user->name }}</td>
                            <td>{{ $item->text }}</td>
                            <td>{{ my_jdate($item->created_at, 'Y/m/d H:i') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endsection
@endcomponent
