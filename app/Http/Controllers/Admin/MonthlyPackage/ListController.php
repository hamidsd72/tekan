<?php

namespace App\Http\Controllers\Admin\MonthlyPackage;

use App\Model\MonthlyPackage;
use App\Model\MonthlyPackageReport;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class ListController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست طرح های ماهانه';
        elseif ('single') return 'طرح ها ماهانه';
    }

    public function __construct() {
        $this->middleware('permission:month_package_list', ['only' => ['index']]);
        $this->middleware('permission:month_package_create', ['only' => ['store']]);
        $this->middleware('permission:month_package_edit', ['only' => ['reload','update']]);
    }

    public function index() {
        $items  = MonthlyPackage::orderBy('status')->get();
        $active = $items->where('status','active')->first()?$items->where('status','active')->first()->reports->where('status','pending')->count():null;
        return view('admin.monthly_package.list.index', compact('items','active'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function reload($id) {
        // $items = MonthlyPackage::where('status', 'active')->orderByDesc('id')->get();
        // foreach ($items as $item) {
        //     $item->status = 'pending';
        //     $item->update();
        // }
        
        $reports = MonthlyPackageReport::where('package_id', $id)->get();
        foreach ($reports as $item) {
            $item->status = 'deleted';
            $item->update();
        }
        
    }

    public function store(Request $request) {
        $this->validate($request, [
            'title'     => 'required|max:250',
            'status'    => 'max:250',
        ],
            [
                'title.required'    => 'لطفا عنوان را وارد کنید',
                'title.max'         => 'عنوان نباید بیشتر از 250 کاراکتر باشد',
                'status.max'        => 'وضعیت نباید بیشتر از 250 کاراکتر باشد',
            ]);

        $package = new MonthlyPackage();

        try {
            // if ($request->status=='active') $this->reload();
            
            $package->title     = $request->title;
            $package->status    = $request->status;
            $package->save();
            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request , $id) {
        $this->validate($request, [
            'title'     => 'required|max:250',
            'status'    => 'max:250',
        ],
            [
                'title.required'    => 'لطفا عنوان را وارد کنید',
                'title.max'         => 'عنوان نباید بیشتر از 250 کاراکتر باشد',
                'status.max'        => 'وضعیت نباید بیشتر از 250 کاراکتر باشد',
            ]);

        $package = MonthlyPackage::findOrFail($id);
        if ($request->status=='pending') $this->reload($id);

        try {
            $package->title     = $request->title;
            $package->status    = $request->status;
            $package->update();
            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $this->reload($id);
        MonthlyPackage::findOrFail($id)->delete();
        return redirect()->back()->withInput()->with('flash_message', ' حذف آیتم با موفقیت ایجاد شد.');
    }

}
