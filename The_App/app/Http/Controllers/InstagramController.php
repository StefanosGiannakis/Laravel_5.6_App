<?php

namespace App\Http\Controllers;
// include "settings.php";
use App\Http\Controllers\SettingsController;
use Illuminate\Http\Request;
$settings=getSettings();
$Instagram= new InstagramAPI($settings);

class InstagramController extends Controller
{

       public $clientID="";
       public $clientSecret="";
       public $redirectURI="";

       public function __construct($settings= array())
       {
           $this->clientID=$settings['clientID'];
           $this->clientSecret=$settings['clientSecret'];
           $this->redirectURI=$settings['redirectURI'];
       }

       public function getAccesTokenAndUserDetails($code)
       {
            $postFields = array(
                'client_id'=>$this->clientID,
                'client_secret'=>$this->clientSecret,
                'grant_type'=>'authorization_code',
                'redirect_uri'=>$this->redirectURI,
                'code'=>$code
            );

        // create curl resource 
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://api.instagram.com/oauth/access_token"); 
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields); 
        // $response contains the output string 
        $response = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);    

        return json_decode($response,true); //true for associative array
       }

       public function getLoginURL()
       {
           
           $url="https://api.instagram.com/oauth/authorize/?client_id=$this->clientID&redirect_uri=$this->redirectURI&response_type=code";
        
        return redirect()->away($url);
       }
       public function returnWelcome()
       {
           return view('welcome');
       }


}
