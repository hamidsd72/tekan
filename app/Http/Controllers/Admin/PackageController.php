<?php

namespace App\Http\Controllers\Admin;

use App\Model\Package;
use App\Model\PackageReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PackageController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست پک های پشتیبان';
        elseif ('single') return 'پک پشتیبان';
    }

    public function __construct() { $this->middleware('auth'); }

    public function index() {
        $items = Package::where('user_id', auth()->user()->id)->get();
        return view('admin.package.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function edit($id) {
        $item = Package::where('user_id', auth()->user()->id)->findOrFail($id);
        return view('admin.package.edit', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create() {
        return view('admin.package.create', ['title1' => ' افزودن '.$this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'title' => 'required|max:240',
            'slug' => 'required|max:250|unique:packages',
            'text' => 'required',
            'price' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'pic_card' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ], [
                'title.required' => 'لطفا نام پکیج را وارد کنید',
                'title.max' => 'نام پکیج نباید بیشتر از 240 کاراکتر باشد',
                'slug.required' => 'لطفا نامک را وارد کنید',
                'slug.max' => 'نامک نباید بیشتر از 250 کاراکتر باشد',
                'slug.unique' => ' نامک وارد شده یکبار ثبت شده',
                'text.required' => 'لطفا توضیحات را وارد کنید',
                'price.required' => 'لطفا هزینه را وارد کنید',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',

            ]);
        try {
            $item->title = $request->title;
            $item->slug = $request->slug;
            $item->text = $request->text;
            $item->sort_by = $request->sort_by;
            $item->home_view = $home_view;
            $item->status = $request->status;
            $item->price = $request->price;
            $item->price_type = $request->price_type;
            $item->save();
            return redirect()->route('admin.package.list')->with('flash_message', 'پکیج  با موفقیت اضافه شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در افزودن پکیج  بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required|max:240',
            'slug' => 'required|max:250|unique:packages,slug,' . $id,
            'text' => 'required',
            'price' => 'required',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'pic_card' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ], [
                'title.required' => 'لطفا نام پکیج را وارد کنید',
                'title.max' => 'نام پکیج نباید بیشتر از 240 کاراکتر باشد',
                'slug.required' => 'لطفا نامک را وارد کنید',
                'slug.max' => 'نامک نباید بیشتر از 250 کاراکتر باشد',
                'slug.unique' => ' نامک وارد شده یکبار ثبت شده',
                'text.required' => 'لطفا توضیحات را وارد کنید',
                'price.required' => 'لطفا هزینه را وارد کنید',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',

            ]);
        try {
            $item->title = $request->title;
            $item->slug = $request->slug;
            $item->text = $request->text;
            $item->sort_by = $request->sort_by;
            $item->home_view = $home_view;
            $item->status = $request->status;
            $item->price = $request->price;
            $item->price_type = $request->price_type;
            $item->update();
            return redirect()->route('admin.package.list')->with('flash_message', 'پکیج  با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش پکیج  بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Package::where('user_id', auth()->user()->id)->findOrFail($id);
        try {
            // if($item->project) return redirect()->back()->withInput()->with('err_message', 'برای این پکیج پروژه ای ثبت شده است و قابل حذف نمی باشد');
            // $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }


}


