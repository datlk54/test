<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * get list matched User
     * @param null $user
     * @return mixed
     */
    public function getMatchUser ($user = null) {
        $whereSub = !is_null($user) ? "u.id = $user->id" : "1 = 1";
        $sub = "SELECT prefers, min(u.id) AS oldest_id FROM users AS u WHERE $whereSub GROUP BY u.prefers";
        $dataUsers = $this->select(['a.oldest_id','users.id'])
            ->join(\DB::raw("({$sub}) AS a"), function ($join) {
                $join->on('a.oldest_id', '<>', 'users.id')
                    ->on('a.prefers', '=', 'users.prefers');
            })
            ->orderBy('a.oldest_id','ASC')
            ->get();
        return $dataUsers;
    }
}
