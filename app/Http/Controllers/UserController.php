<?php

namespace App\Http\Controllers;

use App\Model\Code;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function mobile_verified()
    {
        $user = auth()->user();

        if ($user && $user->mobile_verified) {
            return redirect()->route('admin.index');
        }

        if ($user) {
            if (!$user->latestCodeToday()) {
                $codeSent = $user->sendMobileVerifiedCode();
                if ($codeSent) {
                    session()->put('verification_code', true);
                }
            }

            return view('auth.verification.mobile');

        } else {
            abort('403');
        }
    }

    public function send_code_verified_mobile(Request $request)
    {

        $user = auth()->user();

        if ($user->latest10MinCode()) {
            return redirect()->back()->with('err_message','کد تایید برای شما ارسال شده است، در صورت دریافت نکردن، دقایقی دیگر تلاش کنید!');
        }


        if ($user->sendMobileVerifiedCode()) {
            return redirect()->back()->with('success_message', 'کد تایید با موفقیت برای شما ارسال گردید');
        }

        return redirect()->back()->with('err_message', 'خطایی در ارسال کد رخ داده است، مجددا تلاش کنید!');

    }

    public function post_mobile_verified(Request $request)
    {
        $request->validate([
            'code'=>'required'
        ]);

        $code = Code::where('code', $request->code)->where('created_at','>',Carbon::now()->subMinute(20))->first();
        if ($code) {
            $user = auth()->user();
            $user->mobile_verified = Carbon::now()->toDateTimeString();
            $user->save();
            return redirect()->route('admin.index');
        }
        return redirect()->back()->with('err_message', 'کد وارد شده صحیح نمی باشد، درخواست کد جدید بدهید!');
    }
}
