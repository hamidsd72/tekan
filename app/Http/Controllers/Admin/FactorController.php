<?php

namespace App\Http\Controllers\Admin;

use App\Model\Code;
use App\Model\Sms;
use App\User;
use App\Model\Setting;
use App\Model\Factor;
use App\Model\Category;
use App\Model\Product;
use App\Model\Photo;
use App\Model\ServiceCat;
use App\Model\ProvinceCity;
use App\Model\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class FactorController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'لیست فروش';
        } elseif ('single') {
            return 'فروش';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        if ($request->has('myself')) {
            $items = Factor::where('creator_id',auth()->user()->id)->paginate($this->controller_paginate());
            return view('admin.factor.index', compact('items'), ['title1' => 'لیست فروش من', 'title2' => 'لیست فروش من']);
        }

            $items = Factor::paginate($this->controller_paginate());
            return view('admin.factor.index', compact('items'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('sum')]);
    }


    public function create()
    {
        $user = auth()->user();
        if ($user->hasRole('مدیر')) {
            $customers = User::getAllCustomer();
        } else {
            $customers = User::getMyCustomers();
        }

        $customers = $customers->get();
        $categories = Category::all();
        $products = Product::all();
        $session_products = null;

        if (session()->has('factor_products')) {
            $productsSessionId = session()->get('factor_products');
            if ($productsSessionId && is_array($productsSessionId)) {
                $session_products = Product::whereIn('id', $productsSessionId)->get();
            }
        }

        return view('admin.factor.create', compact('customers', 'session_products', 'categories', 'products'), ['title1' => $this->controller_title('sum'), 'title2' => 'افزودن فروش جدید']);
    }


    public function add_product(Request $request, $id)
    {
        $item = Factor::findOrFail($id);

        $this->validate($request, [
            'product_id' => 'required',
        ], [
            'product_id.required' => 'لطفا  فروش را انتخاب کنید',
        ]);

        $item->products()->attach($request->product_id, ['number_products' => $request->number_products ?? 1]);

        return redirect()->back()->with('flash_message', 'محصول با موفقیت به فروش اضافه شد!');
    }



//    //work with session
//    public function add_product(Request $request)
//    {
//        $this->validate($request, [
//            'product_id' => 'required',
//        ], [
//            'product_id.required' => 'لطفا  فروش را انتخاب کنید',
//        ]);
//
//
//        if (session()->has('factor_products')) {
//            $factor_products = session()->get('factor_products');
//
//            array_push($factor_products, $request->product_id);
//
//            array_unique($factor_products);
//
//            session()->put('factor_products', $factor_products);
//        } else {
//            session()->put('factor_products', [$request->product_id]);
//        }
//
//        return redirect()->back()->with('flash_message', 'فروش با موفقیت به پیشنمایش فروش اضافه گردید!');
//    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required',
            'total' => 'required',
            'receiving_date' => 'required',
            'follow_date' => 'required',
        ],
            [
                'customer_id.required' => 'لطفا نام مشتری را انتخاب کنید',
                'total.required' => 'لطفا مبلغ کل را وارد کنید',
                'receiving_date.required' => 'لطفا  تاریخ گرفتن سفارش وارد کنید',
                'follow_date.required' => 'لطفا   تاریخ پیگیری مجدد وارد کنید',
            ]);

        try {
            $item = new Factor();
            $item->creator_id = $request->creator_id ??  auth()->user()->id;
            $item->customer_id = $request->customer_id;
            $item->total = $request->total;
            $item->receiving_date = convertFaDateToEn($request->receiving_date);
            $item->follow_date = convertFaDateToEn($request->follow_date);
            $item->save();

            if($request->has('redirect_url'))
                return redirect()->route('admin.factor.edit', [$item->id,'redirect_url'=>$request->redirect_url])->with('flash_message', 'فروش با موفقیت ایجاد شد،سفارشات فروش را انتخاب کنید.');

            return redirect()->route('admin.factor.edit', $item->id)->with('flash_message', 'فروش با موفقیت ایجاد شد،سفارشات فروش را انتخاب کنید.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد فروش بوجود آمده،مجددا تلاش کنید');
        }
    }


    public function edit($id)
    {
        $item = Factor::findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('مدیر')) {
            $customers = User::getAllCustomer();
        } else {
            $customers = User::getMyCustomers();
        }

        $customers = $customers->get();
        $categories = Category::all();
        $products = Product::all();

        return view('admin.factor.edit', compact('item', 'categories', 'customers', 'products'), ['title1' => $this->controller_title('sum'), 'title2' => 'ویرایش فروش']);
    }

    public function update(Request $request, $id)
    {
        $item = Factor::findOrFail($id);

        $this->validate($request, [
            'customer_id' => 'required',
            'total' => 'required',
            'receiving_date' => 'required',
            'follow_date' => 'required',
        ], [
            'customer_id.required' => 'لطفا نام مشتری را انتخاب کنید',
            'total.required' => 'لطفا مبلغ کل را وارد کنید',
            'receiving_date.required' => 'لطفا  تاریخ گرفتن سفارش وارد کنید',
            'follow_date.required' => 'لطفا   تاریخ پیگیری مجدد وارد کنید',
        ]);

        try {
            $item->customer_id = $request->customer_id;
            $item->total = $request->total;
            $item->receiving_date = num_to_en($request->receiving_date);
            $item->follow_date = num_to_en($request->follow_date);
            $item->save();

            if ($request->has('redirect_url')) {
                return redirect($request->redirect_url)->with('flash_message','فروش  با موفقیت ثبت شد.');
            }

            return redirect()->route('admin.factor.edit', $item->id)->with('flash_message', 'فروش  با موفقیت ثبت شد.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش فروش بوجود آمده،مجددا تلاش کنید');
        }

    }

    public function destroy($id)
    {
        $item = Factor::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'فروش با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف فروش بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function factor_product_destroy($factorId, $productId)
    {
        $item = Factor::findOrFail($factorId);
        try {
            $item->products()->detach($productId);
            return redirect()->back()->with('flash_message', 'محصول با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف فروش بوجود آمده،مجددا تلاش کنید');
        }
    }


}


