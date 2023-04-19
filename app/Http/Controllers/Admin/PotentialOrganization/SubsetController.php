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
        if ($type == 'sum') return 'مشتریان های سازمانی من';
        elseif ('single') return 'مشتریان سازمانی من';
    }

    public function __construct() {
        $this->middleware('permission:user_customer_org_list', ['only' => ['index']]);
        $this->middleware('permission:user_customer_org_report_list', ['only' => ['report']]);
    }
    public function index($id=null) {

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
        return view('admin.potential_organization.subset.index', compact('items','id'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function report(Request $request, $id=null) {
        if ($id===null) $id = [auth()->user()->id];
        else $id = [$id];
        $id = getSubUser($id)[2];
        
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
        $product_chart_bar_list     = [];
        
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
        
        // $products   = $factors->unique('product_id');
        // foreach ($products as $product) {
        //     // فروش محصول
        //     array_push($product_chart_bar_names, $factors->where('product_id',$product->product_id)->sum('count'));
        //     array_push($product_chart_bar_sum_buy, $product->product?$product->product->name:'محصول یافت نشد');
        // }

        // foreach ($categories as $category) {
        //     // دسته بندی ها و تعداد محصولات هر دسته
        //     array_push($category_chart_bar_names, $category->products->count());
        //     array_push($category_chart_bar_sum_buy, $category->name);
        // }

        // $brands = $factors->unique('brand_id');
        // if ($brands->count()) {

        //     $brands     = Category::whereIn('id',$brands->pluck('brand_id'))->get(['id','name']);
        //     $category   = $factors->unique('category_id');

        //     foreach ($category as $cat) {
        //         // فروش محصول
        //         $item   = $factors->where('category_id',$cat->category_id);
        //         $brand  = $item->unique('brand_id')->pluck('brand_id');
                
        //         $list   = null;

        //         array_push($product_chart_bar_sum_buy, $item->sum('count'));
        //         array_push($product_chart_bar_names, $cat->product?$cat->product->category->name:'یافت نشد');

        //         foreach ($brand as $li) {
        //             $list = $list.$brands->where('id',$li)->first()->name.' '.num2fa($factors->where('brand_id',$li)->sum('count')).' , ';
        //         }

        //         array_push($product_chart_bar_list, $list);
        //     }
        // }

        $categories = $factors->unique('category_id');
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

        return view('admin.potential_organization.subset.report', compact('product_chart_bar_list','start','category_chart_bar_names','category_chart_bar_sum_buy','total','state','product_chart_bar_names','product_chart_bar_sum_buy',
        'from_month','to_month','year','month_arr','new_customer_arr','my_customer_arr','razi_customer_arr','vafadar_customer_arr','havadar_customer_arr','referr_customer_arr','all_customer_arr'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

}

