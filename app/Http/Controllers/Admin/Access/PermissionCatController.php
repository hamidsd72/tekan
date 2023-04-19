<?php

namespace App\Http\Controllers\Admin\Access;


use App\Model\PermissionCat;
use App\Http\Requests\Access\PermissionCatRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
 
use Spatie\Permission\Models\Permission;
class PermissionCatController extends Controller
{
    public function controller_title($type)
    {
        switch ($type)
        {
            case 'index':
                return 'لیست مدل ها';
                break;
            case 'create':
                return 'افزودن  مدل';
                break;
            case 'edit':
                return 'ویرایش مدل';
                break;
            case 'url_back':
                return route('admin.permissionCat.index');
                break;
            default:
                return '';
                break;
        }
    } 
    public function __construct()
    {
        $this->middleware('permission:permission_cat_list', ['only' => ['index','show']]);
        $this->middleware('permission:permission_cat_create', ['only' => ['create','store']]);
        $this->middleware('permission:permission_cat_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:permission_cat_delete', ['only' => ['destroy']]);
    }
    public function index()
    {
        $items=PermissionCat::orderBy('sort_by')->get();
        return view('admin.access.permission_cat.index', compact('items'), ['title' => $this->controller_title('index')]);
    }
    public function show($id)
    {

    }
    public function create()
    {
        $url_back=$this->controller_title('url_back');
        $sort_number=PermissionCat::count()+1;
        return view('admin.access.permission_cat.create',compact('url_back','sort_number'), ['title' => $this->controller_title('create')]);
    }
    public function store(PermissionCatRequest $request)
    {
        try {
            $item = PermissionCat::create([
                'table_name' => $request->table_name,
                'sort_by' => $request->sort_by,
                'access_code' => $request->access_code,
                'access_list_code' => $request->access_code.'_list',
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function edit($id)
    {
        $url_back=$this->controller_title('url_back');
        $item=PermissionCat::findOrFail($id);
        return view('admin.access.permission_cat.edit',compact('url_back','item'), ['title' => $this->controller_title('edit')]);
    }
    public function update(PermissionCatRequest $request,$id)
    {
        $item=PermissionCat::findOrFail($id);
        try {
            $item1=PermissionCat::where('id',$id)->update([
                'table_name' => $request->table_name,
                'sort_by' => $request->sort_by,
                'access_code' => $request->access_code,
                'access_list_code' => $request->access_code.'_list',
            ]);
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function destroy($id)
    {
        $item=PermissionCat::findOrFail($id);
        try {
            if(count($item->permissions))
            {
                return redirect()->back()->withInput()->with('err_message', 'برای حذف نباید مجوزی برای این بخش تعریف شده باشد');
            }
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
}
