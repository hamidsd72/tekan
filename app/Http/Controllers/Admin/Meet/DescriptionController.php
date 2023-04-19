<?php

namespace App\Http\Controllers\Admin\Meet;

use App\Model\MeetDescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DescriptionController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'توضیحات جلسات';
        elseif ('single') return 'توضیحات جلسه';
    }

    public function __construct() {
        $this->middleware('permission:workshop_dis', ['only' => ['show','store','destroy']]);
    }

    public function show($id) {
        $items = MeetDescription::where('meet_id',$id)->get();
        return view('admin.meet.description.show', compact('id','items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'description'   => 'required|max:2555',
            'meet_id'       => 'required|integer',
        ],
            [
                'description.required'  => 'لطفا نوضیحات جلسه را وارد کنید',
                'description.max'       => 'نوضیحات جلسه نباید بیشتر از 2555 کاراکتر باشد',
                'meet_id.required'      => 'لطفا آی دی جلسه را وارد کنید',
                'meet_id.integer'       => 'آی دی جلسه معتبر نیست', 
            ]);

        $item = new MeetDescription;
        try {
            $item->meet_id      = $request->meet_id;
            $item->description  = $request->description;
            $item->save();

            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = MeetDescription::findOrFail($id);
        try {
            $item->delete();
            return redirect()->route('admin.workshop.index')->with('flash_message', ' حذف آیتم با انجام شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
