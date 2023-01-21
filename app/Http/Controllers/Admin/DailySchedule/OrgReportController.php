<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\OrgPerformance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Model\Potential;
use App\Model\LabelPerformance;

class OrgReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'گزارش لیست عملکرد شخصی سازمانی';
        elseif ('single') return 'گزارش عملکرد شخصی سازمانی';
    }

    public function __construct() { $this->middleware('auth'); }

    function chartReport($users, $start_day=null, $end_day=null, $label=1) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        $items  = OrgPerformance::whereIn('user_id', $users )->where('label_id' ,$label)->where('status','active')
        ->whereBetween('date_en',[$start_day,$end_day])->get(['id','label_id','status','date_en']);
        
        $dayOfMonth = $start_day->diffInDays($end_day , false);
        $list       = [0];
        $days       = [];
        $counter    = 0;

        for ($i=0; $i < $dayOfMonth; $i++) {
            $day_items = $items->whereBetween('date_en',[$start_day->addDay(), $end_day->addDay()])->count();
            if ($day_items > 0) $counter += 1;
            else                $counter -= 1;

            array_push( $list, $counter);
            array_push( $days, num2fa(my_jdate($start_day,'F d')));
        }

        return [$list,$days];
    }

    function report($users, $start_day=null, $end_day=null, $label=1) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        $items  = OrgPerformance::whereIn('user_id', $users )->where('label_id' ,$label)->whereBetween('date_en',[$start_day,$end_day])->get('user_id')->unique('user_id')->count();

        return [
            num2fa(' %'.intVal(($items*100)/count($users))).' / نفر ثبت کرده '.num2fa($items),
            num2fa(' %'.intVal(((count($users)-$items)*100)/count($users))).' / نفر ثبت نکرده '.num2fa(count($users)-$items),
        ];
    }

    public function show($id) {
        $id         = [$id];
        $list       = getSubUser($id)[0];
        $start_day  = persianStartOfMonth();
        $end_day    = persianEndOfMonth()->addDay();
        $items      = $this->chartReport($id ,$start_day ,$end_day);
        $list1      = $this->report($id ,$start_day ,$end_day);
        $list2      = $this->report($list ,$start_day ,$end_day);
        $month      = my_jdate(Carbon::today(),'m');
        $labels     = LabelPerformance::orderBy('sort')->get();
        return view('admin.daily-schedule.org-report.index', compact('items','list1','list2','id','month','labels'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function filter(Request $request) {
        $users  = [$request->id];
        $list   = getSubUser($users)[0];

        if ($request->year && $request->month) {
            $year   = $request->year;
            $month  = $request->month;
            if (strlen($request->month) < 2) $month = '0'.$request->month;
            switch (intVal($request->month)) {
                case 12:
                    $addDay = 29;
                    break;
                case 1:
                    $addDay = 31;
                    break;
                case 2:
                    $addDay = 31;
                    break;
                case 3:
                    $addDay = 31;
                    break;
                default:
                    $addDay = 30;
                    break;
            }
            
            $start_day  = Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay();
            $end_day    = Carbon::parse(j2g($year.'/'.$month.'/01'))->addDay($addDay);

            $items      = $this->chartReport($users ,$start_day ,$end_day ,$request->label);
            $list1      = $this->report($users ,$start_day ,$end_day ,$request->label);
            $list2      = $this->report($list ,$start_day ,$end_day ,$request->label);
     
            return response()->json(['items' => $items, 'list1' => $list1,'list2' => $list2] , 200); 
        }

        return response()->json(['message' => 'مشگل داخلی سرور'] , 500); 
    }

}
