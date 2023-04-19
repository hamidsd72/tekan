<?php

namespace App\Http\Controllers\Admin\Connection;

use App\Model\Connection;
use App\Model\QuadPerformance;
use App\Model\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ListController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'لیست ارتباطات شخصی';
        elseif ('single') return 'ارتباطات شخصی';
    }

    public function __construct() {
        $this->middleware('permission:connection_list', ['only' => ['index',]]);
        $this->middleware('permission:connection_create', ['only' => ['create','store']]);
        $this->middleware('permission:connection_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:connection_delete', ['only' => ['destroy']]);
    }

    public function index() {
        $items = Connection::where('user_id', auth()->user()->id )->orderByDesc('id')->get();
        return view('admin.connection.list.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function create() {
        return view('admin.connection.list.create' , ['title1' => $this->controller_title('single').' افزودن ', 'title2' => $this->controller_title('sum').' افزودن ']);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'name'          => 'required|max:250',
            'store_type'    => 'required|max:250',
            'action_type'   => 'max:250',
            'candidate'     => 'max:250',
            'status'        => 'max:250',
            'time'          => 'max:250',
            'description'   => 'max:2550',
        ],
            [
                'name.required'         => 'لطفا نام و نام خانوادگی را وارد کنید',
                'name.max'              => 'نام و نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
                'store_type.required'   => 'لطفا نوع بازار را وارد کنید',
                'store_type.max'        => 'نوع بازار نباید بیشتر از 240 کاراکتر باشد',
                'action_type.required'  => 'لطفا نوع اقدام را وارد کنید',
                'action_type.max'       => 'نوع اقدام نباید بیشتر از 240 کاراکتر باشد',
                'candidate.max'         => 'کاندید نباید بیشتر از 240 کاراکتر باشد',
                'status.max'            => 'وضعیت نباید بیشتر از 240 کاراکتر باشد',
                'time.max'              => 'زمان نباید بیشتر از 240 کاراکتر باشد',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراکتر باشد',
            ]);

        try {
            $item = new Connection;
            $item->name         = $request->name;
            $item->store_type   = $request->store_type;
            $item->action_type  = $request->action_type;
            $item->candidate    = $request->candidate;
            $item->status       = $request->status;
            $item->description  = $request->description;
            $item->time         = $request->time;
            $item->time_en      = Carbon::parse(j2g(toEnNumber($request->time)));
            $item->user_id      = auth()->user()->id;
            
            $item->save();
            
            if ($item->action_type!=null && $item->time!=null) {
                $quad = new QuadPerformance;
                $quad->user_id      = auth()->user()->id;
                $quad->name         = $item->name;

                $quad->label        = 'گفتگو با محوریت شبکه سازی';
                $quad->label_en     = 'networking';
                if ($item->action_type=="اقدام به فروش") {
                    $quad->label    =  'گفتگو با محوریت فروش یا مشتری مداری';
                    $quad->label_en = 'communication';        
                } elseif ($item->action_type=="توسعه ارتباطات") {
                    $quad->label        = 'گفتگو با محوریت توسعه ارتباطات';
                    $quad->label_en     = 'conversation';
                }

                $quad->item_id      = $item->id;
                $quad->date         = $item->time;
                $quad->date_en      = Carbon::parse(j2g(toEnNumber($item->time)));
                $quad->save();

                $text = $quad->label.' : '.$quad->name;
                
                Notification::setItem(
                    "App\Notifications\Invoice",
                    "App\User",
                    $quad->id,
                    ('{"date": "'.$text.'"}')
                );
            }
                    
            return redirect()->route('admin.connection-list.index')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $item = Connection::where('user_id', auth()->user()->id )->findOrFail($id);
        return view('admin.connection.list.edit', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update(Request $request , $id) {
        $item = Connection::findOrFail($id);
        if ($item->user_id!=auth()->user()->id) return redirect()->back()->withInput()->with('err_message', 'شما کاربر این آیتم نیستین');

        $this->validate($request, [
            'name'          => 'required|max:250',
            'store_type'    => 'required|max:250',
            'action_type'   => 'max:250',
            'candidate'     => 'max:250',
            'status'        => 'max:250',
            'time'          => 'max:250',
            'description'   => 'max:2550',
        ],
            [
                'name.required'         => 'لطفا نام و نام خانوادگی را وارد کنید',
                'name.max'              => 'نام و نام خانوادگی نباید بیشتر از 240 کاراکتر باشد',
                'store_type.required'   => 'لطفا نوع بازار را وارد کنید',
                'store_type.max'        => 'نوع بازار نباید بیشتر از 240 کاراکتر باشد',
                'action_type.required'  => 'لطفا نوع اقدام را وارد کنید',
                'action_type.max'       => 'نوع اقدام نباید بیشتر از 240 کاراکتر باشد',
                'candidate.max'         => 'کاندید نباید بیشتر از 240 کاراکتر باشد',
                'status.max'            => 'وضعیت نباید بیشتر از 240 کاراکتر باشد',
                'time.max'              => 'زمان نباید بیشتر از 240 کاراکتر باشد',
                'description.max'       => 'توضیحات نباید بیشتر از 2550 کاراکتر باشد',
            ]);

        try {
            $old_quad   = QuadPerformance::where('user_id', auth()->user()->id)->where('item_id', $item->id)->first();
            if ($old_quad) {
                $old_nofify = Notification::where('user_id', auth()->user()->id)->where('type', 'App\Notifications\Invoice')->where('read_at', null)->where('notifiable_id', $old_quad->id)->first();
                if ($old_nofify) $old_nofify->delete();
                $old_quad->delete();
            }

            $item->name         = $request->name;
            $item->store_type   = $request->store_type;
            $item->action_type  = $request->action_type;
            $item->candidate    = $request->candidate;
            $item->status       = $request->status;
            $item->description  = $request->description;
            $item->time         = $request->time;
            $item->time_en      = Carbon::parse(j2g(toEnNumber($request->time)));
            $item->update();

            if ($item->action_type!=null && $item->time!=null) {
                $quad = new QuadPerformance;
                $quad->user_id      = auth()->user()->id;
                $quad->name         = $item->name;

                $quad->label        = 'گفتگو با محوریت شبکه سازی';
                $quad->label_en     = 'networking';
                if ($item->action_type=="اقدام به فروش") {
                    $quad->label    =  'گفتگو با محوریت فروش یا مشتری مداری';
                    $quad->label_en = 'communication';        
                } elseif ($item->action_type=="توسعه ارتباطات") {
                    $quad->label        = 'گفتگو با محوریت توسعه ارتباطات';
                    $quad->label_en     = 'conversation';
                }

                $quad->item_id      = $item->id;
                $quad->date         = $item->time;
                $quad->date_en      = Carbon::parse(j2g(toEnNumber($item->time)));
                $quad->save();

                $text = $quad->label.' : '.$quad->name;
                
                Notification::setItem(
                    "App\Notifications\Invoice",
                    "App\User",
                    $quad->id,
                    ('{"date": "'.$text.'"}')
                );
            }

            return redirect()->route('admin.connection-list.index')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در بروزرسانی آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Connection::findOrFail($id);
        if ($item->user_id!=auth()->user()->id) return redirect()->back()->withInput()->with('err_message', 'شما کاربر این آیتم نیستین');

        try {
            $item->delete();
            return redirect()->back()->with('flash_message', 'آیتم با موفقیت حذف شد.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
