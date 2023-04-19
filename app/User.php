<?php

namespace App;

use App\Model\Code;
use App\Model\Sms;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\File;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'mobile',
        'password',
        'mobile_verified',
        'code_id',
        'status',
    ];

    static $allSubCategoryUsers = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }


    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function notifications()
    {
        return $this->hasMany('App\Model\Notification', 'user_id');
    }
    public function photo()
    {
        return $this->morphOne('App\Model\Photo', 'pictures');
    }

    public function file()
    {
        return $this->morphOne('App\Model\Filep', 'files');
    }

    public function state()
    {
        return $this->belongsTo('App\Model\ProvinceCity', 'state_id');
    }

    public function potentials()
    {
        return $this->belongsToMany('App\Model\Potential','user_potential')->withPivot('sub_id');
    }

    public function my_potentials()
    {
        return $this->hasMany('App\Model\Potential','user_id');
    }
    public function my_potentials1()
    {
        return $this->hasMany('App\Model\Potential','user_id')->whereNotIn('present_ta_peresent',[null,'خرید اولیه انجام نشده']);
    }

    public function potential()
    {
        return $this->belongsTo('App\Model\Potential','name');
    }

    public function targets() {
        return $this->hasMany('App\Model\Target','user_id');
    }

    public function active_target() {
        $items  = $this->targets();
        if ($items->count()) {
            
            $targets = $items->where('date','>',start_en());
            if ($targets->count()) return $targets->first();

            $item = $items->first();
            if($item){
                $item->level    = null;
                $item->personal = null;
                $item->network  = null;
                $item->update();
            }
           

            return $this->targets()->first();
        }
        return null;
    }
    
    public function code()
    {
        return $this->hasMany('App\Model\Code', 'user_id');
    }

    public function latestCode()
    {
        return Code::where('user_id', $this->id)->latest('created_at');
    }

    public function latest10MinCode()
    {
        $fiveMinAgo = Carbon::now()->subMinute(10)->toDateTimeString();
        return Code::where('user_id', $this->id)->where('created_at', '>', $fiveMinAgo)->first();
    }

    public function latestCodeToday()
    {
        $fiveMinAgo = Carbon::now()->subDay()->toDateTimeString();
        return Code::where('user_id', $this->id)->where('created_at', '>', $fiveMinAgo)->first();
    }

    public function city()
    {
        return $this->belongsTo('App\Model\ProvinceCity', 'city_id');
    }

    public function reagent()
    {
        return $this->belongsTo('App\User', 'reagent_id');
    }
    //old
//    public function reagent()
//    {
//        return $this->hasOne('App\User', 'reagent_id', 'reagent_code');
//    }
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
    public function from_customer()
    {
        return $this->belongsTo('App\User', 'from_customer_id', 'id');
    }

    public function from_customers()
    {
        return $this->hasMany('App\User', 'from_customer_id', 'id');
    }

    public function redirect_page($link, $msg)
    {
        return redirect($link)->with('call_message', $msg);
    }

    public function call_pending()
    {
        $call_r = Model\CallRequest::where('status', 'pending')->where('consultant_id', auth()->user()->id)->first();
        if ($call_r)
            return $call_r;
        else
            return null;
    }

    public function call_doing()
    {
        $call_r = Model\CallRequest::where('status', 'doing')->where('user', auth()->user()->id)->first();
        if ($call_r)
            return $call_r;
        else
            return null;
    }


    public function sendMobileVerifiedCode()
    {
        $random_code = Code::randomUniqueCode();

        $code = new Code();
        $code->user_id = auth()->user()->id;
        $code->code = $random_code;
        $code->save();

        if ($code) {

            $message = "کد تایید حساب کاربری
             code:$random_code";
            $response = Sms::SendSms($message, $this->mobile);

            if (substr($response, '0', '7') == 'success') {
                session()->put('verification_code', true);
                return $code;
            }
        }

        return false;
    }

    public static function getFirstLevelUsers($id = null)
    {
        $id = is_null($id) ? auth()->user()->id : $id;

        $items = User::whereHas('roles');
        if (auth()->user()->hasRole(['مدیر'])) {
            $items = $items->whereIn('reagent_id', [$id, null]);
        } else {
            $items = $items->where('reagent_id', $id);
        }

        return $items->orderByDesc('id');
    }


    public static function getAllUsersAndCustomers()
    {
        $user = auth()->user();
        $users = collect([]);

        if ($user->hasRole('مدیر')) {
            $users =  User::where('id','!=',auth()->user()->id);
        }elseif($user->hasRole('کاربر')){
            $users =  User::where('reagent_id',$user->id)->orWhere('creator_id',$user->id);
        }elseif($user->hasRole('نماینده مستقل')){
            $usersId = [];
            $usersInfos = self::getSubCategoryUsersFromId($user->id,);
            $customersId = self::sub_customers_for($user->id)->get();

            if ($usersInfos) {
                $usersId = $usersInfos->pluck('id')->toArray();
            }

            if ($customersId && $customersId->count() > 0) {
                $usersId =  array_merge($usersId, $customersId->pluck('id')->toArray());
            }


            $users = !empty($usersId) ? User::whereIn('id', $usersId) : collect([]);
        }

        return $users;
    }



    public static function getAllUsers($role = null)
    {
        if (is_null($role)) {
            $users = User::whereHas('roles')->orderByDesc('id');
        } else {
            return User::whereHas('roles', function ($query) use ($role) {
                return $query->where('name', $role);
            })->orderByDesc('id');

        }

        if (!Auth::user()->hasRole(['مدیر','نماینده مستقل'])) {
            $users = $users->where('creator_id', auth()->user()->id);
        }

        if (!is_null($users) && $users->count() > 0) {
            $usersId = self::getSubCategoryUsersIdFromId(auth()->user()->id, $ids = null);
            $users = !empty($usersId) ? User::whereIn('id', $usersId) : collect([]);
        }

        return $users;
    }

    public static function getSubCategoryUsersIdFromId($id, $ids = null, $level = null)
    {
        $users = self::getSubCategoryUsersFromId($id, $ids = null, $level = null);

        if ($users && $users->count() > 0) {
            return $users->sortBy('level')->pluck('id')->toArray();
        }

        return [];
    }

    public function getSubUsers()
    {
        return self::getSubCategoryUsersFromId($this->id);
    }
    public function getSubCustomers()
    {
        return User::whereDoesntHave('roles')->where('creator_id', $this->id )->orWhere('from_customer_id',$this->id)->orderByDesc('created_at')->get();
    }

    //پیدا کردن سطح لول کاربر با کسی که الان اهراز هویت شده است
    public function getUserLevelWithMe()
    {
      $subUser =   self::getSubCategoryUsersFromId(auth()->user()->id)->where('id',$this->id)->first();
      if($subUser)
          return $subUser->level;

        return null;
    }

    //تمام کاربران زیر مجموعه یک شناسه را به ترتیب سطح بر میگرداند ( بدست میاورد)
    public static function getSubCategoryUsersFromId($id, $ids = null, $level = null)
    {
        $subCategoryUsers = self::$allSubCategoryUsers;

        if (is_null($level)) {
            $level = 1;
        }

        if (is_null($ids)) {
            $users = User::whereHas('roles')->where('reagent_id', $id)->get();

            $users->each(function ($user) use ($level) {
                $user->level = $level;
            });

            $level += 1;
            self::$allSubCategoryUsers = $users;

            if ($users && $users->count() > 0) {
                self::getSubCategoryUsersFromId($id, $users->pluck('id')->toArray(), $level);
            }

        } else {
            $users = User::whereHas('roles')->whereIn('reagent_id', $ids)->get();

            $users->each(function ($user) use ($level) {
                $user->level = $level;
            });
            $level += 1;

            self::$allSubCategoryUsers = $subCategoryUsers->merge($users);

            if ($users && $users->count() > 0) {
                self::getSubCategoryUsersFromId($id, $users->pluck('id')->toArray(), $level);
            }
        }

        return self::$allSubCategoryUsers;
    }

    //کاربران زیرمجموعه
    public function sub_users()
    {
        return $this->hasMany('App\User', 'reagent_id');
    }

    public static function sub_users_for($id)
    {
        return User::whereHas('roles')->where('reagent_id', $id)->orderByDesc('created_at')->with('sub_users', 'sub_customers')->get();
    }

    //مشتریان زیر دسته
    public function sub_customers()
    {
        return $this->hasMany('App\User', 'creator_id')->whereDoesntHave('roles');
    }

    public static function sub_customers_for($id = null)
    {
        return User::whereDoesntHave('roles')->where('creator_id', is_null($id) ? auth()->user()->id : $id )->orderByDesc('created_at');
    }

    public static function getAllCustomer($role = null)
    {
        return User::whereDoesntHave('roles')->orderByDesc('id');
    }

    public static function getFirstLevelMyCustomers()
    {
        return User::whereDoesntHave('roles')->where('creator_id', auth()->user()->id)->orderByDesc('id');
    }

    //todo
    //should return all customer under me
    public static function getMySubCategoryCustomers()
    {
        $auth = auth()->user();

        if ($auth->hasRole('مدیر')) {
            return User::whereDoesntHave('roles')->orderByDesc('id');
        } elseif ($auth->hasRole('نماینده مستقل')) {
            $subUsers = self::getSubCategoryUsersFromId(auth()->user()->id);

            $ids = $subUsers ? $subUsers->pluck('id')->toArray() : [];
            array_push($ids, auth()->user()->id);

            return User::whereDoesntHave('roles')->whereIn('creator_id', $ids)->orderByDesc('id');
        }else{
            return User::whereDoesntHave('roles')->where('creator_id', auth()->user()->id)->orderByDesc('id');
        }
    }


    public function getUserTypeBadgeAttribute()
    {
        if ($this->roles()->count() > 0) {
            return "<span class='badge badge-success'> ".$this->roles->first()->name."</span>";
        }
        return "<span class='badge badge-info'> مشتری</span>";
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->photo()->get()
                ->each(function ($photo) {
                    $path = $photo->path;
                    File::delete($path);
                    $photo->delete();
                });
            $item->file()->get()
                ->each(function ($file) {
                    $path = $file->path;
                    File::delete($path);
                    $file->delete();
                });
        });
    }


//    public function city()
//    {
//        return $this->belongsTo('App\Model\Code', 'city_id');
//    }


































    public function customers() {
        return $this->hasMany('App\Model\Customer', 'user_id');
    }

}

