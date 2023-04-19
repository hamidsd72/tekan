<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\LabelPerformance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrgPerformanceLabelController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست گزینه های عملکرد شخصی سازمانی';
        elseif ('single') return 'گزینه های عملکرد شخصی سازمانی';
    }

    public function __construct() {
        $this->middleware('permission:org-performance-label_list', ['only' => ['index',]]);
        $this->middleware('permission:org-performance-label_edit', ['only' => ['update','store']]);
    }

    public function index() {
        $items = LabelPerformance::orderByDesc('sort')->get();
        return view('admin.daily-schedule.org-performance-label.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'label'         => 'max:255',
            'role'          => 'max:255',
        ],
            [
                'label.max'             => 'لیبل آیتم نباید بیشتر از 255 کاراکتر باشد',
                'role.max'              => 'رول ها نباید بیشتر از 255 کاراکتر باشد',
            ]);

        try {
            $item = new LabelPerformance();
            $item->label    = $request->label;
            $item->role = $request->role;
            $item->save();
            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request , $id) {
        $this->validate($request, [
            'status'        => 'max:10',
            'sort'          => 'integer',
            'role'          => 'max:255',
        ],
            [
                'status.max'            => 'وضعیت نباید بیشتر از 10 کاراکتر باشد',
                'sort.max'              => 'ترتیب نباید بیشتر از 9 کاراکتر باشد',
                'role.max'              => 'رول ها نباید بیشتر از 255 کاراکتر باشد',
            ]);

        try {
            $item = LabelPerformance::find($id);
            if ( $request->status ) $item->status = $request->status;
            if ( $request->sort )   $item->sort = $request->sort;
            if ( $request->role )   $item->role = $request->role;
            $item->update();
            return redirect()->back()->withInput()->with('flash_message', ' ویرایش آیتم با موفقیت انجام شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
