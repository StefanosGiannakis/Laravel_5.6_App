<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function getSettings(){
    
    
    return  $settings=array(
        "clientID"=>"ad138f70217140f69e9873bdcc397c9e",
        "clientSecret"=>"ef929ec7de3742d7b40dbe3cb5a3b553",
        "redirectURI"=>"http://localhost/Laravel-echo/Laravel-echo_Vue.js/app/PHP_Instagram_login/callback.php"
        );
    }
    
}
