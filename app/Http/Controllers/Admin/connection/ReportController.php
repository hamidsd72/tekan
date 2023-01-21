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

    public function __construct() { $this->middleware('auth'); }

    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        return strtr( $input, $replace_pairs );
    }

    public function toFaNumber($input) {
        $replace_pairs = array( '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹' );
        return strtr( $input, $replace_pairs );
    }

    function monthsName() {
        return [
            'فرودین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند',
        ];
    }

    function persianStartOfYear($year=null) {
        if ($year) {
            return Carbon::parse(j2g($year.'/01/01'));
        }
        return Carbon::parse(j2g(my_jdate(Carbon::now(), 'Y').'/01/01'));
    }

    function persianStartOfMonth() {
        return Carbon::now()->subDay( my_jdate(Carbon::now(), 'd') - 1 );
    }

    function persianEndOfMonth() {
        $firstOfMonth = $this->persianStartOfMonth();
        $month = my_jdate(Carbon::now(), 'm');


        if ($month == 12) {
            $endOfMonth = $firstOfMonth->addDay(29);
        } elseif ($month < 12 && $month > 6) {
            $endOfMonth = $firstOfMonth->addDay(30);
        } else {
            $endOfMonth = $firstOfMonth->addDay(31);
        }

        if ($month < my_jdate($endOfMonth, 'm')) {
            return $endOfMonth;
        }
        // برای سال کبیسه
        return $endOfMonth->addDay();
    }

    public function cartCalculate($startOfYear, $endOfYear, $startMonth=1) {
        // زمان شروع
        $start = $startOfYear;
        $end   = $endOfYear;
        // اگر امسال بود حداکثر تا ماه فعلی جلو بره
        $endmonth = my_jdate(Carbon::now(), 'm');
        // اگر سال های قبل بود ۱۲ ماه رو بگیره
        if (intVal($start->format('Y')) < intVal($this->persianStartOfYear()->format('Y'))) $endmonth = 12;
        // شمارنده
        $index = 0;
        $new_added  = array();
        $finished   = array();
        
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
        $chat_data = array();
        $months    = array();
        // محاسبه اطلاعات چارت
        $data  = $this->cartCalculate($this->persianStartOfYear(),$this->persianStartOfYear());
        // گرفتن نام ماه به تعداد لازم
        for ($i=0; $i < count($data[0]); $i++) array_push( $months, $this->monthsName()[$i] );
        // بارگذاری اطلاعات چارت
        array_push( $chat_data, $months );
        array_push( $chat_data, $data[0] );
        array_push( $chat_data, $data[1] );
        // مقادیر چاپی
        $new_added = Connection::where('user_id', auth()->user()->id )->where('created_at','>', Carbon::today() )->where('status',null)->count();
        $finished  = Connection::where('user_id', auth()->user()->id )->where('created_at','>', Carbon::today() )->where('status','!=',null)->count();
        return view('admin.connection.report.index', compact('chat_data','new_added','finished'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function search(Request $request) {
        try {
            if ($request->query('chart')=='chart') {                
                $months = array();
                // محاسبه اطلاعات چارت
                $data = $this->cartCalculate($this->persianStartOfYear($request->query('chart_year')),$this->persianStartOfYear($request->query('chart_year')));
                // گرفتن نام ماه به تعداد لازم
                for ($i=0; $i < 12; $i++) array_push( $months, $this->monthsName()[$i] );
                return response()->json(['months' => $months, 'data1' => $data[0], 'data2' => $data[1]], 200); 
            } elseif ($request->query('chart')=='text') {
                $startDate = carbon::parse(j2g($this->toEnNumber($request->query('start_date'))));
                $endDate = carbon::parse(j2g($this->toEnNumber($request->query('end_date'))))->addDay();
                $new_added = Connection::where('user_id', auth()->user()->id )->whereBetween('created_at',[$startDate,$endDate])->where('status',null)->count();
                $finished  = Connection::where('user_id', auth()->user()->id )->whereBetween('created_at',[$startDate,$endDate])->where('status','!=',null)->count();
                return response()->json(['new_added' => $this->toFaNumber($new_added), 'finished' => $this->toFaNumber($finished)], 200); 
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'مشکلی در جستجو در گزارشات بوجود آمده،مجددا تلاش کنید'], 500); 
        }
    }

}




