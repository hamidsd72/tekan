<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Model\Setting;
use App\Model\ServiceCat;
use App\Model\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SubServiceController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'زیردسته های مشاوره';
        } elseif ('single') {
            return 'زیردسته های مشاوره';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = ServiceCat::orderByDesc('id')->where('type','sub_service')->paginate($this->controller_paginate());
        return view('admin.service.sub_service.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create()
    {
        $items = ServiceCat::orderByDesc('id')->get(['id','title']);
        return view('admin.service.sub_service.create',compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:240',
            'slug' => 'required|max:250|unique:service_cats',
            'text' => 'nullable|max:200',
        ],
            [
                'title.required' => 'لطفا نام دسته بندی را وارد کنید',
                'title.max' => 'نام دسته بندی نباید بیشتر از 240 کاراکتر باشد',
                'slug.required' => 'لطفا نامک را وارد کنید',
                'slug.max' => 'نامک نباید بیشتر از 250 کاراکتر باشد',
                'slug.unique' => ' نامک وارد شده یکبار ثبت شده', 
                'pic' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            ]);
        try {
            $item = new ServiceCat();
            $item->title        = $request->title;
            $item->slug         = $request->slug;
            $item->sub_text1    = $request->sub_text1;
            $item->sub_text2    = $request->sub_text2;
            $item->sub_text3    = $request->sub_text3;
            $item->sub_text4    = $request->sub_text4;
            $item->text         = $request->text;
            $item->service_id   = $request->service_id;
            $item->type = 'sub_service';
            if ($request->hasFile('pic')) {
                if ($item->pic != null) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'source/asset/uploads/service_cat/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic_card-');;
            }
            $item->save();
            return redirect()->route('admin.sub_service.index')->with('flash_message', 'دسته بندی خدمت با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد دسته بندی خدمت بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($sub_service)
    {
        $items = ServiceCat::orderByDesc('id')->get(['id','title']);
        $item = ServiceCat::find($sub_service);
        return view('admin.service.sub_service.edit', compact('item','items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update(Request $request, $sub_service)
    {
        $this->validate($request, [
            'title' => 'required|max:240',
            'slug' => 'required|max:250|unique:service_cats,slug,'.$sub_service,
            'pic' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'text' => 'nullable|max:200',
        ],
            [
                'title.required' => 'لطفا نام دسته بندی را وارد کنید',
                'title.max' => 'نام دسته بندی نباید بیشتر از 240 کاراکتر باشد',
                'slug.required' => 'لطفا نامک را وارد کنید',
                'slug.max' => 'نامک نباید بیشتر از 250 کاراکتر باشد',
                'slug.unique' => ' نامک وارد شده یکبار ثبت شده',
            ]);
        $item = ServiceCat::find($sub_service);
        // try {
            $item->title        = $request->title;
            $item->slug         = $request->slug;
            $item->sub_text1    = $request->sub_text1;
            $item->sub_text2    = $request->sub_text2;
            $item->sub_text3    = $request->sub_text3;
            $item->sub_text4    = $request->sub_text4;
            $item->text         = $request->text;
            $item->service_id   = $request->service_id;
            if ($request->hasFile('pic')) {
                if ($item->pic != null) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'source/asset/uploads/service_cat/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic_card-');;
            }
            $item->slug = $request->slug;
            $item->update();
            return redirect()->route('admin.sub_service.index')->with('flash_message', 'دسته بندی خدمت با موفقیت ویرایش شد.');
        // } catch (\Exception $e) {
        //     return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش دسته بندی خدمت بوجود آمده،مجددا تلاش کنید');
        // }
    }

    public function destroy($sub_service)
    {
        $item = ServiceCat::find($sub_service);
        try {
            if(count($item->service)>0)
            {
                return redirect()->back()->withInput()->with('err_message', 'دسته دارای خدمات می باشد و نمیتوان حذف کرد');
            }
            $item->delete();
            return redirect()->back()->with('flash_message', 'دسته بندی خدمت با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف دسته بندی خدمت بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function active($id, $type)
    {
        $item = ServiceCat::find($id);
        try {
            $item->status = $type;
            $item->update();
            if ($type == 'pending') {
                return redirect()->back()->with('flash_message', 'نمایش دسته بندی خدمت با موفقیت غیرفعال شد.');
            }
            if ($type == 'active') {
                return redirect()->back()->with('flash_message', 'نمایش دسته بندی خدمت با موفقیت فعال شد.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در تغییر وضعیت دسته بندی خدمت بوجود آمده،مجددا تلاش کنید');
        }
    }
}


