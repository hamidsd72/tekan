<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use App\Model\Category;
use App\Model\Package;
use Carbon\Carbon;
use App\Model\PackageReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست پک های پشتیبان';
        elseif ('single') return 'پک پشتیبان';
    }

    public function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        return strtr( $input, $replace_pairs );
    }
    
    public function __construct() { $this->middleware('auth'); }

    public function index() {
        $items      = Package::where('user_id', auth()->user()->id)->get();
        return view('admin.customer_bank.package.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create() {
        $categories = Category::all();
        return view('admin.customer_bank.package.create', compact('categories'), ['title1' => ' افزودن '.$this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $item = Package::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('admin.customer_bank.package.show', compact('item'), ['title1' => ' اطلاعات '.$this->controller_title('single').' '.$item->product?$item->product->name:'__', 'title2' => $this->controller_title('sum')]);
    }

    public function edit($id) {
        $item = Package::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('admin.customer_bank.package.edit', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
//            'name'          => 'required|max:255',
            'product_id'    => 'required|integer',
            'count'         => 'required|integer',
            'description'   => 'max:2550',
        ], [
//                'name.required'         => 'لطفا عنوان را وارد کنید',
//                'name.max'              => 'عنوان نباید بیشتر از 240 کاراکتر باشد',
                'product_id.required'   => 'لطفا نام محصول را وارد کنید',
                'product_id.integer'    => 'نام محصول معتبر نیست',
                'count.required'        => 'لطفا تعداد محصول را وارد کنید',
                'count.integer'         => 'تعداد محصول معتبر نیست',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراتکتر باشد',
            ]);
        try {
            $item = new Package();
            $item->user_id      = auth()->user()->id;
//            $item->name         = $request->name;
            $item->product_id   = $request->product_id;
            $item->count        = $request->count;
            $item->description  = $request->description;
            $item->save();
            return redirect()->route('admin.user-customer-package.index')->with('flash_message', 'آیتم با موفقیت اضافه شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
//            'name'          => 'required|max:255',
            'description'   => 'max:2550',
        ], [
//                'name.required'         => 'لطفا uk,hk را وارد کنید',
//                'name.max'              => 'uk,hk نباید بیشتر از 240 کاراکتر باشد',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراتکتر باشد',
            ]);
        $item = Package::where('user_id',auth()->user()->id)->findOrFail($id);
        try {
//            $item->name         = $request->name;
            $item->description  = $request->description;
            $item->update();
            return redirect()->route('admin.user-customer-package.index')->with('flash_message', 'آیتم با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Package::where('user_id', auth()->user()->id)->findOrFail($id);
        try {
            if($item->package_reports()->count()) return redirect()->back()->withInput()->with('err_message', 'این آیتم دارای فاکتور میباشد , قابل حذف نیست');
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function add_package_report(Request $request) {
        $this->validate($request, [
            'package_id'    => 'required|integer',
            'customer_id'   => 'integer',
            'status'        => 'required|max:255',
            'count'         => 'required|integer|min:1',
            'description'   => 'max:2550',
        ], [
                'package_id.required'   => 'لطفا نام پکیج را وارد کنید',
                'package_id.integer'    => 'نام پکیج معتبر نیست',
                // 'customer_id.required'  => 'لطفا نام مشتری را وارد کنید',
                'customer_id.integer'   => 'نام مشتری معتبر نیست',
                'status.required'       => 'لطفا وضعیت را وارد کنید',
                'status.max'            => 'وضعیت نباید بیشتر از 240 کاراکتر باشد',
                'count.required'        => 'لطفا تعداد محصول را وارد کنید',
                'count.integer'         => 'تعداد محصول معتبر نیست',
                'count.min'         => 'تعداد محصول معتبر نیست',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراتکتر باشد',
            ]);

        $package = Package::where('user_id', auth()->user()->id)->findOrFail($request->package_id);
        if ($request->count > ($package->count-$package->package_reports()->sum('count'))) return redirect()->back()->withInput()->with('err_message', 'عدد وارد شده از باقیمانده پکیج بیشتر است');
        
        try {
            $item = new PackageReport();
            $item->user_id      = auth()->user()->id;
            $item->package_id   = $request->package_id;
            $item->customer_id  = $request->customer_id;
            $item->status       = $request->status;
            $item->count        = $request->count;
            $item->description  = $request->description;
            $item->time         = $request->time;
            $item->time_en      = Carbon::parse(j2g($this->toEnNumber($request->time)));
            $item->save();
            return redirect()->route('admin.user-customer-package.index')->with('flash_message', 'آیتم با موفقیت اضافه شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy_package_report($id) {
        $item = PackageReport::where('user_id', auth()->user()->id)->findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}

