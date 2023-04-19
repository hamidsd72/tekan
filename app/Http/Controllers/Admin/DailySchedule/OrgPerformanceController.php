<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\OrgPerformance;
use App\Model\LabelPerformance;
use App\Model\Notification;
use App\Model\Connection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class OrgPerformanceController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست عملکرد شخصی سازمانی';
        elseif ('single') return 'عملکرد شخصی سازمانی';
    }

    public function __construct() {
        $this->middleware('permission:daily_schedule_org_list', ['only' => ['show',]]);
        $this->middleware('permission:daily_schedule_org_create', ['only' => ['create','store']]);
        $this->middleware('permission:daily_schedule_org_status', ['only' => ['update']]);
        $this->middleware('permission:daily_schedule_org_date', ['only' => ['update']]);
        $this->middleware('permission:daily_schedule_org_delete', ['only' => ['destroy']]);
    }

    public function show($id, $status='pending') {
        notificationsOrgReaded();
        $items = OrgPerformance::where('user_id', $id )->where('status',$status)->orderBy('date_en')->get();
        if ($status=='pending') {
            foreach ($items as $item) {
                $item->activate = true;
                if (Carbon::today()->diffInDays(Carbon::parse($item->date_en), false) > 0) {
                    $item->activate = false;
                }
            }
        }
        return view('admin.daily-schedule.org-performance.index', compact('items','id'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create($step=0) {
        $labels = LabelPerformance::where('status','active')->orderBy('sort')->get();
        $today  = Carbon::now();
        $time   = num2fa(my_jdate($today->subDay($step), 'Y/m/d'));
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.daily-schedule.org-performance.create' , compact('time','step','users','labels'), ['title1' => $this->controller_title('single').' افزودن ', 'title2' => $this->controller_title('sum').' افزودن ']);
    }

    public function store(Request $request) {

        $this->validate($request, ['date' => 'max:20',], ['date.max' => 'زمان نباید بیشتر از 20 کاراکتر باشد',]);

        try {
            $date_en = Carbon::parse(j2g(toEnNumber($request->time)));
            
            foreach (LabelPerformance::orderBy('sort')->get() as $label) {

                $labelReque = $label->id;

                if ($request->$labelReque!=null) {
                    $items = explode(',', $request->$labelReque);
                    
                    for ($j=0; $j < count($items); $j++) { 
                        $item = new OrgPerformance;
                        $item->user_id      = auth()->user()->id;
                        $item->name         = $items[$j];
                        $item->label_id     = $labelReque;
                        $item->date         = $request->time;
                        $item->date_en      = $date_en;
                        $item->save();

                        $text = $item->label->label.' : '.$item->name;
                        
                        Notification::setItem(
                            "App\Notifications\OrgInvoice",
                            "App\User",
                            $item->id,
                            ('{"date": "'.$text.'"}')
                        );
                    }
                    
                }
                
            }
            return redirect()->route('admin.daily-schedule-org-performance.show',auth()->user()->id)->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request , $id) {
        $item = OrgPerformance::findOrFail($id);
        if ($item->user_id!=auth()->user()->id) return redirect()->back()->withInput()->with('err_message', 'شما کاربر این آیتم نیستین');
        notificationReadAtNull($id);
        $this->validate($request, [
            'status'        => 'max:250',
            'date'          => 'max:20',
        ],
            [
                'status.max'            => 'وضعیت نباید بیشتر از 240 کاراکتر باشد',
                'date.max'              => 'زمان نباید بیشتر از 20 کاراکتر باشد',
            ]);

        try {
            if ($request->status) $item->status = $request->status;
            if ($request->date) {
                $item->date     = $request->date;
                $item->date_en  = Carbon::parse(j2g(toEnNumber($request->date)));
            }
            if ($request->time) {
                $item->time     = $request->time;
            }
            $item->update();
            return redirect()->back()->withInput()->with('flash_message', ' ویرایش آیتم با موفقیت انجام شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = OrgPerformance::findOrFail($id);
        if ($item->user_id!=auth()->user()->id) return redirect()->back()->withInput()->with('err_message', 'شما کاربر این آیتم نیستین');

        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
