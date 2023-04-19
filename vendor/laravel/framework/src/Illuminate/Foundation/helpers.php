<?php

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Broadcasting\Factory as BroadcastFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cookie\Factory as CookieFactory;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Mix;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\CallQueuedClosure;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\HtmlString;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\ImageManagerStatic as Image;
use Carbon\Carbon;
use App\Model\Optimizer;
use App\Model\Notification;
use App\Model\Potential;
use App\Model\ProvinceCity;
use App\Model\Customer;
use App\Model\Meet;

if (!function_exists('explode_last')) {
    function explode_last($item,$joda)
    {
        $type = explode($joda, $item);
        $end = end($type);
        return $end;
    }
}

if (!function_exists('meet_updater')) {
    function meet_updater() {
        $items = Meet::where('total','>',0)->where('reply','>',0)->where('addDays','>',0)->where('ready_date','<',Carbon::today())->get();
        foreach ($items as $item) {
            $item->total    -= 1;
            $start_date     = Carbon::parse($item->date);
            $add            = ($item->reply - $item->total) * $item->addDays;
            $item->ready_date = $start_date->addDay($add);
            $item->update();

            Notification::setItem(
                "App\Notifications\Meet",
                "App\User",
                $item->id,
                ('{"date": "'.$item->slug.'"}')
            );
        }
    }
}

if (!function_exists('start')) {
    function start() {
        return  g2j( Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' )) ,'Y/m/d');
    }
}

if (!function_exists('start_en')) {
    function start_en() {
        return Carbon::parse(j2g( g2j(Carbon::today(),'Y/m').'/01' ));
    }
}

// motification updated to column read_at equals to null
if (!function_exists('notificationReadAtNull')) {
    function notificationReadAtNull($id) {
        foreach (Notification::where('type','App\Notifications\Invoice')->where('notifiable_id',$id)->get(['id','read_at']) as $notification) {
            $notification->read_at = null;
            $notification->save();
        }
    }
}

// motification updated to column read_at equals to time now 
if (!function_exists('notificationsReaded')) {
    function notificationsReaded() {
        foreach (auth()->user()->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }
}

if (!function_exists('notificationsQuadReaded')) {
    function notificationsQuadReaded() {
        foreach (auth()->user()->unreadNotifications->where('type','App\Notifications\Invoice') as $notification) {
            $notification->markAsRead();
        }
    }
}

if (!function_exists('notificationsOrgReaded')) {
    function notificationsOrgReaded() {
        foreach (auth()->user()->unreadNotifications->where('type','App\Notifications\OrgInvoice') as $notification) {
            $notification->markAsRead();
        }
    }
}

if (!function_exists('getSubUser')) {
    // get all potential
    function getSubUser($user) {
        $input  = $user;
        $list   = [];
        for ($i=0; $i < count($input); $i++) { 
            array_push($list , intVal($input[$i]));
        }
        $state  = [];
        $loop = count($input);
        while (true) {
            $items  = Potential::whereIn('user_id', $input )->get(['id','name']);
            foreach ($items as $item) {
                array_push($list , $item->id);
                array_push($input , $item->name);
            }
            // ذخبره هر سطح
            array_push($state , $items->pluck('id'));
            // شرط خاتمه
            if ($items->count() < $loop) {
                break;
            }
            $loop = count($input);
        }
        return [$list,$state,$input];
    }
}

if (!function_exists('persianStartOfMonth')) {
    function persianStartOfMonth() {
        return Carbon::today()->subDay( my_jdate(Carbon::today(), 'd') - 1 );
    }
}

if (!function_exists('persianEndOfMonth')) {
    function persianEndOfMonth() {
        $firstOfMonth = persianStartOfMonth();
        $month = my_jdate(Carbon::today(), 'm');

        if ($month == 12) {
            $endOfMonth = $firstOfMonth->addDay(29);
        } elseif ($month < 12 && $month > 6) {
            $endOfMonth = $firstOfMonth->addDay(30);
        } else {
            $endOfMonth = $firstOfMonth->addDay(31);
        }

        if ($month < my_jdate($endOfMonth, 'm')) {
            return $endOfMonth;
        }
        // برای سال کبیسه
        return $endOfMonth->addDay();
    }
}

if (!function_exists('persianStartOfYear')) {
    function persianStartOfYear($year=null) {
        if ($year) {
            return Carbon::parse(j2g($year.'/01/01'));
        }
        return Carbon::parse(j2g(my_jdate(Carbon::today(), 'Y').'/01/01'));
    }
}

if (!function_exists('customer_report')) {
    function customer_report($year, $month, $type, $user_id)
    { 
        $res=0;
        $percent=0;
        $day=29;
        if((int)$month < 7 && (int)$month >= 1){$day=31;}
        elseif((int)$month > 6 && (int)$month < 12){$day=30;}
        $from_date = j2g($year . '/' . $month . '/01');
        $to_date = j2g($year . '/' . $month . '/'.$day);
        $from_date_en = Carbon::parse($from_date)->format('Y-m-d');
        $to_date_en = Carbon::parse($to_date)->format('Y-m-d');
        $all_count = Customer::whereIn('user_id', $user_id)->count();
        $items = Customer::whereIn('user_id', $user_id);
        $items = $items->whereDate('created_at','>=', $from_date_en)->whereDate('created_at','<=', $to_date_en)->get();

        if ($type != 'new') {
            $items = $items->filter(function ($item) use($type) {
                if(($item->factor_count + count($item->customer_factors)) > 5 && $type=='havadar')
                {
                    return $item;
                }
                elseif(($item->factor_count + count($item->customer_factors)) > 2 && ($item->factor_count + count($item->customer_factors)) < 6 && $type=='vafadar')
                {
                    return $item;
                }
                elseif(($item->factor_count + count($item->customer_factors)) == 2 && $type=='razi')
                {
                    return $item;
                }
                elseif(($item->factor_count + count($item->customer_factors)) == 1 && $type=='my')
                {
                    return $item;
                }
                elseif(count($item->referrer_users) && $type=='referr')
                {
                    return $item;
                }
            });
        }
        if(count($items))
            $percent=count($items) / $all_count * 100;
        else
            $percent=0;

        $res=count($items);
        if($type=='all')
        {
            $res=$all_count;
        }
        return $res;
    }
}

if (!function_exists('toEnNumber')) {
    function toEnNumber($input) {
        $replace_pairs = array(
              '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
              '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );
        return strtr( $input, $replace_pairs );
    }
}

if (!function_exists('toFaNumber')) {
    function toFaNumber($input) {
        $replace_pairs = array( '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹' );
        return strtr( $input, $replace_pairs );
    }
}

if (!function_exists('num2en')) {
    function num2en($data)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '٤', '٥', '٦'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '4', '5', '6'];
        $output = str_replace($persian, $english, $data);
        return $output;
    }
}

if (!function_exists('num2months')) {
    function num2months($id) {
        switch ($id) {
            case 1:
                return 'فرودین';
                break;
            case 2:
                return 'اردیبهشت';
                break;
            case 3:
                return 'خرداد';
                break;
            case 4:
                return 'تیر';
                break;
            case 5:
                return 'مرداد';
                break;
            case 6:
                return 'شهریور';
                break;
            case 7:
                return 'مهر';
                break;
            case 8:
                return 'آبان';
                break;
            case 9:
                return 'آذر';
                break;
            case 10:
                return 'دی';
                break;
            case 11:
                return 'بهمن';
                break;
            case 12:
                return 'اسفند';
                break;
        }
    }
}

if (!function_exists('faMonthsName')) {
    function faMonthsName() {
        return [
            'فرودین',
            'اردیبهشت',
            'خرداد',
            'تیر',
            'مرداد',
            'شهریور',
            'مهر',
            'آبان',
            'آذر',
            'دی',
            'بهمن',
            'اسفند',
        ];
    }
}

if (!function_exists('month_name')) {
    function month_name($data)
    {
        switch ($data) {
            case '1':
                $res = 'فروردین';
                break;
            case '2':
                $res = 'اردیبهشت';
                break;
            case '3':
                $res = 'خرداد';
                break;
            case '4':
                $res = 'تیر';
                break;
            case '5':
                $res = 'مرداد';
                break;
            case '6':
                $res = 'شهریور';
                break;
            case '7':
                $res = 'مهر';
                break;
            case '8':
                $res = 'آبان';
                break;
            case '9':
                $res = 'آذر';
                break;
            case '10':
                $res = 'دی';
                break;
            case '11':
                $res = 'بهمن';
                break;
            case '12':
                $res = 'اسفند';
                break;
            default:
                $res = '';
                break;
        }
        return $res;
    }
}
if (!function_exists('city_list')) {
    function city_list($state_id)
    {
        $cits = ProvinceCity::where('parent_id', $state_id)->get();

        return $cits;
    }
}
if (!function_exists('num2fa')) {
    function num2fa($data)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', '۴', '۵', '۶'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '٤', '٥', '٦'];
        $output = str_replace($english, $persian, $data);
        return $output;
    }
}
if (!function_exists('j2g')) {
    function j2g($date)
    {
        $ymd = str_replace(['-', ','], '/', $date);
        $ymd = explode('/', $ymd);
        require_once('jdf.php');
        if (count($ymd) == 3) {
            $jalali_date = jalali_to_gregorian($ymd[0], $ymd[1], $ymd[2]);
            return implode('-', $jalali_date);
        }
    }
}
if (!function_exists('g2j')) {
    function g2j($date, $type)
    {
        $timestamp = (strtotime($date));
        require_once('jdf.php');
        $jalali_date = jdate($type, $timestamp);
        return $jalali_date;
    }
}
if (!function_exists('random_color')) {

    function random_color($colors = ['primary', 'secondary', 'info', 'success', 'danger', 'warning'])
    {
        return $colors[rand(0, count($colors) - 1)];
    }
}


if (!function_exists('str_limit')) {

    function str_limit($value, $limit, $end = '...')
    {
        if (mb_strwidth($value, 'UTF-8') <= $limit) {
            return $value;
        }

        return rtrim(mb_strimwidth($value, 0, $limit, '', 'UTF-8')) . $end;
    }
}

if (!function_exists('is_odd')) {

    function is_odd($number)
    {
        if ($number % 2 == 0)
            return false;
        else
            return true;
    }
}

if (!function_exists('convertFaDateToEn')) {

    function convertFaDateToEn($date)
    {
        $en_date = faToen($date);
        return str_replace('/', '-', $en_date);
    }
}
if (!function_exists('faTOen')) {

    function faTOen($string)
    {
        return strtr($string, array('۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4', '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9', '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'));
    }
}

if (!function_exists('status_badge')) {

    function status_badge($status)
    {
        switch ($status) {
            case 'pending':
                return "<span class='badge badge-warning'> معلق</span>";
                break;
            case 'active':
                return "<span class='badge badge-success'> فعال</span>";
                break;
            case 'success':
                return "<span class='badge badge-success'> موفق</span>";
                break;
            case 'later_call':
                return "<span class='badge badge-info'> موکول به زمان دیگر</span>";
                break;
            case 'faild':
                return "<span class='badge badge-danger'> تماس نا موفق</span>";
                break;
            default:
                return "<span class='badge badge-secondary'> " . $status . " </span>";
        }

    }
}

if (!function_exists('week_name_now')) {
    function week_name_now()
    {
        switch (Carbon::now()->dayName) {
            case 'شنبه':
                $today = 'shanbe';
                $e_today = 'e_shanbe';
                break;
            case 'یکشنبه':
                $today = 'yekshanbe';
                $e_today = 'e_yekshanbe';
                break;
            case 'دوشنبه':
                $today = 'doshanbe';
                $e_today = 'e_doshanbe';
                break;
            case 'سه‌شنبه':
                $today = 'seshanbe';
                $e_today = 'e_seshanbe';
                break;
            case 'چهارشنبه':
                $today = 'chaharshanbe';
                $e_today = 'e_chaharshanbe';
                break;
            case 'پنجشنبه':
                $today = 'panjshanbe';
                $e_today = 'e_panjshanbe';
                break;
            case 'جمعه':
                $today = 'jome';
                $e_today = 'e_jome';
                break;
            default:
                break;
        }

        return [$today, $e_today];
    }
}
if (!function_exists('img_resize')) {
    function img_resize($address_1, $address_2, $w, $h)
    {
        $img = Image::make($address_1);
        if ($h == 0) {
            $img->resize($w, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        } elseif ($w == 0) {
            $img->resize(null, $h, function ($constraint) {
                $constraint->aspectRatio();
            });
        } else {
            $img->resize($w, $h);
        }

        $img->save($address_2);

        Optimizer::saveAs($address_2);
        return 'ok';
    }
}

if (!function_exists('send_mail')) {
    function send_mail($email, $subject, $masage)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: <info@sedarcard.com>' . "\r\n";

        $msg = '<div style="width: 95%;min-height: 300px;margin: auto;position: relative;border: 1px solid #e1e1e1;direction: rtl">';
        $msg .= '<div style="padding:20px;text-align: justify;font-size: 18px">';
        $msg .= $masage;
        $msg .= '</div>';
        $msg .= '</div>';
        $msg .= '<div style="background: #dfedfb;height:160px;width: 100%;margin:auto;margin-top:20px;padding-top: 20px;box-shadow: 0 0 5px 1px">';
        $msg .= '<p style="text-align: center;font-weight: bold;font-size: 20px">
مجموعه سدار با تمام توان در خدمت شماست</p>';
        $msg .= '<p style="text-align: center;font-weight: bold">';
        $msg .= '<a href="http://sedarcard.com/" style="direction:ltr;color:#0000a4;font-size: 16px;border-bottom: unset!important;text-decoration: unset!important">http://sedarcard.com/</a>';
        $msg .= '</p>';
        $msg .= '<p style="text-align: center;font-weight: bold">';

        $msg .= '</p>';
        $msg .= '</div>';
        mail($email, $subject, $msg, $headers);
        return "ok";
    }

}

if (!function_exists('num_to_en')) {

    function num_to_en($data)
    {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $output = str_replace($persian, $english, $data);
        return $output;
    }
}
if (!function_exists('price')) {

    function price($str, $sep = ',')
    {
        $result = '';
        $c = 0;
        $num = strlen("$str");
        for ($i = $num - 1; $i >= 0; $i--) {
            if ($c == 3) {
                $result = $sep . $result;
                $result = $str[$i] . $result;
                $c = 0;
            } else {
                $result = $str[$i] . $result;
            }

            $c++;
        }

        return $result;
    }
}
if (!function_exists('my_gdate')) {
    function my_gdate($date)
    {
        $date = explode('-', $date);
        require_once('jdf.php');
        $date = jalali_to_gregorian($date[0], $date[1], $date[2], '-');
        return $date;
    }
}

if (!function_exists('my_jdate')) {
    function my_jdate($date, $type)
    {
        $timestamp = (strtotime($date));
        require_once('jdf.php');
        $jalali_date = jdate($type, $timestamp);
        return $jalali_date;
    }
}

if (!function_exists('file_store')) {
    function file_store($u_file, $u_path, $u_prefix)
    {
        $file = $u_file;
        $originalName = $u_file->getClientOriginalName();
        $destinationPath = $u_path;
        $extension = $file->getClientOriginalExtension();
        $fileName = $u_prefix . md5(time() . '-' . $originalName) . '.' . $extension;
        $file->move($destinationPath, $fileName);
        $f_path = $destinationPath . "" . $fileName;
        // Optimizer::saveAs($f_path);
        return $f_path;
    }
}


if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset('source/asset/' . $path, $secure);
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HttpException with the given data.
     *
     * @param \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int $code
     * @param string $message
     * @param array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort($code, $message = '', array $headers = [])
    {
        if ($code instanceof Response) {
            throw new HttpResponseException($code);
        } elseif ($code instanceof Responsable) {
            throw new HttpResponseException($code->toResponse(request()));
        }

        app()->abort($code, $message, $headers);
    }
}

if (!function_exists('abort_if')) {
    /**
     * Throw an HttpException with the given data if the given condition is true.
     *
     * @param bool $boolean
     * @param \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int $code
     * @param string $message
     * @param array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_if($boolean, $code, $message = '', array $headers = [])
    {
        if ($boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('abort_unless')) {
    /**
     * Throw an HttpException with the given data unless the given condition is true.
     *
     * @param bool $boolean
     * @param \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Support\Responsable|int $code
     * @param string $message
     * @param array $headers
     * @return void
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    function abort_unless($boolean, $code, $message = '', array $headers = [])
    {
        if (!$boolean) {
            abort($code, $message, $headers);
        }
    }
}

if (!function_exists('action')) {
    /**
     * Generate the URL to a controller action.
     *
     * @param string|array $name
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function action($name, $parameters = [], $absolute = true)
    {
        return app('url')->action($name, $parameters, $absolute);
    }
}

if (!function_exists('app')) {
    /**
     * Get the available container instance.
     *
     * @param string|null $abstract
     * @param array $parameters
     * @return mixed|\Illuminate\Contracts\Foundation\Application
     */
    function app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($abstract, $parameters);
    }
}

if (!function_exists('app_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param string $path
     * @return string
     */
    function app_path($path = '')
    {
        return app()->path($path);
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function asset($path, $secure = null)
    {
        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('auth')) {
    /**
     * Get the available auth instance.
     *
     * @param string|null $guard
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function auth($guard = null)
    {
        if (is_null($guard)) {
            return app(AuthFactory::class);
        }

        return app(AuthFactory::class)->guard($guard);
    }
}

if (!function_exists('back')) {
    /**
     * Create a new redirect response to the previous location.
     *
     * @param int $status
     * @param array $headers
     * @param mixed $fallback
     * @return \Illuminate\Http\RedirectResponse
     */
    function back($status = 302, $headers = [], $fallback = false)
    {
        return app('redirect')->back($status, $headers, $fallback);
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param string $path
     * @return string
     */
    function base_path($path = '')
    {
        return app()->basePath($path);
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value against the bcrypt algorithm.
     *
     * @param string $value
     * @param array $options
     * @return string
     */
    function bcrypt($value, $options = [])
    {
        return app('hash')->driver('bcrypt')->make($value, $options);
    }
}

if (!function_exists('broadcast')) {
    /**
     * Begin broadcasting an event.
     *
     * @param mixed|null $event
     * @return \Illuminate\Broadcasting\PendingBroadcast
     */
    function broadcast($event = null)
    {
        return app(BroadcastFactory::class)->event($event);
    }
}

if (!function_exists('cache')) {
    /**
     * Get / set the specified cache value.
     *
     * If an array is passed, we'll assume you want to put to the cache.
     *
     * @param dynamic  key|key,default|data,expiration|null
     * @return mixed|\Illuminate\Cache\CacheManager
     *
     * @throws \Exception
     */
    function cache()
    {
        $arguments = func_get_args();

        if (empty($arguments)) {
            return app('cache');
        }

        if (is_string($arguments[0])) {
            return app('cache')->get(...$arguments);
        }

        if (!is_array($arguments[0])) {
            throw new Exception(
                'When setting a value in the cache, you must pass an array of key / value pairs.'
            );
        }

        return app('cache')->put(key($arguments[0]), reset($arguments[0]), $arguments[1] ?? null);
    }
}

if (!function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('config');
        }

        if (is_array($key)) {
            return app('config')->set($key);
        }

        return app('config')->get($key, $default);
    }
}

if (!function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->configPath($path);
    }
}

if (!function_exists('cookie')) {
    /**
     * Create a new cookie instance.
     *
     * @param string|null $name
     * @param string|null $value
     * @param int $minutes
     * @param string|null $path
     * @param string|null $domain
     * @param bool|null $secure
     * @param bool $httpOnly
     * @param bool $raw
     * @param string|null $sameSite
     * @return \Illuminate\Cookie\CookieJar|\Symfony\Component\HttpFoundation\Cookie
     */
    function cookie($name = null, $value = null, $minutes = 0, $path = null, $domain = null, $secure = null, $httpOnly = true, $raw = false, $sameSite = null)
    {
        $cookie = app(CookieFactory::class);

        if (is_null($name)) {
            return $cookie;
        }

        return $cookie->make($name, $value, $minutes, $path, $domain, $secure, $httpOnly, $raw, $sameSite);
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return \Illuminate\Support\HtmlString
     */
    function csrf_field()
    {
        return new HtmlString('<input type="hidden" name="_token" value="' . csrf_token() . '">');
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if (!function_exists('database_path')) {
    /**
     * Get the database path.
     *
     * @param string $path
     * @return string
     */
    function database_path($path = '')
    {
        return app()->databasePath($path);
    }
}

if (!function_exists('decrypt')) {
    /**
     * Decrypt the given value.
     *
     * @param string $value
     * @param bool $unserialize
     * @return mixed
     */
    function decrypt($value, $unserialize = true)
    {
        return app('encrypter')->decrypt($value, $unserialize);
    }
}

if (!function_exists('dispatch')) {
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    function dispatch($job)
    {
        if ($job instanceof Closure) {
            $job = CallQueuedClosure::create($job);
        }

        return new PendingDispatch($job);
    }
}

if (!function_exists('dispatch_now')) {
    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $job
     * @param mixed $handler
     * @return mixed
     */
    function dispatch_now($job, $handler = null)
    {
        return app(Dispatcher::class)->dispatchNow($job, $handler);
    }
}

if (!function_exists('elixir')) {
    /**
     * Get the path to a versioned Elixir file.
     *
     * @param string $file
     * @param string $buildDirectory
     * @return string
     *
     * @throws \InvalidArgumentException
     *
     * @deprecated Use Laravel Mix instead.
     */
    function elixir($file, $buildDirectory = 'build')
    {
        static $manifest = [];
        static $manifestPath;

        if (empty($manifest) || $manifestPath !== $buildDirectory) {
            $path = public_path($buildDirectory . '/rev-manifest.json');

            if (file_exists($path)) {
                $manifest = json_decode(file_get_contents($path), true);
                $manifestPath = $buildDirectory;
            }
        }

        $file = ltrim($file, '/');

        if (isset($manifest[$file])) {
            return '/' . trim($buildDirectory . '/' . $manifest[$file], '/');
        }

        $unversioned = public_path($file);

        if (file_exists($unversioned)) {
            return '/' . trim($file, '/');
        }

        throw new InvalidArgumentException("File {$file} not defined in asset manifest.");
    }
}

if (!function_exists('encrypt')) {
    /**
     * Encrypt the given value.
     *
     * @param mixed $value
     * @param bool $serialize
     * @return string
     */
    function encrypt($value, $serialize = true)
    {
        return app('encrypter')->encrypt($value, $serialize);
    }
}

if (!function_exists('event')) {
    /**
     * Dispatch an event and call the listeners.
     *
     * @param string|object $event
     * @param mixed $payload
     * @param bool $halt
     * @return array|null
     */
    function event(...$args)
    {
        return app('events')->dispatch(...$args);
    }
}

if (!function_exists('factory')) {
    /**
     * Create a model factory builder for a given class and amount.
     *
     * @param string $class
     * @param int $amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factory($class, $amount = null)
    {
        $factory = app(EloquentFactory::class);

        if (isset($amount) && is_int($amount)) {
            return $factory->of($class)->times($amount);
        }

        return $factory->of($class);
    }
}

if (!function_exists('info')) {
    /**
     * Write some information to the log.
     *
     * @param string $message
     * @param array $context
     * @return void
     */
    function info($message, $context = [])
    {
        app('log')->info($message, $context);
    }
}

if (!function_exists('logger')) {
    /**
     * Log a debug message to the logs.
     *
     * @param string|null $message
     * @param array $context
     * @return \Illuminate\Log\LogManager|null
     */
    function logger($message = null, array $context = [])
    {
        if (is_null($message)) {
            return app('log');
        }

        return app('log')->debug($message, $context);
    }
}

if (!function_exists('logs')) {
    /**
     * Get a log driver instance.
     *
     * @param string|null $driver
     * @return \Illuminate\Log\LogManager|\Psr\Log\LoggerInterface
     */
    function logs($driver = null)
    {
        return $driver ? app('log')->driver($driver) : app('log');
    }
}

if (!function_exists('method_field')) {
    /**
     * Generate a form field to spoof the HTTP verb used by forms.
     *
     * @param string $method
     * @return \Illuminate\Support\HtmlString
     */
    function method_field($method)
    {
        return new HtmlString('<input type="hidden" name="_method" value="' . $method . '">');
    }
}

if (!function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return \Illuminate\Support\HtmlString|string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        return app(Mix::class)(...func_get_args());
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param \DateTimeZone|string|null $tz
     * @return \Illuminate\Support\Carbon
     */
    function now($tz = null)
    {
        return Date::now($tz);
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param string|null $key
     * @param mixed $default
     * @return mixed
     */
    function old($key = null, $default = null)
    {
        return app('request')->old($key, $default);
    }
}

if (!function_exists('policy')) {
    /**
     * Get a policy instance for a given class.
     *
     * @param object|string $class
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    function policy($class)
    {
        return app(Gate::class)->getPolicyFor($class);
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public folder.
     *
     * @param string $path
     * @return string
     */
    function public_path($path = '')
    {
        return app()->make('path.public') . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : $path);
    }
}

if (!function_exists('redirect')) {
    /**
     * Get an instance of the redirector.
     *
     * @param string|null $to
     * @param int $status
     * @param array $headers
     * @param bool|null $secure
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    function redirect($to = null, $status = 302, $headers = [], $secure = null)
    {
        if (is_null($to)) {
            return app('redirect');
        }

        return app('redirect')->to($to, $status, $headers, $secure);
    }
}

if (!function_exists('report')) {
    /**
     * Report an exception.
     *
     * @param \Throwable $exception
     * @return void
     */
    function report(Throwable $exception)
    {
        app(ExceptionHandler::class)->report($exception);
    }
}

if (!function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param array|string|null $key
     * @param mixed $default
     * @return \Illuminate\Http\Request|string|array
     */
    function request($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('request');
        }

        if (is_array($key)) {
            return app('request')->only($key);
        }

        $value = app('request')->__get($key);

        return is_null($value) ? value($default) : $value;
    }
}

if (!function_exists('rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param callable $callback
     * @param mixed $rescue
     * @param bool $report
     * @return mixed
     */
    function rescue(callable $callback, $rescue = null, $report = true)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            if ($report) {
                report($e);
            }

            return $rescue instanceof Closure ? $rescue($e) : $rescue;
        }
    }
}

if (!function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param string $name
     * @param array $parameters
     * @return mixed
     */
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (!function_exists('resource_path')) {
    /**
     * Get the path to the resources folder.
     *
     * @param string $path
     * @return string
     */
    function resource_path($path = '')
    {
        return app()->resourcePath($path);
    }
}

if (!function_exists('response')) {
    /**
     * Return a new response from the application.
     *
     * @param \Illuminate\View\View|string|array|null $content
     * @param int $status
     * @param array $headers
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    function response($content = '', $status = 200, array $headers = [])
    {
        $factory = app(ResponseFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($content, $status, $headers);
    }
}

if (!function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param mixed $parameters
     * @param bool $absolute
     * @return string
     */
    function route($name, $parameters = [], $absolute = true)
    {
        return app('url')->route($name, $parameters, $absolute);
    }
}

if (!function_exists('secure_asset')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @return string
     */
    function secure_asset($path)
    {
        return asset($path, true);
    }
}

if (!function_exists('secure_url')) {
    /**
     * Generate a HTTPS url for the application.
     *
     * @param string $path
     * @param mixed $parameters
     * @return string
     */
    function secure_url($path, $parameters = [])
    {
        return url($path, $parameters, true);
    }
}

if (!function_exists('session')) {
    /**
     * Get / set the specified session value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param array|string|null $key
     * @param mixed $default
     * @return mixed|\Illuminate\Session\Store|\Illuminate\Session\SessionManager
     */
    function session($key = null, $default = null)
    {
        if (is_null($key)) {
            return app('session');
        }

        if (is_array($key)) {
            return app('session')->put($key);
        }

        return app('session')->get($key, $default);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param string $path
     * @return string
     */
    function storage_path($path = '')
    {
        return app('path.storage') . ($path ? DIRECTORY_SEPARATOR . $path : $path);
    }
}

if (!function_exists('today')) {
    /**
     * Create a new Carbon instance for the current date.
     *
     * @param \DateTimeZone|string|null $tz
     * @return \Illuminate\Support\Carbon
     */
    function today($tz = null)
    {
        return Date::today($tz);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param string|null $key
     * @param array $replace
     * @param string|null $locale
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function trans($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return app('translator');
        }

        return app('translator')->get($key, $replace, $locale);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translates the given message based on a count.
     *
     * @param string $key
     * @param \Countable|int|array $number
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function trans_choice($key, $number, array $replace = [], $locale = null)
    {
        return app('translator')->choice($key, $number, $replace, $locale);
    }
}

if (!function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @param string|null $key
     * @param array $replace
     * @param string|null $locale
     * @return string|array|null
     */
    function __($key = null, $replace = [], $locale = null)
    {
        if (is_null($key)) {
            return $key;
        }

        return trans($key, $replace, $locale);
    }
}

if (!function_exists('url')) {
    /**
     * Generate a url for the application.
     *
     * @param string|null $path
     * @param mixed $parameters
     * @param bool|null $secure
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    function url($path = null, $parameters = [], $secure = null)
    {
        if (is_null($path)) {
            return app(UrlGenerator::class);
        }

        return app(UrlGenerator::class)->to($path, $parameters, $secure);
    }
}

if (!function_exists('validator')) {
    /**
     * Create a new Validator instance.
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Contracts\Validation\Factory
     */
    function validator(array $data = [], array $rules = [], array $messages = [], array $customAttributes = [])
    {
        $factory = app(ValidationFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
if (!function_exists('j2g')) {
    function j2g($date)
    {
        $ymd = explode('/', $date);
        require_once('jdf.php');
        if (count($ymd) == 3) {
            $jalali_date = jalali_to_gregorian($ymd[0], $ymd[1], $ymd[2]);
            return implode('-', $jalali_date);
        }
    }
}
if (!function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param string|null $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     * @param array $mergeData
     * @return \Illuminate\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = app(ViewFactory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}
