
    @foreach ($items->where('referrer_id',$id)  as $item)
        <li>
            <a href="javascript:void(0)">{{$item->name}}</a>
        </li>
    @endforeach
