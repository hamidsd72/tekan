<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\Model\PotentialReport;
use App\Model\Potential;
use App\Http\Controllers\Controller;

class PotentialReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') {
            return 'لیست پتانسیل سازمان';
        } elseif ('single') {
            return 'پتانسیل';
        }
    }

    public function __construct() { $this->middleware('auth'); }

    public function show($id) {
        $items = auth()->user()->my_potentials()->potential_reports;
        return view('admin.potential_organization.potential_report.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update_report($id ,$column ,$status ) {
        $potential  = auth()->user()->my_potentials()->where('id', $id)->firstOrFail();
        $potential->$column = null;
        $potential->update();

        foreach ($potential->potential_report_month->where('column_name', $column)->where('status', 'active') as $old) $old->delete();

        $report     = PotentialReport::where('potential_id', $id)->where('column_name', $column)->where('status', 'pending')->first();
        try {
            $report->status = $status;
            $report->save();
            return redirect()->back()->withInput()->with('flash_message', 'آیتم با موفقیت انجام شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در انجام آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
