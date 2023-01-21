<?php

namespace App\Http\Controllers\Admin;

use App\Model\Code;
use App\Model\Sms;
use App\User;
use App\Model\Setting;
use App\Model\Performance;
use App\Model\Category;
use App\Model\Photo;
use App\Model\ServiceCat;
use App\Model\ProvinceCity;
use App\Model\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class PerformanceController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'گزارش روزانه عملکرد سازمان';
        } elseif ('single') {
            return 'گزارش روزانه عملکرد سازمان';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index($type)
    {
        $types = Performance::types();

        $auth = auth()->user();
        $items = Performance::where('type', $type);

        if (!$auth->hasRole('مدیر')) {
            $items->where('user_id',$auth->id);
        }

        $items = $items->orderByDesc('date')->paginate($this->controller_paginate());
        return view('admin.report.performance.index', compact('items', 'types', 'type'), ['title1' => $this->controller_title('sum'), 'title2' => $types[$type]]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'number' => 'required',
            'type' => 'required',
            'date' => "required|unique:performances",
        ], [
            'number.required' => 'لطفا تعداد را وارد کنید',
            'date.required' => 'لطفا تاریخ را وارد کنید',
            'date.unique' => ' تاریخ تکراری می باشد',
        ]);
        $auth  =auth()->user();
        $date = convertFaDateToEn($request->date);


        $performance = Performance::where('date', $date)->where('type', $request->type)->where('user_id',$auth->id)->first();
        if ($performance) {
            return redirect()->back()->with('err_message', 'در این تاریخ گزارشی ثبت شده است، تاریخ دیگری را امتحان کنید !');
        }

        try {
            $item = new Performance();
            $item->number = $request->number;
            $item->type = $request->type;
            $item->date = convertFaDateToEn($request->date);
            $item->user_id = $auth->id;
            $item->save();

            return redirect()->back()->with('flash_message', 'گزارش روزانه عملکرد سازمان با موفقیت ایجاد شد.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی  بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'number' => 'required',
            'type' => 'required',
            'date' => "required",
        ], [
            'number.required' => 'لطفا تعداد را وارد کنید',
            'date.required' => 'لطفا تاریخ را وارد کنید',
        ]);

        $item = Performance::findOrFail($id);
        $auth =auth()->user();

        $date = convertFaDateToEn($request->date);

        $performance = Performance::where('date', $date)->where('type', $request->type)->where('user_id',$auth->id)->first();
        if ($performance && $performance->id != $id) {
            return redirect()->back()->with('err_message', 'در این تاریخ گزارشی ثبت شده است، تاریخ دیگری را امتحان کنید !');
        }

        try {
            $item->number = $request->number;
            $item->type = $request->type;
            $item->date = $date;
            $item->save();

            return redirect()->back()->with('flash_message', 'گزارش روزانه عملکرد سازمان با موفقیت ویرایش شد.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی  بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = Performance::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف  بوجود آمده،مجددا تلاش کنید');
        }
    }


}


