@extends('layouts.admin')
@section('css')
@endsection
@section('content')
    <section id="chart" class="container-fluid">
        <div class="card res_table">
            <div class="card-header">{{$title2}}</div>
            <div class="card-body">
                    
                {{-- <div class="border-bottom pb-lg-3 mb-3 mx-lg-2">
                    <form action="{{$url}}" id="form_req" method="get">
                        @csrf
                        <div class="row bg-cu m-0" style="max-width: 260px;direction: ltr;">
                            <div class="col-auto p-0">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="searchBar()">جستجو</a>
                            </div>
                            <div class="col p-0">
                                <input type="text" name="end_date" id="endDate" placeholder="بازه پایان" value="{{num2fa(g2j(date('Y-m-d'),'Y/m/d'))}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                            </div>
                            <div class="col p-0">
                                <input type="text" name="start_date" id="startDate" placeholder="بازه شروع" value="{{num2fa($start)}}" class="form-control text-center date_p1" autocomplete="off" readonly required>
                            </div>
                        </div>
                    </form>
                </div> --}}
            
                <div class="px-2 px-lg-4">
                    @for ($i = 0; $i < count($items); $i++)
                        <div class="pt-3 pb-2 d-flex border-bottom">
                            <h6 class="font-weight-bold">{{$text[$i]}} : </h6>
                            <h6 class="mx-1" id="added{{$i}}">{{$items[$i]}}</h6>
                        </div>
                    @endfor
                </div>

                <div class="border-bottom py-lg-3 m-lg-3">
                    <form action="{{$url}}" id="form_req" method="get">
                        @csrf
                        <div class="row bg-cu m-0" style="max-width: 260px;direction: ltr;">
                            <div class="col-auto p-0">
                                <a href="javascript:void(0);" class="btn btn-primary" onclick="newSearchBar()">جستجو</a>
                            </div>
                            <div class="col p-0">
                                <div class="col-auto p-0">
                                    <select name="month" id="monthDate" class="form-control" style="max-width: 100px;">
                                        @for ($i = 1; $i < 13; $i++)
                                            <option value="{{$i}}" @if ($month==$i) selected @endif>{{num2months($i)}}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col p-0">
                                <input type="number" name="year" id="yearDate" value="{{g2j(date('Y-m-d'),'Y')}}" class="form-control text-center" min="1400" autocomplete="off" required>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="px-2 px-lg-4">
                    @for ($j = 0; $j < count($m_pack_title); $j++)
                        <div class="py-2 mb-3 d-flex border-bottom">
                            <h6 class="font-weight-bold">{{$m_pack_title[$j]}} : </h6>
                            <h6 class="mx-1">{{$m_pack_value[$j]}}</h6>
                        </div>
                    @endfor
                </div>

                <div class="px-2 px-lg-4" id="lorem_box_30">
                    @for ($i = 0; $i < count($new_items); $i++)
                        @php
                            $obj = $new_items[$i];
                            $obj = explode(':', $obj);
                        @endphp
                        <span class="h6 text-dark font-weight-bold" id="report{{$i}}">{{$obj[0]}}</span class="h6 text-dark ">:<span>{{$obj[1]}}</span>
                        <hr>
                    @endfor
                </div>

            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        let set_year  = '{{my_jdate(\Carbon\Carbon::today()->format('Y'),'Y')}}';
        let set_month = '{{$month}}';

        function newSearchBar(){
            var month   = document.getElementById('monthDate').value;
            var year    = document.getElementById('yearDate').value;
            var id      = @json($id);

            if (parseInt(set_year) < parseInt(year)) {
                alert('سال وارد شده معتبر نیست');
            } else if( (parseInt(set_year) == parseInt(year)) && (parseInt(set_month) < parseInt(month)) ) {
                alert('ماه وارد شده معتبر نیست');
            } else {
                
                var url = `{{url('/')}}/admin/potential-list/report/filter/${id}/${year}/${month}`;
                $.ajax({
                    type: "GET",
                    url:  url,
                    success: function(objs) {
                        var element = document.querySelector('#lorem_box_30');
                        element.innerHTML = '';
                        for (let index = 0; index < objs.items.length; index++) {
                            var node = document.createElement("span");
                            var node2 = document.createElement("span");
                            var hr = document.createElement("hr");
                            node.classList.add('h6');
                            node2.classList.add('h6');
                            node.classList.add('text-dark');
                            node2.classList.add('text-dark');
                            node.classList.add('font-weight-bold');
                            var data = (objs.items[index]).split(':');
                            node.innerText = data[0];
                            node2.innerText = data[1];
                            element.appendChild( node );
                            element.appendChild( node2 );
                            element.appendChild( hr );
                        }
                    },
                    error: function() {
                        console.log(this.error);
                    }
                });
            }
                
        }

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
                            document.getElementById(`added${index}`).innerHTML  = data_val.items[index];
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
