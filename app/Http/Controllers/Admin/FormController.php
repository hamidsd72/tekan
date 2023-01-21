<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use App\Model\UserForm;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FormController extends Controller {
    public function controller_title($type) {
        if ($type == 'sum') {
            return ' فرم ها و قرارداد ها';
        } elseif ('single') {
            return ' فرم و قرارداد';
        }
    }
    
    public function __construct() {
        $this->middleware(['auth']);
    }

    public function controller_paginate() {
        return Setting::select('paginate')->latest()->firstOrFail()->paginate;
    }

    public function index() {
        $items = UserForm::where('status','active')->orderByDesc('id')->paginate( $this->controller_paginate() );
        return view('admin.admin-form.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function show($id) {
        $items = UserForm::where('type', $id)->where('status','pending')->orderByDesc('id')->paginate($this->controller_paginate());
        return view('admin.admin-form.index', compact('items'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function edit($id) {
        $item = UserForm::findOrFail($id);
        return view('admin.admin-form.show', compact('item'), ['title1' => $this->controller_title('single'), 'title2' => $this->controller_title('sum')]);
    }

    public function update(Request $request, $id) {
        $form = UserForm::findOrFail($id);
        try {
            $form->status = 'active';
            $form->save();
            return redirect()->back()->withInput()->with('flash_message', 'فورم با موفقیت بررسی شد');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('err_message', 'برای ویرایش به مشکل خوردیم، مجدد تلاش کنید');
        }
    }

}
