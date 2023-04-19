<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use App\Model\About;
use App\Model\AboutJoin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'درباره ما';
        } elseif ('single') {
            return 'درباره ما';
        }
    }

    public function controller_paginate()
    {
        return Setting::select('paginate')->latest()->firstOrFail()->paginate;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $item = About::first();
        $items = AboutJoin::where('type', 'about')->get();
        return view('admin.content.about.edit', compact('item', 'items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update2(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:240',
//            'video_link' => 'required|max:240',
            'pic' => 'nullable|image|mimes:png,jpg,jpeg',
//            'text' => 'required',
//            'title_tab1' => 'required|max:240',
//            'text_tab1' => 'required',
//            'title_tab2' => 'required|max:240',
//            'text_tab2' => 'required',
//            'title_tab3' => 'required|max:240',
//            'text_tab3' => 'required',
//            'title_tab4' => 'required|max:240',
//            'text_tab4' => 'required',
        ], [
            'title.required' => 'لطفا عنوان درباره ما را وارد کنید',
            'title.max' => 'عنوان درباره ما نباید بیشتر از 240 کاراکتر باشد',
            'title_home.required' => 'لطفا عنوان درباره ما صفحه اصلی را وارد کنید',
            'title_home.max' => 'عنوان درباره ما صفحه اصلی نباید بیشتر از 240 کاراکتر باشد',
            'text_home.required' => 'لطفا متن درباره ما صفحه اصلی را وارد کنید',
            'pic.image' => 'لطفا یک تصویر انتخاب کنید',
            'pic.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
        ]);

        $item = About::find($id);
        try {
            $item->title = $request->title;
            $item->video_link = $request->video_link;
            $item->text = $request->text;
            $item->title_tab1 = $request->title_tab1;
            $item->text_tab1 = $request->text_tab1;

            $item->title_tab2 = $request->title_tab2;
            $item->text_tab2 = $request->text_tab2;

            $item->title_tab3 = $request->title_tab3;
            $item->text_tab3 = $request->text_tab3;

            $item->title_tab4 = $request->title_tab4;
            $item->text_tab4 = $request->text_tab4;

            if ($request->hasFile('pic')) {
                if (is_file($item->pic)) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'source/asset/uploads/about/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic-');;

            }

            $item->update();

//            if(isset($request->title_join)) {
//                $items=AboutJoin::where('type','about')->get();
//                foreach ($items as $itemss) {
//                    $itemss->delete();
//                }
//                foreach ($request->title_join as $key=>$val)
//                {
//                    $pic=null;
//                    if(isset($request->pic_join[$key]))
//                    {
//                        $pic=$request->pic_join[$key];
//                    }
//                    $about_join=new AboutJoin();
//                    $about_join->title=$val;
//                    $about_join->type='about';
//                    $about_join->text=$request->text_join[$key];
//                    if (is_file($pic)) {
//                        if(isset($request->pic_join1[$key]) and is_file($request->pic_join1[$key]))
//                        {
//                            File::delete($request->pic_join1[$key]);
//                        }
//                        $about_join->pic = file_store($pic, 'source/asset/uploads/about/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic_'.$key.'-');;
//                    }
//                    elseif($request->pic_join1[$key]!=null)
//                    {
//                        $about_join->pic=$request->pic_join1[$key];
//                    }
//
//                }
//            }

            return redirect()->back()->with('flash_message', 'درباره ما با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش درباره ما بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'join_title' => 'required|max:240',
            'join_text' => 'max:2400',
            'join_pic' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'join_title.required' => 'لطفا عنوان درباره ما را وارد کنید',
                'join_title.max' => 'عنوان درباره ما نباید بیشتر از 240 کاراکتر باشد',
                'join_text.max' => 'متن نباید بیشتر از ۲۴۰۰ کاراکتر باشد',
                'join_pic.image' => 'لطفا یک تصویر انتخاب کنید',
                'join_pic.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'join_pic.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        $item = new AboutJoin();
        try {
            $item->join_title = $request->join_title;
            $item->join_text = $request->join_text;
            $item->type = 'about';
            if ($request->hasFile('join_pic')) {
                $item->join_pic = file_store($request->join_pic, 'source/asset/uploads/about_join/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic-');;

            }
            $item->save();

            return redirect()->back()->with('flash_message', 'درباره ما با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش درباره ما بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'title' => 'required|max:240',
//            'video_link' => 'required|max:240',
            'pic' => 'nullable|image|mimes:png,jpg,jpeg',
//            'text' => 'required',
//            'title_tab1' => 'required|max:240',
//            'text_tab1' => 'required',
//            'title_tab2' => 'required|max:240',
//            'text_tab2' => 'required',
//            'title_tab3' => 'required|max:240',
//            'text_tab3' => 'required',
//            'title_tab4' => 'required|max:240',
//            'text_tab4' => 'required',
        ], [
            'title.required' => 'لطفا عنوان درباره ما را وارد کنید',
            'title.max' => 'عنوان درباره ما نباید بیشتر از 240 کاراکتر باشد',
            'title_home.required' => 'لطفا عنوان درباره ما صفحه اصلی را وارد کنید',
            'title_home.max' => 'عنوان درباره ما صفحه اصلی نباید بیشتر از 240 کاراکتر باشد',
            'text_home.required' => 'لطفا متن درباره ما صفحه اصلی را وارد کنید',
            'pic.image' => 'لطفا یک تصویر انتخاب کنید',
            'pic.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
        ]);

        $item = About::find($id);
        try {
            $item->title = $request->title;
            $item->video_link = $request->video_link;
            $item->text = $request->text;
            $item->title_tab1 = $request->title_tab1;
            $item->text_tab1 = $request->text_tab1;

            $item->title_tab2 = $request->title_tab2;
            $item->text_tab2 = $request->text_tab2;

            $item->title_tab3 = $request->title_tab3;
            $item->text_tab3 = $request->text_tab3;

            $item->title_tab4 = $request->title_tab4;
            $item->text_tab4 = $request->text_tab4;

            if ($request->hasFile('pic')) {
                if (is_file($item->pic)) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'source/asset/uploads/about/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic-');;

            }

            $item->update();

            return redirect()->back()->with('flash_message', 'درباره ما با موفقیت ویرایش شد.');



//        $this->validate($request, [
//            'join_title' => 'required|max:240',
//            'join_text' => 'max:2400',
//            'join_pic' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
//        ],
//            [
//                'join_title.required' => 'لطفا عنوان درباره ما را وارد کنید',
//                'join_title.max' => 'عنوان درباره ما نباید بیشتر از 240 کاراکتر باشد',
//                'join_text.max' => 'متن نباید بیشتر از ۲۴۰۰ کاراکتر باشد',
//                'join_pic.image' => 'لطفا یک تصویر انتخاب کنید',
//                'join_pic.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
//                'join_pic.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
//            ]);
//        $item = AboutJoin::findOrFail($id);
//        try {
//            $item->join_title = $request->join_title;
//            $item->join_text = $request->join_text;
//            $item->type = 'about';
//            if ($request->hasFile('join_pic')) {
//                if (is_file($item->join_pic)) {
//                    File::delete($item->join_pic);
//                }
//                $item->join_pic = file_store($request->join_pic, 'source/asset/uploads/about_join/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'pic-');;
//
//            }
//            $item->save();
//
//            return redirect()->back()->with('flash_message', 'درباره ما با موفقیت ویرایش شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش درباره ما بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = AboutJoin::findOrFail($id);
        if (is_file($item->pic)) {
            File::delete($item->pic);
        }
        $item->delete();
        return redirect()->back()->withInput()->with('flash_message', 'درباره ما با موفقیت حذف شد.');
    }

}


