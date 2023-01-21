@extends('layouts.admin')
@section('css')
    <style>
        .tree ul {
            padding-top: 20px; position: relative;
            
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }
        .tree li {
            float: left; text-align: center;
            list-style-type: none;
            position: relative;
            padding: 20px 5px 0 5px;
            
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }
        /*We will use ::before and ::after to draw the connectors*/
        .tree li::before, .tree li::after{
            content: '';
            position: absolute; top: 0; right: 50%;
            border-top: 1px solid #ccc;
            width: 50%; height: 20px;
        }
        .tree li::after{
            right: auto; left: 50%;
            border-left: 1px solid #ccc;
        }
        /*We need to remove left-right connectors from elements without 
        any siblings*/
        .tree li:only-child::after, .tree li:only-child::before {
            display: none;
        }
        /*Remove space from the top of single children*/
        .tree li:only-child{ padding-top: 0;}
        /*Remove left connector from first child and 
        right connector from last child*/
        .tree li:first-child::before, .tree li:last-child::after{
            border: 0 none;
        }
        /*Adding back the vertical connector to the last nodes*/
        .tree li:last-child::before{
            border-right: 1px solid #ccc;
            border-radius: 0 5px 0 0;
            -webkit-border-radius: 0 5px 0 0;
            -moz-border-radius: 0 5px 0 0;
        }
        .tree li:first-child::after{
            border-radius: 5px 0 0 0;
            -webkit-border-radius: 5px 0 0 0;
            -moz-border-radius: 5px 0 0 0;
        }
        /*Time to add downward connectors from parents*/
        .tree ul ul::before{
            content: '';
            position: absolute; top: 0; left: 50%;
            border-left: 1px solid #ccc;
            width: 0; height: 20px;
        }
        .tree li a{
            border: 1px solid #ccc;
            padding: 5px 10px;
            text-decoration: none;
            color: #666;
            font-family: arial, verdana, tahoma;
            font-size: 11px;
            display: inline-block;
            
            border-radius: 5px;
            -webkit-border-radius: 5px;
            -moz-border-radius: 5px;
            
            transition: all 0.5s;
            -webkit-transition: all 0.5s;
            -moz-transition: all 0.5s;
        }
        /*Time for some hover effects*/
        /*We will apply the hover effect the the lineage of the element also*/
        .tree li a:hover, .tree li a:hover+ul li a {
            background: #c8e4f8; color: #000; border: 1px solid #94a0b4;
        }
        /*Connector styles on hover*/
        .tree li a:hover+ul li::after, 
        .tree li a:hover+ul li::before, 
        .tree li a:hover+ul::before, 
        .tree li a:hover+ul ul::before{
            border-color:  #94a0b4;
        }

    </style>
@endsection
@section('content')
    <section class="container-fluid">
        <div class="tree">
            <ul id="ul0">
                <li>
                    {{-- <a href="javascript:void(0)" id="item0" onclick="openLine('{{$items->where('user_id',auth()->user()->id)->pluck('id')}}')"> --}}
                    <a href="javascript:void(0)" id="item0" onclick="getLine('{{auth()->user()->id}}')">
                        {{auth()->user()->first_name.' '.auth()->user()->last_name}}
                        {{' ('.auth()->user()->customers()->count().') '}}
                        {{'('.(auth()->user()->potential?auth()->user()->potential->kasb_o_kar_kochak_ya_bozorg=='بزرگ'?'BB':'SB':'__').')'}}
                    </a>
                    <ul id="ul{{auth()->user()->id}}"></ul>
                    {{-- @if ($items??'')
                        @if ($items->where('user_id',auth()->user()->id)->count())
                            @include('admin.organization-member._partial', [ 'items'=>$items, 'id' => auth()->user()->id ])
                        @endif
                    @endif --}}
                </li>
            </ul>
        </div>
    </section>
@endsection
@section('js')
    <script>
        function getLine(id) {

            var url   = `{{url("/admin/organization-member-tree")}}/${id}`;
            $.ajax({
                type: "GET",
                url:  url,
                success: function(dataVal) {
                    
                    let items = dataVal.items;

                    if (items.length > 0) {
                        
                        const ul = document.getElementById(`ul${id}`);

                        for (let index = 0; index < items.length; index++) {
                            const item = items[index];
                            ul.insertAdjacentHTML("afterbegin",
                                `<li>
                                    <a href="javascript:void(0)" id="item${item.id}" onclick="getLine(${item.id})">${item.fullname} (${item.count}) (${item.kasb_o_kar_kochak_ya_bozorg})</a>
                                    <ul id=ul${item.id}></ul>
                                </li>`    
                            );
                        }

                    } else {
                        alert('this line is empty - این خط خالی است');
                    }
                    
                },
                error: function() {
                    console.log(this.error);
                }
            });
        }

        function openLine(list) {
            if (list.length) {
                let items    = list.substr(1, (list.length)-2);
                let subItems = items.split(",");
                for (let index = 0; index < subItems.length; index++) {
                    document.getElementById((`item${subItems[index]}`)).classList.remove('d-none');
                }
            }
        }
    </script>
@endsection
