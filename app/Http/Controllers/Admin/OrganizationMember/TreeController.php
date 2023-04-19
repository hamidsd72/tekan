<?php

namespace App\Http\Controllers\Admin\OrganizationMember;

use App\Model\Potential;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;

class TreeController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'نمودار اعضا سازمان';
        elseif ('single') return 'نمودار اعضا سازمان';
    }

    public function __construct() {
        $this->middleware('permission:organization_member_tree_list', ['only' => ['index','show']]);
    }

    public function index($id=null) {
        if ($id===null) $id = auth()->user()->id;
        $items  = Potential::whereIn('id',getSubUser([$id])[0])->whereNotIn('present_ta_peresent',['خرید اولیه انجام نشده'])->get();
        return view('admin.organization-member.tree' , compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }
    
    public function show($id) {
        $items  = Potential::where('user_id',$id)->whereNotIn('present_ta_peresent',['خرید اولیه انجام نشده'])->get(['id','user_id','name','kasb_o_kar_kochak_ya_bozorg']);
        $state  = [];
        if ($items->count()) {
            $users  = User::whereIn('id', $items->pluck('name') )->get('state_id')->unique('state_id');
            foreach ($users as $user) {
                if ($user->state) {
                    array_push($state, $user->state->name);
                    array_push($state, ( ( $users->where('state_id', $user->state->id)->count() *100) / $users->count() ).'%' );
                }
            }
        }
        
        foreach ($items as $item) {
            if ($item->kasb_o_kar_kochak_ya_bozorg) {
                if ($item->kasb_o_kar_kochak_ya_bozorg=='بزرگ') $item->kasb_o_kar_kochak_ya_bozorg= 'BB';
                else $item->kasb_o_kar_kochak_ya_bozorg= 'SB';
            } else {
                $item->kasb_o_kar_kochak_ya_bozorg = '__';
            }
            if ($item->full_name()) $item->fullname = $item->full_name();
            if ($item->user) $item->count = num2fa($item->user->customers()->count());
        }

        return response()->json(['items' => $items,'state' => $state] , 200); 
    }

    public function map($id=null) {
        if ($id===null) $id = auth()->user()->id;
        $items  = Potential::whereIn('user_id',getSubUser([$id])[2])->whereNotIn('present_ta_peresent',['خرید اولیه انجام نشده'])->get();

        $state  = [];
        if ($items->count()) {
            $users  = User::where('state_id','>',0)->whereIn('id', $items->pluck('name') )->get('state_id');

            foreach ($users->unique('state_id') as $user) {
                if ($user->state) {
                    array_push($state, $user->state->name);
                    array_push($state, ( ( $users->where('state_id', $user->state_id)->count() *100) / $users->count() ).'%' );
                }
            }
        }
        
        return view('admin.organization-member.map' , compact('items','state'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

}
