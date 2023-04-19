<?php

namespace App\Http\Controllers\Admin\MonthlyPackage;

use App\Model\MonthlyPackage;
use App\Model\Potential;
use App\Model\MonthlyPackageReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware('permission:month_package_report_list', ['only' => ['index']]);
    }

    public function controller_title($type) {
        if ($type == 'sum') {
            return 'لیست پتانسیل های تایید شده برای طرح های فعال جاری';
        } elseif ('single') {
            return 'پتانسیل های تایید شده برای طرح های فعال جاری';
        }
    }

    public function index() {
        $monthlyPackage = MonthlyPackage::where('status','active')->pluck('id');
        $reports        = MonthlyPackageReport::whereIn('package_id', $monthlyPackage)->where('status','!=','deleted')->get();
        return view('admin.monthly_package.report.index', compact('reports'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store($potential_id, $pack_id) {
        $package    = new MonthlyPackageReport();
        try {
            $package->potential_id  = $potential_id;
            $package->package_id    = $pack_id;
            $package->user_id       = auth()->user()->id;
            $package->status        = 'pending';
            $package->save();
            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update($id, $status) {
        $package = MonthlyPackageReport::findOrFail($id);
        try {
            $package->status    = $status;
            $package->update();
            return redirect()->back()->withInput()->with('flash_message', ' ویرایش آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
