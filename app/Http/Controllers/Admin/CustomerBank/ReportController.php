<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use App\Model\Customer;
use App\Model\ProvinceCity;
use App\Model\Factor;
use App\Model\Category;
use App\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست گزارشات';
        elseif ('single') return 'گزارشات';
    }

    public function __construct() {
        $this->middleware('permission:user_customer_report_list', ['only' => ['index','showCities']]);
    }

    public function index(Request $request, $id=null, $type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];

        $to_year=g2j(date('Y-m-d'),'Y');
        $from_month=1;
        $to_month=g2j(date('Y-m-d'),'m');
        $year=$to_year;

        if(isset($request->year)) {
            $year=num2en($request->year);
            if($year<$to_year) $to_month=12;
        }

        $month_arr=['شروع'];
        $new_customer_arr=[0];
        $my_customer_arr=[0];
        $razi_customer_arr=[0];
        $vafadar_customer_arr=[0];
        $havadar_customer_arr=[0];
        $referr_customer_arr=[0];
        $all_customer_arr=[0];

        foreach (range($from_month , $to_month) as $m) {
            array_push($month_arr,month_name($m));
            array_push($new_customer_arr,customer_report($year,$m,'new',$list));
            array_push($my_customer_arr,customer_report($year,$m,'my',$list));
            array_push($razi_customer_arr,customer_report($year,$m,'razi',$list));
            array_push($vafadar_customer_arr,customer_report($year,$m,'vafadar',$list));
            array_push($havadar_customer_arr,customer_report($year,$m,'havadar',$list));
            array_push($referr_customer_arr,customer_report($year,$m,'referr',$list));
            array_push($all_customer_arr,customer_report($year,$m,'all',$list));
        }

        $start = g2j( Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' ) ),'Y/m/d' );
        // استان و تعداد مشتری ها
        $state = [];
        // فروش محصول
        $product_chart_bar_list     = [];
        $product_chart_bar_names    = [];
        $product_chart_bar_sum_buy  = [];
        // دسته بندی محصولات
        $category_chart_bar_names   = [];
        $category_chart_bar_sum_buy = [];
        
        // فاکتورهای ماه جاری
        $factors    = Factor::where('product_id','!=',null)->whereIn('user_id',$list)->get();
        // مشتری ها
        $customers  = Customer::whereIn('user_id', $list )->get(['id','state_id']);

        $total      = $customers->count();
        $states     = $customers->unique('state_id');
        foreach ($states as $item) {
            // استان و تعداد مشتری
            if ($item->state) {
                array_push($state, $item->state->name);
                array_push($state, $customers->where('state_id',$item->state_id)->count());
            }
        }
        
        // $brands     = $factors->unique('brand_id');
        $categories = $factors->unique('category_id');

        // if ($brands->count() && $categories->count()) {
        if ($categories->count()) {
            foreach ($categories as $cat) {
                // تغداد دسته این دسته
                $objs   = $factors->where('category_id',$cat->category_id);
                $sum    = $objs->sum('count');
                
                $category_name  = $cat->product ? $cat->product->category->name: 'یافت نشد';
                
                array_push($product_chart_bar_sum_buy, $sum);
                array_push($product_chart_bar_names, $category_name.'id:('.$cat->id.') ');

                $text   = '';
                foreach ($objs->unique('brand_id') as $brand) {
                    // تغداد برند این دسته
                    $brand_factors  = $objs->where('brand_id',$brand->brand_id)->sum('count');
                    $brand_name     = $brand->product ? $brand->product->brand->name: 'یافت نشد';
                    $text           = $text.$brand_name.' %'.intVal( ($brand_factors/$sum) * 100 ).' ____ ';
                }

                array_push($product_chart_bar_list, $category_name.' : %'.intVal( ($sum/$factors->sum('count')) * 100 ).' / '.$text);
                $text   = '';
                
            }

        }

        return view('admin.customer_bank.report_my.index', compact('id','start','total','state','product_chart_bar_list','product_chart_bar_names','product_chart_bar_sum_buy'
        ,'from_month','to_month','year','month_arr','new_customer_arr','my_customer_arr','razi_customer_arr','vafadar_customer_arr','havadar_customer_arr','referr_customer_arr',
        'all_customer_arr'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function showCities($slug, $id=null, $type='all') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];
        
        $state  = ProvinceCity::where('name', $slug)->first();
        if (!$state) return redirect()->back()->withInput()->with('err_message', 'استان یافت نشد');

        $cities = $state->children()->get(['id','name']);
        // مشتریان این استان
        $customers = Customer::whereIn('user_id', $list)->where('state_id',$state->id)->get(['id','city_id']);

        foreach ($cities as $item) {
            $count = $customers->where('city_id',$item->id)->count();
            $item->membersData = $count.' نفر '.intVal(($count*100)/($customers->count()>0?$customers->count():1)).'%';
        }
        $name   = 'تعداد مشتری';
        return view('admin.customer_bank.report_my.showCities', compact('name','cities'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum').' استان '.$state->name]);
    }
    
    public function showCitiesNew($slug, $id=null, $type='all') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];
        
        $state  = ProvinceCity::where('name', $slug)->first();
        if (!$state) return redirect()->back()->withInput()->with('err_message', 'استان یافت نشد');
        $cities = $state->children;
        $users  = User::whereIn('id', $list)->where('state_id',$state->id)->get(['id','city_id']);
        
        foreach ($cities as $item) {
            $count = $users->where('city_id',$item->id)->count();
            $item->membersData = $count.' نفر '.intVal(($count*100)/ ($users->count()>0?$users->count():1) ).'%';
        }
        $name   = 'تعداد سازمان';
        return view('admin.customer_bank.report_my.showCities', compact('name','cities'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum').' استان '.$state->name]);
    }

    public function search(Request $request) {
        if ($request->type=='single') $list = [$request->id];
        else $list = getSubUser([$request->id])[2];

        $to_year=g2j(date('Y-m-d'),'Y');
        $from_month=1;
        $to_month=g2j(date('Y-m-d'),'m');
        $year=$to_year;
        if(isset($request->year))
        {
            $year=num2en($request->year);
            if($year<$to_year)
            {
                $to_month=12;
            }
        }
        $month_arr=[];
        $new_customer_arr=[];
        $my_customer_arr=[];
        $razi_customer_arr=[];
        $vafadar_customer_arr=[];
        $havadar_customer_arr=[];
        $referr_customer_arr=[];
        $all_customer_arr=[];
        foreach (range($from_month , $to_month) as $m)
        {
            array_push($month_arr,month_name($m));
            array_push($new_customer_arr,customer_report($year,$m,'new',$list));
            array_push($my_customer_arr,customer_report($year,$m,'my',$list));
            array_push($razi_customer_arr,customer_report($year,$m,'razi',$list));
            array_push($vafadar_customer_arr,customer_report($year,$m,'vafadar',$list));
            array_push($havadar_customer_arr,customer_report($year,$m,'havadar',$list));
            array_push($referr_customer_arr,customer_report($year,$m,'referr',$list));
            array_push($all_customer_arr,customer_report($year,$m,'all',$list));
        }

        return response()->json([
            'month_arr'=>$month_arr,
            'new_customer_arr'=>$new_customer_arr,
            'my_customer_arr'=>$my_customer_arr,
            'razi_customer_arr'=>$razi_customer_arr,
            'vafadar_customer_arr'=>$vafadar_customer_arr,
            'havadar_customer_arr'=>$havadar_customer_arr,
            'referr_customer_arr'=>$referr_customer_arr,
            'all_customer_arr'=>$all_customer_arr,
        ]);
    }

    public function searchBar(Request $request) {
        $start_date = Carbon::parse(j2g(num2en($request->start_date)));
        $end_date   = Carbon::parse(j2g(num2en($request->end_date)))->addDay();
        if ($request->search_type=='single') $list = [$request->id];
        else $list = getSubUser([$request->id])[2];

        $factors = Factor::where('product_id','!=',null)->whereIn('user_id', $list)->whereBetween('time_en',[$start_date,$end_date])->get();

        if ($request->type=='product') {
            // فروش محصول
            $product_chart_bar_names   = [];
            $product_chart_bar_sum_buy = [];
            
            $products = $factors->unique('product_id');

            foreach ($products as $product) {
                // فروش محصول
                array_push($product_chart_bar_names, $factors->where('product_id',$product->product_id)->sum('count'));
                array_push($product_chart_bar_sum_buy, $product->product?$product->product->name:'محصول یافت نشد');
            }
            
            return response()->json([
                'product_chart_bar_names'   => $product_chart_bar_names,
                'product_chart_bar_sum_buy' => $product_chart_bar_sum_buy,
            ]);

        } elseif($request->type=='customer') {
            // جارت میله ای
            // initials
            $chart_bar_names   = [];
            $chart_bar_sum_buy = [];
            $items   = $factors->unique('customer_id');
            // set final data
            foreach ($items as $item) {
                array_push($chart_bar_names, $factors->where('customer_id',$item->customer_id)->sum('count'));
                array_push($chart_bar_sum_buy, $item->customer?$item->customer->name:'مشتری یافت نشد');
            }
            
            return response()->json([
                'chart_bar_names'   => $chart_bar_names,
                'chart_bar_sum_buy' => $chart_bar_sum_buy,
            ]);
        } elseif($request->type=='category') {
            $product_chart_bar_list     = [];
            $product_chart_bar_names    = [];
            $product_chart_bar_sum_buy  = [];

            // $products   = $factors->unique('product_id');
            // foreach ($products as $product) {
            //     // فروش محصول
            //     array_push($product_chart_bar_sum_buy, $factors->where('product_id',$product->product_id)->sum('count'));
            //     array_push($product_chart_bar_names, $product->product?$product->product->category->name:'یافت نشد');
            //     array_push($product_chart_bar_list, $product->product);
            // }
            
            // return response()->json([
            //     'chart_bar_names'   => $product_chart_bar_names,
            //     'chart_bar_sum_buy' => $product_chart_bar_sum_buy,
            //     'chart_bar_list'    => $product_chart_bar_list,
            // ]);

            $brands = $factors->unique('brand_id');
            if ($brands->count()) {

                $brands     = Category::whereIn('id',$brands->pluck('brand_id'))->get(['id','name']);
                $category   = $factors->unique('category_id');

                foreach ($category as $cat) {
                    // فروش محصول
                    $item   = $factors->where('category_id',$cat->category_id);
                    $brand  = $item->unique('brand_id')->pluck('brand_id');
                    
                    $list   = null;

                    array_push($product_chart_bar_sum_buy, $item->sum('count'));
                    array_push($product_chart_bar_names, $cat->product?$cat->product->category->name:'یافت نشد');

                    foreach ($brand as $li) {
                        $list = $list.$brands->where('id',$li)->first()->name.' '.num2fa($factors->where('brand_id',$li)->sum('count')).' , ';
                    }

                    array_push($product_chart_bar_list, $list);
                }
                
            }
            return response()->json([
                'chart_bar_names'   => $product_chart_bar_names,
                'chart_bar_sum_buy' => $product_chart_bar_sum_buy,
                'chart_bar_list'    => $product_chart_bar_list,
            ]);
            
        }
        return false;
    }
    
}
