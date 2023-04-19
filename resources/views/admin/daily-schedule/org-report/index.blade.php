@extends('layouts.admin')
@section('content')
    <section id="chart" class="container-fluid">
        <div class="card res_table">
            <div class="card-header">
                {{$title2}}
            </div>
            
            <div class="card-body">
                <div class="border-bottom mx-lg-2">
                    <div class="row mb-0" style="direction: ltr;">
                        
                        <div class="col-auto my-auto">
                            <form action="{{route('admin.daily-schedule-report.filter')}}" id="form_req" method="get">
                                @csrf
                                <div class="row bg-cu" style="max-width: 400px;">
                                    <div class="col-auto p-0">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="newSearchBar()">جستجو</a>
                                    </div>
                                    <div class="col p-0">
                                        <input type="text" name="date" id="date" class="form-control date_p" required readonly>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$id[0]}}">
                                </div>
                            </form>
                        </div>
                        
                        <div class="col"></div>
                        <div class="col-auto my-auto">
                            {{-- <div class="mb-2 d-flex">
                                <h6 id="new_per1" class="text-danger">{{$list1[1]}}</h6>
                                <h6 id="new_per0" class="px-2 text-success">{{$list1[0]}}</h6>
                                <h6 class="mx-1"> : گزارش عملکرد شخصی ۴×۱ اعضا لیست پتانسیل شخصی</h6>
                            </div> --}}
                            <h6 class="mx-1 font-weight-bold"> : گزارش عملکرد شخصی ۴×۱ اعضا سازمان</h6>
                            <h6 id="new_org0" class="px-2 text-success">{{$list2[0]}}</h6>
                            <h6 id="new_org1" class="text-danger">{{$list2[1]}}</h6>
                        </div>

                    </div>
                </div>
                
                <div class="border-bottom px-lg-3 py-lg-2 mb-3 mx-lg-2">
                    <div class="row mb-0" style="direction: ltr;">
                        <div class="col p-0"></div>
                        <div class="col-auto p-0">
                            <div class="mb-2 row">
                                <div class="col-auto px-1">
                                <form action="{{route('admin.daily-schedule-org-report.show.users','active')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
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
                                <form action="{{route('admin.daily-schedule-org-report.show.users','deactive')}}" method="post">
                                    @csrf
                                    <input id="id" type="hidden" name="id" value="{{$id[0]}}">
                                    <input id="showStartDate" type="hidden" name="start_date" class="startDates" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                    <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                </form>
                                </div>
                                <div class="col px-0 pt-1">
                                    <h6 class="mx-1 font-weight-bold text-danger"> : نقراتی که گزارش ثبت نکردن (لیست پتانسیل شخصی)</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-0" style="direction: ltr;">
                    <div class="col-auto mb-lg-auto">
                        <form action="{{route('admin.daily-schedule-report.filter')}}" id="form_req" method="get">
                            @csrf
                            <div class="row bg-cu" style="max-width: 400px;">
                                <div class="col-auto p-0">
                                    <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
                                </div>
                                <div class="col-auto p-0">
                                    <input type="number" name="year" id="yearDate" placeholder="سال" value="{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}" class="form-control text-center" required>
                                </div>
                                <div class="col-auto p-0">
                                    <select name="month" id="monthDate" class="form-control" style="max-width: 100px;">
                                        @for ($i = 1; $i < 13; $i++)
                                            <option value="{{$i}}" @if ($month==$i) selected @endif>{{num2months($i)}}</option>
                                        @endfor
                                    </select>
                                </div>
                                {{-- <div class="col-auto p-0">
                                    <select name="label_en" id="label_en" class="form-control" style="max-width: 100px;">
                                        @foreach ($labels as $key => $label)
                                            <option value="{{$label->id}}" @if($key==0) selected @endif>{{$label->label}}</option>
                                        @endforeach
                                    </select>
                                </div> --}}
                                <input type="hidden" name="id" id="id" value="{{$id[0]}}">
                            </div>
                        </form>
                    </div>
                </div>


                <div class="col-12 chart-scrollable">
                    {{-- <canvas id="myChart"></canvas>
                </div>
                <div class="col-12 mt-5"> --}}
                    <div class="frame">
                        <canvas id="myBarChart"></canvas>
                    </div>
                </div>
                
            </div>

        </div>
    </section>
@endsection
@section('js')
    <script src="{{asset('admin/js/chart.js')}}"></script>
    
    <script>
        let set_year  = '{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}';
        let set_month = '{{$month}}';
        var myBarChart

        const barLabels = @json($items[0]);
        const barData = {
            labels: barLabels,
            datasets: [{
                label: `گزارش عملکرد شخصی ۴×۱ سازمانی`,
                data: @json($items[1]),
                backgroundColor: [
                    'rgba(153, 102, 255, 0.2)',
                ],
                borderColor: [
                    'rgb(153, 102, 255)',
                ],
                borderWidth: 1
            }]
        };
        
        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            },
        };

        myBarChart = new Chart(document.getElementById('myBarChart'),barConfig);

        function newSearchBar(){
            var month   = document.getElementById('monthDate').value;
            var year    = document.getElementById('yearDate').value;
            var date    = document.getElementById('date').value;
            document.querySelectorAll('.startDates').forEach(start_date => { start_date.value = date });

            if (parseInt(set_year) < parseInt(year)) {
                alert('سال وارد شده معتبر نیست');
            } else if( (parseInt(set_year) == parseInt(year)) && (parseInt(set_month) < parseInt(month)) ) {
                alert('ماه وارد شده معتبر نیست');
            } else {

                var url = `{{url("/admin/daily-schedule-org-report-filter")}}?id=${@json($id)}&date=${date}`;
                $.ajax({
                    type: "GET",
                    url:  url,
                    success: function(data_val) {
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

        }

        function searchBar(){
            var month   = document.getElementById('monthDate').value;
            var year    = document.getElementById('yearDate').value;

            if (parseInt(set_year) < parseInt(year)) {
                alert('سال وارد شده معتبر نیست');
            } else if( (parseInt(set_year) == parseInt(year)) && (parseInt(set_month) < parseInt(month)) ) {
                alert('ماه وارد شده معتبر نیست');
            } else {

                var url = `{{url("/admin/daily-schedule-org-report-filter")}}?id=${@json($id)}&year=${year}&month=${month}`;
                $.ajax({
                    type: "GET",
                    url:  url,
                    success: function(data_val) {

                        const barData = {
                            labels: barLabels,
                            datasets: [{
                                label: `گزارش عملکرد شخصی ۴×۱ سازمانی`,
                                data: data_val.chart[1],
                                backgroundColor: [
                                    'rgba(153, 102, 255, 0.2)',
                                ],
                                borderColor: [
                                    'rgb(153, 102, 255)',
                                ],
                                borderWidth: 1
                            }]
                        };
                        
                        myBarChart.destroy();
                        const barConfig = {
                            type: 'bar',
                            data: barData,
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                }
                            },
                        };
                        myBarChart = new Chart(document.getElementById('myBarChart'),barConfig);
            
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
    </script>
@endsection

{{-- <script>
    let set_year  = '{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}';
    let set_month = '{{$month}}';
    var myChart
    const labels = @json($items[1]);
    const data  = {
        labels: labels,
        datasets: [
            {
                label: 'گزارش عملکرد شخصی ۴×۱ سازمانی',
                backgroundColor: '#7d5808',
                borderColor: '#7d5808',
                data: @json($items[0]),
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

        if (parseInt(set_year) < parseInt(year)) {
            alert('سال وارد شده معتبر نیست');
        } else if( (parseInt(set_year) == parseInt(year)) && (parseInt(set_month) < parseInt(month)) ) {
            alert('ماه وارد شده معتبر نیست');
        } else {

            var url = `{{url("/admin/daily-schedule-org-report-filter")}}?id=${@json($id)}&year=${year}&month=${month}&label=${label_en}`;
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

                    const labels = data_val.chart[1];
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: `گزارش عملکرد شخصی ۴×۱ سازمانی`,
                                backgroundColor: '#7d5808',
                                borderColor: '#7d5808',
                                data: data_val.chart[0].slice(1, (data_val.chart[0].length)),
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
</script> --}}