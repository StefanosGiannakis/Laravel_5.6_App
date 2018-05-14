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
use Illuminate\Contracts\Auth\Authenticatable;


class InstagramCallbackController extends Controller
{
    
    public $Obj;
    
    public function __construct(In $InstagramController)
    {
        $this->Obj = new $InstagramController;
    }
    
   
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
        $EMAIL = $this->data->user->username . '@' . 'myapp' . '.com';
        $user = User::where('email', $EMAIL)->first();
        
        if (!$user) {
            
            $this->CallUserCreate();
        }
        // auth()->login($user);
        return $this->ifAlreadyAuser();

    }
    public function ifAlreadyAuser()// bcz laravel is already checking if simple user exists 
    {
       $this-> CustomAuthCall();
        // Auth::login($user,true);
        // dd(Auth::id());
         // I check and is not necessary to pass this as the 2nd argument
        // $remember = false;
        // Auth::loginUsingId($user->id, true);
        // Auth::login($user, $remember);
        // dd(Auth::login($user, $remember));
        return $this->rhome();
        // return $user;
    }
    public function CustomAuthCall(){
        $EMAIL = $this->data->user->username . '@' . 'myapp' . '.com';
        $user = User::where('email', $EMAIL)->first();
        Auth::login($user,true);
    }
public function rhome(){

    
    return redirect()->route('home');
}
    public function CallUserCreate()
    {
        $EMAIL = $this->data->user->username . '@' . 'myapp' . '.com';
        
        $this->createUser();
        
        $userDB = User::where('email', $EMAIL)->first();

        // Auth::loginUsingId($userDB->id, true);
        // dd(Auth::id());
        Auth::login($userDB,true);// worjing same as Auth::loginUsingId($userDB->id, true); i tested both
        return $this->CreateInstagramUser($userDB->id);

    }

    public function createUser()
    {
        $EMAIL = $this->data->user->username . '@' . 'myapp' . '.com';
        $user = new User;
       //dd( $request->all());
          //Create a user for every Instagram User registered
        $user::create([
            'name' => $this->data->user->username,
            'email' => $EMAIL,
            'password' => null,
        ]);
        
        // Auth::loginUsingId($user->id);
        
        return $user;
    }
    

    
    public function CreateInstagramUser($id)
    {
        // find user 
        $user = User::where('email', $this->data->user->username . '@' . 'myapp' . '.com');
        // $rr=Auth::guard('web')->User();
        // dd($rr);
        // Create Instagram User
        $user = new Instagram;
        $user->instagram_id = $this->data->user->id;
        $user->username = $this->data->user->username;
        $user->userId = $id; ### Stef 
        $user->fullname = $this->data->user->full_name;
        $user->website = $this->data->user->website;
        $user->bio = $this->data->user->bio;
        $user->profilePicture = $this->data->user->profile_picture;
        $user->provider = 'Instagram'; //assign the provider by hand
        $user->save();
        
         
        return Auth::loginUsingId($id, true);   // we return route instead of 
                                      // view bcz we need just logged on url bar
    }

    // public function loggedPage()
    // {
    //     return view('logged');
    // }

}
