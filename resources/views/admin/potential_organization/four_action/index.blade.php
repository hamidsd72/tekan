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
                            <form action="{{route('admin.four_action.custom.filter')}}" id="form_req" method="get">
                                @csrf
                                <input type="hidden" name="type" value="text">
                                <div class="row bg-cu" style="max-width: 300px;">
                                    <div class="col-auto p-0">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="text" name="end_date" id="endDate" placeholder="بازه پایان" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="text" name="start_date" id="startDate" placeholder="بازه شروع" value="{{num2fa($start)}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$id}}">
                                </div>
                            </form>
                        </div>
                        
                        <div class="col"></div>
                        <div class="col-auto">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="mb-2 d-flex">
                                    <h6 class="mx-3" id="new_added_org{{$i}}">{{$org_sumRow[$i]}}</h6>
                                    <h6 id="new_added{{$i}}">{{$sumRow[$i]}}</h6>
                                    <h6 class="mx-1 font-weight-bold"> : {{$nameRow[$i]}}</h6>
                                </div>
                            @endfor
                        </div>

                    </div>
                </div>
                
                <div class="border-bottom pb-lg-3 mb-3 mx-lg-2">
                    <div class="row mb-0" style="direction: ltr;">
                        
                        <div class="col p-0"></div>
                        <div class="col-auto p-0">
                            @if ($is_submit[0])
                                <div class="mb-2 row">
                                    <div class="col-auto px-1">
                                    <form action="{{route('admin.four_action.userslist.show','is_submit')}}" method="get">
                                        @csrf
                                        <input id="id" type="hidden" name="id" value="{{$id}}">
                                        <input id="id" type="hidden" name="data" value="four_action">
                                        <input id="showStartDate" type="hidden" name="start_date" value="{{num2fa($start)}}">
                                        <input id="showEndDate" type="hidden" name="end_date" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                        <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                    </form>
                                    </div>
                                    <div class="col px-0 pt-1">
                                        <div class="d-flex">
                                            <h6 id="is_submit_0">{{$is_submit[0]}}</h6>
                                            <h6 class="mx-1 font-weight-bold"> : افرادی که گزارش ثبت کردن</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                 
                            @if ($is_submit[1])
                                <div class="mb-2 row">
                                    <div class="col-auto px-1">
                                        <form action="{{route('admin.four_action.userslist.show','is_not_submit')}}" method="get">
                                            @csrf
                                            <input id="id" type="hidden" name="id" value="{{$id}}">
                                            <input id="id" type="hidden" name="data" value="four_action">
                                            <input id="showStartDate" type="hidden" name="start_date" value="{{num2fa($start)}}">
                                            <input id="showEndDate" type="hidden" name="end_date" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                            <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                        </form>
                                    </div>
                                    <div class="col px-0 pt-1">
                                        <div class="d-flex">
                                            <h6 id="is_submit_1">{{$is_submit[1]}}</h6>
                                            <h6 class="mx-1 font-weight-bold"> : افرادی که گزارش ثبت نکردند</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($usersSendDailyWork[0])
                                <div class="mb-2 row">
                                    <div class="col-auto px-1">
                                    <form action="{{route('admin.four_action.userslist.show','is_submit')}}" method="get">
                                        @csrf
                                        <input id="id" type="hidden" name="id" value="{{$id}}">
                                        <input id="id" type="hidden" name="data" value="daily_work">
                                        <input id="showStartDate" type="hidden" name="start_date" value="{{num2fa($start)}}">
                                        <input id="showEndDate" type="hidden" name="end_date" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                        <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                    </form>
                                    </div>
                                    <div class="col px-0 pt-1">
                                        <div class="d-flex">
                                            <h6 id="usersSendDailyWork_0">{{$usersSendDailyWork[0]}}</h6>
                                            <h6 class="mx-1 font-weight-bold"> : افرادی که تاییدیه لیست پتانسیل ثبت کردن</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                                 
                            @if ($usersSendDailyWork[1])
                                <div class="mb-2 row">
                                    <div class="col-auto px-1">
                                        <form action="{{route('admin.four_action.userslist.show','is_not_submit')}}" method="get">
                                            @csrf
                                            <input id="id" type="hidden" name="id" value="{{$id}}">
                                            <input id="id" type="hidden" name="data" value="daily_work">
                                            <input id="showStartDate" type="hidden" name="start_date" value="{{num2fa($start)}}">
                                            <input id="showEndDate" type="hidden" name="end_date" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}">
                                            <button type="submit" class="badge rounded-pill bg-info p-1 p-lg-2"> نمایش لیست افراد </button>
                                        </form>
                                    </div>
                                    <div class="col px-0 pt-1">
                                        <div class="d-flex">
                                            <h6 id="usersSendDailyWork_1">{{$usersSendDailyWork[1]}}</h6>
                                            <h6 class="mx-1 font-weight-bold"> : افرادی که تاییدیه لیست پتانسیل ثبت نکردند</h6>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                
                <div class="col-12 pb-5 chart-scrollable">
                    <div class="frame">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
                <div class="col-12 chart-scrollable mt-5">
                    <div class="frame">
                        <canvas id="myChartOrg"></canvas>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('admin/js/chart.js')}}"></script>
    <script>
        var myChart
        var myChartOrg
        const labels    = @json($daysColumn);
        const data = {
            labels: labels,
            datasets: [
                {
                    label: '۴ اقدام',
                    backgroundColor: '#8856ce',
                    borderColor: '#8856ce',
                    data: @json($sumColumn1),
                },
                {
                    label: 'پرزنت',
                    backgroundColor: '#791515',
                    borderColor: '#791515',
                    data: @json($sumColumn2),
                },
                {
                    label: 'شو گالری',
                    backgroundColor: '#7b0f63',
                    borderColor: '#7b0f63',
                    data: @json($sumColumn3),
                },
                {
                    label: 'استارت اکشن',
                    backgroundColor: '#0f7b1c',
                    borderColor: '#0f7b1c',
                    data: @json($sumColumn4),
                },
                {
                    label: 'روتین کارگاهی',
                    backgroundColor: '#7d5808',
                    borderColor: '#7d5808',
                    data: @json($sumColumn5),
                },
            ]
        };
        const dataOrg = {
            labels: labels,
            datasets: [
                {
                    label: '۴ اقدام سازمان',
                    backgroundColor: '#8856ce',
                    borderColor: '#8856ce',
                    data: @json($org_sumColumn1),
                },
                {
                    label: 'پرزنت سازمان',
                    backgroundColor: '#791515',
                    borderColor: '#791515',
                    data: @json($org_sumColumn2),
                },
                {
                    label: 'شو گالری سازمان',
                    backgroundColor: '#7b0f63',
                    borderColor: '#7b0f63',
                    data: @json($org_sumColumn3),
                },
                {
                    label: 'استارت اکشن سازمان',
                    backgroundColor: '#0f7b1c',
                    borderColor: '#0f7b1c',
                    data: @json($org_sumColumn4),
                },
                {
                    label: 'روتین کارگاهی سازمان',
                    backgroundColor: '#7d5808',
                    borderColor: '#7d5808',
                    data: @json($org_sumColumn5),
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

        const configOrg = {type: 'line',data: dataOrg,options: options};
        myChartOrg = new Chart(document.getElementById('myChartOrg'),configOrg);

        function searchBar(){
            var start   = document.getElementById('startDate').value;
            var end     = document.getElementById('endDate').value;
            document.getElementById('showStartDate').value  = start;
            document.getElementById('showEndDate').value    = end;
            var url = `{{url("/admin/four_action/search/chart")}}?start_date=${start}&end_date=${end}&id=${@json($id)}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(data_val) {
                                            
                    if (data_val.is_submit) {
                        document.getElementById('is_submit_0').innerHTML  = data_val.is_submit[0];
                        document.getElementById('is_submit_1').innerHTML  = data_val.is_submit[1];
                    }
                    
                    if (data_val.sumRow) {
                        for (let index = 0; index < data_val.sumRow.length; index++) {
                            document.getElementById(`new_added${index}`).innerHTML  = data_val.sumRow[index];
                        }
                    }

                    if (data_val.org_sumRow) {
                        for (let index = 0; index < data_val.org_sumRow.length; index++) {
                            document.getElementById(`new_added_org${index}`).innerHTML  = data_val.org_sumRow[index];
                        }
                    }

                    const labels = data_val.daysColumn;
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: '۴ اقدام',
                                backgroundColor: '#8856ce',
                                borderColor: '#8856ce',
                                data: data_val.sumColumn1,
                            },
                            {
                                label: 'پرزنت',
                                backgroundColor: '#791515',
                                borderColor: '#791515',
                                data: data_val.sumColumn2,
                            },
                            {
                                label: 'شو گالری',
                                backgroundColor: '#7b0f63',
                                borderColor: '#7b0f63',
                                data: data_val.sumColumn3,
                            },
                            {
                                label: 'استارت اکشن',
                                backgroundColor: '#0f7b1c',
                                borderColor: '#0f7b1c',
                                data: data_val.sumColumn4,
                            },
                            {
                                label: 'روتین کارگاهی',
                                backgroundColor: '#7d5808',
                                borderColor: '#7d5808',
                                data: data_val.sumColumn5,
                            },
                        ]
                    };

                    const dataOrg = {
                        labels: labels,
                        datasets: [
                            {
                                label: '۴ اقدام سازمان',
                                backgroundColor: '#8856ce',
                                borderColor: '#8856ce',
                                data: data_val.org_sumColumn1,
                            },
                            {
                                label: 'پرزنت سازمان',
                                backgroundColor: '#791515',
                                borderColor: '#791515',
                                data: data_val.org_sumColumn2,
                            },
                            {
                                label: 'شو گالری سازمان',
                                backgroundColor: '#7b0f63',
                                borderColor: '#7b0f63',
                                data: data_val.org_sumColumn3,
                            },
                            {
                                label: 'استارت اکشن سازمان',
                                backgroundColor: '#0f7b1c',
                                borderColor: '#0f7b1c',
                                data: data_val.org_sumColumn4,
                            },
                            {
                                label: 'روتین کارگاهی سازمان',
                                backgroundColor: '#7d5808',
                                borderColor: '#7d5808',
                                data: data_val.org_sumColumn5,
                            },
                        ]
                    };

                    myChart.destroy();
                    myChartOrg.destroy();

                    if (data_val.message.length) {
                        alert(data_val.message);
                    } else {
                        const config    = {type: 'line',data: data,options: options};
                        const configOrg = {type: 'line',data: dataOrg,options: options};
                        myChart         = new Chart(document.getElementById('myChart'),config);
                        myChartOrg      = new Chart(document.getElementById('myChartOrg'),configOrg);
                    }

                },
                error: function() {
                    console.log(this.error);
                }
            });
        }
    </script>
@endsection
