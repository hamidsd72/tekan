<?php

namespace App\Http\Controllers\Admin\Access;

use App\Model\PermissionCat;
use App\Http\Requests\Access\PermissionRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function controller_title($type)
    {
        switch ($type) {
            case 'index':
                return 'لیست مجوزها';
                break;
            case 'create':
                return 'افزودن  مجوز';
                break;
            case 'edit':
                return 'ویرایش مجوز';
                break;
            case 'url_back':
                return route('admin.permission.index');
                break;
            default:
                return '';
                break;
        }
    }

    public function __construct()
    {
        $this->middleware('permission:permission_list', ['only' => ['index','show']]);
        $this->middleware('permission:permission_create', ['only' => ['create','store']]);
        $this->middleware('permission:permission_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission_delete', ['only' => ['destroy']]);
    }

    public function index()
    {
        $items = PermissionCat::whereHas('permissions');
        if (!Auth::user()->HasRole('developer')) {
            $items = $items->whereNotIn('id', [1, 2]);
        }
        $items = $items->orderBy('sort_by', 'ASC')->get();
        return view('admin.access.permission.index', compact('items'), ['title' => $this->controller_title('index')]);
    }

    public function show($id)
    {

    }

    public function create()
    {
        $url_back = $this->controller_title('url_back');
        $cats = PermissionCat::orderBy('sort_by', 'ASC');
        if (!Auth::user()->HasRole('developer')) {
            $cats = $cats->whereNotIn('id', [1, 2]);
        }
        $cats=$cats->get();
        return view('admin.access.permission.create', compact('url_back', 'cats'), ['title' => $this->controller_title('create')]);
    }

    public function store(PermissionRequest $request)
    {
        $cat=PermissionCat::findOrFail($request->category_id);
        if(Permission::where('name',$cat->access_code.'_'.$request->name)->first())
        {
            return redirect()->back()->withInput()->with('err_message', 'کد دسترسی نباید تکراری باشد');
        }
        try {
            $item = Permission::create([
                'category_id' => $request->category_id,
                'name' => $cat->access_code.'_'.$request->name,
                'title' => $request->title,
                'guard_name' => 'web',
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function edit($id)
    {
        $url_back = $this->controller_title('url_back');
        $cats = PermissionCat::orderBy('sort_by', 'ASC');
        if (!Auth::user()->HasRole('developer')) {
            $cats = $cats->whereNotIn('id', [1, 2]);
        }
        $cats=$cats->get();
        $item = Permission::findOrFail($id);
        $cat_set=PermissionCat::find($item->category_id);
        return view('admin.access.permission.edit', compact('url_back', 'item', 'cats','cat_set'), ['title' => $this->controller_title('edit')]);
    }

    public function update(PermissionRequest $request, $id)
    {
        $item = Permission::findOrFail($id);
        $cat=PermissionCat::findOrFail($request->category_id);
        if(Permission::where('name',$cat->access_code.'_'.$request->name)->where('id','!=',$id)->first())
        {
            return redirect()->back()->withInput()->with('err_message', 'کد دسترسی نباید تکراری باشد');
        }
        try {
            $item->category_id = $request->category_id;
            $item->name = $cat->access_code.'_'.$request->name;
            $item->title = $request->title;
            $item->update();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = Permission::findOrFail($id);
        try {
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
}
