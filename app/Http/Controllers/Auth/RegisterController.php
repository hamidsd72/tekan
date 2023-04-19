<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Model\ProvinceCity;
use App\Providers\RouteServiceProvider;
use App\User;
use App\Model\Potential;
use App\Model\Sms;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class RegisterController extends Controller {

    use RegistersUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct() { $this->middleware('guest'); }

    // protected function validator(array $data) {
    //     return Validator::make($data, [
    //         'first_name' => ['required', 'string', 'max:255'],
    //         'last_name' => ['string', 'max:255'],
    //         'email' => ['nullable','string', 'email', 'max:255', 'unique:users'],
    //         'mobile' => ['required', 'numeric','unique:users',],
    //         'national_code' => ['required', 'numeric','unique:users',],
    //         'password' => ['required', 'string', 'min:6'],
    //         'password_confirmation' => ['required','same:password'],
    //     ]);
    // }

    public function getCreate() {
        $states     = ProvinceCity::where('parent_id', null)->get();
        $citys      = ProvinceCity::all();
        $referred   = null;
        // کد معرف چرت نباشه
        if (request()->referred && User::where('reagent_code',request()->referred)->count()) $referred = request()->referred;
        return view('auth.register',compact('states','citys','referred'));
    }

    // protected function create(array $data)
    protected function create(Request $request) {
        $request->validate(
            [
                'first_name'            => ['required', 'string', 'max:255'],
                'last_name'             => ['string', 'max:255'],
                'mobile'                => ['required', 'numeric','unique:users',],
                'whatsapp'              => ['required', 'numeric'],
                'state_id'              => ['required', 'numeric'],
                'city_id'               => ['required', 'numeric'],
                // 'hph'                   => ['required','unique:users,reagent_code'],
                // 'national_code'         => ['required', 'numeric','unique:users',],
                'password'              => ['required', 'string', 'min:6'],
                'password_confirmation' => ['required','same:password'],
            ],[
                'hph.required'  =>'کد hph را وارد کنید.',
                'hph.unique'    =>'کد hph وارد شده تکراری می باشد.',
            ]); 

            $ref = User::where('reagent_code',$request->up_hph)->first('id');
            if (!$ref)
                return redirect()->back()->withInput()->with('err_message', 'معرف یافت نشد!');


            $user=new User();
            $user->first_name   = $request->first_name;
            $user->last_name    = $request->last_name;
            $user->mobile       = $request->mobile;
            $user->whatsapp     = $request->whatsapp;
            $user->state_id     = $request->state_id;
            $user->city_id      = $request->city_id;
            // $user->mobile_verified  = \Carbon\Carbon::now();
            // $user->national_code= $request->national_code;
            $user->password     = $request->password;
            $user->status       = 'active';
            $user->save();
            
            $user->reagent_code = \Str::random(5).$user->id;
            $user->update();
            
            $item = new Potential();
            // شماره واسط
            $item->user_id              = $ref->id;
            $item->name                 = $user->id;
            $item->create_by            = 'self';
            $item->present_ta_peresent  = 'خرید اولیه انجام شده';
            $item->save();

            //اگر کد معرف وجود داشت، شناسه کاربری که کد معرف مربوط به آن می باشد در بخش اطلاعات کاربری که میخواهد ثبت نام کند ذخیره میشود
            // if (isset($request->up_hph)){
            //     $reagentBelongsToUser = User::where('reagent_code', $request->up_hph)->first();
            //     if ($reagentBelongsToUser) {
            //         $user->reagent_id = $reagentBelongsToUser->id;
            //         $user->assignRole('کاربر');
            //     }
            // }
        $user->assignRole('general');
            auth()->loginUsingId($user->id);
        return redirect()->route('admin.index');
    }
}
