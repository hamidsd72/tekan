<?php

namespace App\Http\Controllers\Admin;

use App\Activity;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Sarfraznawaz2005\VisitLog\Models\VisitLog;
use SoapClient;
use App\Upload;
use App\Code;
use App\Photo;
use App\Club;
use App\Email;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function excel(Request $request)
    {

        if ($request->excel->getClientOriginalExtension() != 'xlsx') {
            return redirect('admin/excel/index')->with(['status' => 'danger-600', "message" => 'شما فقط مجاز به آپلود فایل اکسل هستید.']);
        }

        $validator = Validator::make($request->all(), [
            'excel' => 'required',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->with([
                'status' => 'danger - 600',
                "message" => 'لطفا فایل excel را بارگذاری کنید'
            ])->withErrors($validator)->withInput();
        }
        $url = '';
        if ($request->hasFile('excel')) {
            $excel = new Photo();
            $excel->path = file_store($request->excel, 'includes/asset/uploads/product/excel/', 'excel-');
            $url = $excel->path;
        }


        Excel::load($url, function ($excel) {

            $excel->sheet('Sheet1', function ($sheet) {

                foreach ($sheet->getRowIterator() AS $row) {
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                    $cells = [];
                    foreach ($cellIterator as $cell) {
                        $cells[] = $cell->getValue();
                    }
                    $rows[] = $cells;
                }


                foreach ($rows as $key => $value) {


                    $code = Code::create([
                        'code' => $value[0],
                        'batch' => $value[1],
                        'dosage' => $value[3],
                        'status' => 0,
                        'title' => $value[2]
                    ]);
                    $code->save();


                }


            });
        })->get();


        return redirect('admin/excel/index')->with(['status' => 'success', "message" => 'اکسل با موفقیت بارگذاری شد.']);


    }

    public function index()
    {
        $activities = Activity::orderBy('id', 'DESC')->take(5)->get();
        return view('admin.index', compact('activities'));
    }

    public function upload()
    {
        $items = Upload::all();
        return view('admin.upload', compact('items'), ['title' => 'آپلود فایل']);
    }

    public function club_insert(Request $request)
    {
        $item = Club::first();


        try {
            $item->lot = $request->lot;
            $item->text = $request->text;
            $item->order = $request->order;
            $item->award = $request->award;

            $item->save();

            return redirect()->back()->with(['status' => 'success', "message" => 'موفقیت ویرایش شد.']);

        } catch (\Exception $e) {

            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);

        }


    }

    public function club_list(Request $request)
    {
        $item = Club::first();

        return view('admin.club.club.home', compact('item'));

    }

    public function upload_store(Request $request)
    {
        try {
            if ($request->hasFile('file')) {
                $file = new Upload();
                $file->title = $request->title;
                $file->link = file_store($request->file, 'includes/asset/uploads/files/', 'file-');
                $file->save();
                return redirect('admin/upload')->with(['status' => 'success', "message" => 'فایل با موفقیت آپلود شد.']);
            } else {
                return redirect()->back()->with(['status' => 'danger-600', "message" => 'فایل انتخاب نشده است']);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function upload_del($id)
    {
        try {
            $file = Upload::find($id);
            $fn = $file->link;
            unlink($file->link);
            $file->delete();
            return redirect('admin/upload')->with(['status' => 'success', "message" => 'فایل با موفقیت حذف شد.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'danger-600', "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید.']);
        }
    }

    public function visit()
    {
        //visit today
        $today = VisitLog::whereDate('created_at', date('Y-m-d'))->count();

        //visit yesterday
        $yesterday = date('Y-m-d', strtotime("-1 days"));
        $yester = VisitLog::whereDate('created_at', $yesterday)->count();

        //visit week
        $week = VisitLog::whereBetween('created_at', [\Carbon\Carbon::today()->startOfWeek(), \Carbon\Carbon::today()->endOfWeek()])->count();

        //visit week
        $month = VisitLog::whereBetween('created_at', [\Carbon\Carbon::today()->startOfMonth(), \Carbon\Carbon::today()->endOfMonth()])->count();

        //visit all
        $all = VisitLog::select('created_at')->count();

        return view('admin.visitor.show', compact('yester', 'all', 'today', 'week', 'month'), ['title' => 'بازدید ها', 'description' => 'لیست تمام بازدید ها']);
    }

    public function activities()
    {
        $items = Activity::orderBy('id', 'DESC')->get();
        return view('admin.activity.index', compact('items'), ['title' => 'لیست تمام فعالیت ها']);
    }

    public function test()
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $sms_client = new SoapClient('http://api.payamak-panel.com/post/send.asmx?wsdl', array('encoding' => 'UTF-8'));
        $parameters['username'] = "9128181892";
        $parameters['password'] = "Mehdi%62";
        $parameters['to'] = "9193727097";
        $parameters['from'] = "10004785";
        $parameters['text'] = "";
        $parameters['isflash'] = false;

        echo $sms_client->SendSimpleSMS2($parameters)->SendSimpleSMS2Result;
    }

    public function pass_store(Request $request)
    {
        try {
            $user = User::find($request->id);

            if (!Hash::check($request->old_pass, $user->password)) {
                return redirect()->back()->with([
                    'status' => 'danger-600',
                    "message" => 'رمز عبور وارد شده نادرست می باشد'
                ]);
            }

            if ($request->pass != $request->conf_pass) {
                return redirect()->back()->with([
                    'status' => 'danger-600',
                    "message" => 'پسورد ها باهم مطابقت ندارند'
                ]);
            }

            $user->password = bcrypt($request->pass);
            $user->save();
            return redirect()->back()->with(['status' => 'success', "message" => 'با موفقیت انجام شد']);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'status' => 'danger-600',
                "message" => 'یک خطا رخ داده است، لطفا بررسی بفرمایید'
            ]);
        }
    }

    public function serachCode(Request $request)
    {

        $code = Code::where('code', 'LIKE', '%' . $request->text . '%')->paginate(20);

        return view('admin.excel.index', compact('code'));
    }

    public function email()
    {
        $items = Email::paginate(12);
        return view('admin.email.index', compact('items'));
    }

}
