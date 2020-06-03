<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

    use EntrustUserTrait; // add this trait to your user model
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','type','facebook_id','google_id','phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function addNew($input)

    {
        $check = static::where('facebook_id',$input['facebook_id'])->first();
        if(is_null($check)){

            return static::create($input);

        }
        return $check;

    }
    public function addNew1($input)

    {
        $check = static::where('google_id',$input['google_id'])->first();
        if(is_null($check)){

            return static::create($input);

        }
        return $check;

    }

    public function isAdmin()
    {
        return $this->admin;
    }
    
    public function favorites()
    {
    	return $this->hasMany('App\Favorite','user_id','id');
    }
    
    public function isFavorite($product_id)
    {
    	$check = Favorite::where('user_id',$this->id)->where('product_id',$product_id)->first();
    	if($check){
    		return false;
    	}else{
    		return true;
    	}
    }
}
