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
use Illuminate\Support\Facades\Auth;

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

        //We need a function to check if an instagram user exists
        
        // auth()->login($user);
        return $this->createUser();

    }

    public function createUser()
    {
        $EMAIL=$this->data->user->username.'@'.'myapp'.'.com' ;
        $Obj= new User;
       //dd( $request->all());
          //Create a user for every Instagram User registered
          $Obj::create([
            'name' => $this->data->user->username,
            'email' =>$EMAIL,
            'password' => null
        ]);
           

        $userDB = User::where('email', $EMAIL)->first();
        // Log in the user 
        Auth::login($userDB);
        // $todos=User::where('email',$EMAIL)->get('id');    
        // $userDB = DB::table('users')
        //         ->whereColumn([
        //             ['email', '=', $EMAIL],
        //         ])->get();
        // dd($userDB->id);
        // dd(Auth::check());
        return $this->CreateInstagramUser($userDB->id);

    }                                        

    public function CreateInstagramUser($id)
    {
        // find user 
        $user = User::where('email',$this->data->user->username.'@'.'myapp'.'.com');
        // $rr=Auth::guard('web')->User();
        // dd($rr);
        // Create Instagram User
        $user= new Instagram;
        $user->instagram_id=$this->data->user->id;
        $user->username= $this->data->user->username;
        $user->userId= $id; ### Stef 
        $user->fullname=$this->data->user->full_name;
        $user->website=$this->data->user->website;
        $user->bio=$this->data->user->bio;
        $user->profilePicture= $this->data->user->profile_picture;
        $user->provider='Instagram'; //assign the provider by hand
        $user->save();
        
        // Session::flash('success','Your user Created');
        // return redirect()->back();
        return redirect()->route('home');   // we return route instead of 
                                             // view bcz we need just logged on url bar
    }

    public function loggedPage()
    {
        return view('logged'); 
    }                           

}
