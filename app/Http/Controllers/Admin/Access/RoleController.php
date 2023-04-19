<?php

namespace App\Http\Controllers\Admin\Access;

use App\Model\PermissionCat;
use App\Http\Requests\Access\RoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function controller_title($type)
    {
        switch ($type) {
            case 'index':
                return 'لیست سطح دسترسی';
                break;
            case 'create':
                return 'افزودن  سطح دسترسی';
                break;
            case 'edit':
                return 'ویرایش سطح دسترسی';
                break;
            case 'url_back':
                return route('admin.role.index');
                break;
            default:
                return '';
                break;
        }
    }

    public function __construct()
    {
        $this->middleware('permission:role_list', ['only' => ['index', 'show']]);
        $this->middleware('permission:role_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role_delete', ['only' => ['destroy']]);
    }

    public function index()
    {

        $items = Role::orderBy('id');
        if (!Auth::user()->HasRole('developer')) {
            $items->whereNotIn('name', ['developer','admin']);
        }
        $items = $items->get();
        return view('admin.access.role.index', compact('items'), ['title' => $this->controller_title('index')]);
    }

    public function show($id)
    {
    }

    public function create()
    {
        $permission_cats = PermissionCat::orderBy('sort_by', 'asc');
        if (!Auth::user()->HasRole('developer')) {
            $permission_cats = $permission_cats->whereNotIn('id', [1, 2]);
        }
        $permission_cats = $permission_cats->get();
        $url_back = $this->controller_title('url_back');
        return view('admin.access.role.create', compact('url_back', 'permission_cats'), ['title' => $this->controller_title('create')]);
    }

    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create(['name' => $request->input('name'), 'title' => $request->input('title')]);
            $role->syncPermissions($request->input('permission'));
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function edit($id)
    {
        $url_back = $this->controller_title('url_back');
        $item = Role::findOrFail($id);
        $permission_role = [];
        foreach ($item->permissions as $permission) {
            array_push($permission_role, $permission->id);
        }
        $permission_cats = PermissionCat::orderBy('sort_by', 'asc');
        if (!Auth::user()->HasRole('developer')) {
            $permission_cats = $permission_cats->whereNotIn('id', [1, 2]);
        }
        $permission_cats = $permission_cats->get();
        return view('admin.access.role.edit', compact('url_back', 'item', 'permission_cats', 'permission_role'), ['title' => $this->controller_title('edit')]);
    }

    public function update(RoleRequest $request, $id)
    {
        $item = Role::findOrFail($id);;
        try {
            $item->name = $request->input('name');
            $item->title = $request->input('title');
            $item->update();
            $item->syncPermissions($request->input('permission'));
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت ویرایش شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = Role::where('id',$id);
        if (!Auth::user()->HasRole('developer')) {
            $item->whereNotIn('name', ['developer', 'admin']);
        }
        $item=$item->firstOrFail();
        try {
            $item->delete();
            return redirect($this->controller_title('url_back'))->with('flash_message', 'اطلاعات با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای حذف به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

}
