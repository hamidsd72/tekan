<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\QuadPerformance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Model\Potential;
use App\User;

class ReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'گزارش لیست عملکرد شخصی ۴×۱';
        elseif ('single') return 'گزارش عملکرد شخصی ۴×۱';
    }

    public function __construct() { 
        $this->middleware('permission:daily_schedule_4_1_report_list', ['only' => ['show','filter']]);
    }

    function chartReport($users, $start_day=null, $end_day=null, $label_en=null , $dayOfMonth=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }
        
        if ($label_en===null) {
            $label_en   = ['communication','conversation','networking','growth'];
            $dayOfMonth = $start_day->diffInDays(Carbon::today() , false);
        }
        
        $items  = QuadPerformance::whereIn('user_id', $users )->whereIn('label_en' ,$label_en)->where('status','active')
        ->where('date_en','>',$start_day)->get(['id','label','status','date_en','label_en']);
        
        $list       = [0];
        $repo       = [];
        $days       = ['شروع'];
        $counter    = 0;

        for ($i=0; $i < $dayOfMonth; $i++) {
            $day_items = $items->where('date_en','>=',$start_day->addDay())->where('date_en','<',$end_day->addDay())->count();
            if ($day_items > 0) $counter += 1;
            else                $counter -= 1;

            array_push( $list, $counter);
            array_push( $days, num2fa(my_jdate($start_day,'F d')));
        }
        return [$list,$days];
    }

    function report($users, $start_day=null, $end_day=null, $label_en=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        if ($label_en===null) {
            $label_en='communication';
        }

        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        $items  = QuadPerformance::whereIn('user_id', $users )->where('label_en' ,$label_en)->whereBetween('date_en',[$start_day,$end_day])->get('user_id')->unique('user_id')->count();

        return [
            num2fa(' %'.intVal(($items*100)/count($users))).' / نفر ثبت کرده '.num2fa($items),
            num2fa(' %'.intVal(((count($users)-$items)*100)/count($users))).' / نفر ثبت نکرده '.num2fa(count($users)-$items),
        ];
    }

    function newreport($users, $date=null, $label_en=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');

        if ($label_en===null) {
            $label_en='communication';
        }

        if ($date===null) $date = Carbon::today();
        $end_day    = Carbon::parse(j2g(my_jdate($date, 'Y/m/d')))->addDay();
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }
        
        // $items  = QuadPerformance::whereIn('user_id', $users )->where('label_en' ,$label_en)->whereBetween('date_en',[$date,$end_day])->get('user_id')->unique('user_id')->count();
        $items  = QuadPerformance::whereIn('user_id', $users )->whereBetween('date_en',[$date,$end_day])->get('user_id')->unique('user_id')->count();
        $users  = count(array_unique($users));

        return [
            num2fa(' %'.intVal(($items*100)/$users)).' / نفر ثبت کرده '.num2fa($items),
            num2fa(' %'.intVal((($users-$items)*100)/$users)).' / نفر ثبت نکرده '.num2fa($users-$items),
        ];
    }

    function newreport2($users, $date=null, $label_en=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get(['name','user_id']);

        if ($label_en===null) {
            $label_en='communication';
        }

        if ($date===null) $date = Carbon::today();
        $end_day    = Carbon::parse(j2g(my_jdate($date, 'Y/m/d')))->addDay();
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }
        
        $items  = QuadPerformance::whereIn('user_id', $users )->whereBetween('date_en',[$date,$end_day])->get('user_id')->unique('user_id');
        if ($items->count()) {
            $subs   = $subs->whereNotIn('name', $items->pluck('user_id'));
        }
        return [$items->pluck('user_id'), $subs->pluck('name')];
    }

    public function show($id) {
        $id         = [$id];
        $list       = getSubUser($id)[2];
        $start_day  = persianStartOfMonth();
        // $end_day    = Carbon::now();
        $items      = $this->chartReport($id ,persianStartOfMonth()->subDay() ,persianStartOfMonth());
        // $list1      = $this->report($id ,$start_day ,$end_day);
        // $list2      = $this->report($list ,$start_day ,$end_day);
        $list1      = $this->newreport($id);
        $list2      = $this->newreport($list);
        $month      = my_jdate(Carbon::today(),'m');
        return view('admin.daily-schedule.report.index', compact('id','items','list1','list2','id','month'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function filter(Request $request) {
        $users  = [$request->id];
        $list   = getSubUser($users)[2];

        if ($request->year && $request->month) {
            $year   = $request->year;
            $month  = $request->month;
            if (strlen($request->month) < 2) $month = '0'.$request->month;
            switch (intVal($request->month)) {
                case 12:
                    $addDay = 28;
                    break;
                case 1:
                    $addDay = 30;
                    break;
                case 2:
                    $addDay = 30;
                    break;
                case 3:
                    $addDay = 30;
                    break;
                default:
                    $addDay = 29;
                    break;
            }

            $end_day    = Carbon::parse(j2g($year.'/'.$month.'/01'));
            $loop       = (Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay())->diffInDays( (Carbon::parse(j2g($year.'/'.$month.'/01'))->addDay($addDay)) , false);
            if( $request->month == my_jdate(Carbon::today(), 'm') ) $loop = (Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay())->diffInDays( Carbon::now() , false);
            $items      = $this->chartReport($users ,Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay() ,$end_day ,[$request->label_en], $loop);
            return response()->json(['chart' => $items] , 200); 
        }

        if ($request->date) {
            $date = Carbon::parse(j2g(num2en($request->date)));
            $list1      = $this->newreport($users ,$date);
            $list2      = $this->newreport($list ,$date);
            return response()->json(['list1' => $list1,'list2' => $list2] , 200); 
        }

        return response()->json(['message' => 'مشگل داخلی سرور'] , 500); 
    }

    public function users(Request $request, $type) {
        $start      = Carbon::parse(j2g(num2en($request->start_date)));
        if ($request->type=='single') {
            $list   = [$request->id];
        } else {
            $list   = getSubUser([$request->id])[2];
        }
        $list = $this->newreport2($list, $start);
        if ($type=='active') {
            $type   = 'ثبت انجام شده';
            $items  = User::whereIn('id', $list[0])->get();
        } else {
            $type   = 'ثبت انجام نشده';
            $items  = User::whereIn('id', $list[1])->get();
        }

        return view('admin.potential_organization.four_action.user', compact('items'), ['title1' => 'کاربران'.' - '.$type, 'title2' => 'لیست کاربران']);
    }
}
