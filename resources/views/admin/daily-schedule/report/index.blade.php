@extends('layouts.admin')
@section('content')
    <section id="chart" class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                {{$title2}}
            </div>
            <div class="card-body">
                <div class="border-bottom pb-lg-3 mb-3 mx-lg-2">
                    <div class="row mb-0" style="direction: ltr;">
                        
                        <div class="col-auto mb-lg-auto">
                            <form action="{{route('admin.daily-schedule-report.filter')}}" id="form_req" method="get">
                                @csrf
                                <div class="row bg-cu" style="max-width: 400px;">
                                    <div class="col-auto p-0">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="newSearchBar()">جستجو</a>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="text" name="date" id="date" class="form-control text-center date_p" required readonly>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$id[0]}}">
                                </div>
                            </form>
                        </div>
                        
                        <div class="col"></div>
                        <div class="col-auto">
                            <h6 class="mx-1 font-weight-bold"> : گزارش عملکرد شخصی ۴×۱ اعضا لیست پتانسیل شخصی</h6>
                            <div class="my-3">
                                <h6 id="new_per1" class="text-danger">{{$list1[1]}}</h6>
                                <h6 id="new_per0" class="px-2 text-success">{{$list1[0]}}</h6>
                            </div>

                            <h6 class="mx-1 font-weight-bold"> : گزارش عملکرد شخصی ۴×۱ اعضا سازمان</h6>
                            <div class="my-3">
                                <h6 id="new_org1" class="text-danger">{{$list2[1]}}</h6>
                                <h6 id="new_org0" class="px-2 text-success">{{$list2[0]}}</h6>
                            </div>
                        </div>

                    </div>
                </div>
                
                {{-- <div class="my-5"></div> --}}

                <div class="border-bottom px-lg-3 pb-lg-2 mb-3 mx-lg-2">
                    <div class="row mb-0" style="direction: ltr;">
                        <div class="col p-0"></div>
                        <div class="col-auto p-0">
                            <div class="mb-2 row">
                                <div class="col-auto px-1">
                                <form action="{{route('admin.daily-schedule-report.show.users','active')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
                                    <input id="id" type="hidden" name="type" value="single">
                                    <input id="showStartDate" type="hidden" name="start_date" class="startDates" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                    <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                </form>
                                </div>
                                <div class="col px-0 pt-1">
                                    <h6 class="mx-1 font-weight-bold text-success"> : نقراتی که گزارش ثبت کردن (لیست پتانسیل شخصی)</h6>
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <div class="col-auto px-1">
                                <form action="{{route('admin.daily-schedule-report.show.users','deactive')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
                                    <input id="id" type="hidden" name="type" value="single">
                                    <input id="showStartDate" type="hidden" name="start_date" class="startDates" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                    <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                </form>
                                </div>
                                <div class="col px-0 pt-1">
                                    <h6 class="mx-1 font-weight-bold text-danger"> : نقراتی که گزارش ثبت نکردن (لیست پتانسیل شخصی)</h6>
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <div class="col-auto px-1">
                                <form action="{{route('admin.daily-schedule-report.show.users','active')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
                                    <input id="id" type="hidden" name="type" value="all">
                                    <input id="showStartDate" type="hidden" name="start_date" class="startDates" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                    <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                </form>
                                </div>
                                <div class="col px-0 pt-1">
                                    <h6 class="mx-1 font-weight-bold text-success"> : نقراتی که گزارش  ثبت کردن (اعضا سازمان)</h6>
                                </div>
                            </div>

                            <div class="mb-2 row">
                                <div class="col-auto px-1">
                                <form action="{{route('admin.daily-schedule-report.show.users','deactive')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
                                    <input id="id" type="hidden" name="type" value="all">
                                    <input id="showStartDate" type="hidden" name="start_date" class="startDates" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                    <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                </form>
                                </div>
                                <div class="col px-0 pt-1">
                                    <h6 class="mx-1 font-weight-bold text-danger"> : نقراتی که گزارش  ثبت نکردن (اعضا سازمان)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mx-2">
                    <div class="row" style="direction: ltr;">
                            
                        <div class="col-auto mb-lg-auto custom3">
                            <form action="{{route('admin.daily-schedule-report.filter')}}" id="form_req" method="get" >
                                @csrf
                                <div class="row bg-cu" >
                                    <div class="col-auto p-0">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="number" name="year" id="yearDate" placeholder="سال" value="{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}" class="form-control text-center max-w-lg-52" required>
                                    </div>
                                    <div class="col-auto p-0">
                                        <select name="month" id="monthDate" class="form-control max-w-lg-82">
                                            @for ($i = 1; $i < 13; $i++)
                                                <option value="{{$i}}" @if ($month==$i) selected @endif>{{num2months($i)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-lg-auto p-lg-0">
                                        <select name="label_en" id="label_en" class="form-control p-0 max-w-lg-100">
                                            <option value="communication" selected>گفتگو با محوریت توسعه ارتباطات</option>
                                            <option value="conversation">گفتگو با محوریت فروش یا مشتری مداری</option>
                                            <option value="networking">گفتگو با محوریت شبکه سازی</option>
                                            <option value="growth">گفتگو با محوریت رشد شخصی</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$id[0]}}">
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>

                <div class="col-12 chart-scrollable">
                    <div class="frame">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                
            </div>

        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('admin/js/chart.js')}}"></script>
    <script>
        let set_year    = '{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}';
        let set_month   = '{{$month}}';
        let my_data     = @json($items[0]);
        var myChart
        const labels = @json($items[1]);
        const data  = {
            labels: labels,
            datasets: [
                {
                    label: 'گزارش عملکرد شخصی ۴×۱ گفتگو با محوریت توسعه ارتباطات',
                    backgroundColor: '#7d5808',
                    borderColor: '#7d5808',
                    data: my_data,
                },
            ]
        };

        let options = {
            scales: {
                y: {
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        };

        const config = {type: 'line',data: data,options: options};
        myChart = new Chart(document.getElementById('myChart'),config);
        
        function searchBar(){
            var month   = document.getElementById('monthDate').value;
            var year    = document.getElementById('yearDate').value;
            var label_en= document.getElementById('label_en').value;

            var label   = 'گفتگو با محوریت رشد شخصی';
            if (label_en=='communication') {
                label   = 'گفتگو با محوریت توسعه ارتباطات';
            } else if (label_en=='conversation') {
                label   = 'گفتگو با محوریت فروش یا مشتری مداری';
            } else if (label_en=='networking') {
                label   = 'گفتگو با محوریت شبکه سازی';
            }

            console.log(label_en,label);
            if (parseInt(set_year) < parseInt(year)) {
                alert('سال وارد شده معتبر نیست');
            } else if( (parseInt(set_year) == parseInt(year)) && (parseInt(set_month) < parseInt(month)) ) {
                alert('ماه وارد شده معتبر نیست');
            } else {

                var url = `{{url("/admin/daily-schedule-report-filter")}}?id=${@json($id)}&year=${year}&month=${month}&label_en=${label_en}`;
                console.log(url)
                $.ajax({
                    type: "GET",
                    url:  url,
                    success: function(data_val) {
                        const labels = data_val.chart[1];
                        const data = {
                            labels: labels,
                            datasets: [
                                {
                                    label: `گزارش عملکرد شخصی ۴×۱ ${label}`,
                                    backgroundColor: '#7d5808',
                                    borderColor: '#7d5808',
                                    data: data_val.chart[0],
                                },
                            ]
                        };
            
                        let options = {
                            scales: {
                                y: {
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        };
                        
                        myChart.destroy();
                        const config = {type: 'line',data: data,options: options};
                        myChart = new Chart(document.getElementById('myChart'),config);
            
                        if (data_val.message) {
                            alert(data_val.message);
                        }
            
                    },
                    error: function() {
                        console.log(this.error);
                    }
                });
            
            }
        }

        function newSearchBar(){
            var date = document.getElementById('date').value;
            document.querySelectorAll('.startDates').forEach(start_date => { start_date.value = date });
            var url = `{{url("/admin/daily-schedule-report-filter")}}?id=${@json($id)}&date=${date}`;

            $.ajax({
                type: "GET",
                url:  url,
                success: function(data_val) {
                    if (data_val.list1) {
                        document.getElementById('new_per0').innerHTML  = data_val.list1[0];
                        document.getElementById('new_per1').innerHTML  = data_val.list1[1];
                    }

                    if (data_val.list2) {
                        document.getElementById('new_org0').innerHTML  = data_val.list2[0];
                        document.getElementById('new_org1').innerHTML  = data_val.list2[1];
                    }
        
                },
                error: function() {
                    console.log(this.error);
                }
            });
            
        }
    </script>
@endsection
