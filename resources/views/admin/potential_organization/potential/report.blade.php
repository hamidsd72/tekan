@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section id="chart" class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}</div>
            <div class="card-body">
                    
                <div class="border-bottom pb-lg-3 mb-3 mx-lg-2">
                    <form action="{{$url}}" id="form_req" method="get">
                        @csrf
                        <div class="row bg-cu m-0" style="max-width: 240px;direction: ltr;">
                            <div class="col-auto p-0">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
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
            
                <div class="px-2 px-lg-4">
                    @for ($i = 0; $i < count($items); $i++)
                        <div class="mb-2 d-flex">
                            <h6>{{$text[$i]}} : </h6>
                            <h6 class="mx-1" id="new_added{{$i}}">{{$items[$i]}}</h6>
                        </div>
                    @endfor
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function searchBar(){
            var start   = document.getElementById('startDate').value;
            var end     = document.getElementById('endDate').value;
            var url = `{{$url}}?start='${start}'&end='${end}'`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(data_val) {
                    if (data_val.items) {
                        for (let index = 0; index < data_val.items.length; index++) {
                            document.getElementById(`new_added${index}`).innerHTML  = data_val.items[index];
                        }
                    }
                },
                error: function() {
                    console.log(this.error);
                }
            });
        }
        
    </script>
@endsection