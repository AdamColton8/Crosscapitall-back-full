<?php

namespace App\Http\Controllers\Auth;

use DB;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use App\Models\WalletUser;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function register(Request $request)
    {
        $ref_link = $request->get('ref_link');
        if (isset($ref_link))
        {
            $searchingInviter = DB::table('users')->where('ref_link', '=', $request->get('ref_link'))->first();

            if ($searchingInviter)
                $inviter = $searchingInviter->id;
            else $inviter = 0;
        } else {
            $inviter = 0;
        }

        $this->validate($request, [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'question' => 'required|string|max:555',
            'answer' => 'required|string|max:555',
        ]);

        $user = new User();
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->username = $request->get('username');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->status = 'active';
        $user->parent_id = $inviter;
        $user->group = 'user';
        $user->question = $request->get('question');
        $user->answer = $request->get('answer');
        $user->save();
        
        
        for($i=0; $i<=2; $i++){
            $userWallet = new WalletUser();
            $userWallet->user_id = $user->id;
            if($i == 0){
                $userWallet->payment_alias = "perfect_money";
                $userWallet->name = "Perfect Money";
            }else if($i == 1){ 
                $userWallet->payment_alias = "advanced_cash";
                $userWallet->name = "Advanced cash";
            }else{
                $userWallet->payment_alias = "payeer";
                $userWallet->name = "Payeer";
            }
            $userWallet->wallet_key = "";
            $userWallet->created_at = (new \DateTime());
            $userWallet->updated_at = (new \DateTime());
            $userWallet->save();
        }

        return response()->json('ок');
    }
}
?>