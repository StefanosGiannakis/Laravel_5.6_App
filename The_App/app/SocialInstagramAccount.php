<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialInstagramAccount extends Model
{
    protected $fillable = ['id', 'instagram_id', 'provider'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}








###or not
// handling service that will try to register the use
// login if the account already exists. Create folderapp/Services