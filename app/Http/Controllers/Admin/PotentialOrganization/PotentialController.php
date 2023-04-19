<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\User;
use App\Model\Potential;
use App\Model\PotentialReport;
use App\Model\FourAction;
use App\Model\MonthlyPackage;
use App\Model\Following;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class PotentialController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') {
            return 'لیست پتانسیل سازمان';
        } elseif ('single') {
            return 'پتانسیل';
        }
    }

    function next_report($list, $year, $month) {
        $data = [];
        // اماده سازی تاریخ
        if (strlen($month) < 2) $month = '0'.$month;
        switch (intVal($month)) {
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


        $start_day  = Carbon::parse(j2g($year.'/'.$month.'/01'));
        $end_day    = Carbon::parse(j2g($year.'/'.$month.'/01'))->addDay($addDay);
        // پایان اماده سازی تاریخ
        
        $potential  = Potential::whereIn('user_id', $list )->get();
        if ($potential->count()) {
            $items  = PotentialReport::whereIn('potential_id', $potential->pluck('id') )->whereBetween('date',[$start_day,$end_day])->get();
            
            // hadaf_gozari_shakhsi
            $active_items = $items->where('column_name' ,'hadaf_gozari_shakhsi');
            if ( $active_items->count() ) {
                $item   = $active_items->where('value',1500000 );
                if ($item->count()) array_push($data, ' هدف گذاری فروش یک و نیم م: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value',3000000 );
                if ($item->count()) array_push($data, ' هدف گذاری فروش سه م : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value',6000000 );
                if ($item->count()) array_push($data, ' هدف گذاری فروش پنج م : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value',10000000 );
                if ($item->count()) array_push($data, ' هدف گذاری فروش ده م : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
            }

            // hadaf_gozari_level
            $active_items = $items->where('column_name' ,'hadaf_gozari_level');
            if ( $active_items->count() ) {
                $item   = $active_items->where('value', 'نمایده ۲ ستاره' );
                if ($item->count()) array_push($data, ' هدف گذاری لول نمایده ۲ ستاره: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'نمایده ۳ ستاره' );
                if ($item->count()) array_push($data, ' هدف گذاری لول نمایده ۳ ستاره: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'نمایده ۴ ستاره' );
                if ($item->count()) array_push($data, ' هدف گذاری لول نمایده ۴ ستاره: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'نمایده مستقل' );
                if ($item->count()) array_push($data, ' هدف گذاری لول نمایده مستقل: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'حامی نقرهای' );
                if ($item->count()) array_push($data, ' هدف گذاری لول حامی نقرهای: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'حامی طلایی' );
                if ($item->count()) array_push($data, ' هدف گذاری لول حامی طلایی: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'حامی پلاتین' );
                if ($item->count()) array_push($data, ' هدف گذاری لول حامی پلاتین: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'حامی الماس' );
                if ($item->count()) array_push($data, ' هدف گذاری لول حامی الماس: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'شبکه ساز نقره ای' );
                if ($item->count()) array_push($data, ' هدف گذاری لول شبکه ساز نقره ای: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'شبکه ساز طلایی' );
                if ($item->count()) array_push($data, ' هدف گذاری لول شبکه ساز طلایی: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
            }

            // candid_shabakesazi
            $active_items = $items->where('column_name' ,'candid_shabakesazi');
            if ( $active_items->count() ) {
                $item   = $active_items->where('value', 'برنزی' );
                if ($item->count()) array_push($data, ' تندیس برنزی شبکه سازی : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'نقره ای' );
                if ($item->count()) array_push($data, ' تندیس نقره ای شبکه سازی : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );

                $item   = $active_items->where('value', 'طلایی' );
                if ($item->count()) array_push($data, ' تندیس طلا شبکه سازی : '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
            }

            // candid_forosh
            $active_items = $items->where('column_name' ,'candid_forosh');
            if ( $active_items->count() ) {
                $item   = $active_items->where('value', 'برنزی' );
                if ($item->count()) array_push($data, ' کاندید فروش برنزی: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'نقره ای' );
                if ($item->count()) array_push($data, ' کاندید فروش نقره ای: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
                
                $item   = $active_items->where('value', 'طلایی' );
                if ($item->count()) array_push($data, ' کاندید فروش طلایی: '.num2fa($item->count()).' کاندید / '.num2fa($item->where('status','active')->count()).' تیک خورده ('.intVal(($item->where('status','active')->count()*100)/$item->count()).'%)' );
            }
        }
        
        return $data;
    }
    
    public function __construct() {
        $this->middleware('permission:potential_list', ['only' => ['index']]);
        $this->middleware('permission:potential_create', ['only' => ['create','store']]);
        $this->middleware('permission:potential_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:potential_status', ['only' => ['reactivate']]);
        $this->middleware('permission:potential_report_list', ['only' => ['report']]);
        $this->middleware('permission:potential_org_report_list', ['only' => ['report']]);
        $this->middleware('permission:potential_org_list', ['only' => ['list']]);
    }

    public function report(Request $request, $id, $type='single', $filter=false) {
        if ($id===null) $id = auth()->user()->id;
        if ($type=='single') {
            $users = [$id];
            $url  = route('admin.potential-list.report.list.filter',[$id,'single']);
            $append_text = 'شخصی';
        } else {
            $users = getSubUser([$id])[2];
            $url  = route('admin.potential-list.report.list.filter',[$id,'all']);
            $append_text = 'سازمانی';
        }

        $start = persianStartOfMonth();
        if ($request->start) $start = Carbon::parse(j2g(str_replace("'","",num2en($request->start))));

        $end = Carbon::now();
        if ($request->end) {
            $end = Carbon::parse(j2g(str_replace("'","",num2en($request->end))));
            $filter = true;
        }
        
        $new_items  = $this->next_report( $users, my_jdate( Carbon::now(),'Y' ), my_jdate( Carbon::now(),'m' ));
        // $items      = Potential::whereIn( 'user_id' , $users )->whereBetween('created_at', [$start, $end] )->get();
        $items      = Potential::whereIn( 'user_id' , $users )->get();
        if ($items->count())    $childs = Potential::whereIn( 'user_id' , $items->pluck('name'))->get(['user_id','name']);
        else                    $childs = false;

        $list           = [];
        $text           = [];
        $m_pack_title   = [];
        $m_pack_value   = [];

        // تعداد کسب و کار کوچک
        $buss   = $items->where('present_ta_peresent','!=',null);
        $small  = $buss->where('kasb_o_kar_kochak_ya_bozorg','کوچک')->count();
        $big    = $buss->where('kasb_o_kar_kochak_ya_bozorg','بزرگ')->count();
        array_push($list,  $small>0?$small.' نفر / '.intVal(($small*100)/$buss->count()).'%':'0 نفر / 0%' );
        array_push($list,  $big>0?$big.' نفر / '.intVal(($big*100)/$buss->count()).'%':'0 نفر / 0%' );
        array_push($text, 'تعداد کسب و کار کوچک' );
        array_push($text, 'تعداد کسب و کار بزرگ' );
        
        // فالویی
        $folowe = $buss->where('folowe_ya_4eqdam','فالویی')->count();
        array_push($list,  $folowe>0?$folowe.' نفر / '.intVal(($folowe*100)/$buss->count()).'%':'0 نفر / 0%' );
        array_push($text, 'فالویی' );

        // چهار اقدام
        $buss2  = $buss->where('present_ta_estage','!=',null);
        $eqdam  = $buss2->where('folowe_ya_4eqdam','چهار اقدام')->count();
        array_push($list,  $eqdam>0?$eqdam.' نفر / '.intVal(($eqdam*100)/$buss->count()).'%':'0 نفر / 0%' );
        array_push($text, 'چهار اقدامی' );

        // تعداد پرزنت شده ها - four action
        array_push($list,  FourAction::whereIn( 'user_id' , $users )->whereBetween('created_at', [$start, $end] )->count('present').' نفر ');
        array_push($text, 'تعداد پرزنت شده ها (از چهار اقدام)' );

        // تعداد خرید اولیه گرفته شده و نشده
        $zade   = $buss->count();
        $nazade = $items->where('present_ta_peresent',null)->count();
        array_push($list, $zade.' نفر زده / '.$nazade.' نفر نزده ');
        array_push($text, 'تعداد خرید اولیه' );

        // تعداد فست استارت زده و نزده
        $zade   = $buss->whereIn('present_ta_peresent',['آموزش فست استارت دیده','اولین پرزنت ست شده','اولین پرزنت ست نشده'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر دیده / '.$nazade.' نفر ندیده ');
        array_push($text, 'آموزش فست استارت' );


        // تعداد افرادی که ورودی شبکه ساز گرفتن و نگرفتن
        $zade   = $buss2->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر گرفتن / '.$nazade.' نفر نگرفتن ');
        array_push($text, 'تعداد افرادی که اولین ورودی شبکه ساز' );

        // تعداد افرادی که آموزش پشتیبان دیده و ندیده
        $zade   = $buss->whereNotIn('present_ta_estage',['اولین ورودی گرفته نشده','اولین ورودی گرفته شده','آموزش پشتیبان ندیده'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر دیدن / '.$nazade.' نفر ندیدن ');
        array_push($text, 'تعداد افرادی که آموزش پشتیبانی' );

        // تعداد افرادی که آموزش همانندسازی دیده و ندیده
        $zade   = $buss->whereNotIn('present_ta_estage',['اولین ورودی گرفته نشده','اولین ورودی گرفته شده','آموزش پشتیبان ندیده','آموزش پشتیبان دیده','دومین ورودی گرفته شده','دومین ورودی گرفته نشده','آموزش همانندسازی ندیده'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر دیدن / '.$nazade.' نفر ندیدن ');
        array_push($text, 'تعداد افرادی که آموزش همانندسازی' );

        // تعداد افرادی که استیج ۲ نفره شبکه ساز گرفتن و نگرفتن
        $zade   = $buss->whereNotIn('present_ta_estage',['دومین ورودی گرفته شده','آموزش همانندسازی دیده','آموزش همانندسازی ندیده','۴ تا تیم اکتیو دارد','۴ تا تیم اکتیو ندارد'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر زدن / '.$nazade.' نفر نزدن ');
        array_push($text, 'تعداد افرادی که استیج ۲ نفره شبکه ساز' );

        // تعداد افرادی که استیج ۴ نفره شبکه ساز گرفتن و نگرفتن
        $zade   = $buss->where('present_ta_estage','۴ تا تیم اکتیو دارد')->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر زدن / '.$nazade.' نفر نزدن ');
        array_push($text, 'تعداد افرادی که استیج ۴ نفره شبکه ساز' );
        
        // تعداد افرادی که پتاتسیل ست کردن و نکردن
        $zade = 0;
        if ($childs) {
            foreach ($items as $item) {
                if ($childs->where( 'user_id' , $item->name)->count()) {
                    $zade += 1;
                }
            }
        }

        // $nazade = $buss->where('create_by','!=','self')->count() - $zade;
        $nazade = $items->count() - $zade;
        // array_push($list, $zade.' نفر ست کرده / '.$nazade.' نفر ست نکرده ');
        // array_push($text, 'تعداد پرزنت شده ها (پتانسیل ها)' );
        
        $items = [];
        for ($i = 0; $i < count($list); $i++) {
            array_push($items, num2fa($list[$i]) );
        }    

        foreach (MonthlyPackage::where('status','active')->get(['id','title']) as $monthlyPackage) {
            $kol    = $monthlyPackage->reports->where('status','!=','deleted');
            $zade   = $kol->where('status','active')->count();
            array_push($m_pack_title, (' طرح '.$monthlyPackage->title));
            array_push($m_pack_value, (num2fa(($kol->count()).' کاندید شده و '.$zade.' تیک خورده ')));
        }

        $start  = g2j( Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' )) ,'Y/m/d');
        $month  = my_jdate(Carbon::today(),'m');

        if ($filter) {
            return response()->json(['items' => $items] , 200); 
        } else {
            return view('admin.potential_organization.potential.report', compact('m_pack_title','m_pack_value','items','text','url','start','new_items','month','id'), ['title1' => ' گزارش لیست پتانسیل '.$append_text, 'title2' => 'گزارش']);
        }
        
    }

    public function next_report_filter($id, $year, $month, $type='single') {
        $list = [$id];
        if ($type!='single') $list = getSubUser([$id])[0];
        $items  = $this->next_report( $list, $year, $month);
        return response()->json(['items' => $items] , 200); 
    }

    public function list($id=null) {
        if ($id===null) $id = auth()->user()->id;
        $items  = getSubUser([$id]);
        $list   = $items[0];
        $state  = $items[1];

        // بارگذاری آیتم ها
        $items  = Potential::whereIn('user_id',$items[2])->get();
        
        foreach ($items as $obj) {
            // چسباندن سطح به آیتم ها
            $step   = 0;
            $input  = $obj->user_id;
            if ($input) {
                while (true) {
                    $step += 1;
                    if ($input == $id) break;

                    $item   = Potential::where('name', $input )->first();
                    
                    if ( $item && $item->user_id) {
                        $input = $item->user_id;
                    } else {
                        break;
                    }
                }
                $obj->level    = $step;
            }
        }
        // $items  = Potential::whereIn('id', $list )->get();
        
        // $step = 0;
        // for ($i=count($state); $i > 0; $i--) { 
        //     $list   = explode( ',', substr($state[$i-1],1,-1) );
        //     foreach ($items as $item) {
        //         // چسباندن سطح به آیتم ها
        //         if ( in_array($item->id, $list) ) {
        //             $item->level    = (count($state)-$step);
        //         }
        //     }
        //     $step   += 1;
        // }
        
        $items  = $items->sortBy('level');

        $append_items = [];
        foreach ($items as $item) {
            // کاربر ایتم
            $item_user  = $item->user;
            if ($item_user && $item_user->status=='deactive') {
                // نگه داشتن آی دی پتانسیل غیرفعال جهت مرحله یافتن فرزند
                $user_id = $item->name;
                // ----------------این کد مربوط به اینجا نیست----------------
                // while (true) {
                //     // تا یافتن معرف فعال جلو میرود
                //     if ($item->user->status=='active') break;
                //     $headItem = Potential::where('name', $item->user_id)->first(['name','user_id']);
                //     if ( $headItem===null ) {
                //         $item->name = $item->admin->id;
                //         $item->user_id  = $headItem;
                //         break;
                //     }
                //     $item->name     = $headItem->name;
                //     $item->user_id  = $headItem->user_id;
                // }
                // ^----------------این کد مربوط به اینجا نیست^----------------^

                $append = Potential::where('user_id', $user_id)->get(); 
                // افزودن فرزندان به والد جدید
                foreach ($append as $child) {
                    array_push($append_items , $child);
                }
            }
        }
        // ادقام با فرزندان ایتم غیرفغال و حذف غیرفعال ها از لیست
        foreach ($items->sortByDesc('id') as $child) {
            if ($child->user) {
                array_unshift($append_items , $child);
            }
        }
        $items = $append_items;
        return view('admin.potential_organization.potential.list', compact('items'), ['title1' => 'لیست پتانسیل سازمان', 'title2' => 'لیست']);
    }

    public function index($id=null, $type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];
        $followingList  = Following::where('user_id', auth()->id())->pluck('potential_id');
        if (count($followingList)) {
            $items      = Potential::whereIn( 'user_id' , $list )->orWhere('user_id', $followingList)->get();
        } else {
            $items      = Potential::whereIn( 'user_id' , $list )->get();
        }
        $list = [];
        $append_items = [];

        foreach ($items as $item) {
            // کاربر ایتم
            $item_user  = $item->user;
            if ($item_user && $item_user->status=='deactive') {
                // نگه داشتن آی دی پتانسیل غیرفعال جهت مرحله یافتن فرزند
                $user_id = $item->name;
                // ----------------این کد مربوط به اینجا نیست----------------
                // while (true) {
                //     // تا یافتن معرف فعال جلو میرود
                //     if ($item->user->status=='active') break;
                //     $headItem = Potential::where('name', $item->user_id)->first(['name','user_id']);
                //     if ( $headItem===null ) {
                //         $item->name = $item->admin->id;
                //         $item->user_id  = $headItem;
                //         break;
                //     }
                //     $item->name     = $headItem->name;
                //     $item->user_id  = $headItem->user_id;
                // }
                // ^----------------این کد مربوط به اینجا نیست^----------------^

                $append = Potential::where('user_id', $user_id)->get(); 
                // افزودن فرزندان به والد جدید
                foreach ($append as $child) {
                    array_push($append_items , $child);
                    array_push($list , $child->id);
                }
            }
        }
        // ادقام با فرزندان ایتم غیرفغال و حذف غیرفعال ها از لیست
        foreach ($items->sortByDesc('id') as $child) {
            if ($child->user) {
                array_unshift($append_items , $child);
                array_push($list , $child->id);
            }
        }

        $last_reports   = null;
        if ($list) $last_reports = PotentialReport::whereIn('potential_id', $list)->where('date','>',start_en())->where('updated_at', '>', Carbon::today())->where('status', '!=', 'pending')->orderBy('date')->get();
        $monthlyPackage = MonthlyPackage::where('status','active')->get();

        return view('admin.potential_organization.potential.index', compact('items','monthlyPackage','last_reports'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create() {
        $users = User::all();
        foreach ($users as $user) {
            $user->full_name = $user->first_name.' '.$user->last_name;
        }
        return view('admin.potential_organization.potential.create', compact('users'),['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'first_name'    => 'required|max:240',
            'last_name'     => 'required|max:240',
            'mobile'        => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric|unique:users',
        ],
            [
                'first_name.required'   => 'لطفا نام کاربر را وارد کنید',
                'first_name.max'        => 'نام کاربر حداکثر 240 کاراکتر باشد',
                'last_name.required'    => 'لطفا نام خانوادگی کاربر را وارد کنید',
                'last_name.max'         => 'نام خانوادگی کاربر حداکثر 240 کاراکتر باشد',
                'mobile.required'       => 'لطفا موبایل خود را وارد کنید',
                'mobile.regex'          => 'لطفا موبایل خود را وارد کنید',
                'mobile.digits'         => 'لطفا فرمت موبایل را رعایت کنید',
                'mobile.numeric'        => 'لطفا موبایل خود را بصورت عدد وارد کنید',
                'mobile.unique'         => 'موبایل وارد شده یکبار ثبت نام شده',
            ]);
        $user = new User();
        $item = new Potential();
        try {
            $user->reagent_id           = auth()->user()->id;
            $user->first_name           = $request->first_name;
            $user->last_name            = $request->last_name;
            if ( $request->present_ta_peresent=="خرید اولیه انجام نشده" ) {
                $user->status           = 'deactive';
            }
            $user->mobile               = $request->mobile;
            $user->whatsapp             = $request->whatsapp;
            $user->password             = 'password1234';
            $user->save();
            
            $user->reagent_code         = \Str::random(5).$user->id;
            $user->update();

            $item->user_id                      = auth()->user()->id;
            $item->name                         = $user->id;
            $item->hadaf_gozari_shakhsi         = $request->hadaf_gozari_shakhsi;
            $item->hadaf_gozari_level           = $request->hadaf_gozari_level;
            $item->kasb_o_kar_kochak_ya_bozorg  = $request->kasb_o_kar_kochak_ya_bozorg;
            $item->folowe_ya_4eqdam             = $request->folowe_ya_4eqdam;
            $item->hadaf_jam_daramad_mah        = $request->hadaf_jam_daramad_mah;
            $item->candid_shabakesazi           = $request->candid_shabakesazi;
            $item->candid_forosh                = $request->candid_forosh;
            $item->present_ta_peresent          = $request->present_ta_peresent;
            $item->save();

             // assign new roles
            $user->assignRole('general');
            return redirect()->route('admin.potential-list.edit',$item->id)->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $item  = Potential::where('user_id', auth()->user()->id)->findOrFail($id);
        $step  = 1;
        // // present_ta_peresent
        if($item->present_ta_peresent=='اولین پرزنت ست شده')        $step = 4;
        elseif($item->present_ta_peresent=='آموزش فست استارت دیده') $step = 3;
        elseif($item->present_ta_peresent=='خرید اولیه انجام شده')  $step = 2;
        // present_ta_estage
        if($item->present_ta_estage=='۴ تا تیم اکتیو دارد')         $step = 9;
        elseif($item->present_ta_estage=='آموزش همانندسازی دیده')   $step = 8;
        elseif($item->present_ta_estage=='دومین ورودی گرفته شده')   $step = 7;
        elseif($item->present_ta_estage=='آموزش پشتیبان دیده')      $step = 6;
        elseif($item->present_ta_estage=='اولین ورودی گرفته شده')   $step = 5;
        return view('admin.potential_organization.potential.edit', compact('item','step'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update(Request $request, $id) {
        $item = Potential::findOrFail($id);
        try {
            if ($request->present_ta_peresent!=null)            $item->present_ta_peresent          = $request->present_ta_peresent;
            if ($request->kasb_o_kar_kochak_ya_bozorg!=null)    $item->kasb_o_kar_kochak_ya_bozorg  = $request->kasb_o_kar_kochak_ya_bozorg;
            if ($request->present_ta_estage!=null)              $item->present_ta_estage            = $request->present_ta_estage;
            if ($request->hadaf_gozari_shakhsi!=null)           $item->hadaf_gozari_shakhsi         = $request->hadaf_gozari_shakhsi;
            if ($request->folowe_ya_4eqdam!=null)               $item->folowe_ya_4eqdam             = $request->folowe_ya_4eqdam;
            if ($request->hadaf_gozari_level!=null)             $item->hadaf_gozari_level           = $request->hadaf_gozari_level;
            if ($request->hadaf_jam_daramad_mah!=null)          $item->hadaf_jam_daramad_mah        = $request->hadaf_jam_daramad_mah;
            if ($request->candid_shabakesazi!=null)             $item->candid_shabakesazi           = $request->candid_shabakesazi;
            if ($request->candid_forosh!=null)                  $item->candid_forosh                = $request->candid_forosh;
            $item->save();

            $user = $item->user;
            if ($user->status != 'active') {
                $user->status  = 'active';
                $user->save();
            }
            return redirect()->route('admin.potential-list.index')->with('flash_message', 'آیتم با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function reactivate($id) {

        $item  = Potential::where('user_id', auth()->user()->id)->findOrFail($id);
        $user  = $item->user;

        if ($user->status=='active') $user->status = 'deactive';
        else $user->status = 'active';
        $user->update();

        return redirect()->back()->withInput()->with('flash_message', 'وضعیت با موفقیت تغییر کرد');
    }

    public function follow($id) {
        if ($id == auth()->id()) return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد بوجود آمده،مجددا تلاش کنید');
        try {
            $item   = Following::where('user_id', auth()->id())->where('potential_id', $id)->first();
            if ($item) {
                $item->delete();
            } else {
                $item               = new Following();
                $item->user_id      = auth()->id();
                $item->potential_id = $id;
                $item->save();
            }
            return redirect()->back()->withInput()->with('flash_message', 'با موفقیت انجام شد');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد بوجود آمده،مجددا تلاش کنید');
        }
    }
}
