<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use \Carbon\Carbon;
use App\Model\Category;
use App\Model\Factor;
use App\Model\Customer;
use App\Model\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FactorController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست فاکتور';
        elseif ('single') return 'فاکتور';
    }

    public function __construct() { $this->middleware(['auth']); }

    public function create($id) {
        $item       = Customer::where('user_id',auth()->user()->id)->findOrFail($id);
        $categories = Category::all();
        return view('admin.customer_bank.factor.create', compact('item','categories'), ['title1' => $this->controller_title('sum'), 'title2' => ' افزودن '.$this->controller_title('single')]);
    }

    public function show($id) {
        $item  = Customer::where('user_id',auth()->user()->id)->findOrFail($id);
        $items = $item->customer_factors;
        return view('admin.customer_bank.factor.show', compact('item','items'), ['title1' => $this->controller_title('sum'), 'title2' => $item->name]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'customer_id'   => 'required',
            'product_id'    => 'required',
            'count'         => 'required',
        ],
            [
                'customer_id.required'  => 'لطفا نام مشتری را انتخاب کنید',
                'product_id.required'   => 'لطفا نام محصول را وارد کنید',
                'count.required'        => 'لطفا تعداد سفارش وارد کنید',
            ]);
            $time       = Carbon::now();
            $customer   = Customer::findOrFail($request->customer_id);
            $product    = Product::findOrFail($request->product_id);
            // $total      = intVal($request->count) * intVal($product->amount);
            $total      = 0;
        try {
            $item = new Factor();
            $item->user_id      = auth()->user()->id;
            $item->customer_id  = $request->customer_id;
            $item->product_id   = $request->product_id;
            $item->count        = $request->count;
            $item->total        = $total;
            $item->text         = $request->text;
            $item->time         = my_jdate($time,'d F Y');
            $item->time_en      = $time;
            $item->save();

            $customer->time     = my_jdate($time,'d F Y');
            $customer->time_en  = $time;
            $customer->save();
            return redirect()->route('admin.user-customer.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Factor::where('user_id',auth()->user()->id)->findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'فاکتور با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف فاکتور بوجود آمده،مجددا تلاش کنید');
        }
    }

}
