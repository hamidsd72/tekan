<?php

namespace App\Http\Controllers\Admin\OrganizationMember;

use App\Model\Potential;
use Illuminate\Http\Request;
use App\Model\Following;
use App\Http\Controllers\Controller;

class OrganizationMemberController extends Controller {

    public function controller_title($type) {
        if ($type == 'sum') return 'اعضا سازمان';
        elseif ('single') return 'اعضا سازمان';
    }

    public function __construct() {
        $this->middleware('permission:organization_member_list', ['only' => ['index','show']]);
    }

    public function index() {
        $items  = auth()->user()->my_potentials->whereNotIn('present_ta_peresent',[null,'خرید اولیه انجام نشده']);

        $append_items = [];
        
        foreach ($items as $item) {
            // کاربر ایتم
            $item_user  = $item->user;
            if ($item_user && $item_user->status=='deactive') {
                // نگه داشتن آی دی پتانسیل غیرفعال جهت مرحله یافتن فرزند
                $user_id = $item->name;
                // ----------------این کد مربوط به اینجا نیست----------------
                // while (true) {
                //     // تا یافتن معرف فعال جلو میرود
                //     if ($item->user->status=='active') break;
                //     $headItem = Potential::where('name', $item->user_id)->first(['name','user_id']);
                //     if ( $headItem===null ) {
                //         $item->name = $item->admin->id;
                //         $item->user_id  = $headItem;
                //         break;
                //     }
                //     $item->name     = $headItem->name;
                //     $item->user_id  = $headItem->user_id;
                // }
                // ^----------------این کد مربوط به اینجا نیست^----------------^

                $append = Potential::where('user_id', $user_id)->whereNotIn('present_ta_peresent',[null,'خرید اولیه انجام نشده'])->get(); 
                // افزودن فرزندان به والد جدید
                foreach ($append as $child) {
                    array_push($append_items , $child);
                }
            }
        }

        // ادقام با فرزندان ایتم غیرفغال و حذف غیرفعال ها از لیست
        foreach ($items->sortByDesc('id') as $child) {
            if ($child->user) {
                array_unshift($append_items , $child);
            }
        }

        $items = $append_items;
        return view('admin.organization-member.index' , compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $items  = Potential::where('user_id',$id)->whereNotIn('present_ta_peresent',['خرید اولیه انجام نشده'])->get();
        $append_items = [];
        
        foreach ($items as $item) {
            // کاربر ایتم
            $item_user  = $item->user;
            if ($item_user && $item_user->status=='deactive') {
                // نگه داشتن آی دی پتانسیل غیرفعال جهت مرحله یافتن فرزند
                $user_id = $item->name;
                // ----------------این کد مربوط به اینجا نیست----------------
                // while (true) {
                //     // تا یافتن معرف فعال جلو میرود
                //     if ($item->user->status=='active') break;
                //     $headItem = Potential::where('name', $item->user_id)->first(['name','user_id']);
                //     if ( $headItem===null ) {
                //         $item->name = $item->admin->id;
                //         $item->user_id  = $headItem;
                //         break;
                //     }
                //     $item->name     = $headItem->name;
                //     $item->user_id  = $headItem->user_id;
                // }
                // ^----------------این کد مربوط به اینجا نیست^----------------^

                $append = Potential::where('user_id', $user_id)->whereNotIn('present_ta_peresent',[null,'خرید اولیه انجام نشده'])->get(); 
                // افزودن فرزندان به والد جدید
                foreach ($append as $child) {
                    array_push($append_items , $child);
                }
            }
        }

        // ادقام با فرزندان ایتم غیرفغال و حذف غیرفعال ها از لیست
        foreach ($items->sortByDesc('id') as $child) {
            if ($child->user) {
                array_unshift($append_items , $child);
            }
        }

        $items      = $append_items;
        $following  = Following::where('user_id', auth()->id())->get('potential_id');
        return view('admin.organization-member.index' , compact('items','following'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

}

