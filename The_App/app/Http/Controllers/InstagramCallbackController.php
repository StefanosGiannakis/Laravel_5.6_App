<?php
namespace App\Http\Controllers;

session_start();
if (isset($_GET['error'])) {
    header(string, 'Location: login.php');
    exit();
}

use App\Http\Controllers\InstagramController as In;
use Illuminate\Http\Request;
use App\User;
use App\SocialInstagramAccount as Instagram;


class InstagramCallbackController extends Controller
{
    public $Obj;
    public function __construct(In $InstagramController)
    {
        $this->Obj = new $InstagramController;
    }   

    // public function __construct($InstagramObj)
    // {
    //    $this->Instagram = $InstagramObj;
    // }
    public function callback()
    {
        $this->data = $this->Obj->getAccesTokenAndUserDetails($_GET['code']);

        $this->data = json_decode(json_encode($this->data));

        $_SESSION['loggedIn'] = 1; // we need to verify on index.php
        $_SESSION['accessToken'] = $this->data->access_token;
        $_SESSION['id'] = $this->data->user->id;
        $_SESSION['username'] = $this->data->user->username;
        $_SESSION['bio'] = $this->data->user->bio;
        $_SESSION['Website'] = $this->data->user->website;
        $_SESSION['fullname'] = $this->data->user->full_name;
        $_SESSION['profilePicture'] = $this->data->user->profile_picture;


        
        // auth()->login($user);
        return $this->create();

    }

    public function create()
    {
       //dd( $request->all());
        
        $user= new Instagram;
        $user->instagram_id=$this->data->user->id;
        $user->username= $this->data->user->username;
        $user->fullname=$this->data->user->full_name;
        $user->website=$this->data->user->website;
        $user->bio=$this->data->user->bio;
        $user->profilePicture= $this->data->user->profile_picture;
        $user->provider='Instagram'; //assign the provider by hand
        $user->save();

        // Session::flash('success','Your user Created');
        // return redirect()->back();
        return $this->redirectTo();   
    }

    public function redirectTo()
    {
        return view('logged');
    }

}
