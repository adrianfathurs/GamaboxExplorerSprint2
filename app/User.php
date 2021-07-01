<?php

namespace App;

use Illuminate\Support\Facades\Auth; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    // ================================= Development For User Friendship =========================================================================== //

    //======================== functions to get friends attribute ==============================
    /**
     * The roles that belong to the user.
     */
    public function allFriendsOfThisUser()
    {
        return $this->belongsToMany('App\User','App\Friendship','first_user','second_user')
        ->withTimestamps();
    }
    
    public function allThisUserFriendOf()
    {
        return $this->belongsToMany('App\User','App\Friendship','second_user','first_user')
        ->withTimestamps();
    }

    // friendship that this user started
    protected function friendsOfThisUser()
	{
		return $this->belongsToMany('App\User','App\Friendship', 'first_user', 'second_user')
		->withPivot('status')
		->wherePivot('status', 'confirmed');
    }
    
    // friendship that this user was asked for
	protected function thisUserFriendOf()
	{
		return $this->belongsToMany('App\User', 'App\Friendship', 'second_user', 'first_user')
		->withPivot('status')
		->wherePivot('status', 'confirmed');
    }
    
    // accessor allowing you call $user->friends
	public function getFriendsAttribute()
	{
		if ( ! array_key_exists('friends', $this->relations)) $this->loadFriends();
		return $this->getRelation('friends');
	}
 
	protected function loadFriends()
	{
		if ( ! array_key_exists('friends', $this->relations))
		{
            $friends = $this->mergeFriends();
            $this->setRelation('friends', $friends);
        }
    }
 
	protected function mergeFriends()
	{
		if($temp = $this->friendsOfThisUser)
            return $temp->merge($this->thisUserFriendOf);
		else
            return $this->thisUserFriendOf;
	}
    
    public function friend_requests()
    {
        return $this->hasMany('App\Friendship', 'second_user')
        ->where('status', 'pending');
    }

    //======================== end functions to get friends attribute ==============================

    //====================== functions to get blocked_friends attribute ============================

	// friendship that this user started but now blocked
	protected function friendsOfThisUserBlocked()
	{
		return $this->belongsToMany('App\User', 'App\Friendship', 'first_user', 'second_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'first_user');
	}

	// friendship that this user was asked for but now blocked
	protected function thisUserFriendOfBlocked()
	{
		return $this->belongsToMany('App\User', 'App\Friendship', 'second_user', 'first_user')
					->withPivot('status', 'acted_user')
					->wherePivot('status', 'blocked')
					->wherePivot('acted_user', 'second_user');
	}

	// accessor allowing you call $user->blocked_friends
	public function getBlockedFriendsAttribute()
	{
		if ( ! array_key_exists('blocked_friends', $this->relations)) $this->loadBlockedFriends();
			return $this->getRelation('blocked_friends');
	}

	protected function loadBlockedFriends()
	{
		if ( ! array_key_exists('blocked_friends', $this->relations))
		{
			$friends = $this->mergeBlockedFriends();
			$this->setRelation('blocked_friends', $friends);
		}
	}

	protected function mergeBlockedFriends()
	{
		if($temp = $this->friendsOfThisUserBlocked)
			return $temp->merge($this->thisUserFriendOfBlocked);
		else
			return $this->thisUserFriendOfBlocked;
	}
    // ======================================= end functions to get block_friends attribute =========

    public function addFriend(User $user)
	{
        $current_user = Auth::guard('api')->user();
        $id = $current_user->id;
        $attributes = ['acted_user' => $id, 'status' => 'pending'];
        
		$current_user->allFriendsOfThisUser()->attach($user->id, $attributes);
    }
    
    public function acceptFriend(User $user)
	{
        // $current_user = User::findOrFail(3);
        $current_user = Auth::guard('api')->user();
        $id = $current_user->id;
        $attributes = ['acted_user' => $id, 'status' => 'confirmed'];

        $current_user->allThisUserFriendOf()->updateExistingPivot($user->id, $attributes);
	}

    public function blockFriend(User $user)
	{
        // $current_user = User::findOrFail(3);
        $current_user = Auth::guard('api')->user();
        $id = $current_user->id;
        $attributes = ['acted_user' => $id, 'status' => 'blocked'];

        $exists = Friendship::whereFirstUser($id)
            ->whereSecondUser($user->id)
            ->count() > 0;

        if($exists){
            $current_user->allFriendsOfThisUser()->updateExistingPivot($user->id, $attributes);
        }else{
            $current_user->allThisUserFriendOf()->updateExistingPivot($user->id, $attributes);            
        }
    }
    
	public function removeFriend(User $user)
	{
        $current_user = Auth::guard('api')->user();
		$current_user->allFriendsOfThisUser()->detach($user->id);
    }
    
    public function testing()
    {
        $user = User::findOrFail(2);
        $add = $this->blockFriend($user);
        return $add;
    }
}