<?php

namespace App\Http\Controllers\Admin\Meet;

use App\Model\Learn;
use App\Model\Video;
use App\Model\Link;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LearnController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'جلسات آموزشی';
        elseif ('single') return 'جلسه آموزشی';
    }

    public function __construct() {
        $this->middleware('permission:workshop_online_list', ['only' => ['index','show']]);
        $this->middleware('permission:workshop_online_create', ['only' => ['create','store']]);
        $this->middleware('permission:workshop_online_edit', ['only' => ['edit','update']]);
        $this->middleware('permission:workshop_online_delete', ['only' => ['destroy','destroy_file']]);
    }

    public function index() {
        if (in_array( auth()->user()->roles->first()->title ,['مدیر','برنامه نویس'] )) {
            $items  = learn::all();
        } else {
            $items  = learn::where('user_id', auth()->id())->orWhere('role', 'LIKE', '%' . auth()->user()->roles->first()->id . '%')->get();
        }
        return view('admin.meet.learn.index', compact('items',), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        if (in_array( auth()->user()->roles->first()->title ,['مدیر','برنامه نویس'] )) {
            $item   = learn::findOrFail($id);
        } else {
            $item   = learn::where('role', 'LIKE', '%' . auth()->user()->roles->first()->id . '%')->findOrFail($id);
        }
        return view('admin.meet.learn.show', compact('item'), ['title1' => ' نمایش '.$this->controller_title('single'), 'title2' => ' نمایش '.$this->controller_title('sum')]);
    }

    public function create() {
        return view('admin.meet.learn.create', ['title1' => ' افزودن '.$this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function store(Request $request) {

        $this->validate($request, [
            'role'  => 'required|max:25',
            'title' => 'required|max:250',
            'link'  => 'nullable|max:2555',
            'video' => 'nullable|mimes:mp4|max:51200',
        ],
            [
                'role.required'     => 'لطفا رول را وارد کنید',
                'role.max'          => 'رول معتبر نیست', 
                'title.required'    => 'عنوان را وارد کنید', 
                'title.max'         => 'عنوان نباید بیشتر از ۲۵۰ کاراکتر باشد',
                'link.max'          => 'لینک نباید بیشتر از ۲۵۵۵ کاراکتر باشد',
                'video.mimes'       => 'لطفا یک ویدئو با پسوند (mp4) انتخاب کنید',
                'video.max'         => 'لطفا حجم ویدئو حداکثر ۵۰ مگابایت باشد',
            ]);

        if (!$request->link && !$request->hasFile('video')) return redirect()->back()->withInput()->with('err_message', 'آموزش یک ویدیو یا لینک لازم دارد');
        $item = new Learn;
        try {
            $item->user_id  = auth()->user()->id;
            $item->title    = $request->title;
            $item->role     = implode(',',$request->role);
            $item->save();

            if ($request->link) {
                $link = new Link();
                $link->url      = $request->link;
                $link->user_id  = auth()->user()->id;
                $link->item_id  = $item->id;
                $link->model    = 'App\Model\Learn';
                $link->save();
            }

            if ($request->hasFile('video')) {
                $video = new Video();
                $video->path = file_store($request->video, 'source/asset/uploads/learn/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/videos/', 'video-');
                $item->video()->save($video);
            }
            return redirect()->route('admin.learn.index')->with('flash_message', ' افزودن آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد افزودن آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function edit($id) {
        $item  = learn::where('user_id', auth()->id())->findOrFail($id);
        return view('admin.meet.learn.edit', compact('item'), ['title1' => ' ویرایش '.$this->controller_title('single'), 'title2' => ' ویرایش '.$this->controller_title('sum')]);
    }

    public function update(Request $request, $id) {
        
        $this->validate($request, [
            'role'  => 'required|max:25',
            'title' => 'required|max:250',
            'link'  => 'nullable|max:2555',
            'video' => 'nullable|mimes:mp4|max:51200',
        ],
            [
                'role.required'     => 'لطفا رول را وارد کنید',
                'role.max'          => 'رول معتبر نیست', 
                'title.required'    => 'عنوان را وارد کنید', 
                'title.max'         => 'عنوان نباید بیشتر از ۲۵۰ کاراکتر باشد',
                'link.max'          => 'لینک نباید بیشتر از ۲۵۵۵ کاراکتر باشد',
                'video.mimes'       => 'لطفا یک ویدئو با پسوند (mp4) انتخاب کنید',
                'video.max'         => 'لطفا حجم ویدئو حداکثر ۵۰ مگابایت باشد',
            ]);

        $item = Learn::where('user_id',auth()->id())->findOrFail($id);
        try {
            $item->title    = $request->title;
            $item->role     = implode(',',$request->role);
            $item->save();

            if ($request->link) {
                $link = new Link();
                $link->url      = $request->link;
                $link->user_id  = auth()->user()->id;
                $link->item_id  = $item->id;
                $link->model    = 'App\Model\Learn';
                $link->save();
            }

            if ($request->hasFile('video')) {
                $video = new Video();
                $video->path = file_store($request->video, 'source/asset/uploads/learn/' . my_jdate(date('Y/m/d'), 'Y-m-d') . '/videos/', 'video-');
                $item->video()->save($video);
            }
            return redirect()->route('admin.learn.index')->with('flash_message', ' ویرایش آیتم با موفقیت ایجاد شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در ایجاد ویرایش آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy($id) {
        $item = Learn::where('user_id',auth()->id())->findOrFail($id);
        if ($item->videos->count() > 0 || $item->links->count()) {
            return redirect()->back()->withInput()->with('err_message', 'این آیتم شامل لینک یا ویدیو میباشد ابتدا آنها را حذف و بعد آیتم را حذف کنید');
        }
        try {
            $item->delete();
            return redirect()->route('admin.learn.index')->with('flash_message', ' حذف آیتم با انجام شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

    public function destroy_file(Request $request, $id) {
        $item = Learn::where('user_id',auth()->id())->findOrFail($request->item_id);
        if ($request->type=='video') {
            $obj    = $item->videos()->where('id', $id)->firstOrFail();
            File::delete($obj->path);
        } elseif ($request->type=='link') {
            $obj    = $item->links()->where('id', $id)->firstOrFail();
        }
        try {
            $obj->delete();
            return redirect()->route('admin.learn.index')->with('flash_message', ' حذف آیتم با انجام شد.');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->withInput()->with('err_message', 'مشکلی در حذف آیتم بوجود آمده،مجددا تلاش کنید');
        }
    }

}
