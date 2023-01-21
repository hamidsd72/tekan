<?php

namespace App\Http\Controllers\Admin\OrganizationMember;

use App\Model\Potential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrganizationMemberController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'اعضا سازمان';
        elseif ('single') return 'اعضا سازمان';
    }

    public function __construct() { $this->middleware('auth'); }

    public function index() {
        $items  = auth()->user()->my_potentials()->get();
        return view('admin.organization-member.index' , compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $items  = Potential::where('user_id',$id)->get();
        return view('admin.organization-member.index' , compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

}

