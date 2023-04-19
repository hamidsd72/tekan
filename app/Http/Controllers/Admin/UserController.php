<?php

namespace App\Http\Controllers\Admin;

use App\Model\Factor;
use App\Model\Code;
use App\Model\Sms;
use App\Model\Call;
use App\User;
use App\Model\Setting;
use App\Model\Photo;
use App\Model\ServiceCat;
use App\Model\Potential;
use App\Model\ProvinceCity;
use App\Model\Consultation;
use Carbon\Carbon;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class UserController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'لیست اعضاء سازمان';
        } elseif ('single') {
            return 'سازمان';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct() {
        $this->middleware('auth');
    }
    public function roleLevelUpRequest() {
        $role = auth()->user()->roles->first()?auth()->user()->roles->first()->title:null;
        if ( in_array( $role, ['مدیر','برنامه نویس','حامی الماس - کارآفرین پویا','حامی پلاتین - کارآفرین کوشا','حامی طلایی','حامی نقره ای'] ) ) {
            switch ( $role ) {
                case 'حامی الماس - کارآفرین پویا':
                    $show_level = ['حامی الماس - کارآفرین پویا','حامی پلاتین - کارآفرین کوشا','حامی طلایی','حامی نقره ای','نماینده','نماینده مستقل','پشتیبان'];
                    break;
                case 'حامی پلاتین - کارآفرین کوشا':
                    $show_level = ['حامی پلاتین - کارآفرین کوشا','حامی طلایی','حامی نقره ای','نماینده','نماینده مستقل','پشتیبان'];
                    break;
                case 'حامی طلایی':
                    $show_level = ['حامی طلایی','حامی نقره ای','نماینده','نماینده مستقل','پشتیبان'];
                    break;
                case 'حامی نقره ای':
                    $show_level = ['حامی نقره ای','نماینده','نماینده مستقل','پشتیبان'];
                    break;
                default:
                    $show_level = ['حامی الماس - کارآفرین پویا','حامی پلاتین - کارآفرین کوشا','حامی طلایی','حامی نقره ای','نماینده','نماینده مستقل','پشتیبان'];
                    break;
            }
            $items  = User::whereIn('request_level', $show_level)->whereIn('id', getSubUser([auth()->id()])[2] )->get();
            return view('admin.access.level_up.index', compact('items'), ['title1' => 'درخواست های ارتقا لول', 'title2' => 'درخواست ارتقا لول']);
        }

    }

    public function roleLevelUp() {
        $role = auth()->user()->roles->first()?auth()->user()->roles->first()->title:null;
        if ( in_array( $role, ['مدیر','برنامه نویس','حامی الماس - کارآفرین پویا'] ) ) {
            return redirect()->back()->withInput()->with('err_message', 'درخواست برای این رول مجاز نیست');
        }

        switch ( $role ) {
            case 'پشتیبان':
                $level = 'نماینده';
                break;
            case 'نماینده':
                $level = 'نماینده مستقل';
                break;
            case 'نماینده مستقل':
                $level = 'حامی نقره ای';
                break;
            case 'حامی نقره ای':
                $level = 'حامی طلایی';
                break;
            case 'حامی طلایی':
                $level = 'حامی پلاتین - کارآفرین کوشا';
                break;
            case 'حامی پلاتین - کارآفرین کوشا':
                $level = 'حامی الماس - کارآفرین پویا';
                break;
            default:
                $level = 'پشتیبان';
                break;
        }
        auth()->user()->request_level = $level;
        auth()->user()->update();
        return redirect()->back()->with('flash_message', 'درخواست با موفقیت ارسال شد');
    }

    public function roleLevelUpResult($id , $result) {
        if ( auth()->user()->roles->count() && in_array( auth()->user()->roles->first()->title,
         ['مدیر','برنامه نویس','حامی الماس - کارآفرین پویا','حامی طلایی','حامی نقره ای','حامی پلاتین - کارآفرین کوشا'] ) ) {

            $user = User::findOrFail($id);
            if ($user->request_level) {
                
                if ($user->getRoleNames()->count()) {
                    foreach ($user->getRoleNames() as $role_name) $user->removeRole($role_name);
                }
                
                $role = \App\Model\Role::where('title', $user->request_level)->firstOrFail();
                if ($result=='ok') $user->assignRole($role->name);
                $user->request_level = null;
                $user->update();
                return redirect()->back()->with('flash_message', 'درخواست با موفقیت انجام شد');
            }
            
        }

        return redirect()->back()->withInput()->with('err_message', 'درخواست برای این رول مجاز نیست');
    }

    public function userRole(Request $request)
    {
        $user = User::findOrFail($request->id);
        if ($request->role_name) {
            // remove current roles
            foreach ($user->getRoleNames() as $role_name) {
                $user->removeRole($role_name);
            }
            // assign new roles
            $user->assignRole($request->role_name);
        }
        return back();
    }

    public function user_potential_update(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'potentials_id' => 'required'
        ]);
        $user = User::findOrFail($request->id);

       $potential_exist = $user->potentials()->find($request->potentials_id);
        if ($potential_exist) {
            if ($request->has('redirect_url'))
                return redirect($request->redirect_url)->with('err_message','این آیتم وجود دارد !');

            return redirect()->back()->with('err_message','این آیتم وجود دارد !');
        }


        $user->potentials()->attach($request->potentials_id);

        if ($request->has('redirect_url')) {
            return redirect($request->redirect_url);
        }
        return redirect(route('admin.user.show',$user->id).'?tab=potential');
    }

    public function user_potential_update_sub(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'potential_id' => 'required',
                //            'sub_potential' => 'required'
        ]);

        $user = User::findOrFail($request->user_id);

        try {

            $item = $user->potentials()->find($request->potential_id);

            $item->pivot->sub_id = $request->sub_potential;
            $item->pivot->save();

            return redirect()->back()->with('flash_message', 'پتانسیل با موفقیت بروزرسانی شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در بروزرسانی بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy_potential($userId, $potentialId)
    {
        $user = User::findOrFail($userId);

        try {
            $item = $user->potentials()->detach($potentialId);
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }

    }

    public function index($role = null)
    {
        if (is_null($role)) {
            $items = User::getFirstLevelUsers();

            $title2 = $this->controller_title('sum');
        } else {
            $items = User::getAllUsers($role);
            $title2 = "لیست اعضاء ( $role )" ;

        }

//        $items = User::getAllUsers($role);

        $items = $items->paginate($this->controller_paginate());

        $potentials = Potential::whereNull('parent_id')->get();

        return view('admin.user.index', compact('items', 'potentials'), ['title1' => 'کاربران', 'title2' => $title2]);
    }

    public function potantial_index($id, $name = '')
    {

        $potential = Potential::find($id);
        $potentials = Potential::whereNull('parent_id')->get();
        $items = User::getAllUsers()->whereHas('potentials', function ($query) use ($id) {
            $query->where('id', $id);
        });

        $items = $items->paginate($this->controller_paginate());

        $title2 = $potential ? $potential->name : $name;


        $users = User::getAllUsers()->get();

        return view('admin.user.index', compact('items','users', 'potential', 'potentials'), ['title1' => ' پتانسیل سازمان', 'title2' => ' لیست کاربران -  ' . $title2]);
    }

    public function sub_user_index($id)
    {
        $user = User::findOrFail($id);

        $items = User::getFirstLevelUsers($id);
        $items = $items->paginate($this->controller_paginate());


        return view('admin.user.index', compact('items', 'user'), [
            'title1' => 'کاربران',
            'title2' => " لیست اعضاء سازمان '$user->full_name '"
        ]);
    }

    public function index_tree()
    {
        $user = auth()->user();
        $items = $user->sub_users;

        return view('admin.user.tree', compact('user', 'items'), ['title1' => 'کاربران', 'title2' => 'کاربران من']);
    }

    public function show($id)
    {
        $item = User::findOrFail($id);
        $user = $item;

        if ($item->roles && $item->roles->count() == 0)
            return redirect()->route('admin.customer.show', $item->id);

        if (Auth::user()->hasRole('مدیر')) {
            $item = User::find($id);
        } elseif (Auth::user()->hasRole('نماینده مستقل')) {
            $item = User::where('id', $id)->first();
            if (!$item)
                abort(404);
        } elseif (Auth::user()->hasRole('کاربر')) {
            $item = User::where('id', $id)->where('creator_id', Auth::user()->id)->first();
            if (!$item)
                abort(404);
        } else {
            abort(404);
        }


        $calls = Call::where('user_id', $item->id)->get();

        $users = User::sub_users_for($item->id);
        $customers = User::sub_customers_for($id)->get();


        $factors = Factor::where('creator_id', $item->id)->get();

        $potentials = Potential::whereNull('parent_id')->get();

        $call_statuses = Call::statuses();

        return view('admin.user.show', compact('item','id', 'user','call_statuses', 'potentials', 'calls', 'users', 'customers', 'factors'), [
            'title1' => "اطلاعات '{$item->full_name}' ",
            'title2' => 'پروفایل کاربر'
        ]);
    }

    public function create()
    {
        $states = ProvinceCity::where('parent_id', null)->get();
        return view('admin.user.create', compact('states'), ['title1' => 'کاربران', 'title2' => 'افزودن کاربر']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|max:240',
            'last_name' => 'required|max:240',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric|unique:users',
            'email' => 'nullable|email|unique:users',
            'national_code' => 'required|unique:users',
            'reagent_code' => 'required|unique:users',
//            'date_birth' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
//            'locate' => 'required',
//            'address' => 'required',
//            'education' => 'required',
            'password' => 'required|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'first_name.required' => 'لطفا نام خود را وارد کنید',
                'first_name.max' => 'نام نباید بیشتر از 240 کاراکتر باشد',
                'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید',
                'last_name.max' => 'نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
                'mobile.required' => 'لطفا موبایل خود را وارد کنید',
                'mobile.regex' => 'لطفا موبایل خود را وارد کنید',
                'mobile.digits' => 'لطفا فرمت موبایل را رعایت کنید',
                'mobile.numeric' => 'لطفا موبایل خود را بصورت عدد وارد کنید',
                'mobile.unique' => 'موبایل وارد شده یکبار ثبت نام شده',
                'email.required' => 'لطفا ایمیل خود را وارد کنید',
                'email.email' => 'فرمت ایمیل را رعایت کنید',
                'email.unique' => ' ایمیل وارد شده یکبار ثبت نام شده',
                'whatsapp.required' => 'لطفا شماره واتساپ فعال خود را وارد کنید',
                'date_birth.required' => 'لطفا تاریخ تولد خود را وارد کنید',
                'state_id.required' => 'لطفا استان خود را وارد کنید',
                'city_id.required' => 'لطفا شهر خود را وارد کنید',
                'locate.required' => 'لطفا منطقه خود را وارد کنید',
                'address.required' => 'لطفا آدرس خود را وارد کنید',
                'education.required' => 'لطفا مدرک تحصیلی خود را وارد کنید',
                'password.required' => 'لطفا رمز عبور خود را وارد کنید',
                'password.min' => 'رمز عبور نباید کمتر از 6 کاراکتر باشد',
                'password.confirmed' => 'رمز عبور با تکرار آن برابر نیست',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        try {
            $item = new User();
            $item->first_name = $request->first_name;
            $item->last_name = $request->last_name;
            $item->mobile = $request->mobile;
            $item->email = $request->email;
            $item->national_code = $request->national_code;
            $item->reagent_code = $request->reagent_code;
            $item->date_birth = num_to_en($request->date_birth);
            $item->state_id = $request->state_id;
            $item->city_id = $request->city_id;
            $item->locate = $request->locate;
            $item->address = $request->address;
            $item->education = $request->education;
            $item->password = $request->password ?? null;
            $item->reagent_id = $request->reagent_id ??  auth()->user()->id;
            $item->creator_id = $request->creator_id ?? auth()->user()->id;
            $item->save();
            $item->assignRole('کاربر');
            if ($request->hasFile('photo')) {
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');;
                $item->photo()->save($photo);
            }


            if ($request->has('redirect_url')) {
                return redirect($request->redirect_url)->with('flash_message', 'کاربر با موفقیت ایجاد شد.');
            }

            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ایجاد شد.');

//            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id)
    {

        if (Auth::user()->hasRole('مدیر')) {
            $item = User::find($id);
        } elseif (Auth::user()->hasRole('کاربر')) {
            $item = User::where('id', $id)->where('creator_id', auth()->user()->id)->first();

            if (!$item)
                abort(404);

        } else {
            abort(404);
        }

        $states = ProvinceCity::where('parent_id', null)->get();
        $citys = ProvinceCity::where('parent_id', $item->state_id)->get();
        return view('admin.user.edit', compact('item', 'states', 'citys'), ['title1' => 'کاربران', 'title2' => 'ویرایش کاربر']);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'first_name' => 'required|max:240',
            'last_name' => 'required|max:240',
            'mobile' => 'required|regex:/(09)[0-9]{9}/|digits:11|numeric|unique:users,mobile,' . $id,
            'national_code' => 'required|unique:users,national_code,' . $id,
            'email' => 'nullable|email|unique:users,email,' . $id,
            'reagent_code' => 'nullable|reagent_code|unique:users,reagent_code,' . $id,
//            'date_birth' => 'nullable',
            'state_id' => 'nullable',
            'city_id' => 'nullable',
//            'locate' => 'nullable',
//            'address' => 'nullable',
//            'education' => 'nullable',
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'first_name.required' => 'لطفا نام خود را وارد کنید',
                'first_name.max' => 'نام نباید بیشتر از 240 کاراکتر باشد',
                'last_name.required' => 'لطفا نام خانوادگی خود را وارد کنید',
                'last_name.max' => 'نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
                'mobile.national_code' => 'لطفا کدملی خود را وارد کنید',
                'mobile.required' => 'لطفا موبایل خود را وارد کنید',
                'mobile.regex' => 'لطفا موبایل خود را وارد کنید',
                'mobile.digits' => 'لطفا فرمت موبایل را رعایت کنید',
                'mobile.numeric' => 'لطفا موبایل خود را بصورت عدد وارد کنید',
                'mobile.unique' => 'موبایل وارد شده یکبار ثبت نام شده',
                'email.required' => 'لطفا ایمیل خود را وارد کنید',
                'email.email' => 'فرمت ایمیل را رعایت کنید',
                'email.unique' => ' ایمیل وارد شده یکبار ثبت نام شده',
                'date_birth.required' => 'لطفا تاریخ تولد خود را وارد کنید',
                'state_id.required' => 'لطفا استان خود را وارد کنید',
                'city_id.required' => 'لطفا شهر خود را وارد کنید',
                'locate.required' => 'لطفا منطقه خود را وارد کنید',
                'address.required' => 'لطفا آدرس خود را وارد کنید',
                'education.required' => 'لطفا مدرک تحصیلی خود را وارد کنید',
                'password.min' => 'رمز عبور نباید کمتر از 6 کاراکتر باشد',
                'password.confirmed' => 'رمز عبور با تکرار آن برابر نیست',
                'photo.image' => 'لطفا یک تصویر انتخاب کنید',
                'photo.mimes' => 'لطفا یک تصویر با پسوندهای (png,jpg,jpeg) انتخاب کنید',
                'photo.max' => 'لطفا حجم تصویر حداکثر 5 مگابایت باشد',
            ]);
        $item = User::find($id);
        try {
            $item->first_name = $request->first_name;
            $item->last_name = $request->last_name;
            $item->mobile = $request->mobile;
            $item->national_code = $request->national_code;
            $item->reagent_code = $request->reagent_code;
            $item->email = $request->email;
            $item->date_birth = num_to_en($request->date_birth);
            $item->state_id = $request->state_id;
            $item->city_id = $request->city_id;
//            $item->locate = $request->locate;
            $item->address = $request->address;
            $item->education = $request->education;
            if ($request->password) {
                $item->password = $request->password;
            }
            $item->update();
            if ($request->hasFile('photo')) {
                if ($item->photo) {
                    $old_path = $item->photo->path;
                    File::delete($old_path);
                    $item->photo->delete();
                }
                $photo = new Photo();
                $photo->path = file_store($request->photo, 'source/asset/uploads/user/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/photos/', 'photo-');;
                $item->photo()->save($photo);
                img_resize(
                    $photo->path,//address img
                    $photo->path,//address save
                    100,// width: if width==0 -> width=auto
                    100// height: if height==0 -> height=auto
                // end optimaiz
                );
            }
            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ویرایش شد.');
//            return redirect()->route('admin.user.list')->with('flash_message', 'کاربر با موفقیت ویرایش شد.');
        } catch (\Exception $e) {

            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id)
    {
        $item = User::find($id);
        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'کاربر با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function active($id, $type)
    {
        $item = User::find($id);
        try {
            $item->user_status = $type;
            $item->update();
            if ($type == 'blocked') {
                return redirect()->back()->with('flash_message', 'کاربر با موفقیت مسدود شد.');
            }
            if ($type == 'active') {
                return redirect()->back()->with('flash_message', 'کاربر با موفقیت فعال شد.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در تغییر وضعیت کاربر بوجود آمده،مجددا تلاش کنید');
        }
    }


}


