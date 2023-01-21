<ul>
    @foreach ($items->where('referrer_id',$id)  as $item)
        <li>
            <a href="javascript:void(0)">{{$item->name}} - <span class="text-info">{{count($item->referrer_users)}}</span></a>
            @if ($items->where('referrer_id',$item->id)->count())
                @include('admin.customer_bank.tree._partial', [ 'items'=>$items, 'id' => $item->id ])
            @endif
        </li>
    @endforeach
</ul>