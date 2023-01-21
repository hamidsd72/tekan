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
                            <form action="{{route('admin.connection-report.search')}}" id="form_req" method="get">
                                @csrf
                                <input type="hidden" name="type" value="text">
                                <div class="row bg-cu" style="max-width: 300px;">
                                    <div class="col-auto p-0">
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="textSeter()">جستجو</a>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="text" name="end_date" id="endDate" placeholder="بازه پایان" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="text" name="start_date" id="startDate" placeholder="بازه شروع" value="{{num2fa($start)}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="col"></div>
                        <div class="col-auto mb-3">
                            <div class="mb-2 d-flex">
                                <h6 id="new_added">{{$new_added}}</h6>
                                <h6 class="mx-1">: تعداد نفرات اضافه شده به لیست</h6>
                            </div>
                            <div class="d-flex">
                                <h6 id="finished">{{$finished}}</h6>
                                <h6 class="mx-1">: تعداد نفرات اقدام شده از لیست</h6>
                            </div>
                        </div>

                    </div>
                </div>
                
                <form action="{{route('admin.connection-report.search')}}" id="form_req2" method="get" class="float-left mx-2" >
                    @csrf
                    <input type="hidden" name="type" value="chart">
                    <div class="row" style="max-width: 300px;">
                        <div class="col-auto p-0">
                        <input type="hidden" name="lorem" required>
                            <input type="number" name="chart_year" id="chartYear" placeholder="سال مورد نظر" min="1300" max="{{intVal(my_jdate(\Carbon\Carbon::now(),'Y'))}}"
                             class="form-control text-center" autocomplete="off" value="{{intVal(my_jdate(\Carbon\Carbon::now(),'Y'))}}" required>
                        </div>
                        <div class="col-auto p-0">
                            <a href="javascript:void(0);" class="btn btn-primary btn_click" onclick="chartSeter()">جستجو</a>
                        </div>
                    </div>
                </form>

                <div class="my-5"></div>
                <div class="col-12">
                    <canvas style="max-height: 400px;" id="myChart"></canvas>
                </div>
                
            </div>

        </div>
    </section>
@endsection

@section('js')
    <script src="{{asset('admin/js/chart.js')}}"></script>
    <script>
        var myChart
        const labels = @json($chat_data[0]);
        const data = {
            labels: labels,
            datasets: [
                {
                    label: 'تعداد نفرات اضافه شده به لیست',
                    backgroundColor: 'cadetblue',
                    borderColor: 'teal',
                    data: @json($chat_data[1]),
                },
                {
                    label: 'تعداد نفرات اقدام شده از لیست',
                    backgroundColor: 'green',
                    borderColor: 'forestgreen',
                    data: @json($chat_data[2]),
                }
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

        function textSeter(){
            var url   = `{{url("/admin/connection-report/data/search")}}?chart=text&start_date=${document.getElementById('startDate').value}
            &end_date=${document.getElementById('endDate').value}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(items) {
                    document.getElementById('new_added').innerHTML = items.new_added;
                    document.getElementById('finished').innerHTML  = items.finished;
                },
                error: function() {
                    console.log(this.error);
                }
            });
        }

        function chartSeter(){
            var url   = `{{url("/admin/connection-report/data/search")}}?chart=chart&chart_year=${document.getElementById('chartYear').value}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(data_val) {

                    const labels = data_val.months;
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: 'تعداد نفرات اضافه شده به لیست',
                                backgroundColor: 'cadetblue',
                                borderColor: 'teal',
                                data: data_val.data1,
                            },
                            {
                                label: 'تعداد نفرات اقدام شده از لیست',
                                backgroundColor: 'green',
                                borderColor: 'forestgreen',
                                data: data_val.data2,
                            }
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

                },
                error: function() {
                    console.log(this.error);
                }
            });
        }
    </script>
@endsection
