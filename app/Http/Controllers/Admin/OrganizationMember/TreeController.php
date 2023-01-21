<?php

namespace App\Http\Controllers\Admin\OrganizationMember;

use App\Model\Potential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TreeController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'نمودار اعضا سازمان';
        elseif ('single') return 'نمودار اعضا سازمان';
    }

    public function __construct() { $this->middleware('auth'); }

    public function index($id=null) {
        if ($id===null) $id = auth()->user()->id;
        $items  = Potential::whereIn('id',getSubUser([$id])[0])->get();
        return view('admin.organization-member.tree' , compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $items  = Potential::where('user_id',$id)->get(['id','user_id','name','kasb_o_kar_kochak_ya_bozorg']);
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

        return response()->json(['items' => $items] , 200); 
    }

}
