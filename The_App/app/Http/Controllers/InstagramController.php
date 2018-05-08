<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Instagram\Settings as Settings;

class InstagramController extends Controller
{

    public $settings=array(
        "clientID"=>"ad138f70217140f69e9873bdcc397c9e",
        "clientSecret"=>"ef929ec7de3742d7b40dbe3cb5a3b553",
        "redirectURI"=>"http://localhost/Instagram_App_github/Laravel_5.6_App/The_App/public/callback"
    );
    public $clientID = "";
    public $clientSecret = "";
    public $redirectURI = "";

    public function __construct($settings = array())
    {
        $this->clientID = $this->settings['clientID'];
        $this->clientSecret = $this->settings['clientSecret'];
        $this->redirectURI = $this->settings['redirectURI'];
    }

    public function getAccesTokenAndUserDetails($code)
    {
        $postFields = array(
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'authorization_code',
            'redirect_uri' => $this->redirectURI,
            'code' => $code
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

        return json_decode($response, true); //true for associative array
    }

    public function getLoginURL()
    {
        return "https://api.instagram.com/oauth/authorize/?client_id=$this->clientID&redirect_uri=$this->redirectURI&response_type=code";
    }


    public function redirectTo()
    {
        $url=$this->getLoginURL();
        // return $url;
        return redirect()->away($url);
    }
}
$sets= new InstagramController();
$InstagramObj= new InstagramController($sets->settings);


