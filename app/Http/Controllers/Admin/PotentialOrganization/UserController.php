<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\User;
use App\Model\Potential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PotentialController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') {
            return 'لیست پتانسیل سازمان';
        } elseif ('single') {
            return 'پتانسیل';
        }
    }

    public function __construct() { $this->middleware('auth'); }

    public function index() {
        $items = Potential::where( 'user_id' , auth()->user()->id )->get();
        return view('admin.potential_organization.user.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create() {
        return view('admin.potential_organization.user.create', ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name'  => 'required|integer',
        ],
            [
                'name.required' => 'لطفا نام کاربر را وارد کنید',
                'name.integer'  => 'نام کاربر معتبر نیست',
            ]);
        $user = User::findOrFail($request->name);
        $item = new Potential();
        try {
            $item->user_id                      = auth()->user()->id;
            $item->name                         = $user->id;
            $item->present_ta_peresent          = $request->present_ta_peresent;
            $item->kasb_o_kar_kochak_ya_bozorg  = $request->kasb_o_kar_kochak_ya_bozorg;
            $item->present_ta_estage            = $request->present_ta_estage;
            $item->hadaf_gozari_shakhsi         = $request->hadaf_gozari_shakhsi;
            $item->folowe_ya_4eqdam             = $request->folowe_ya_4eqdam;
            $item->hadaf_gozari_level           = $request->hadaf_gozari_level;
            $item->save();
            return redirect()->route('admin.potential-list.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $potential = Potential::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('admin.potential_organization.user.edit', compact('potential'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update(Request $request, $id) {
        $item = Potential::findOrFail($id);
        try {
            $item->present_ta_peresent          = $request->present_ta_peresent;
            $item->kasb_o_kar_kochak_ya_bozorg  = $request->kasb_o_kar_kochak_ya_bozorg;
            $item->present_ta_estage            = $request->present_ta_estage;
            $item->hadaf_gozari_shakhsi         = $request->hadaf_gozari_shakhsi;
            $item->folowe_ya_4eqdam             = $request->folowe_ya_4eqdam;
            $item->hadaf_gozari_level           = $request->hadaf_gozari_level;
            $item->save();
            return redirect()->route('admin.potential-list.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}


