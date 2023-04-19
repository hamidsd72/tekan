<?php

namespace App\Http\Controllers\Admin\Meet;

use App\Model\Meet;
use App\Model\Notification;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class WorkshopController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'Coach یا جلسات گارگاهی';
        elseif ('single') return 'Coach یا جلسات گارگاهی';
    }

    public function __construct() {
        $this->middleware('permission:workshop_list', ['only' => ['index']]);
        $this->middleware('permission:workshop_report', ['only' => ['show']]);
        $this->middleware('permission:workshop_create', ['only' => ['create','store']]);
        $this->middleware('permission:workshop_delete', ['only' => ['destroy']]);
    }

    public function index() {
        // جلسات امروز
        $meets  = Meet::where('user_id', auth()->id())->where('ready_date', '<', Carbon::now())->pluck('id');
        if ($meets) {
            $meets  = auth()->user()->unreadNotifications->where('type','App\Notifications\Meet')->whereIn('notifiable_id',$meets);
            foreach ($meets as $notification) $notification->markAsRead();
        }

        $items = Meet::where('user_id',auth()->id())->get();
        foreach ($items as $item) {
            if (Carbon::today()->diffInDays(Carbon::parse($item->ready_date), false) < 1) $item->activate = true;
        }
        
        return view('admin.meet.workshop.index', compact('items',), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        foreach (auth()->user()->unreadNotifications->where('type','App\Notifications\MeetCreateReport') as $notification) $notification->markAsRead();
        // $item = Meet::findOrFail($id);
        $slug   = num2en($id);
        $item   = Meet::where('slug', $slug)->first();
        return view('admin.meet.workshop.show', compact('item'), ['title1' => ' گزارشات '.$this->controller_title('single'), 'title2' => ' گزارشات '.$this->controller_title('sum')]);
    }

    public function create() {
        return view('admin.meet.workshop.create', ['title1' => ' افزودن '.$this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'title'     => 'required|max:255',
            'reply'     => 'nullable|integer',
            'addDays'   => 'nullable|integer',
            'date'      => 'required',
        ],
            [
                'title.required'        => 'لطفا موضوع جلسه را وارد کنید',
                'title.max'             => 'موضوع جلسه نباید بیشتر از 255 کاراکتر باشد',
                'slug.unique'           => ' نامک وارد شده یکبار ثبت شده',
                'reply.integer'         => 'مقدار تکرار جلسات معتبر نیست', 
                'addDay.integer'        => 'مقدار روزهای تکرار جلسات معتبر نیست', 
                'date.required'         => 'لطفا تاریخ شروع را وارد کنید',
            ]);

        $item = new Meet;
        try {
            $slug = str_replace('/','',\Hash::make($request->title.Carbon::now()));
            $slug = str_replace('?','',$slug);
            $slug = str_replace('|','',$slug);
            $slug = str_replace('_','',$slug);
            $slug = str_replace('&','',$slug);
            $slug = str_replace('%','',$slug);
            $slug = str_replace('=','',$slug);
            // برای جدا کردن اسلاگ از ای دی جلسه
            $slug = str_replace(',','',$slug);
            
            $item->user_id      = auth()->user()->id;
            $item->title        = $request->title;
            $item->slug         = auth()->user()->id.$slug.auth()->user()->id;
            if($request->reply) {
                $item->reply    = $request->reply;
                $item->addDays  = $request->addDays;
                $item->total    = $request->reply;
            }
            $item->date         = Carbon::parse(j2g(num2en($request->date)));
            $item->ready_date   = $item->date;
            $item->save();

            Notification::setItem(
                "App\Notifications\Meet",
                "App\User",
                $item->id,
                ('{"date": "'.$slug.'"}')
            );
            return redirect()->route('admin.workshop.index')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'date'      => 'required',
        ],
            [
                'date.required'          => 'لطفا تاریخ شروع را وارد کنید',
            ]);

        $item = Meet::where('user_id',auth()->id())->findOrFail($id);
        try {
            $item->ready_date = Carbon::parse(j2g(num2en($request->date)));
            $item->update();
            Notification::setItem(
                "App\Notifications\Meet",
                "App\User",
                $item->id,
                ('{"date": "'.$item->slug.'"}')
            );
            // create notification
            return redirect()->route('admin.workshop.index')->with('flash_message', ' ویرایش آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Meet::where('user_id',auth()->id())->findOrFail($id);
        try {
            $item->delete();
            return redirect()->route('admin.workshop.index')->with('flash_message', ' حذف آیتم با انجام شد.');
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
