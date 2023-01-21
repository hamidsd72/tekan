<ul>
    @foreach ($items->where('user_id',$id)  as $item)
        <li>
            <a href="javascript:void(0)" id="item{{$item->id}}" class="d-none">
                {{$item->full_name()}}
                {{' ('.$item->user?$item->user->customers()->count():' __ '.') '}}
                {{'('.($item->potential?$item->potential->kasb_o_kar_kochak_ya_bozorg=='بزرگ'?'BB':'SB':'__').')'}}
            </a>
            @if ($items->where('user_id',$item->name)->count())
                @include('admin.organization-member._partial', [ 'items'=>$items, 'id' => $item->id ])
            @endif
        </li>
    @endforeach
</ul>
