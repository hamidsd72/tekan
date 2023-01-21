<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\User;
use App\Model\Potential;
use App\Model\FourAction;
use App\Model\MonthlyPackage;
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

    public function __construct() { $this->middleware('auth'); }

    public function report(Request $request, $id, $type='single', $filter=false) {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') {
            $list = [$id];
            $url  = route('admin.potential-list.report.list.filter',[$id,'single']);
        } else {
            $list = getSubUser([$id])[0];
            $url  = route('admin.potential-list.report.list.filter',[$id,'all']);
        }

        if ($request->start) $start = num2en($request->start);
        else                 $start = persianStartOfMonth();

        if ($request->end) {
            $end = num2en($request->end);
            $filter = true;
        }
        else                 $end = Carbon::now();
        
        $items = Potential::whereIn( 'user_id' , $list )->whereBetween('created_at', [$start, $end] )->get();
        if ($items->count()) $childs = Potential::whereIn( 'user_id' , $items->pluck('name'))->get('name');
        else                  $childs = false;

        $list   = [];
        $text   = [];

        // تعداد کسب و کار کوچک
        $buss   = $items->where('present_ta_peresent','!=',null);
        $small  = $buss->where('kasb_o_kar_kochak_ya_bozorg','کوچک')->count();
        $big    = $buss->where('kasb_o_kar_kochak_ya_bozorg','بزرگ')->count();
        array_push($list,  $small>0?$small.' نفر / '.(($small*100)/$buss->count()).'%':'0 نفر / 0%' );
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
        array_push($list,  FourAction::whereIn( 'user_id' , $list )->whereBetween('created_at', [$start, $end] )->count('present').' نفر ');
        array_push($text, 'تعداد پرزنت شده ها' );

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
        array_push($text, 'تعداد افرادی که اولین ورودی شبکه ساز گرفتن' );

        // تعداد افرادی که آموزش پشتیبان دیده و ندیده
        $zade   = $buss->whereNotIn('present_ta_estage',['اولین ورودی گرفته نشده','اولین ورودی گرفته شده','آموزش پشتیبان ندیده'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر دیدن / '.$nazade.' نفر ندیدن ');
        array_push($text, 'تعداد افرادی که آموزش پشتیبان دیدن' );

        // تعداد افرادی که استیج ۲ نفره شبکه ساز گرفتن و نگرفتن
        $zade   = $buss->whereNotIn('present_ta_estage',['دومین ورودی گرفته شده','آموزش همانندسازی دیده','آموزش همانندسازی ندیده','۴ تا تیم اکتیو دارد','۴ تا تیم اکتیو ندارد'])->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر زدن / '.$nazade.' نفر نزدن ');
        array_push($text, 'تعداد افرادی که استیج ۲ نفره شبکه ساز زدن' );

        // تعداد افرادی که استیج ۴ نفره شبکه ساز گرفتن و نگرفتن
        $zade   = $buss->where('present_ta_estage','۴ تا تیم اکتیو دارد')->count();
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر زدن / '.$nazade.' نفر نزدن ');
        array_push($text, 'تعداد افرادی که استیج ۴ نفره شبکه ساز زدن' );
        
        // تعداد افرادی که پتاتسیل ست کردن و نکردن
        $zade = 0;
        if ($childs) {
            foreach ($items as $item) {
                if ($childs->where( 'user_id' , $item->name)->count()) {
                    $zade += 1;
                }
            }
        }
        $nazade = $buss->count() - $zade;
        array_push($list, $zade.' نفر ست کرده / '.$nazade.' نفر ست نکرده ');
        array_push($text, 'تعداد پرزنت شده  ها' );
        
        $items = [];
        for ($i = 0; $i < count($list); $i++) {
            array_push($items, num2fa($list[$i]) );

        }

        $start  = g2j( Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' )) ,'Y/m/d');

        if ($filter) {
            return response()->json(['items' => $items] , 200); 
        } else {
            return view('admin.potential_organization.potential.report', compact('items','text','url','start'), ['title1' => 'گزارش لیست پتانسیل', 'title2' => 'گزارش']);
        }
        
    }
    
    public function list($id=null) {
        if ($id===null) $id = auth()->user()->id;
        $items  = getSubUser([$id]);
        $list   = $items[0];
        $state  = $items[1];

        // بارگذاری آیتم ها
        $items  = Potential::whereIn('id', $list )->get();
        
        $step = 0;
        for ($i=count($state); $i > 0; $i--) { 
            $list   = explode( ',', substr($state[$i-1],1,-1) );
            foreach ($items as $item) {
                // چسباندن سطح به آیتم ها
                if ( in_array($item->id, $list) ) {
                    $item->level    = (count($state)-$step);
                }
            }
            $step   += 1;
        }
        
        $items  = $items->sortBy('level');

        return view('admin.potential_organization.potential.list', compact('items'), ['title1' => 'لیست پتانسیل سازمان', 'title2' => 'لیست']);
    }

    public function index($id=null, $type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[0];

        $items          = Potential::whereIn( 'user_id' , $list )->get();
        $monthlyPackage = MonthlyPackage::where('status','active')->first();
        return view('admin.potential_organization.potential.index', compact('items','monthlyPackage'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
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
            $user->mobile               = $request->mobile;
            $user->password             = 'password1234';
            $user->save();

            $item->user_id                      = auth()->user()->id;
            $item->name                         = $user->id;
            $item->hadaf_gozari_shakhsi         = $request->hadaf_gozari_shakhsi;
            $item->hadaf_gozari_level           = $request->hadaf_gozari_level;
            $item->kasb_o_kar_kochak_ya_bozorg  = $request->kasb_o_kar_kochak_ya_bozorg;
            $item->folowe_ya_4eqdam             = $request->folowe_ya_4eqdam;
            $item->hadaf_jam_daramad_mah        = $request->hadaf_jam_daramad_mah;
            $item->candid_shabakesazi           = $request->candid_shabakesazi;
            $item->candid_forosh                = $request->candid_forosh;
            $item->save();
            return redirect()->route('admin.potential-list.edit',$item->id)->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
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
            return redirect()->route('admin.potential-list.index')->with('flash_message', 'آیتم با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}


