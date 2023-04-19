<?php

namespace App\Http\Controllers\Admin\Meet;

use App\User;
use App\Model\Meet;
use App\Model\MeetReport;
use App\Model\Notification;
use App\Model\Potential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'توضیحات جلسات';
        elseif ('single') return 'توضیحات جلسه';
    }

    public function __construct() { $this->middleware('auth'); }

    function getHeadUser($head , $user) {
        $list   = [];
        while (true) {
            $item   = Potential::where('name', $user )->first('user_id');
            $user   = $item->user_id;
            array_push($list , $user );
            if ($head == $user) break;
        }
        return $list;
    }

    public function show($id) {
        $item   = MeetReport::findOrFail($id);
        $unreadNotifications = auth()->user()->unreadNotifications->where('type','App\Notifications\MeetCreateReport');

        return view('admin.meet.report.show', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create($slug) {
        $slug   = num2en($slug);
        $item   = Meet::where('slug', $slug)->first();
        return view('admin.meet.report.create', compact('item'), ['title1' => ' ثبت '.$this->controller_title('single'), 'title2' => ' ثبت '.$this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'text'      => 'required|max:2555',
            'meet_id'   => 'required|integer',
        ],
            [
                'text.required'     => 'لطفا نوضیحات جلسه را وارد کنید',
                'text.max'          => 'نوضیحات جلسه نباید بیشتر از 2555 کاراکتر باشد',
                'meet_id.required'  => 'لطفا آی دی جلسه را وارد کنید',
                'meet_id.integer'   => 'آی دی جلسه معتبر نیست', 
            ]);

        $item = new MeetReport;
        $meet = Meet::findOrFail($request->meet_id);
        try {
            $item->user_id  = auth()->id();
            $item->meet_id  = $request->meet_id;
            $item->text     = $request->text;
            $item->save();

            // find all potantial top users
            $users = User::whereIn('id',$this->getHeadUser($meet->user_id,auth()->id()))->get('id');

            foreach ($users as $user) {
                Notification::setItemByUserId(
                    "App\Notifications\MeetCreateReport",
                    "App\User",
                    $item->id,
                    ('{"date": "'.($meet->slug).'"}'),
                    $user->id
                );
            }
            return redirect()->route('admin.index')->with('flash_message', ' گزارش با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد گزارش بوجود آمده، مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = MeetReport::findOrFail($id);
        try {
            $item->delete();
            return redirect()->route('admin.workshop.index')->with('flash_message', ' حذف آیتم با انجام شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
