<?php

namespace App\Http\Controllers\Admin\Target;

use App\Model\Target;
use App\Model\QuadPerformance;
use App\Model\OrgPerformance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class TargetController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'اهداف';
        elseif ('single') return 'هدف';
    }

    public function __construct() { 
        $this->middleware(['auth','OnlyActiveUser']);
        $this->middleware('permission:target_system_list', ['only' => ['show','store','update','filter']]);
        $this->middleware('permission:target_me_list', ['only' => ['show','store','update','filter']]);
    }

    public function index($id=null ,$step=1, $step2=1) {
        if ($id===null) {
            $id = auth()->id();
            if (auth()->user()->roles->count()==0) auth()->user()->assignRole('general');
            meet_updater();
        }
        $user_id    = $id;
        if ($step < 1) {
            $add = abs($step-1);
            $time  = num2fa(my_jdate(Carbon::today()->addDay()->addDay($add), 'Y/m/d'));
        } else {
            $time   = num2fa(my_jdate(Carbon::today()->addDay()->subDay($step), 'Y/m/d'));
        }

        if ($step2 < 1) {
            $add2 = abs($step2-1);
            $time2  = num2fa(my_jdate(Carbon::today()->addDay()->addDay($add2), 'Y/m/d'));
        } else {
            $time2  = num2fa(my_jdate(Carbon::today()->addDay()->subDay($step2), 'Y/m/d'));
        }

        $nItem  = \App\User::find($id)->active_target();
        $start  = Carbon::parse(j2g(num2en($time)));
        $end    = Carbon::parse(j2g(num2en($time)))->addDay();
        $start2 = Carbon::parse(j2g(num2en($time2)));
        $end2   = Carbon::parse(j2g(num2en($time2)))->addDay();

        $perDailyReport = QuadPerformance::where('user_id', $id )->whereBetween('date_en', [$start,$end])->get();
        foreach ($perDailyReport as $item) {
            $item->activate = true;
            if (Carbon::today()->diffInDays(Carbon::parse($item->date_en), false) > 0) {
                $item->activate = false;
            }
        }
        $orgDailyReport = OrgPerformance::where('user_id', $id )->whereBetween('date_en', [$start2,$end2])->get();
        foreach ($orgDailyReport as $item) {
            $item->activate = true;
            if (Carbon::today()->diffInDays(Carbon::parse($item->date_en), false) > 0) {
                $item->activate = false;
            }
        }
        return view('admin.target.system', compact('user_id','nItem','perDailyReport','orgDailyReport','step','step2','time','time2'), ['title1' => 'داشبورد', 'title2' => 'داشبورد' ]);
    }

    public function show($id) {
        $item = auth()->user()->active_target();
        return view('admin.target.personal', compact('id','item'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('single')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'level'     => 'max:250',
            'personal'  => 'max:250',
            'network'   => 'max:250',
            'burning'   => 'max:250',
            'other'     => 'max:2550',
        ],
            [
                'level.max'     => 'هدف لول ماه نباید بیشتر از 250 کاراکتر باشد',
                'personal.max'  => 'هدف فروش شخصی نباید بیشتر از 250 کاراکتر باشد',
                'network.max'   => 'هدف شبکه سازی نباید بیشتر از 250 کاراکتر باشد',
                'burning.max'   => 'هدف سوزان نباید بیشتر از 250 کاراکتر باشد',
                'other.max'     => 'سایر اهداف نباید بیشتر از 2550 کاراکتر باشد',
            ]);

        try {

            $item = Target::where('user_id', auth()->id())->first();

            if ($item) {
                if ($request->level)    $item->level    = $request->level;
                if ($request->personal) $item->personal = $request->personal;
                if ($request->network)  $item->network  = $request->network;
                if ($request->burning)  $item->burning  = $request->burning;
                if ($request->other)    $item->other    = $request->other;
                $item->update();
            }else {
                $item = new Target();
                $item->level    = $request->level;
                $item->personal = $request->personal;
                $item->network  = $request->network;
                $item->burning  = $request->burning;
                $item->other    = $request->other;
                $item->date     = Carbon::today();
                $item->user_id  = auth()->user()->id;
                $item->save();
            }
                    
            return redirect()->back()->withInput()->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request , $id) {
        $item = Target::where('user_id', auth()->id())->findOrFail($id);

        $this->validate($request, [
            'level'     => 'max:250',
            'personal'  => 'max:250',
            'network'   => 'max:250',
            'burning'   => 'max:250',
            'other'     => 'max:2550',
        ],
            [
                'level.max'     => 'هدف لول ماه نباید بیشتر از 250 کاراکتر باشد',
                'personal.max'  => 'هدف فروش شخصی نباید بیشتر از 250 کاراکتر باشد',
                'network.max'   => 'هدف شبکه سازی نباید بیشتر از 250 کاراکتر باشد',
                'burning.max'   => 'هدف سوزان نباید بیشتر از 250 کاراکتر باشد',
                'other.max'     => 'سایر اهداف نباید بیشتر از 2550 کاراکتر باشد',
            ]);

        try {
            if ($request->level)    $item->level    = $request->level;
            if ($request->personal) $item->personal = $request->personal;
            if ($request->network)  $item->network  = $request->network;
            if ($request->burning)  $item->burning  = $request->burning;
            if ($request->other)    $item->other    = $request->other;
            $item->update();
            return redirect()->back()->withInput()->with('flash_message', ' ویرایش آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function filter($id, Request $request) {
        if ($request->time) {
            $start  = Carbon::parse(j2g(num2en($request->time)));
            $end    = Carbon::parse(j2g(num2en($request->time)))->addDay();
            return response()->json([ 'time' => QuadPerformance::where('user_id', $id )->whereBetween('date_en', [$start,$end])->get()], 200);
        }
        
        $start2 = Carbon::parse(j2g(num2en($request->time2)));
        $end2   = Carbon::parse(j2g(num2en($request->time2)))->addDay();
        $items = OrgPerformance::where('user_id', $id )->whereBetween('date_en', [$start2,$end2])->get();
        foreach ($items as $item) $item->label = $item->label->label;
        return response()->json([ 'time' => $items], 200);
    }
}
