<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use App\Model\Customer;
use App\Model\ProvinceCity;
use App\Model\Factor;
use App\Model\Category;
use App\Model\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class ReportController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست گزارشات';
        elseif ('single') return 'گزارشات';
    }

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request, $id=null, $type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[0];

        $to_year=g2j(date('Y-m-d'),'Y');
        $from_month=1;
        $to_month=g2j(date('Y-m-d'),'m');
        $year=$to_year;

        if(isset($request->year)) {
            $year=num2en($request->year);
            if($year<$to_year) $to_month=12;
        }

        $month_arr=[];
        $new_customer_arr=[];
        $my_customer_arr=[];
        $razi_customer_arr=[];
        $vafadar_customer_arr=[];
        $havadar_customer_arr=[];
        $referr_customer_arr=[];
        $all_customer_arr=[];

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
        $product_chart_bar_names    = [];
        $product_chart_bar_sum_buy  = [];
        // دسته بندی محصولات
        $category_chart_bar_names   = [];
        $category_chart_bar_sum_buy = [];
        
        // فاکتورهای ماه جاری
        $factors    = Factor::whereIn('user_id',$list)->whereDate('time_en','>',Carbon::parse(j2g($start)))->get();

        // مشتری ها
        $customers  = Customer::whereIn('user_id', $list )->get(['id','state_id']);
        // دسته بندی های محصولات
        $categories  = Category::all(['id','name']);


        $total      = $customers->count();
        $states     = $customers->unique('state_id');
        foreach ($states as $item) {
            // استان و تعداد مشتری
            if ($item->state) {
                array_push($state, $item->state->name);
                array_push($state, $customers->where('state_id',$item->state_id)->count());
            }
        }
        
        $products   = $factors->unique('product_id');
        foreach ($products as $product) {
            // فروش محصول
            array_push($product_chart_bar_names, $factors->where('product_id',$product->product_id)->sum('count'));
            array_push($product_chart_bar_sum_buy, $product->product?$product->product->name:'محصول یافت نشد');
        }

        foreach ($categories as $category) {
            // دسته بندی ها و تعداد محصولات هر دسته
            array_push($category_chart_bar_names, $category->products->count());
            array_push($category_chart_bar_sum_buy, $category->name);
        }

        return view('admin.customer_bank.report_my.index', compact('start','category_chart_bar_names','category_chart_bar_sum_buy','total','state','product_chart_bar_names','product_chart_bar_sum_buy',
        'from_month','to_month','year','month_arr','new_customer_arr','my_customer_arr','razi_customer_arr','vafadar_customer_arr','havadar_customer_arr','referr_customer_arr','all_customer_arr'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function showCities($slug, $id=null, $type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[0];
        
        $state  = ProvinceCity::where('name', $slug)->first();
        if (!$state) return redirect()->back()->withInput()->with('err_message', 'استان یافت نشد');

        $cities = $state->children()->get(['id','name']);
        // مشتریان این استان
        $customers = Customer::whereIn('user_id', $list)->where('state_id',$state->id)->get(['id','city_id']);

        foreach ($cities as $item) {
            $count = $customers->where('city_id',$item->id)->count();
            $item->membersData = $count.' نفر '.intVal(($count*100)/$customers->count()).'%';
        }
        
        return view('admin.customer_bank.report_my.showCities', compact('cities'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum').' استان '.$state->name]);
    }

    public function search(Request $request) {
        if ($request->type=='single') $list = [$request->id];
        else $list = getSubUser([$request->id])[0];

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
        if ($request->type=='single') $list = [$request->id];
        else $list = getSubUser([$request->id])[0];

        $factors = Factor::whereIn('user_id', $list)->whereBetween('time_en',[$start_date,$end_date])->get(['id','customer_id','product_id','count']);

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
            
            $factors = Factor::whereIn('user_id', $list)->get(['id','product_id','count']);
            $category_chart_bar_names   = [];
            $category_chart_bar_sum_buy = [];

            $category = Category::where('name',$request->category_name)->first('id');

            if ($category->count()) {
                $products = Product::where('category_id',$category->id)->whereBetween('created_at',[$start_date,$end_date])->get();
                if (!$products->count()) {
                    array_push($category_chart_bar_names, 0);
                    array_push($category_chart_bar_sum_buy,  'در این بازه محصولی یافت نشد');
                }
            } else {
                array_push($category_chart_bar_names, 0);
                array_push($category_chart_bar_sum_buy,  'در این دسته بندی محصولی یافت نشد');
            }

            foreach ($products as $product) {
                // فروش محصول
                array_push($category_chart_bar_names, $factors->where('product_id',$product->id)->sum('count'));
                array_push($category_chart_bar_sum_buy, $product->name);
            }

            return response()->json([
                'chart_bar_names'   => $category_chart_bar_names,
                'chart_bar_sum_buy' => $category_chart_bar_sum_buy,
            ]);
        }
        return false;
    }
    
}
