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
                                        <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
                                    </div>
                                    <div class="col-auto p-0">
                                        <input type="number" name="year" id="yearDate" placeholder="سال" value="1401" min="1400" class="form-control text-center" required>
                                    </div>
                                    <div class="col-auto p-0">
                                        <select name="month" id="monthDate" class="form-control" style="max-width: 100px;">
                                            @for ($i = 1; $i < 13; $i++)
                                                <option value="{{$i}}" @if ($month==$i) selected @endif>{{num2months($i)}}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-auto p-0">
                                        <select name="label_en" id="label_en" class="form-control" style="max-width: 100px;">
                                            <option value="communication" selected>گفتگو با محوریت توسعه ارتباطات</option>
                                            <option value="conversation">گفتگو با محوریت فروش یا مشتری مداری</option>
                                            <option value="networking">گفتگو با محوریت شبکه سازی</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="id" id="id" value="{{$id[0]}}">
                                </div>
                            </form>
                        </div>
                        
                        <div class="col"></div>
                        <div class="col-auto">
                            <div class="mb-2 d-flex">
                                <h6 id="new_per1" class="text-danger">{{$list1[1]}}</h6>
                                <h6 id="new_per0" class="px-2 text-success">{{$list1[0]}}</h6>
                                <h6 class="mx-1"> : گزارش عملکرد شخصی ۴×۱ اعضا لیست پتانسیل شخصی</h6>
                            </div>

                            <div class="mb-2 d-flex">
                                <h6 id="new_org1" class="text-danger">{{$list2[1]}}</h6>
                                <h6 id="new_org0" class="px-2 text-success">{{$list2[0]}}</h6>
                                <h6 class="mx-1"> : گزارش عملکرد شخصی ۴×۱ اعضا سازمان</h6>
                            </div>
                        </div>

                    </div>
                </div>
                
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
        const labels = @json($items[1]);
        const data  = {
            labels: labels,
            datasets: [
                {
                    label: 'گزارش عملکرد شخصی ۴×۱ گفتگو با محوریت توسعه ارتباطات',
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
            console.log(document.getElementById('label_en'));

            if (label_en=='communication') {
                label_en = 'گفتگو با محوریت توسعه ارتباطات';
            } else if (label_en=='conversation') {
                label_en = 'گفتگو با محوریت فروش یا مشتری مداری';
            } else if (label_en=='networking') {
                label_en = 'گفتگو با محوریت شبکه سازی';
            }

            var url = `{{url("/admin/daily-schedule-report-filter")}}?id=${@json($id)}&year=${year}&month=${month}&label_en=${label_en}`;
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

                    const labels = data_val.items[1];
                    const data = {
                        labels: labels,
                        datasets: [
                            {
                                label: `گزارش عملکرد شخصی ۴×۱ ${label_en}`,
                                backgroundColor: '#7d5808',
                                borderColor: '#7d5808',
                                data: data_val.items[0],
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

    </script>
@endsection
