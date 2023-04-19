<?php

namespace App\Http\Controllers\Admin\DailySchedule;

use App\Model\OrgPerformance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Model\Potential;
use App\Model\LabelPerformance;
use App\User;

class OrgReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'گزارش لیست عملکرد شخصی برای سازمانی';
        elseif ('single') return 'گزارش عملکرد شخصی برای سازمانی';
    }

    public function __construct() { 
        $this->middleware('permission:daily_schedule_org_report_list', ['only' => ['show','filter']]);
    }

    function chartReport($users, $start_day=null, $end_day=null, $label=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        if ($label===null) {
            $label = 1;
            $dayOfMonth = $start_day->diffInDays(Carbon::today() , false);
        } else $dayOfMonth = $start_day->diffInDays($end_day , false);

        $items  = OrgPerformance::whereIn('user_id', $users )->where('label_id' ,$label)->where('status','active')
        ->whereBetween('date_en',[$start_day,$end_day])->get(['id','label_id','status','date_en']);
        
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

    function newchartReport($users, $start_day=null, $end_day=null) {
        $list   = [];
        $total  = [];
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        $items  = OrgPerformance::whereIn('user_id', $users )->where('status','active')->whereBetween('date_en',[$start_day,$end_day])->get(['id','label_id']);
        
        $labels = LabelPerformance::all(['id','label']);

        foreach ($labels as $label) {
            array_push( $list, $label->label);
            array_push( $total, $items->where('label_id', $label->id)->count() );
        }

        return [$list,$total];
    }

    function report($users, $start_day=null, $end_day=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        foreach ($subs as $sub) {
            array_push($users, $sub->name );
        }

        $items  = OrgPerformance::whereIn('user_id', $users )->whereBetween('date_en',[$start_day,$end_day])->get('user_id')->unique('user_id')->count();

        return [
            num2fa(' %'.intVal(($items*100)/count($users))).' / نفر ثبت کرده '.num2fa($items),
            num2fa(' %'.intVal(((count($users)-$items)*100)/count($users))).' / نفر ثبت نکرده '.num2fa(count($users)-$items),
        ];
    }

    function newreport($users, $date=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get('name');
        
        if ($date===null) $date = Carbon::today();
        $end_day    = Carbon::parse(j2g(my_jdate($date, 'Y/m/d')))->addDay();

        $users  = [];
        foreach ($subs as $sub) array_push($users, $sub->name );

        $items  = OrgPerformance::whereIn('user_id', $users )->whereBetween('date_en',[$date,$end_day])->get('user_id')->unique('user_id')->count();
        $users  = count(array_unique($users));

        return [
            num2fa(' %'.intVal(($items*100)/$users)).' / نفر ثبت کرده '.num2fa($items),
            num2fa(' %'.intVal((($users-$items)*100)/$users)).' / نفر ثبت نکرده '.num2fa($users-$items),
        ];
    }

    function newreport2($users, $date=null) {
        $users  = $users;
        $subs   = Potential::whereIn('user_id',$users)->get(['name','user_id']);
        
        if ($date===null) $date = Carbon::today();
        $end_day    = Carbon::parse(j2g(my_jdate($date, 'Y/m/d')))->addDay();

        $users  = [];
        foreach ($subs as $sub) array_push($users, $sub->name );

        $items  = OrgPerformance::whereIn('user_id', $users )->whereBetween('date_en',[$date,$end_day])->get('user_id')->unique('user_id');
        $users  = count(array_unique($users));

        if ($items->count()) {
            $subs   = $subs->whereNotIn('name', $items->pluck('user_id'));
        }
        return [$items->pluck('user_id'), $subs->pluck('name')];
    }

    public function show($id) {
        $id         = [$id];
        $list       = getSubUser($id)[2];

        $start_day  = persianStartOfMonth();
        $end_day    = Carbon::now();

        // $items      = $this->chartReport($id ,persianStartOfMonth()->subDay() ,persianStartOfMonth());
        // $list1      = $this->report($id ,$start_day ,$end_day);
        // $list2      = $this->report($list ,$start_day ,$end_day);
        $items      = $this->newchartReport($id ,persianStartOfMonth()->subDay() ,$end_day);
        $list1      = $this->newreport($id);
        $list2      = $this->newreport($list);
        $month      = my_jdate(Carbon::today(),'m');
        $labels     = LabelPerformance::where('status','active')->orderBy('sort')->get();
        return view('admin.daily-schedule.org-report.index', compact('id','items','list1','list2','id','month','labels'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
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
            
            $start_day  = Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay();
            $end_day    = Carbon::parse(j2g($year.'/'.$month.'/01'))->addDay($addDay);
            $items      = $this->newchartReport($users ,Carbon::parse(j2g($year.'/'.$month.'/01'))->subDay() ,$end_day);
     
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
        $list = getSubUser([$request->id])[2];
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
