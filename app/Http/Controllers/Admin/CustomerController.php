<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Model\Code;
use App\Model\Sms;
use App\User;
use App\Model\Photo;
use App\Model\ServiceCat;
use App\Model\ProvinceCity;
use App\Model\Connection;
use App\Model\Customer;
use App\Model\Consultation;
use App\Model\Call;
use App\Model\Factor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CustomerController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'بانک مشتریان';
        elseif ('single') return 'مشتری';
    }

    public function __construct() { $this->middleware('auth'); }

    public function index() {
        // if ($request->has('myself')) {
        //     $title = 'مشتریان شخصی';
        //     $items = User::getFirstLevelMyCustomers()->get();
        // } else $items = User::getMySubCategoryCustomers()->get();
        // $serviceCat = ServiceCat::all();
        $items = Customer::where('user_id',auth()->user()->id)->get();
        return view('admin.customer.index', compact('items'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $item = Customer::where('user_id',auth()->user()->id)->find($id);
        if ($item) return response()->json(['name' => $item->name, 'class' => 'text-success'], 200);
        else return response()->json(['name' => 'یافت نشد', 'class' => 'text-danger'], 404); 
    }

    public function create() {
        $states = ProvinceCity::where('parent_id', null)->get();
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.customer.create', compact('states','users'), ['title1' => ' افزودن '.$this->controller_title('singe'), 'title2' => $this->controller_title('single')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name'          => 'required|max:255',
            'state_id'      => 'required|integer',
            'city_id'       => 'required|integer',
            'mobile'        => 'required|unique:users',
            'profile'       => 'max:255',
            'description'   => 'max:2550',
            // 'email' => 'nullable|email|unique:users',
            // 'photo' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
        ],
            [
                'name.required'         => 'لطفا نام و نام خانوادگی خود را وارد کنید',
                'name.max'              => 'نام و نام خانوادگی نباید بیشتر از 255 کاراکتر باشد',
                'state_id.required'     => 'لطفا استان خود را وارد کنید',
                'city_id.required'      => 'لطفا شهر خود را وارد کنید',
                'mobile.required'       => 'لطفا موبایل خود را وارد کنید',
                'mobile.regex'          => 'لطفا موبایل خود را وارد کنید',
                'mobile.digits'         => 'لطفا فرمت موبایل را رعایت کنید',
                'mobile.numeric'        => 'لطفا موبایل خود را بصورت عدد وارد کنید',
                'mobile.unique'         => 'موبایل وارد شده یکبار ثبت نام شده',
                'profile.max'           => 'پروفایل نباید بیشتر از 255 کاراکتر باشد',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراکتر باشد',
            ]);
            if ($request->referrer_id) {
                $referrer = Customer::where('user_id',auth()->user()->id)->find(intVal(substr($request->referrer_id,5,1000)));
                if (!$referrer) return redirect()->back()->withInput()->with('err_message', 'معرف یافت نشد ،مجددا امتحان کنید');
            }
        try {
            $item = new Customer();
            $item->name         = $request->name;
            $item->state_id     = $request->state_id;
            $item->city_id      = $request->city_id;
            if ($request->referrer_id) $item->referrer_id = $referrer->id;
            $item->mobile       = $request->mobile;
            $item->profile      = $request->profile;
            $item->description  = $request->description;
            $item->user_id      = auth()->user()->id;
            $item->time         = my_jdate(Carbon::now(),'d F Y');
            $item->time_en      = Carbon::now();
            $item->save();
            return redirect()->route('admin.user-customer.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $item   = Customer::where('user_id',auth()->user()->id)->findOrFail($id);
        $states = ProvinceCity::where('parent_id', null)->get();
        $citys  = ProvinceCity::where('parent_id', $item->state_id)->get();
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.customer.edit', compact('item', 'states', 'citys','users'), ['title1' => $this->controller_title('sum'), 'title2' => 'ویرایش مشتری']);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name'          => 'required|max:255',
            'state_id'      => 'required|integer',
            'city_id'       => 'required|integer',
            'mobile'        => 'required|unique:users',
            'profile'       => 'max:255',
            'description'   => 'max:2550',
        ],
            [
                'name.required'         => 'لطفا نام و نام خانوادگی خود را وارد کنید',
                'name.max'              => 'نام و نام خانوادگی نباید بیشتر از 255 کاراکتر باشد',
                'state_id.required'     => 'لطفا استان خود را وارد کنید',
                'city_id.required'      => 'لطفا شهر خود را وارد کنید',
                'mobile.required'       => 'لطفا موبایل خود را وارد کنید',
                'mobile.regex'          => 'لطفا موبایل خود را وارد کنید',
                'mobile.digits'         => 'لطفا فرمت موبایل را رعایت کنید',
                'mobile.numeric'        => 'لطفا موبایل خود را بصورت عدد وارد کنید',
                'mobile.unique'         => 'موبایل وارد شده یکبار ثبت نام شده',
                'profile.max'           => 'پروفایل نباید بیشتر از 255 کاراکتر باشد',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراکتر باشد',
            ]);
        $item   = Customer::where('user_id',auth()->user()->id)->findOrFail($id);

        try {
            $item->name         = $request->name;
            $item->state_id     = $request->state_id;
            $item->city_id      = $request->city_id;
            $item->mobile       = $request->mobile;
            $item->profile      = $request->profile;
            $item->description  = $request->description;
            $item->save();
            return redirect()->route('admin.user-customer.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Customer::findOrFail($id);
        try {
            dd('اگر فاکتور نداشت');
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function active($id, $type) {
        $item = Customer::findOrFail($id);
        try {
            $item->status = $type;
            $item->update();
            if ($type == 'blocked') return redirect()->back()->with('flash_message', 'آیتم با موفقیت مسدود شد.');
            if ($type == 'active') return redirect()->back()->with('flash_message', 'آیتم با موفقیت فعال شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در تغییر وضعیت آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
