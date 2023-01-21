<?php

namespace App\Http\Controllers\Admin;

use App\Model\Code;
use App\Model\Sms;
use App\User;
use App\Model\Setting;
use App\Model\Product;
use App\Model\Category;
use App\Model\Photo;
use App\Model\ServiceCat;
use App\Model\ProvinceCity;
use App\Model\Consultation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'لیست محصولات';
        } elseif ('single') {
            return 'محصول';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware(['auth','isAdmin']);
    }

    public function index()
    {
        $items = Product::paginate($this->controller_paginate());
        return view('admin.product.index', compact('items'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('sum')]);
    }

    public function filter($id) {
        $products   = Product::where('category_id',$id)->get(['id','name']);
        $photos     = Photo::where('pictures_type','App\Model\Product')->whereIn('pictures_id', $products->pluck('id'))->get(['pictures_id','path']);
        foreach ($products as $product) {
            $product->pic = url('/').'/'.$photos->where('pictures_id', $product->id)->first()->path;
        }
        return $products;
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.product.create', compact('categories'), ['title1' => $this->controller_title('sum'), 'title2' => 'افزودن محصول']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:240|unique:products',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'name.required' => 'لطفا نام محصول را وارد کنید',
                'name.max' => 'نام محصول نباید بیشتر از 240 کاراکتر باشد',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        try {
            $item = new Product();
            $item->name = $request->name;
            $item->category_id = $request->category_id;
            $item->creator_id = auth()->user()->id;
            $item->save();

            if ($request->hasFile('photo')) {
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/product/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');;
                $item->photo()->save($photo);
            }

            return redirect()->route('admin.product.index')->with('flash_message', 'محصول با موفقیت ایجاد شد.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد محصول بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id)
    {
        $item = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.product.edit', compact('item', 'categories'), ['title1' => $this->controller_title('sum'), 'title2' => 'ویرایش محصول']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => "required|max:240|unique:products,name,$id",
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'name.required' => 'لطفا نام محصول را وارد کنید',
                'name.max' => 'نام محصول نباید بیشتر از 240 کاراکتر باشد',
                'name.unique' => 'نام محصول وارد شده یکبار ثبت  شده است',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);


        $item = Product::find($id);
        try {
            $item->name = $request->name;
            $item->category_id = $request->category_id;

            $item->update();
            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    $old_path = $item->photo->path;
                    File::delete($old_path);
                    $item->photo->delete();
                }
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/product/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');;
                $item->photo()->save($photo);
            }
            return redirect()->back()->with('flash_message', 'محصول با موفقیت ویرایش شد.');
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش محصول بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = Product::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'محصول با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف محصول بوجود آمده،مجددا تلاش کنید');
        }
    }


}


