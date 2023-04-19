<?php

use App\Model\Product;
Route::get('test', function () {

    $user = App\User::find(274);
    $tes =$user->potentials()->find(26);
//    $tes =$user->potentials()->find(1);
    dd($tes,$tes->sub,$tes->pivot->sub_id);
    //dd($tes,$tes->sub,$tes->sub,$tes->pivot->sub,$user->potentials->toArray());

});

Route::get('notification/daily/mark-as-read', function () {
    notificationsReaded();
    return redirect()->back()->with('flash_message', 'با موفقیت انجام شد.');
})->name('mark-as-read');
    
Route::get('query', function () {
    $users = \App\User::sub_users_for(1);
    dd($users);
});

Route::get('deactive-user/{id}', function ($id) {
    if ( auth()->user()->status=='active' ) return redirect()->route('admin.index');
    $user = \App\User::find($id);
    $user_fullname = $user->first_name.' '.$user->last_name;
    return view('auth.deactive_user', compact('user_fullname'));
})->name('deactive.user');




// Route::get('/', 'AdminController@index')->name('index');
Route::get('/', 'Target\TargetController@index')->name('index');
Route::resource('post', 'BlogController');
Route::get('post/type/{id}', 'BlogController@index')->name('post.index.type');
Route::get('post/reactivate/{id}', 'BlogController@active')->name('post.reactivate');
Route::get('post/delete/{id}', 'BlogController@destroy')->name('post.hard.delete');
Route::resource('network-setting', 'SettingController');
Route::resource('sub_service', 'SubServiceController');
Route::get('sub_service/destroy/{id}', 'SubServiceController@destroy')->name('sub_service.destroy');
Route::get('sub_service/active/{id}/{type}', 'SubServiceController@active')->name('sub_service.active');
Route::resource('form-price', 'FormPriceController');
Route::resource('notification', 'NotificationController');
//destroy notification
Route::get('notifications/read-my-notify', 'NotificationController@read_my_notify')->name('notification.read_my_notify');;
Route::get('notification/{id}/destroy', 'NotificationController@destroy')->name('notification.destroy');;

Route::resource('ads-tours', 'TourismController');
Route::resource('forms', 'FormController');
// موقعیت های شغلی
Route::resource('job-opportunities', 'JobOpportunitiesController');
Route::get('job-opportunities/type/{id}', 'JobOpportunitiesController@index')->name('job-opportunities.type');
Route::get('job-opportunities/reactivate/{id}', 'JobOpportunitiesController@active')->name('job-opportunities.reactivate');
// لیست بانک ها
Route::resource('banks', 'BanksController');
// website data
Route::resource('data', 'DataController');
Route::resource('ads-tours-album', 'AlbumController');
Route::get('ads-tours/{id}/destroy', 'TourismController@destroy')->name('ads-tours-destroy-item');
Route::get('ads-tours/album/{id}/destroy', 'AlbumController@destroy')->name('ads-tours-album-destroy-item');

// setting
Route::get('setting-edit', 'SettingController@edit')->name('setting.edit');
Route::post('setting-update/{id}', 'SettingController@update')->name('setting.update');
Route::get('setting-update/destroy/{id}', 'SettingController@destroy')->name('setting.destroy');

Route::get('setting/index-edit', 'SettingController@index_edit')->name('setting.index.edit');
Route::post('setting/index-update', 'SettingController@index_update')->name('setting.index.update');

// about
Route::post('about-update/{id}', 'AboutController@update')->name('about.update');
Route::post('about-update-home/{id}', 'AboutController@update_home')->name('about.update.home');
Route::get('about-edit', 'AboutController@edit')->name('about.edit');
Route::resource('about-join', 'AboutController');
// rule
Route::get('rule-edit', 'RuleController@edit')->name('rule.edit');
Route::post('rule-update/{id}', 'RuleController@update')->name('rule.update');

// guide
Route::get('guide-edit', 'GuideController@edit')->name('guide.edit');
Route::post('guide-update/{id}', 'GuideController@update')->name('guide.update');
Route::get('about-destroy/{id}', 'GuideController@destroy')->name('about.destroy');
// contact
Route::get('contact-list', 'ContactController@index')->name('contact.list');
Route::get('contact-list/type/{type}', 'ContactController@index')->name('contact.list.type');
Route::get('contact-list/pay/{type}', 'ContactController@index')->name('contact.list.pay');
Route::get('contact-list/pay/accept/{id}', 'ContactController@accept')->name('contact.list.pay.accept');
Route::get('contact-list/pay/reject/{id}', 'ContactController@reject')->name('contact.list.pay.reject');
Route::post('contact-send-email/{id}', 'ContactController@send_email')->name('contact.send.email');
Route::post('contact-send-ticket/{id}', 'ContactController@send_ticket')->name('contact.send.ticket');
Route::get('contact-destroy/{id}', 'ContactController@destroy')->name('contact.destroy');
Route::get('contact-edit', 'ContactSettingController@edit')->name('contact.edit');
Route::post('contact-update/{id}', 'ContactSettingController@update')->name('contact.update');

// user ask
Route::get('ask-list', 'AskController@index')->name('ask.list');
Route::get('ask-list/type/{type}', 'AskController@index')->name('ask.list.type');

// user consultant list
Route::get('consultant-list', 'ConsultationController@index')->name('consultant.list');
Route::get('consultant-list/type/{type}', 'ConsultationController@index')->name('consultant.list.type');
Route::post('consultant/refer-to-consultation', 'ConsultationController@refer')->name('refer.consultation.to.consultant');
Route::get('consultant/{id}/destroy', 'ConsultationController@destroy')->name('consultation.destroy');
Route::get('consultant/{id}/action', 'ConsultationController@action_show')->name('consultant.action');

//consultation call

Route::post('consultant-call/{id}/store', 'ConsultationCallController@store')->name('consultant_call.store');
Route::post('consultant-call/{id}/destroy', 'ConsultationCallController@destroy')->name('consultant_call.destroy');

//todo
Route::get('todo-list', 'TodoController@index')->name('todo.list');

//todo list
Route::get('todo', 'TodoController@index')->name('todo.list'); //Route::get('todo', [UserController::class ,'todo'])->name('todo');
Route::any('todo/{id}/visit', 'TodoController@visit')->name('todo.visit'); //Route::any('todo/{id}/visit', [UserController::class, 'todo_visit'])->name('todo-visit');
Route::any('todo/{id}/change/{status}', 'TodoController@status')->name('todo.status'); //Route::get('todo-status2/{id}/{status}', [UserController::class, 'todo_status'])->name('todo-status');
//Route::get('todo-create', [UserController::class, 'todo_create'])->name('todo-create');
//Route::put('todo-store', [UserController::class, 'todo_store'])->name('todo-store');


// slider
Route::get('slider-list', 'SliderController@index')->name('slider.list');
Route::get('slider-create', 'SliderController@create')->name('slider.create');
Route::post('slider-store', 'SliderController@store')->name('slider.store');
Route::get('slider-edit/{id}', 'SliderController@edit')->name('slider.edit');
Route::post('slider-update/{id}', 'SliderController@update')->name('slider.update');
Route::post('slider-sort', 'SliderController@sort')->name('slider.sort');
Route::get('slider-destroy/{id}', 'SliderController@destroy')->name('slider.destroy');

// award
Route::get('award-list', 'AwardController@index')->name('award.list');
Route::get('award-create', 'AwardController@create')->name('award.create');
Route::post('award-store', 'AwardController@store')->name('award.store');
Route::get('award-edit/{id}', 'AwardController@edit')->name('award.edit');
Route::post('award-update/{id}', 'AwardController@update')->name('award.update');
Route::post('award-sort', 'AwardController@sort')->name('award.sort');
Route::get('award-destroy/{id}', 'AwardController@destroy')->name('award.destroy');

// off_code
Route::get('off-list', 'OffController@index')->name('off.list');
Route::get('off-create', 'OffController@create')->name('off.create');
Route::post('off-store', 'OffController@store')->name('off.store');
Route::get('off-edit/{id}', 'OffController@edit')->name('off.edit');
Route::post('off-update/{id}', 'OffController@update')->name('off.update');
Route::get('off-destroy/{id}', 'OffController@destroy')->name('off.destroy');
Route::get('off-active/{id}/{type}', 'OffController@active')->name('off.active');

// customer
Route::get('customer-list', 'CustomController@index')->name('customer.list');
Route::get('customer-create', 'CustomController@create')->name('customer.create');
Route::post('customer-store', 'CustomController@store')->name('customer.store');
Route::get('customer-edit/{id}', 'CustomController@edit')->name('customer.edit');
Route::get('customer-active/{id}/{type}', 'CustomController@active')->name('customer.active');

Route::post('customer-update/{id}', 'CustomController@update')->name('customer.update');
Route::get('customer-destroy/{id}', 'CustomController@destroy')->name('customer.destroy');

// profile
Route::get('profile-show', 'ProfileController@show')->name('profile.show');
Route::get('profile-edit', 'ProfileController@edit')->name('profile.edit');
Route::get('password-edit', 'ProfileController@password_edit')->name('password.edit');
Route::post('profile-update/{id}', 'ProfileController@update')->name('profile.update');
Route::post('password-update/{id}', 'ProfileController@password_update')->name('password.update');
Route::post('store-code', 'ProfileController@store_code')->name('store.code');
Route::get('show-code', 'ProfileController@show_code')->name('show.code');

Route::get('photo/{id}/destroy', 'PhotoController@destroy')->name('photo.destroy');


// user
Route::post('user/role/update', 'UserController@userRole')->name('user-role.update');
Route::get('user-list', 'UserController@index')->name('user.list');
Route::get('user/{id}/sub-users', 'UserController@sub_user_index')->name('sub-user.list');
Route::get('user-list-tree', 'UserController@index_tree')->name('user.list.tree');
Route::get('user-list/role/{role}', 'UserController@index')->name('user.list.roles');
Route::get('user-show/{id}', 'UserController@show')->name('user.show');
Route::get('user-create', 'UserController@create')->name('user.create');
Route::post('user-store', 'UserController@store')->name('user.store');
Route::get('user-edit/{id}', 'UserController@edit')->name('user.edit');
Route::post('user-update/{id}', 'UserController@update')->name('user.update');
Route::get('user-destroy/{id}', 'UserController@destroy')->name('user.destroy');
Route::get('user-active/{id}/{type}', 'UserController@active')->name('user.active');

//user potentials
Route::resource('potential', 'PotentialController');
Route::get('potential/{id}/destroy', 'PotentialController@destroy')->name('potential.destroy');

Route::get('user/potentials/{id}/{name?}/list', 'UserController@potantial_index')->name('user.potential.list');
Route::any('user/potential/update', 'UserController@user_potential_update')->name('user-potential.update');
Route::any('user/potential/update-sub', 'UserController@user_potential_update_sub')->name('user-potential.update-sub');
Route::any('user/{userId}/potential/{potentialId}/destroy', 'UserController@destroy_potential')->name('user-potential.destroy');

//report performance
Route::get('report/performance/{type}', 'PerformanceController@index')->name('report.performance.list');
Route::get('report/performance', 'PerformanceController@create')->name('report.performance.create');
Route::post('report/performance/store', 'PerformanceController@store')->name('report.performance.store');
Route::post('report/performance/{id}/update', 'PerformanceController@update')->name('report.performance.update');
Route::get('report/performance/{id}/destroy', 'PerformanceController@destroy')->name('report.performance.destroy');


// Route::post('customer/role/update', 'CustomerController@customerRole')->name('customer-role.update');
// Route::get('customer-list', 'CustomerController@index')->name('customer.list');
// Route::get('customer-show/{id}', 'CustomerController@show')->name('customer.show');
// Route::get('customer-create', 'CustomerController@create')->name('customer.create');
// Route::post('customer-store', 'CustomerController@store')->name('customer.store');
// Route::get('customer-edit/{id}', 'CustomerController@edit')->name('customer.edit');
// Route::post('customer-update/{id}', 'CustomerController@update')->name('customer.update');
// Route::get('customer-destroy/{id}', 'CustomerController@destroy')->name('customer.destroy');


//call management
Route::resource('call', 'CallController');
Route::get('call/{id}/destroy', 'CallController@destroy')->name('call.destroy');

//product management
Route::resource('product', 'ProductController');
Route::get('product/cat/filter/ajax/{id}', 'ProductController@filter')->name('product.cat-filter-ajax');
Route::get('product/{id}/destroy', 'ProductController@destroy')->name('product.destroy');

Route::resource('category', 'CategoryController');
Route::get('category/{id}/destroy', 'CategoryController@destroy')->name('category.destroy');


Route::resource('factor', 'FactorController');

Route::post('factor/add/{id}/product', 'FactorController@add_product')->name('factor.add-product');
Route::get('factor/{id}/destroy', 'FactorController@destroy')->name('factor.destroy');
Route::get('factor/{factorId}/product/{productId}/destroy', 'FactorController@factor_product_destroy')->name('factor-product.destroy');


//// agent
//Route::get('agent-list', 'AgentController@index')->name('agent.list');
//Route::get('agent-show/{id}', 'AgentController@show')->name('agent.show');
//Route::get('agent-create', 'AgentController@create')->name('agent.create');
//Route::post('agent-store', 'AgentController@store')->name('agent.store');
//Route::get('agent-edit/{id}', 'AgentController@edit')->name('agent.edit');
//Route::post('agent-update/{id}', 'AgentController@update')->name('agent.update');
//Route::get('agent-destroy/{id}', 'AgentController@destroy')->name('agent.destroy');
//Route::get('agent-active/{id}/{type}', 'AgentController@active')->name('agent.active');
//
//// marketer
//Route::get('marketer-list', 'MarteterController@index')->name('marketer.list');
//Route::get('marketer-show/{id}', 'MarteterController@show')->name('marketer.show');
//Route::post('marketer-store', 'MarteterController@store')->name('marketer.store');
//Route::get('marketer-create', 'MarteterController@create')->name('marketer.create');
//Route::get('marketer-edit/{id}', 'MarteterController@edit')->name('marketer.edit');
//Route::post('marketer-update/{id}', 'MarteterController@update')->name('marketer.update');
//Route::get('marketer-destroy/{id}', 'MarteterController@destroy')->name('marketer.destroy');
//Route::get('marketer-active/{id}/{type}', 'MarteterController@active')->name('marketer.active');

// agent-request
Route::get('agent-request-list', 'AgentRequestController@index')->name('agent.request.list');
Route::get('agent-request-show/{id}', 'AgentRequestController@show')->name('agent.request.show');
Route::get('agent-request-status/{type}/{id}', 'AgentRequestController@status')->name('agent.request.status');
Route::post('agent-request-active/{id}', 'AgentRequestController@active')->name('agent.request.active');

Route::get('agent-request-create', 'AgentRequestController@create')->name('agent.request.create');
Route::post('agent-request-store', 'AgentRequestController@store')->name('agent.request.store');

// service cat
Route::get('service-category-list', 'ServiceCategoryController@index')->name('service.category.list');
Route::get('service-category-create', 'ServiceCategoryController@create')->name('service.category.create');
Route::post('service-category-store', 'ServiceCategoryController@store')->name('service.category.store');
Route::post('service-category-sort', 'ServiceCategoryController@sort')->name('service.category.sort');
Route::get('service-category-edit/{id}', 'ServiceCategoryController@edit')->name('service.category.edit');
Route::post('service-category-update/{id}', 'ServiceCategoryController@update')->name('service.category.update');
Route::get('service-category-destroy/{id}', 'ServiceCategoryController@destroy')->name('service.category.destroy');
Route::get('service-category-active/{id}/{type}', 'ServiceCategoryController@active')->name('service.category.active');

// service
Route::get('service-list', 'ServiceController@index')->name('service.list');
// Route::get('service-create/{type}', 'ServiceController@create')->name('service.create');
Route::get('service-create', 'ServiceController@create')->name('service.create');
Route::post('service-store', 'ServiceController@store')->name('service.store');
Route::get('service-edit/{id}', 'ServiceController@edit')->name('service.edit');
Route::post('service-update/{id}', 'ServiceController@update')->name('service.update');
Route::get('service-destroy/{id}', 'ServiceController@destroy')->name('service.destroy');
Route::get('service-active/{id}/{type}', 'ServiceController@active')->name('service.active');
Route::get('service-order/{from}/{to}', 'ServiceController@order')->name('service.order');



Route::post('package-connect-project/{packageBuy}', 'PackageController@store_connect_project')->name('package.connect_project');
Route::get('package-list', 'PackageController@index')->name('package.list');
Route::get('package-edit/{id}', 'PackageController@edit')->name('package.edit');
Route::get('package-destroy/{id}', 'PackageController@destroy')->name('package.destroy');
Route::post('package-update/{id}', 'PackageController@update')->name('package.update');
Route::get('package-create', 'PackageController@create')->name('package.create');
Route::post('package-store', 'PackageController@store')->name('package.store');


Route::get('project-list', 'ProjectController@index')->name('project.list');
Route::get('project-edit/{id}', 'ProjectController@edit')->name('project.edit');
Route::get('project-destroy/{id}', 'ProjectController@destroy')->name('project.destroy');
Route::post('project-update/{id}', 'ProjectController@update')->name('project.update');
Route::get('project-create', 'ProjectController@create')->name('project.create');
Route::post('project-store', 'ProjectController@store')->name('project.store');


// service package
Route::get('service-package-list', 'ServicePackageController@index')->name('service.package.list');
Route::get('service-package-create', 'ServicePackageController@create')->name('service.package.create');
Route::post('service-package-store', 'ServicePackageController@store')->name('service.package.store');
Route::get('service-package-edit/{id}', 'ServicePackageController@edit')->name('service.package.edit');
Route::post('service-package-update/{id}', 'ServicePackageController@update')->name('service.package.update');
Route::post('sort-by-join', 'ServicePackageController@sort_by_join')->name('sort.by.join');
Route::get('service-package-destroy/{id}', 'ServicePackageController@destroy')->name('service.package.destroy');
Route::get('service-package-active/{id}/{type}', 'ServicePackageController@active')->name('service.package.active');

// service learn
Route::get('service-learn-list', 'ServiceController@learn_index')->name('service.learn.list');
Route::get('service-learn-create', 'ServiceController@learn_create')->name('service.learn.create');
Route::post('service-learn-store', 'ServiceController@learn_store')->name('service.learn.store');
Route::get('service-learn-edit/{id}', 'ServiceController@learn_edit')->name('service.learn.edit');
Route::post('service-learn-update/{id}', 'ServiceController@learn_update')->name('service.learn.update');
Route::get('service-learn-destroy/{id}', 'ServiceController@learn_destroy')->name('service.learn.destroy');
Route::get('service-learn-active/{id}/{type}', 'ServiceController@learn_active')->name('service.learn.active');
// learn package cat
Route::get('learn-package-category-list', 'LearnPackageCategoryController@index')->name('learn.package.category.list');
Route::get('learn-package-category-create', 'LearnPackageCategoryController@create')->name('learn.package.category.create');
Route::post('learn-package-category-store', 'LearnPackageCategoryController@store')->name('learn.package.category.store');
Route::get('learn-package-category-edit/{id}', 'LearnPackageCategoryController@edit')->name('learn.package.category.edit');
Route::post('learn-package-category-update/{id}', 'LearnPackageCategoryController@update')->name('learn.package.category.update');
Route::get('learn-package-category-destroy/{id}', 'LearnPackageCategoryController@destroy')->name('learn.package.category.destroy');


// service  learn package
Route::get('service-learn-package-list', 'ServicePackageController@learn_index')->name('service.learn.package.list');
Route::get('service-learn-package-create', 'ServicePackageController@learn_create')->name('service.learn.package.create');
Route::post('service-learn-package-store', 'ServicePackageController@learn_store')->name('service.learn.package.store');
Route::get('service-learn-package-edit/{id}', 'ServicePackageController@learn_edit')->name('service.learn.package.edit');
Route::post('service-learn-package-update/{id}', 'ServicePackageController@learn_update')->name('service.learn.package.update');
Route::post('learn-sort-by-join', 'ServicePackageController@learn_sort_by_join')->name('learn.sort.by.join');
Route::get('service-learn-package-destroy/{id}', 'ServicePackageController@learn_destroy')->name('service.learn.package.destroy');
Route::get('service-learn-package-active/{id}/{type}', 'ServicePackageController@learn_active')->name('service.learn.package.active');

//package video
Route::get('service-package-video-list/{id}', 'ServicePackageVideoController@index')->name('service.package.video.list');
Route::post('service-package-video-store/{id}', 'ServicePackageVideoController@store')->name('service.package.video.store');
Route::get('service-package-video-destroy/{id}', 'ServicePackageVideoController@destroy')->name('service.package.video.destroy');
Route::post('service-package-video-sort/{id}', 'ServicePackageVideoController@sort')->name('service.package.video.sort');
Route::get('service-package-video-active/{id}/{type}', 'ServicePackageVideoController@active')->name('service.package.video.active');

// package price
Route::get('service-package-price-list/{id}', 'ServicePackagePriceController@index')->name('service.package.price.list');
Route::post('service-package-price-store/{id}/{type}', 'ServicePackagePriceController@store')->name('service.package.price.store');
Route::get('service-package-price-destroy/{id}', 'ServicePackagePriceController@destroy')->name('service.package.price.destroy');
Route::get('service-package-price-active/{id}/{type}', 'ServicePackagePriceController@active')->name('service.package.price.active');

// service buy
Route::get('service-buy-list', 'ServiceBuyController@index')->name('service.buy.list');
Route::get('service-buy-active/{id}/{type}', 'ServiceBuyController@active')->name('service.buy.active');

// transaction
Route::get('report-transaction-list', 'TransactionController@index')->name('report.transaction.list');
Route::get('report-transaction-create', 'TransactionController@create')->name('report.transaction.create');
Route::get('report-transaction-search', 'TransactionController@search')->name('report.transaction.search');

//  service level
Route::get('service-level-list/{s_id}', 'ServiceLevelController@index')->name('service.level.list');
Route::get('service-level-create/{s_id}', 'ServiceLevelController@create')->name('service.level.create');
Route::post('service-level-store/{s_id}', 'ServiceLevelController@store')->name('service.level.store');
Route::get('service-level-edit/{id}', 'ServiceLevelController@edit')->name('service.level.edit');
Route::post('service-level-update/{id}', 'ServiceLevelController@update')->name('service.level.update');
Route::get('service-level-destroy/{id}', 'ServiceLevelController@destroy')->name('service.level.destroy');

//  service query
Route::get('service-query-list/{l_id}', 'ServiceQueryController@index')->name('service.query.list');
Route::get('service-query-create/{l_id}', 'ServiceQueryController@create')->name('service.query.create');
Route::post('service-query-store/{l_id}', 'ServiceQueryController@store')->name('service.query.store');
Route::get('service-query-edit/{id}', 'ServiceQueryController@edit')->name('service.query.edit');
Route::post('service-query-update/{id}', 'ServiceQueryController@update')->name('service.query.update');
Route::get('service-query-destroy/{id}', 'ServiceQueryController@destroy')->name('service.query.destroy');
Route::get('service-query-active/{id}/{type}', 'ServiceQueryController@active')->name('service.query.active');

//  service plus
Route::get('service-plus-list/{p_id}', 'ServicePlusController@index')->name('service.plus.list');
Route::get('service-plus-create/{p_id}', 'ServicePlusController@create')->name('service.plus.create');
Route::post('service-plus-store/{p_id}', 'ServicePlusController@store')->name('service.plus.store');
Route::get('service-plus-edit/{id}', 'ServicePlusController@edit')->name('service.plus.edit');
Route::post('service-plus-update/{id}', 'ServicePlusController@update')->name('service.plus.update');
Route::get('service-plus-destroy/{id}', 'ServicePlusController@destroy')->name('service.plus.destroy');
Route::get('service-plus-active/{id}/{type}', 'ServicePlusController@active')->name('service.plus.active');

// meta
Route::get('meta-list', 'MetaController@index')->name('meta.list');
Route::get('meta-create', 'MetaController@create')->name('meta.create');
Route::post('meta-store', 'MetaController@store')->name('meta.store');
Route::get('meta-edit/{id}', 'MetaController@edit')->name('meta.edit');
Route::post('meta-update/{id}', 'MetaController@update')->name('meta.update');
Route::get('meta-destroy/{id}', 'MetaController@destroy')->name('meta.destroy');
Route::get('meta-active/{id}/{type}', 'MetaController@active')->name('meta.active');

// texts
Route::get('text-list', 'TextController@index')->name('text.list');
Route::get('text-create', 'TextController@create')->name('text.create');
Route::post('text-store', 'TextController@store')->name('text.store');
Route::get('text-edit/{id}', 'TextController@edit')->name('text.edit');
Route::post('text-update/{id}', 'TextController@update')->name('text.update');
Route::get('text-destroy/{id}', 'TextController@destroy')->name('text.destroy');

// visitlog
Route::get('visit_log', 'VisitLogController@index')->name('visit.log');

// call request
Route::get('call/request', 'CallController@index')->name('call.request');

// pass ask request
Route::post('pass-ask', 'AskController@pass')->name('pass.to');









// ========================= NEW ROUTES =========================
// connections -> lists
Route::resource('connection-list', 'Connection\ListController');
Route::get('connection-list/force/delete/{id}', 'Connection\ListController@destroy')->name('connection-list.force.delete');

// connections -> reports
Route::get('connection-report-list', 'Connection\ReportController@index')->name('connection-report.list');
Route::get('connection-report/data/search', 'Connection\ReportController@search')->name('connection-report.search');

// customer_bank -> customers
Route::resource('user-customer', 'CustomerBank\CustomerController');
Route::get('user-customer/force/delete/{id}', 'CustomerBank\CustomerController@destroy')->name('user-customer.force.delete');
Route::get('user-customer/custom/index/{id}/{type?}', 'CustomerBank\CustomerController@index')->name('user-customer.custom.index');

// customer_bank -> foctors
Route::resource('user-customer-factor', 'CustomerBank\FactorController');
Route::get('user-customer-factor/force/delete/{id}', 'CustomerBank\FactorController@destroy')->name('user-customer-factor.force.delete');
// create factor
Route::get('user-customer-factor/create/factor/{id}', 'CustomerBank\FactorController@create')->name('user-customer-factor.create.factor');

// customer_bank -> my packages
Route::resource('user-customer-package', 'CustomerBank\PackageController');
Route::get('user-customer-package/force/delete/{id}', 'CustomerBank\PackageController@destroy')->name('user-customer-package.force.delete');
Route::post('user-customer-package/add/report/store', 'CustomerBank\PackageController@add_package_report')->name('user-customer-package-report.store');
Route::get('user-customer-package/report/force/delete/{id}', 'CustomerBank\PackageController@destroy_package_report')->name('user-customer-package-report.force.delete');

// customer_bank -> tree
Route::get('user-customer-tree/user/tree/{id?}', 'CustomerBank\TreeController@index')->name('user-customer-tree.index-page');

// customer_bank -> report
Route::get('user-customer-report', 'CustomerBank\ReportController@index')->name('user-customer-report.index');
Route::get('user-customer-report/search', 'CustomerBank\ReportController@search')->name('user-customer-report.search');
Route::get('user-customer-report/search/bar', 'CustomerBank\ReportController@searchBar')->name('user-customer-report.search.bar');
Route::get('user-customer-report/custom/index/{id}/{type?}', 'CustomerBank\ReportController@index')->name('user-customer-report.custom.index');
Route::get('user-customer-report/state/cities/{slug}', 'CustomerBank\ReportController@showCities')->name('user-customer-report.state.cities');
Route::get('user-customer-report/state/cities/new/{slug}', 'CustomerBank\ReportController@showCitiesNew')->name('user-customer-report.state.cities_new');

// potential_organization -> four_action
Route::resource('four_action', 'PotentialOrganization\FourActionController');
Route::get('four_action/index/item/show/{id}/{type?}', 'PotentialOrganization\FourActionController@index')->name('four_action.item-show.index');
Route::get('four_action/create/{step}', 'PotentialOrganization\FourActionController@create')->name('four_action.custom.create');
Route::get('four_action/search/chart', 'PotentialOrganization\FourActionController@filter')->name('four_action.custom.filter');
Route::get('four_action/userslist/show/{type}', 'PotentialOrganization\FourActionController@users')->name('four_action.userslist.show');
Route::get('four_action/users/send-daily-work/{id?}', 'PotentialOrganization\FourActionController@usersDailyWork')->name('four_action.users-send-daily-work');

// potential_organization -> four_action
Route::resource('potential-list', 'PotentialOrganization\PotentialController');
Route::get('potential-list/user/status/reactivate/{id}', 'PotentialOrganization\PotentialController@reactivate')->name('potential-list-user-status-reactivate');
Route::get('potential-list/index/item/show/{id}/{type?}', 'PotentialOrganization\PotentialController@index')->name('potential-list.item-show.index');
Route::get('potential-list/organization/list/{id?}', 'PotentialOrganization\PotentialController@list')->name('potential-list.list');
Route::get('potential-list/report/list/{id}/{type?}', 'PotentialOrganization\PotentialController@report')->name('potential-list.report.list');
Route::get('potential-list/report/list/filter/{id}/{type}', 'PotentialOrganization\PotentialController@report')->name('potential-list.report.list.filter');
Route::get('potential-list/report/filter/{id}/{year}/{month}/{type?}', 'PotentialOrganization\PotentialController@next_report_filter')->name('potential-list.filter');
Route::get('potential-list/potential/follow/{id}/', 'PotentialOrganization\PotentialController@follow')->name('potential-follow');

// potential_organization -> subset
Route::get('potential/organization/subset/index/show/{id?}', 'PotentialOrganization\SubsetController@index')->name('subset.index');
Route::get('potential/organization/subset/report/{id?}', 'PotentialOrganization\SubsetController@report')->name('subset.report');

// monthly_package -> list
Route::resource('monthly-package', 'MonthlyPackage\ListController');
Route::get('monthly-package/coustom/delete/{id}', 'MonthlyPackage\ListController@destroy')->name('monthly-package.delete');

// monthly_package -> report
Route::get('monthly-package-report', 'MonthlyPackage\ReportController@index')->name('monthly-package-report.index');
Route::get('monthly-package-report/store/{potential_id}/{pack_id}', 'MonthlyPackage\ReportController@store')->name('monthly-package-report.stroe');
Route::get('monthly-package-report/update/{id}/{status}', 'MonthlyPackage\ReportController@update')->name('monthly-package-report.update');

Route::resource('organization-member', 'OrganizationMember\OrganizationMemberController');
Route::resource('organization-member-tree', 'OrganizationMember\TreeController');
Route::get('organization-member-tree/organization/show/map/{id?}', 'OrganizationMember\TreeController@map')->name('organization-map');

// daily-schedule => quad-performance
Route::resource('daily-schedule-quad-performance', 'DailySchedule\QuadPerformanceController');
Route::get('daily-schedule-quad-performance/show/{id}/status/{status}', 'DailySchedule\QuadPerformanceController@show')->name('quad-performance.custom.show.status');
Route::get('daily-schedule-quad-performance/create/{step}/step', 'DailySchedule\QuadPerformanceController@create')->name('quad-performance.custom.create');

// daily-schedule => reports
Route::resource('daily-schedule-report', 'DailySchedule\ReportController');
Route::get('daily-schedule-report-filter', 'DailySchedule\ReportController@filter')->name('daily-schedule-report.filter');
Route::post('daily-schedule-report-show/users/{type}', 'DailySchedule\ReportController@users')->name('daily-schedule-report.show.users');

// daily-schedule => org-performance
Route::resource('daily-schedule-org-performance', 'DailySchedule\OrgPerformanceController');
Route::get('daily-schedule-org-performance/show/{id}/status/{status}', 'DailySchedule\OrgPerformanceController@show')->name('org-performance.custom.show.status');
Route::get('daily-schedule-org-performance/create/{step}/step', 'DailySchedule\OrgPerformanceController@create')->name('org-performance.custom.create');

// daily-schedule => org-reports
Route::resource('daily-schedule-org-report', 'DailySchedule\OrgReportController');
Route::get('daily-schedule-org-report-filter', 'DailySchedule\OrgReportController@filter')->name('daily-schedule-org-report.filter');
Route::post('daily-schedule-org-report-shoe/users/{type}', 'DailySchedule\OrgReportController@users')->name('daily-schedule-org-report.show.users');

// potential_organization -> potential_report
// Route::get('potential-report/items/show/{id}', 'PotentialOrganization\PotentialReportController@show')->name('potential-report.items-show');
Route::get('potential-report/item/{id}/{column}/{status}', 'PotentialOrganization\PotentialReportController@update_report')->name('potential-report.item-update');

// target -> target
Route::resource('target', 'Target\TargetController');
Route::get('target/custom/index/{id}', 'Target\TargetController@index')->name('target.custom.index');
Route::get('target/{id}/{step}/{step2}/date', 'Target\TargetController@index')->name('target.customoze.date.index');
Route::get('api/v1/target/{id}/filter', 'Target\TargetController@filter')->name('api.target.filter');

// meet -> workshop
Route::resource('workshop', 'Meet\WorkshopController');

// meet -> description
Route::resource('workshop-description', 'Meet\DescriptionController');

// meet -> report
Route::resource('workshop-report', 'Meet\ReportController');
Route::get('meet/workshop-report/{slug}', 'Meet\ReportController@create')->name('meet.workshop-report.show-by-slug');
// meet -> learn
Route::resource('learn', 'Meet\LearnController');
Route::post('learn/file/delete/{id}', 'Meet\LearnController@destroy_file')->name('learn.file.destroy');
// meet -> show-learn
// Route::resource('show-learn', 'Meet\LearnController');

//Access
Route::resource('permissionCat', 'Access\PermissionCatController');
Route::resource('permission', 'Access\PermissionController');
Route::resource('role', 'Access\RoleController');

// role level up
Route::get('user/level-up', 'UserController@roleLevelUp')->name('user.level-up');
Route::get('user/level-up-result/{id}/{result}', 'UserController@roleLevelUpResult')->name('user.level-up.result');
Route::get('user/level-up/request', 'UserController@roleLevelUpRequest')->name('user.level-up.request');
Route::resource('org-performance-label', 'DailySchedule\OrgPerformanceLabelController');


Route::get('plan-game', 'Game\GameController@index')->name('plan.game.index');
// ========================= END ROUTES =========================

