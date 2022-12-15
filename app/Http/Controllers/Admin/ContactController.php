<?php

namespace App\Http\Controllers\Admin;

use App\Contact;
use App\Employment;
use App\Models\EmploymentPage;
use App\Setting;
use App\Models\Lang;
use App\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    // Construct Function
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Index Function
    public function index()
    {
        $items = Contact::orderByDesc('id')->get();
        return view('admin.contact.index', compact('items'), ['title' => 'فرم ها']);
    }

    public function indexbc()
    {
        $items = Bccontact::get();
        return view('admin.broadcast.contact.index', compact('items'), ['title' => 'فرم ها']);
    }

    // Employment
    // Index Function
    public function list()
    {
        $items = Employment::orderBy('id','DESC')->get();
        return view('admin.contact.employment.index', compact('items'), ['title' => 'فرم استخدامی']);
    }

    public function page_list()
    {
        $items = EmploymentPage::orderBy('id','ASC')->get();
        return view('admin.contact.employment.list', compact('items'), ['title' => 'صفحات فرم استخدامی']);
    }

    public function store()
    {
        $new= new EmploymentPage();
        $new->title='جدید';
        $new->title_link='جدید';
        $new->status_link='pending';
        $new->status_pic='pending';
        $new->save();
        return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت افزوده شد.']);
    }
    public function edit($id=null)
    {
        if($id>0)
        {
            $item=EmploymentPage::find($id);
            return view('admin.contact.employment.edit1', compact('item'), ['title' => 'ویرایش صفحه داخلی ('.$item->title.') فرم استخدامی']);
        }
        $item = Setting::find(1);
        return view('admin.contact.employment.edit', compact('item','id'), ['title' => 'ویرایش صفحه فرم استخدامی']);
    }
    public function update(Request $request)
    {
        $item = Setting::find(1);
        $validator = Validator::make($request->all(), [
            'employ_text' => 'required',
//            'employ_text_en' => 'required',
            'employ_unit' => 'required',
            'employ_know' => 'required',
            'employ_type' => 'required',
//            'employ_unit_en' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item->employ_text = $request->employ_text;
            $item->employ_unit = $request->employ_unit;
            $item->employ_know = $request->employ_know;
            $item->employ_type = $request->employ_type;
            if ($request->hasFile('employ_pic')) {
                if (is_file($item->employ_pic)) {
                    $old_path = $item->employ_pic;
                    File::delete($old_path);
                }
                $item->employ_pic = file_store($request->employ_pic, 'includes/asset/uploads/employment/photos/', 'pic-');;
            }
            if (isset($request->employ_pic_active)) {
                $item->employ_pic_active = 'active';
            }
            else
            {
                $item->employ_pic_active = 'pending';
            }

            $item->update();


//            if($item->langs)
//            {
//                foreach ($item->langs as $lang){
//                    if($lang->fild_name=='employ_text' ||
//                       $lang->fild_name=='employ_unit'
//                    )
//                    {
//                        $lang->delete();
//                    }
//                }
//            }
//            store_lang($request,'setting',$item->id,['employ_text','employ_unit']);



            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' صفحه فرم استخدام را ویرایش کرد';
            $item->activity()->save($activity);

            return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت ویرایش شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
    public function update1(Request $request,$id)
    {
        $item = EmploymentPage::find($id);
        $validator = Validator::make($request->all(), [
            'title_link' => 'required',
//            'title_link_en' => 'required',
            'title' => 'required',
//            'title_en' => 'required',
            'text' => 'required',
//            'text_en' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'لطفا فیلد ها را بررسی نمایید، بعضی از فیلد ها نمی توانند خالی باشند.'
            ])->withErrors($validator)->withInput();
        }
        try {
            $item->title = $request->title;
            $item->title_link = $request->title_link;
            $item->text = $request->text;
            if ($request->hasFile('pic')) {
                if (is_file($item->pic)) {
                    $old_path = $item->pic;
                    File::delete($old_path);
                }
                $item->pic = file_store($request->pic, 'includes/asset/uploads/employment/photos/', 'picPage-');;
            }
            $item->update();


//            if($item->langs)
//            {
//                foreach ($item->langs as $lang){
//                        $lang->delete();
//                }
//            }
//            store_lang($request,'employ_page',$item->id,['title_link','title','text']);

            $activity = new Activity();
            $activity->user_id = Auth::user()->id;
            $activity->type = 'update';
            $activity->text = ' صفحه داخلی '.$item->title.' فرم استخدام را ویرایش کرد';
            $item->activity()->save($activity);

            return redirect()->route('admin.employment.page.list')->with(['status' => 'success', "message" => ' با موفقیت ویرایش شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }
    public function status($type,$id)
    {
        $item=EmploymentPage::find($id);
        if($item->$type=='active')
        {
            $item->$type='pending';
        }
        else
        {
            $item->$type='active';
        }
        $item->update();
        return redirect()->back()->with(['status' => 'success', "message" => ' با موفقیت انجام شد.']);
    }
}
