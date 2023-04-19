<?php

namespace App\Http\Controllers\Admin\CustomerBank;

use Carbon\Carbon;
use App\Model\ProvinceCity;
use App\Model\Connection;
use App\Model\Customer;
use App\Model\Potential;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CustomerController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'بانک مشتریان';
        elseif ('single') return 'مشتری';
    }

    public function __construct() {
        $this->middleware('permission:user_customer_list', ['only' => ['index','show']]);
        $this->middleware('permission:user_customer_create', ['only' => ['create','store']]);
        $this->middleware('permission:user_customer_edit', ['only' => ['edit','update','active']]);
        $this->middleware('permission:user_customer_delete', ['only' => ['destroy']]);
    }

    public function index($id=null ,$type='single') {
        if ($id===null)      $id = auth()->user()->id;
        if ($type=='single') $list = [$id];
        else                 $list = getSubUser([$id])[2];
        // برای یافتن مشتریان کاربران غیرفعال
        $items  = auth()->user()->my_potentials()->get();
        foreach ($items as $item) {
            if ($item->user && $item->user->status=='deactive') {
                array_push($list, $item->name);
            }
        }
        // --------------------------------------------------
        $items = Customer::whereIn('user_id',$list)->get();
        return view('admin.customer_bank.customer.index', compact('items'), ['title1' => $this->controller_title('sum'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $item = Customer::where('user_id',auth()->user()->id)->find($id);
        if ($item) return response()->json(['name' => $item->name, 'class' => 'text-success'], 200);
        else return response()->json(['name' => 'یافت نشد', 'class' => 'text-danger'], 404); 
    }

    public function create() {
        $states = ProvinceCity::where('parent_id', null)->get();
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.customer_bank.customer.create', compact('states','users'), ['title1' => ' افزودن '.$this->controller_title('singe'), 'title2' => $this->controller_title('single')]);
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name'          => 'required|max:255',
            'state_id'      => 'required|integer',
            'city_id'       => 'required|integer',
            'mobile'        => 'required|unique:users',
            'factor_count'  => 'integer',
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
                'factor_count.integer'  => 'تعداد فاکتور نامعتبر میباشده',
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
            $item->factor_count = $request->factor_count;
            $item->description  = $request->description;
            $item->user_id      = auth()->user()->id;
            $item->time         = my_jdate(Carbon::now(),'d F Y');
            $item->time_en      = Carbon::now();
            $item->save();
            return redirect()->route('admin.user-customer.index')->with('flash_message', 'آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $item   = Customer::where('user_id',auth()->user()->id)->findOrFail($id);
        $states = ProvinceCity::where('parent_id', null)->get();
        $citys  = ProvinceCity::where('parent_id', $item->state_id)->get();
        $users  = Connection::where('user_id', auth()->user()->id )->get(['id','name']);
        return view('admin.customer_bank.customer.edit', compact('item', 'states', 'citys','users'), ['title1' => $this->controller_title('sum'), 'title2' => 'ویرایش مشتری']);
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name'          => 'required|max:255',
            'state_id'      => 'required|integer',
            'city_id'       => 'required|integer',
            'mobile'        => 'required|unique:users',
            'factor_count'  => 'integer',
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
                'factor_count.integer'  => 'تعداد فاکتور نامعتبر میباشده',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراکتر باشد',
            ]);
        $item   = Customer::where('user_id',auth()->user()->id)->findOrFail($id);

        try {
            $item->name         = $request->name;
            $item->state_id     = $request->state_id;
            $item->city_id      = $request->city_id;
            $item->mobile       = $request->mobile;
            $item->factor_count = $request->factor_count;
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
            if ($item->customer_factors()->count()) return redirect()->back()->with('err_message', 'آیتم دارای فاکتور میباشد , نمیتوان حذف کرد.');
            // foreach ($item->customer_factors_deleted() as $factor) {
            //     $factor->delete();
            // }
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            // dd($e);
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
