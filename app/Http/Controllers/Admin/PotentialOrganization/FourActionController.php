<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\User;
use App\Model\FourAction;
use App\Model\Potential;
use App\Model\DailyWork;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class FourActionController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'عملکرد روزانه پتانسیل سازمان';
        elseif ('single') return 'عملکرد روزانه پتانسیل سازمان';
    }

    public function __construct() {
        $this->middleware('permission:four_action_list', ['only' => ['create','store']]);
        $this->middleware('permission:four_action_report_list', ['only' => ['index']]);
    }

    public function report($start ,$end ,$length, $users, $type) {
        $users      = $users;
        foreach (Potential::whereIn('id', getSubUser($users)[0] )->pluck('name') as $item) {
            array_push($users, $item);
        }
        $users      = User::whereIn('id', $users )->where('created_at','<',$end)->pluck('id');
        $items      = FourAction::whereBetween('time_en',[$start,$end])->whereIn('user_id',$users)->get();
        $usersSendDailyWork = DailyWork::where('created_at','>',Carbon::today())->count();
        
        // روزها 
        $daysColumn = ['شروع'];
        // ۴ اقدام
        $sumColumn1 = [0];
        // پرزنت
        $sumColumn2 = [0];
        // شو گالری
        $sumColumn3 = [0];
        // استارت اکشن
        $sumColumn4 = [0];
        // روتین کارگاهی
        $sumColumn5 = [0];
        // جمع کل ستون های بالا
        $nameRow    = [];
        $sumRow     = [];
        // افرادی که فرم ثبت کردن یا نکردن
        $count_users_submit = $items->where('time_en','>',Carbon::today()->subday())->unique('user_id')->count();
        $count_users        = $users->count();
        $is_submit_users    = $count_users_submit>0?(intVal(($count_users_submit*100)/$count_users)):0;
        $is_submit          = ['%'.num2fa($is_submit_users).' - '.num2fa($count_users_submit) , '%'.num2fa(100-$is_submit_users).' - '.num2fa($count_users-$count_users_submit)];

        $SendDailyWork      = $usersSendDailyWork>0?(intVal(($usersSendDailyWork*100)/$count_users)):0;
        $usersSendDailyWork = ['%'.num2fa($SendDailyWork).' - '.num2fa($usersSendDailyWork) , '%'.num2fa(100-$SendDailyWork).' - '.num2fa($count_users-$usersSendDailyWork)];
        
        array_push($nameRow, 'چهار اقدام' );
        array_push($sumRow, (num2fa($items->sum('four_action'))).' '.$type );
        array_push($nameRow, 'پرزنت' );
        array_push($sumRow, (num2fa($items->sum('present'))).' '.$type );
        array_push($nameRow, 'شو گالری' );
        array_push($sumRow, (num2fa($items->sum('show_gallery'))).' '.$type );
        array_push($nameRow, 'استارت اکشن' );
        array_push($sumRow, (num2fa($items->sum('start_action'))).' '.$type );
        array_push($nameRow, 'روتین کارگاهی' );
        array_push($sumRow, (num2fa($items->sum('workshop_routine'))).' '.$type );        

        if ($length < (persianStartOfMonth()->diffInDays( persianEndOfMonth(), false)+1) ) {
            $start = $start->subDay();
            for ($i=1; $i <= $length; $i++) {
                $newTime = $start->addDay(); 
                $getItem = $items->where('time_en',$newTime);

                array_push($daysColumn, my_jdate($newTime, 'd F') );
                array_push($sumColumn1, $getItem->sum('four_action') );
                array_push($sumColumn2, $getItem->sum('present') );
                array_push($sumColumn3, $getItem->sum('show_gallery') );
                array_push($sumColumn4, $getItem->sum('start_action') );
                array_push($sumColumn5, $getItem->sum('workshop_routine') );
            }
        }

        return [
            $daysColumn
            ,$sumColumn1
            ,$sumColumn2
            ,$sumColumn3
            ,$sumColumn4
            ,$sumColumn5
            ,$nameRow
            ,$sumRow
            ,$is_submit
            ,$usersSendDailyWork
        ];
    }

    public function index($id=null ,$type='single') {
        $end        = Carbon::now();
        $start      = Carbon::parse(j2g( g2j(Carbon::now(),'Y/m').'/01' ) );
        $org_end    = Carbon::now();
        $org_start  = Carbon::parse(j2g( g2j(Carbon::now(),'Y/m').'/01' ) );
        $length = $start->diffInDays( $end, false) + 1;

        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];

        $report     = $this->report($start , $end, $length, $list, 'شخصی');
        $orgReport  = $this->report($org_start , $org_end, $length, getSubUser([$id])[2], 'سازمان');
        // روزها
        $daysColumn = $report[0];
        // ۴ اقدام
        $sumColumn1 = $report[1];
        // پرزنت
        $sumColumn2 = $report[2];
        // شو گالری
        $sumColumn3 = $report[3];
        // استارت اکشن
        $sumColumn4 = $report[4];
        // روتین کارگاهی
        $sumColumn5 = $report[5];
        // جمع کل ستون های بالا
        $nameRow    = $report[6];
        $sumRow     = $report[7];
        // افرادی که گزارش رو ثبت کردن و افرادی که ثبت نکردن ----> [0,1]
        $is_submit  = $report[8];
        // افرادی که دکمه گزارش روزانه رو ثبت کردن و افرادی که ثبت نکردن ----> [0,1]
        $usersSendDailyWork = $report[9];
        // --------------------------------------------------------------------------------
        // ۴ اقدام
        $org_sumColumn1 = $orgReport[1];
        // پرزنت
        $org_sumColumn2 = $orgReport[2];
        // شو گالری
        $org_sumColumn3 = $orgReport[3];
        // استارت اکشن
        $org_sumColumn4 = $orgReport[4];
        // روتین کارگاهی
        $org_sumColumn5 = $orgReport[5];
        // جمع کل ستون های بالا
        $org_sumRow     = $orgReport[7];
        
        $start  = g2j( Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' )) ,'Y/m/d');

        return view('admin.potential_organization.four_action.index' ,
         compact('id','start','is_submit','daysColumn','sumColumn1','sumColumn2','sumColumn3','sumColumn4','sumColumn5','nameRow','sumRow',
         'org_sumColumn1','org_sumColumn2','org_sumColumn3','org_sumColumn4','org_sumColumn5','org_sumRow','usersSendDailyWork'),
         ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function users(Request $request, $type) {
        $end    = Carbon::parse(j2g(num2en($request->query('end_date'))))->addDay();
        $start  = Carbon::parse(j2g(num2en($request->query('start_date'))));
        $users  = [intVal($request->id)];
        foreach (Potential::whereIn('id', getSubUser($users)[0] )->pluck('name') as $item) {
            array_push($users, $item);
        }
        
        // $users  = User::whereIn('id', $users )->where('created_at','<',$end)->pluck('id');
        // $items  = FourAction::whereBetween('time_en',[$start,$end])->whereIn('user_id',$users)->get('user_id');
        $users  = User::whereIn('id', $users )->pluck('id');
        if ($request->data=='daily_work') {
            $items  = DailyWork::where('created_at','>',Carbon::today())->whereIn('user_id',$users)->get('user_id');
        } else {
            $items  = FourAction::where('time_en','>',Carbon::today()->subDay())->whereIn('user_id',$users)->get('user_id');
        }

        if ($type=='is_submit') {
            $type   = 'ثبت انجام شده';
            $items  = User::whereIn('id', $items->unique('user_id')->pluck('user_id'))->get();
        } else {
            $type   = 'ثبت انجام نشده';
            $items  = User::whereIn('id', $users)->whereNotIn('id', $items->unique('user_id')->pluck('user_id'))->get();
        }

        return view('admin.potential_organization.four_action.user', compact('items'), ['title1' => 'کاربران'.' - '.$type, 'title2' => 'لیست کاربران']);
    }

    public function filter(Request $request) {
        $end        = Carbon::parse(j2g(num2en($request->query('end_date'))))->addDay();
        $start      = Carbon::parse(j2g(num2en($request->query('start_date'))));
        $org_end    = Carbon::parse(j2g(num2en($request->query('end_date'))))->addDay();
        $org_start  = Carbon::parse(j2g(num2en($request->query('start_date'))));
        $length     = $start->diffInDays( $end, false);
        $report     = $this->report($start , $end, $length, [$request->id], 'شخصی');
        $orgReport  = $this->report($org_start , $org_end, $length, getSubUser([$request->id])[2], 'سازمان');
        $message = '';

        if ($length > persianStartOfMonth()->diffInDays( persianEndOfMonth(), false)) $message = 'برای بازه بیش از ۳۰ روز چارت قابل نمایش نیست';
        
        return response()->json([
            'daysColumn'    => $report[0],
            'sumColumn1'    => $report[1],
            'sumColumn2'    => $report[2],
            'sumColumn3'    => $report[3],
            'sumColumn4'    => $report[4],
            'sumColumn5'    => $report[5],
            'nameRow'       => $report[6],
            'sumRow'        => $report[7],
            'is_submit'     => $report[8],
            'org_sumColumn1'=> $orgReport[1],
            'org_sumColumn2'=> $orgReport[2],
            'org_sumColumn3'=> $orgReport[3],
            'org_sumColumn4'=> $orgReport[4],
            'org_sumColumn5'=> $orgReport[5],
            'org_sumRow'    => $orgReport[7],
            'message'       => $message,
        ], 200);
    }

    public function create($step=1) {
        $today = Carbon::now()->addDay();
        $time = num2fa(my_jdate($today->subDay($step), 'Y/m/d'));
        return view('admin.potential_organization.four_action.create' , compact('time','step'), ['title1' => ' افزودن '.$this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'four_action'       => 'required|integer',
            'present'           => 'required|integer',
            'show_gallery'      => 'required|integer',
            'start_action'      => 'required|integer',
            'workshop_routine'  => 'required|integer',
            'time'              => 'required',
        ],
            [
                'four_action.required'      => 'لطفا چهار اقدام را وارد کنید',
                'four_action.integer'       => 'مقدار چهار اقدام معتبر نیست',
                'present.required'          => 'لطفا پرزنت را وارد کنید',
                'present.integer'           => 'مقدار پرزنت معتبر نیست', 
                'show_gallery.required'     => 'لطفا شو گالری را وارد کنید',
                'show_gallery.integer'      => 'مقدار شو گالری معتبر نیست',
                'start_action.required'     => 'لطفا start_action را وارد کنید',
                'start_action.integer'      => 'مقدار start_action معتبر نیست', 
                'workshop_routine.required' => 'لطفا روتین کارگاهی را وارد کنید',
                'workshop_routine.integer'  => 'مقدار روتین کارگاهی معتبر نیست', 
                'show_gallery.required'     => 'لطفا تاریخ را وارد کنید',
            ]);

        $item = new FourAction;
        try {
            $item->four_action      = $request->four_action;
            $item->present          = $request->present;
            $item->show_gallery     = $request->show_gallery;
            $item->start_action     = $request->start_action;
            $item->workshop_routine = $request->workshop_routine;
            $item->time             = $request->time;
            $item->time_en          = Carbon::parse(j2g(num2en($request->time)));
            $item->user_id          = auth()->user()->id;
            $item->save();
            return redirect()->route('admin.four_action.create')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function usersDailyWork() {
        $item = new DailyWork;
        try {
            $item->user_id = auth()->user()->id;
            if (DailyWork::where('created_at','>',Carbon::today())->where('user_id',auth()->user()->id)->count()==0) $item->save();
            return redirect()->route('admin.four_action.create')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }
    
}

