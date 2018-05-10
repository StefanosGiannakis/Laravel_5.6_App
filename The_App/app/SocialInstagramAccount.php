<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialInstagramAccount extends Model
{
    protected $fillable = ['id', 'instagram_id', 'provider'];

    public function user()
    {
        //return $this->belongsTo('App\User','user_id');
        return $this->belongsTo('App\User', 'id');
    }
}








###or not
// handling service that will try to register the use
// login if the account already exists. Create folderapp/Services