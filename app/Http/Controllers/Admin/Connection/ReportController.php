<?php

namespace App\Http\Controllers\Admin\Connection;

use App\Model\Connection;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست گزارشات';
        elseif ('single') return 'گزارشات';
    }

    public function __construct() {
        $this->middleware('permission:connection_report_list', ['only' => ['index',]]);
    }

    public function cartCalculate($startOfYear, $endOfYear, $startMonth=1) {
        // زمان شروع
        $start = $startOfYear;
        $end   = $endOfYear;
        // اگر امسال بود حداکثر تا ماه فعلی جلو بره
        $endmonth = my_jdate(Carbon::now(), 'm');
        // اگر سال های قبل بود ۱۲ ماه رو بگیره
        if (intVal($start->format('Y')) < intVal(persianStartOfYear()->format('Y'))) $endmonth = 12;
        // شمارنده
        $index = 0;
        $new_added  = [0];
        $finished   = [0];
        
        for ( $i = $startMonth; $i <= $endmonth; $i++) {
            // اضافه کردن یک ماه به زمان شروع محاسبه
            if ($i > 6 && $i < 12) $addDay = 30;
            if ($i < 7) $addDay = 31;
            else $addDay = 29;
            // شمارنده برای اصلاح زمان داخل حلقه میباشد
            if ($index > 0) $start = $start->addDay($addDay);
            $end = $end->addDay($addDay);
            array_push(
                $new_added,
                Connection::where('user_id', auth()->user()->id )->where('created_at','>', $start )->where('created_at','<', $end )->count()
            );
            array_push(
                $finished,
                Connection::where('user_id', auth()->user()->id )->where('status','!=',null)->where('created_at','>', $start )->where('created_at','<', $end )->count()
            );
            $index += 1;
        }
        return [$new_added,$finished];
    }

    public function index() {
        $chat_data = [];
        $months    = ['شروع'];
        // محاسبه اطلاعات چارت
        $data  = $this->cartCalculate(persianStartOfYear(),persianStartOfYear());
        // گرفتن نام ماه به تعداد لازم
        for ($i=0; $i < count($data[0]); $i++) array_push( $months, faMonthsName()[$i] );
        // بارگذاری اطلاعات چارت
        array_push( $chat_data, $months );
        array_push( $chat_data, $data[0] );
        array_push( $chat_data, $data[1] );
        // مقادیر چاپی
        $start_date=g2j(Carbon::today(),'Y/m').'/01';
        $start_date_en=Carbon::parse(j2g($start_date));
        $start=g2j($start_date_en,'Y/m/d');
        $new_added = Connection::where('user_id', auth()->user()->id )->whereDate('created_at','>=', $start_date_en )->whereDate('created_at','<=', Carbon::today() )->count();
        $finished  = Connection::where('user_id', auth()->user()->id )->whereDate('created_at','>=', $start_date_en )->whereDate('created_at','<=', Carbon::today() )->where('status','!=',null)->count();
        return view('admin.connection.report.index', compact('chat_data','start','new_added','finished'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function search(Request $request) {
        try {
            if ($request->query('chart')=='chart') {                
                $months = ['شروع'];
                // محاسبه اطلاعات چارت
                $data = $this->cartCalculate(persianStartOfYear($request->query('chart_year')),persianStartOfYear($request->query('chart_year')));
                // گرفتن نام ماه به تعداد لازم
                for ($i=0; $i < 12; $i++) array_push( $months, faMonthsName()[$i] );
                return response()->json(['months' => $months, 'data1' => $data[0], 'data2' => $data[1]], 200); 
            } elseif ($request->query('chart')=='text') {
                $startDate = carbon::parse(j2g(toEnNumber($request->query('start_date'))));
                $endDate = carbon::parse(j2g(toEnNumber($request->query('end_date'))))->addDay();
                $new_added = Connection::where('user_id', auth()->user()->id )->whereBetween('created_at',[$startDate,$endDate])->where('status',null)->count();
                $finished  = Connection::where('user_id', auth()->user()->id )->whereBetween('created_at',[$startDate,$endDate])->where('status','!=',null)->count();
                return response()->json(['new_added' => toFaNumber($new_added), 'finished' => toFaNumber($finished)], 200); 
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'مشکلی در جستجو در گزارشات بوجود آمده،مجددا تلاش کنید'], 500); 
        }
    }

}




