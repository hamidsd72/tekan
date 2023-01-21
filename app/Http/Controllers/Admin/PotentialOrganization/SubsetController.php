<?php

namespace App\Http\Controllers\Admin\PotentialOrganization;

use App\User;
use App\Model\Potential;
use App\Model\Factor;
use App\Model\Customer;
use App\Model\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubsetController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'زیرمجموعه های سازمانی من';
        elseif ('single') return 'زیرمجموعه سازمانی من';
    }

    public function __construct() { $this->middleware('auth'); }

    // get all potential user
    public function getUserId($id) {
        // 'name' as, relationship with item users
        $items  = $id;
        $list   = Potential::whereIn('id', getSubUser()[0] )->pluck('name');
        foreach ($list as $item) {
            array_push($items, $item);
        }
        
        return User::whereIn('id', $items )->pluck('id');
    }

    public function index($id=null) {

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
        
        return view('admin.potential_organization.subset.index', compact('items','id'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function report(Request $request, $id=null) {
        if ($id===null) $id = [auth()->user()->id];
        else $id = [$id];
        $items  = getSubUser([$id]);
        
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
            array_push($new_customer_arr,customer_report($year,$m,'new',$id));
            array_push($my_customer_arr,customer_report($year,$m,'my',$id));
            array_push($razi_customer_arr,customer_report($year,$m,'razi',$id));
            array_push($vafadar_customer_arr,customer_report($year,$m,'vafadar',$id));
            array_push($havadar_customer_arr,customer_report($year,$m,'havadar',$id));
            array_push($referr_customer_arr,customer_report($year,$m,'referr',$id));
            array_push($all_customer_arr,customer_report($year,$m,'all',$id));
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
        $factors    = Factor::whereIn('user_id',$id)->whereDate('time_en','>',Carbon::parse(j2g($start)))->get();

        // مشتری ها
        $customers  = Customer::whereIn('user_id', $id )->get(['id','state_id']);
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

        // return view('admin.customer_bank.report_my.index', compact('start','category_chart_bar_names','category_chart_bar_sum_buy','total','state','product_chart_bar_names','product_chart_bar_sum_buy',
        return view('admin.potential_organization.subset.report', compact('start','category_chart_bar_names','category_chart_bar_sum_buy','total','state','product_chart_bar_names','product_chart_bar_sum_buy',
        'from_month','to_month','year','month_arr','new_customer_arr','my_customer_arr','razi_customer_arr','vafadar_customer_arr','havadar_customer_arr','referr_customer_arr','all_customer_arr'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

}

