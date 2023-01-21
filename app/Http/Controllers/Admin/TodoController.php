<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Model\Filep;
use App\Model\ProvinceCity;
use App\Model\Slider;
use App\Model\ServiceCat;
use App\Model\Photo;
use App\Model\Call;
use App\Model\Custom;
use App\Model\Factor;
use App\Model\About;
use App\Model\Setting;
use App\Model\ServiceBuy;
use App\Model\ServiceFactor;
use App\Model\ServicePlus;
use App\Model\ServicePlusBuy;
use App\Model\ServicePackagePrice;
use Illuminate\Support\Facades\Auth;
use App\Model\ConsultationCall;
use App\Model\Testr;
use Illuminate\Support\Facades\Cookie;
use Hekmatinasser\Verta\Verta;
use function foo\func;

class TodoController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return 'لیست برنامه های روزانه';
        } elseif ('single') {
            return 'برنامه روزانه ';
        }
    }

    public function controller_paginate()
    {
        $settings = Setting::select('paginate')->latest()->firstOrFail();
        return $settings->paginate;
    }

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $items = collect();

        if ($request->filled('date'))
            $date =Verta::parse( convertFaDateToEn($request->date));
        else
            $date = Verta::now();

        $next_date = Verta::parse($date)->addDays(1)->format('Y-m-d');
        $previous_date = Verta::parse($date)->subDays(1)->format('Y-m-d');

        $date = $date->format('Y-m-d');

        //todo check later
        $calls = Call::getCalls($date);

        $factors = Factor::getFactors($date);
        if ($calls) {
            foreach ($calls as $item) {
                $item['model'] = 'call';
                $items->push($item);
            }
        }


        if ($factors) {
            foreach ($factors as $item) {
                $item['model'] = 'factor';
                $items->push($item);
            }
        }


        if ($request->filled('user_id') && $request->user_id !== '0') {
            $items = $items->where('creator_id',$request->user_id);
        }


//        $items = $items->orderBy('type', 'asc')->paginate($this->controller_paginate());

        $users = User::role(['کاربر','نماینده مستقل'])->get();

        return view('admin.todo.index', compact('calls','items','factors','date', 'next_date', 'previous_date','users'), [
            'title1' => $this->controller_title('single'),
            'title2' => $this->controller_title('sum'),
            'current' => $request->current
        ]);

    }

    public function visit($id)
    {
        $call = CustomerCall::findOrFail($id);
        if ($call->type == 'pending')
            $call->type = 'done';
        else
            $call->type = 'pending';

        $call->save();

        return response()->json([
            'status' => 'success',
            'call_state' => $call->type
        ]);

//        if (!is_null(request('callback_url'))) {
//            return redirect(request('callback_url'))->with('flash_message', 'وضعیت تودو با موفقیت ثبت شد.');
//        }
//
//        return redirect()->back()->with('flash_message', 'وضعیت تودو با موفقیت ثبت شد.');
    }


}
