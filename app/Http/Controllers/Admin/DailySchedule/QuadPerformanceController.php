<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\QuadPerformance;
use App\Model\Notification;
use App\Model\Connection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class QuadPerformanceController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست عملکرد شخصی ۴×۱';
        elseif ('single') return 'عملکرد شخصی ۴×۱';
    }

    public function __construct() { $this->middleware('auth'); }

    public function show($id) {
        notificationsQuadReaded();
        $items = QuadPerformance::where('user_id', $id )->where('status','pending')->orderBy('date_en')->get();
        foreach ($items as $key => $item) {
            $item->activate = true;
            if (Carbon::today()->diffInDays(Carbon::parse($item->date_en), false) > 0) {
                $item->activate = false;
            }
        }
        return view('admin.daily-schedule.quad-performance.index', compact('items','id'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create($step=1) {
        $today = Carbon::now()->addDay();
        $time = num2fa(my_jdate($today->subDay($step), 'Y/m/d'));
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.daily-schedule.quad-performance.create' , compact('time','step','users'), ['title1' => $this->controller_title('single').' افزودن ', 'title2' => $this->controller_title('sum').' افزودن ']);
    }

    public function store(Request $request) {

        $this->validate($request, ['date' => 'max:20',], ['date.max' => 'زمان نباید بیشتر از 20 کاراکتر باشد',]);

        try {
            $date_en = Carbon::parse(j2g(toEnNumber($request->time)));
            
            for ($i=0; $i < 3; $i++) { 

                if ($i==0) {
                    $data       = $request->namesOne;
                    $label      = 'گفتگو با محوریت توسعه ارتباطات';
                    $label_en   = 'communication';
                } elseif($i==1) {
                    $data       = $request->namesTwo;
                    $label      = 'گفتگو با محوریت فروش یا مشتری مداری';
                    $label_en   = 'conversation';
                } else {
                    $data       = $request->namesTree;
                    $label      = 'گفتگو با محوریت شبکه سازی';
                    $label_en   = 'networking';
                }

                if ($data!=null) {
                    $items = explode(',',$data);
                    
                    for ($j=0; $j < count($items); $j++) { 
                        $item = new QuadPerformance;
                        $item->user_id      = auth()->user()->id;
                        $item->name         = $items[$j];
                        $item->label        = $label;
                        $item->label_en     = $label_en;
                        $item->item_id      = $request->item_id;
                        $item->date         = $request->time;
                        $item->date_en      = $date_en;
                        $item->save();

                        $text = $item->label.' : '.$item->name;
                        
                        Notification::setItem(
                            "App\Notifications\Invoice",
                            "App\User",
                            $item->id,
                            ('{"date": "'.$text.'"}')
                        );
                    }
                    
                }
                
            }
            return redirect()->route('admin.daily-schedule-quad-performance.show',auth()->user()->id)->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request , $id) {
        $item = QuadPerformance::findOrFail($id);
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
            if ($request->time) {
                $item->date     = $request->time;
                $item->date_en  = Carbon::parse(j2g(toEnNumber($request->time)));
            }
            $item->update();
            return redirect()->back()->withInput()->with('flash_message', ' ویرایش آیتم با موفقیت انجام شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = QuadPerformance::findOrFail($id);
        if ($item->user_id!=auth()->user()->id) return redirect()->back()->withInput()->with('err_message', 'شما کاربر این آیتم نیستین');

        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
