<?php

namespace App\Http\Controllers\Admin;

use App\Model\Filep;
use App\Model\Ticket;
use App\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class TicketController extends Controller
{
    public function controller_title($type)
    {
        if ($type == 'sum') {
            return ' لیست تیکت ها';
        } elseif ('single') {
            return ' تیکت';
        }
    }

    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        if (Auth::user()->hasRole('مدیر'))
        {
            $items = Ticket::where('parent_id',null);
            $items=$items->orderBy('status','ASC');
            $items=$items->orderBy('updated_at','DESC');
            $items=$items->paginate(20);
        }
        else
        {
            $items = Ticket::where('create_user_id',Auth::user()->id)->where('parent_id',null);
            $items=$items->orderBy('status','ASC');
            $items=$items->orderBy('updated_at','DESC');
            $items=$items->paginate(20);
        }
        return view('admin.ticket.index', compact('items'), ['title1' => 'ارتباط با مشتری', 'title2' => 'لیست تیکت']);
    }

    public function show($id,Request $request)
    {
        $item = Ticket::findOrFail($id);
        if($item->parent_id)
        {
            abort(404);
        }
        if (!Auth::user()->hasRole('مدیر'))
        {
            if($item->create_user_id!=Auth::user()->id)
            {
                abort(404);
            }
        }
        return view('admin.ticket.show', compact('item'), ['title1' => 'ارتباط با مشتری', 'title2' => 'جزئیات تیکت']);
    }

    public function show_store($id,Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'attachs.*' => 'nullable|max:10240',
        ],
            [
                'description.required' => 'لطفا متن پاسخ را وارد کنید',
                'attachs.*' => 'لطفا یک تصویر انتخاب کنید',
            ]);
        $comment=Ticket::find($id);
        try {
            if (Auth::user()->hasRole('مدیر'))
            {
                $status='2_done';
            }else{
                $status='1_active';
            }
            $child = Ticket::create([
                'parent_id' => $comment->id,
                'title' => $comment->title,
                'description' => $request->description,
                'priority' => $comment->priority,
                'create_user_id' => Auth::user()->id,
                'status'=> $status,
            ]);
            if ($request->hasFile('attachs')) {
                foreach ($request->attachs as $value)
                {
                    $file = new Filep();
                    $file->path = file_store1($value, 'source/asset/uploads/ticket/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/child/', 'child-');;
                    $child->files()->save($file);
                }
            }
            $comment->update_user_id=Auth::user()->id;
            $comment->updated_at=$child->created_at;
            $comment->status=$status;
            $comment->update();
            return redirect()->back()->with('flash_message', 'پاسخ با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن پاسخ به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function close_ticket($id,Request $request)
    {
        $comment=Ticket::find($id);
        $childs=Ticket::where('parent_id',$id)->get();
        try{
            $comment->status='3_closed';
            $comment->update_user_id=Auth::user()->id;
            $comment->update();
            foreach ($childs as $child)
            {
                $child->status='3_closed';
                $child->update_user_id=Auth::user()->id;
                $child->update();
            }
            return redirect()->back()->with('flash_message', 'تیکت با موفقیت بسته شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای بستن تیکت به مشکل خوردیم، مجدد تلاش کنید');
        }
    }
    public function create()
    {
        return view('admin.ticket.create', ['title1' => 'ارتباط با مشتری', 'title2' => 'افزودن تیکت']);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:240',
            'description' => 'required',
            'attachs.*' => 'nullable|max:10240',
        ],
            [
                'title.required' => 'لطفا عنوان را وارد کنید',
                'title.max' => 'عنوان  نباید بیشتر از 240 کاراکتر باشد',
                'description.required' => 'لطفا توضیحات را وارد کنید',
                'attachs.*' => 'لطفا یک تصویر انتخاب کنید',
            ]);
        try {
            $item = Ticket::create([
                'title' => $request->title,
                'description' => $request->description,
                'priority' => $request->priority,
                'create_user_id' => Auth::user()->id,
            ]);
            if ($request->hasFile('attachs')) {
                foreach ($request->attachs as $key=> $value)
                {
                    $file = new Filep();
                    $file->path = file_store1($value, 'source/asset/uploads/ticket/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/ticket/', 'ticket-');;
                    $item->files()->save($file);
                }
            }
            return redirect()->route('admin.ticket.index')->with('flash_message', 'اطلاعات با موفقیت افزوده شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای افزودن به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

}
