<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Auth;
use DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Login Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /*public function username()
    {
        return 'username';

    }*/

    public function username(){
        $login = request()->input('username');
        $password = request()->input('password');
        $users = DB::table('users')
            ->where('email',  $login)
            ->orWhere('username', $login)
            ->first();

        if($users){
            if(!Hash::check($password, $users->password)){
                $error = (object) array('password' => 'password');
                throw ValidationException::withMessages([$error->password => ['Incorrect password']]);
            }
        }

        if($users && ($users->group != "admin")){
            $error = (object) array('username' => 'username');
            throw ValidationException::withMessages(['username' => ["You don't have permission"]]);
        }

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }
    protected function sendFailedLoginResponse(Request $request)
    {
        $request->session()->put('login_error', trans('auth.failed'));
        throw ValidationException::withMessages(
            [
                'error' => [trans('auth.failed')],
            ]
        );
    }

     public function logout(){
        auth()->logout();
        return redirect('/admin');
    }
}
