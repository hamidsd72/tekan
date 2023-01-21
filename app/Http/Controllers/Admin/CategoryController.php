<?php

namespace App\Http\Controllers\Admin;

use App\Model\Code;
use App\Model\Sms;
use App\User;
use App\Model\Setting;
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

class CategoryController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست دسته بندی';
        elseif ('single') return 'دسته بندی';
    }

    public function __construct() { $this->middleware(['auth',]); }

    public function index() {
        $items = Category::all();
        return view('admin.category.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }


    public function create() {
        $categories = Category::all();
        return view('admin.category.create', compact('categories'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:240|unique:categories,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'name.required' => 'لطفا نام دسته بندی را وارد کنید',
                'name.max' => 'نام دسته بندی نباید بیشتر از 240 کاراکتر باشد',
            ]);
        try {
            $item = new Category();
            $item->name = $request->name;
            $item->parent_id = $request->parent_id;
            $item->save();

            return redirect()->route('admin.category.index')->with('flash_message', 'دسته بندی با موفقیت ایجاد شد.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد دسته بندی بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id)
    {
        $item = Category::findOrFail($id);
        $categories = Category::where('id','!=',$id)->get();
        return view('admin.category.edit', compact('item', 'categories'), ['title1' => $this->controller_title('sum'), 'title2' => 'ویرایش دسته بندی']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => "required|max:240|unique:products,name,$id",
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'name.required' => 'لطفا نام دسته بندی را وارد کنید',
                'name.max' => 'نام دسته بندی نباید بیشتر از 240 کاراکتر باشد',
                'name.unique' => 'نام دسته بندی وارد شده یکبار ثبت  شده است',
            ]);


        $item = Category::find($id);
        try {
            $item->name = $request->name;
            $item->parent_id = $request->parent_id;

            $item->update();

            return redirect()->back()->with('flash_message', 'دسته بندی با موفقیت ویرایش شد.');
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش دسته بندی بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = Category::findOrFail($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'دسته بندی با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف دسته بندی بوجود آمده،مجددا تلاش کنید');
        }
    }


}


