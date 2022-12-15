<?php

namespace App\Http\Controllers\User;


use App\Http\Controllers\Controller;
use App\User;
use App\Menu;
use App\Certificate;
use App\Slider;
use App\Models\ContactInfo;
use App\Models\ProductCat;
use App\About;
use App\Models\AboutFeature;
use App\Models\Lang;
use App\Product;
use App\Setting; 
use App\Models\Blog;
use App\Models\Partner;
use App\Models\Project;
use App\ProductCategory;
use App\Sms;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\jdf;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Nestable\Tests\Model\Category;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Session;


class HomeController extends Controller
{
    public function index()
    {
        function fa_number($number) {
            $arr = array();
            for ($i=0; $i < strlen($number); $i++) { 
                switch ($number) {
                    case $number[$i] == "0":
                        array_push($arr, "۰" );
                    break;
                    case $number[$i] == "1":
                        array_push($arr, "۱" );
                    break;
                    case $number[$i] == "2":
                        array_push($arr, "۲" );
                    break;
                    case $number[$i] == "3":
                        array_push($arr, "۳" );
                    break;
                    case $number[$i] == "4":
                        array_push($arr, "۴" );
                    break;
                    case $number[$i] == "5":
                        array_push($arr, "۵" );
                    break;
                    case $number[$i] == "6":
                        array_push($arr, "۶" );
                    break;
                    case $number[$i] == "7":
                        array_push($arr, "۷" );
                    break;
                    case $number[$i] == "8":
                        array_push($arr, "۸" );
                    break;
                    case $number[$i] == "9":
                        array_push($arr, "۹" );
                    break;
                
                    default:
                        array_push($arr, $number[$i] );
                } 
            }
            return implode("",$arr);
        }

        $sliders=Slider::where('show',1)->orderBy('sort_id','asc')->get();
//        $product_cat_homes=ProductCategory::where('status_home','active')->orderBy('id','desc')->take(4)->get();
        $menu_slider_downs=Menu::where('slider_down','active')->orderBy('sort_id')->get();
        $about=About::find(1);
        $products=Product::where('site','active')->where('status_home','active')->orderBy('id','desc')->take(8)->get();
        $setting=Setting::find(1);
        $abouts_f=AboutFeature::where('status','active')->where('status_home','active')->orderBy('id','ASC')->take(3)->get();
        $certs=Certificate::where('status_home','active')->orderBy('id','DESC')->get();
        $partners=Partner::orderBy('id','DESC')->get();
        $blogs = Blog::where('status','active')->where('type','article')->where('status_home','active')->orderBy('id','desc')->take(3)->get();
        $news = Blog::where('status','active')->where('type','news')->where('status_home','active')->orderBy('id','desc')->take(3)->get();
        $services = Blog::where('status','active')->where('type','service')->where('status_home','active')->orderBy('id','desc')->take(4)->get();

        $ProductCategory = ProductCategory::where('name','انتقال قدرت')->first();
        $ProductCategory2 = ProductCategory::where('name','جابجایی مواد')->first();

        $contact_info = ContactInfo::find(1);

        foreach ($abouts_f as $abouts) {
            if(!empty($abouts->title)) {
                $abouts->title = fa_number($abouts->title);
            }
            if(!empty($abouts->text)) {
                $abouts->text = fa_number($abouts->text);
            }
        }


        return view('user.index',compact('services','news','sliders','menu_slider_downs','about','products','setting','abouts_f','certs','partners','blogs','contact_info','ProductCategory2','ProductCategory'));
    }

    //search
    public function search(Request $request)
    {
        if(app()->getLocale()=='fa')
        {
            $products=Product::where('site','active')->where('name','LIKE','%'.$request->search.'%')->orwhere('text','LIKE','%'.$request->search.'%')->orderBy('id','desc')->get();
            $blogs=Blog::where('status','active')->where('title','LIKE','%'.$request->search.'%')->orwhere('text','LIKE','%'.$request->search.'%')->orderBy('id','desc')->get();
        }
        else
        {
            $lang_products=Lang::where('type','product')->where('text_en','LIKE','%'.$request->search.'%')->select('item_id')->get()->toArray();
            $products=Product::where('site','active')->wherein('id',$lang_products)->get();
            $lang_blogs=Lang::where('type','blog')->where('text_en','LIKE','%'.$request->search.'%')->select('item_id')->get()->toArray();
            $blogs=Blog::where('status','active')->wherein('id',$lang_blogs)->get();
        }
        return view('user.search.show',compact('products','blogs'), ['title' => __('text.page_name.search')]);
    }

    //page
    public function page_show($slug)
    {
        if(app()->getLocale()=='fa')
        {
            $item=Menu::where('slug',$slug)->first();
        }
        else
        {
            $item=Menu::where('slug_en',$slug)->first();
        }
        if(!$item)
        {
            return redirect()->route('user.index');
        }
        return view('user.page.show',compact('item'), ['title' => set_lang($item,'name',app()->getLocale())]);
    }
    // reset pass
    public function reset(Request $request)
    {
        $user = User::where('mobile', $request->mobile)->first();

        return redirect()->back()->with(['status' => 'danger-600', "message" => 'سامانه پیامکی فعال نمی باشد']);

//        if (is_null($user)) {
//            return redirect()->back()->with(['status' => 'danger-600', "message" => 'شماره همراه اشتباه می باشد']);
//        }
//
//        if ($user->branch_id != 0) {
//            return redirect()->back()->with(['status' => 'danger-600', "message" => '&#1588;&#1593;&#1576;&#1607; &#1583;&#1575;&#1585; &#1605;&#1581;&#1578;&#1585;&#1605; &#1588;&#1605;&#1575; &#1605;&#1580;&#1575;&#1586; &#1576;&#1607; &#1578;&#1594;&#1740;&#1740;&#1585; &#1585;&#1605;&#1586; &#1593;&#1576;&#1608;&#1585; &#1582;&#1608;&#1583; &#1606;&#1605;&#1740; &#1576;&#1575;&#1588;&#1740;&#1583; &#1604;&#1591;&#1601;&#1575; &#1576;&#1575; &#1662;&#1588;&#1578;&#1740;&#1576;&#1575;&#1606;&#1740; &#1578;&#1605;&#1575;&#1587; &#1581;&#1575;&#1589;&#1604; &#1601;&#1585;&#1605;&#1575;&#1740;&#1740;&#1583;']);
//
//        }


//        try {
//            $pass = mt_rand(1111111, 9999999);
//            $user->password = Hash::make($pass);
//            $user->update();
//
//
//            Sms::sendPass($pass, $user->mobile);
//
//            return redirect()->back()->with(['status' => 'success', "message" => 'رمز عبور با موفقیت ارسال شد']);
//        } catch (Exception $e) {
//            return redirect()->back()->with(['status' => 'danger-600', "message" => '&#1740;&#1705; &#1582;&#1591;&#1575; &#1585;&#1582; &#1583;&#1575;&#1583; &#1604;&#1591;&#1601;&#1575; &#1576;&#1585;&#1585;&#1587;&#1740; &#1705;&#1606;&#1740;&#1583;']);
//
//        }

    }

    public function catalogs()
    {
        return view('user.page.catalogs')->with(['title'=>'کاتالوگ ها']);
    }

    public function software()
    {
        return view('user.page.software')->with(['title'=>'نرم افزار های محاسباتی']);
    }

    public function projects()
    {
        return view('user.page.introduction')->with(['title'=>'پروژه ها – انتقال قدرت']);
    }

    public function project_index()
    {
        $projects = Project::all();

        return view('user.project.index', compact('projects'))->with(['title'=>'پروژه ها – جایجایی مواد و تولید ناب']);
    }

    public function project_show($slug)
    {
        $item = Project::where('slug',$slug)->first();
        return view('user.project.show', compact('item'))->with(['title'=>$item->name]);
    }

}
