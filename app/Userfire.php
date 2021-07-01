<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Userfire extends Model
{
    protected $table = 'userfire';
    protected $fillable = [
        'uid',
        'displayName',
        'email',
        'providerId'
    ];
}
