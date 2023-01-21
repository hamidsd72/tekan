<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use App\User;
use App\Model\Customer;
use App\Http\Controllers\Controller;

class TreeController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'نمودار مشتریان ارجاعی';
        elseif ('single') return 'نمودار';
    }

    public function __construct() { $this->middleware('auth'); }

    public function index($id=null) {
        if ($id===null) $id = auth()->user()->id;

        $item   = User::findOrFail($id);
        $items  = Customer::where('user_id',$item->id)->get();
        return view('admin.customer_bank.tree.index', compact('item','items'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('singel')]);
    }

}
