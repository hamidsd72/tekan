<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Model\Setting;
use App\Model\ServiceBuy;
// use App\Model\Notification;
use App\Model\OrgPerformance;
use App\Model\QuadPerformance;
use App\Model\ProvinceCity;
use App\Model\Agent;
use App\Model\Meta;
use App\Model\Visit;
use App\Model\Network;
use App\Model\ServiceCat;
use App\Model\CallRequest;
use App\Model\Meet;
// use App\Model\Meet;
use Illuminate\Support\Facades\Cookie;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot(Request $request)
    {

        $this->url = $request->fullUrl();
        Blade::directive('item', function ($name) {
            return "<?php echo $name ?>";
        });

        Schema::defaultStringLength(191);
        Carbon::setLocale('fa');

        view()->composer('layouts.admin', function ($view) {
            $next_role  = 'ارتقا به عموم سازمان';
            $active     = true;
            if (auth()->user()->request_level) {
                $active     = false;
                $next_role  = 'درخواست '.auth()->user()->request_level;
            }
            if ( auth()->user()->roles->first() && auth()->user()->request_level===null ) {
                switch ( auth()->user()->roles->first()->title ) {
                    case 'عموم سازمان':
                        $next_role  = 'ارتقا به پشتیبان';
                        break;
                    case 'پشتیبان':
                        $next_role  = 'ارتقا به نماینده';
                        break;
                    case 'نماینده':
                        $next_role  = 'ارتقا به نماینده مستقل';
                        break;
                    case 'نماینده مستقل':
                        $next_role  = 'ارتقا به حامی نقره ای';
                        break;
                    case 'حامی نقره ای':
                        $next_role  = 'ارتقا به حامی طلایی';
                        break;
                    case 'حامی طلایی':
                        $next_role  = 'ارتقا به حامی پلاتین - کارآفرین کوشا';
                        break;
                    case 'حامی پلاتین - کارآفرین کوشا':
                        $next_role  = 'ارتقا به حامی الماس - کارآفرین پویا';
                        break;
                    default:
                        $next_role  = auth()->user()->roles->first()->title;
                        $active     = false;
                        break;
                }
            }

            $unreadNotifications = auth()->user()->unreadNotifications;
            // جلسات امروز
            $meets  = Meet::where('user_id', auth()->id())->where('ready_date', '<', Carbon::now())->pluck('id');
            // نیاز به ریفکتور دارد
            // ------------------------------------------------------------- 
            $unreaDailydNotifications       = QuadPerformance::whereIn('id', auth()->user()->unreadNotifications->where('type','App\Notifications\Invoice')
            ->pluck('notifiable_id'))->where('date_en', '<', Carbon::now())->get(['name','date','label']);

            $unreaOrgDailydNotifications    = OrgPerformance::whereIn('id', auth()->user()->unreadNotifications->where('type','App\Notifications\OrgInvoice')
            ->pluck('notifiable_id'))->where('date_en', '<', Carbon::now())->get(['name','date','label_id']);
            // ------------------------------------------------------------- 

            
            $view->with('notifications', auth()->user()->unreadNotifications->count());
            $view->with('next_role', $next_role);
            $view->with('active', $active);
            $view->with('unreaDailydNotifications', $unreaDailydNotifications);
            $view->with('unreaOrgDailydNotifications', $unreaOrgDailydNotifications);
            if ($meets) $view->with('unreaMeetdNotifications', $unreadNotifications->where('type','App\Notifications\Meet')->whereIn('notifiable_id',$meets));
            $view->with('unreaMeetReportNotifications', $unreadNotifications->where('type','App\Notifications\MeetCreateReport'));
            // $view->with('potential_items', Potential::whereNull('parent_id')->orderBy('sort','asc')->get());
            $view->with('order', ServiceBuy::where('status','pending')->count());
            $view->with('setting', Setting::find(1));
            $view->with('agent_request', Agent::where('seen',0)->count());
            $view->with('call_req', CallRequest::where('status','pending')->where('consultant_id',auth()->id())->first());
        });
        view()->composer('layouts.user', function ($view) {
            //visit
            $ip = getenv('HTTP_CLIENT_IP') ?:
                getenv('HTTP_X_FORWARDED_FOR') ?:
                    getenv('HTTP_X_FORWARDED') ?:
                        getenv('HTTP_FORWARDED_FOR') ?:
                            getenv('HTTP_FORWARDED') ?:
                                getenv('REMOTE_ADDR');
            $date=date('Y-m-d');
            $visit_old=Visit::whereDate('created_at','=',$date)->where('ip',$ip)->first();
            if($visit_old)
            {
                $visit_old->view+=1;
                $visit_old->update();
            }
            else {
                $visit=new Visit();
                $visit->ip=$ip;
                $visit->view=1;
                $visit->save();
            }


            //visit

            $seo = Meta::where('url', $this->url)->first();
            if (is_null($seo)) {
                $seo = Meta::where('url', $this->url . '/')->first();
                if (is_null($seo)) {
                    $seo = Meta::where('url', explode('?', $this->url)[0])->first();
                    if (is_null($seo)) {
                        $seo = Meta::where('url', explode('?', $this->url)[0] . '/')->first();
                    }
                }
            }
            $set=Setting::find(1);
            if (!is_null($seo)) {
                $titleSeo = $seo->title;
                $keywordsSeo = $seo->key_word;
                $descriptionSeo = $seo->description;
            }
            else {
                $titleSeo = $set->title;
                $keywordsSeo = $set->keyword;
                $descriptionSeo = $set->description;
            }
            $ServiceCat = ServiceCat::where('type', 'service')->orderBy('id', 'ASC')->get();

            $view
                ->with('setting', Setting::find(1))
                ->with('titleSeo', $titleSeo)
                ->with('keywordsSeo', $keywordsSeo)
                ->with('descriptionSeo', $descriptionSeo)
                ->with('ServiceCats', $ServiceCat);
            if (Cookie::get('basket') != null){
                $view->with('BasketCount', count(json_decode(Cookie::get('basket'))));
            }else {
                $view->with('BasketCount', '');
            }

        });


        view()->composer('layouts.layout_first_page', function ($view) {
            //visit
            $ip = getenv('HTTP_CLIENT_IP') ?:
                getenv('HTTP_X_FORWARDED_FOR') ?:
                    getenv('HTTP_X_FORWARDED') ?:
                        getenv('HTTP_FORWARDED_FOR') ?:
                            getenv('HTTP_FORWARDED') ?:
                                getenv('REMOTE_ADDR');
            $date=date('Y-m-d');
            $visit_old=Visit::whereDate('created_at','=',$date)->where('ip',$ip)->first();
            if($visit_old)
            {
                $visit_old->view+=1;
                $visit_old->update();
            }
            else {
                $visit=new Visit();
                $visit->ip=$ip;
                $visit->view=1;
                $visit->save();
            }


            //visit

            $seo = Meta::where('url', $this->url)->first();
            if (is_null($seo)) {
                $seo = Meta::where('url', $this->url . '/')->first();
                if (is_null($seo)) {
                    $seo = Meta::where('url', explode('?', $this->url)[0])->first();
                    if (is_null($seo)) {
                        $seo = Meta::where('url', explode('?', $this->url)[0] . '/')->first();
                    }
                }
            }
            $set=Setting::first();
            if (!is_null($seo)) {
                $titleSeo = $seo->title;
                $keywordsSeo = $seo->key_word;
                $descriptionSeo = $seo->description;
            }
            else {
                $titleSeo = $set->title;
                $keywordsSeo = $set->keyword;
                $descriptionSeo = $set->description;
            }
            $ServiceCat = ServiceCat::where('type', 'service')->orderBy('sort')->get();

            $view
                ->with('setting', Setting::first())
                ->with('titleSeo', $titleSeo)
                ->with('keywordsSeo', $keywordsSeo)
                ->with('descriptionSeo', $descriptionSeo)
                ->with('ServiceCats', $ServiceCat);
            if (Cookie::get('basket') != null){
                $view->with('BasketCount', count(json_decode(Cookie::get('basket'))));
            }else {
                $view->with('BasketCount', '');
            }
            $view->with('call_req', CallRequest::where('status','pending')->where('consultant_id',auth()->id())->first());

        });
        view()->composer('layouts.auth', function ($view) {
            $view->with('setting', Setting::find(1));
        });
        view()->composer('user.master', function ($view) {
            $view->with('call_req', CallRequest::where('status','pending')->where('consultant_id',auth()->id())->first());
        });
        view()->composer('auth.register', function ($view) {
            $view->with('states', ProvinceCity::where('parent_id',null)->get());
            $view->with('setting', Setting::find(1));
        });
        view()->composer('auth.register.mobile', function ($view) {
            $view->with('states', ProvinceCity::where('parent_id',null)->get());
            $view->with('setting', Setting::find(1));
        });
        view()->composer('includes.header', function ($view) {
            $view->with('ServiceCats', ServiceCat::where('status', 'active')->where('type', 'service')->orderBy('id', 'ASC')->get());
            $view->with('network', Network::where('status', 'active')->orderBy('sort')->get());
        });
    }

}
