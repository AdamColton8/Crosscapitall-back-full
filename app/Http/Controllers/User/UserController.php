<?php

namespace App\Http\Controllers\User;

use App\Models\Operation;
use App\Models\UserPlan;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Wallet;
use DateInterval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Input, Redirect;
use App\Models\User;
use DB;
use Auth;
use Hash;
use phpseclib\Crypt\Random;
use Response;

class UserController extends Controller
{
    //return data user
    public function user()
    {
        $user = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'username', 'email', 'ref_link')
            ->where('users.id', Auth::id())
            ->first();
            
        $wallet = DB::table('wallet_users')->select('name', 'payment_alias', 'wallet_key', 'wallet_name', 'active')
            ->where('user_id', Auth::id())
            ->get();
        $user->wallet = $wallet;   
        return response()->json($user);
    }

    //return wallets of user
    public function wallets()
    {
        $wallets = Wallet::with('payment')
            ->select('balance', 'payment_alias')
            ->where('user_id', Auth::id())
            ->get();

        foreach ($wallets as $wallet)
        {
            if ($wallet->balance > 0)
                $wallet->balance = Wallet::convertToDollar($wallet->balance);
        }

        $wallets = $wallets->map(function ($item)
        {
            //unset($item['user_id'], $item['wallet_id'], $item['commission']);
            $item->payment = $item->payment->title;
            $item = $item->toArray();
            $item['payment'] = $item['payment']['title'];
            return $item;
        });

        return response()->json($wallets);
    }

    public function operations()
    {
        $operations = Operation::with('payment')
            ->where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        foreach ($operations as $operation) {
            if ($operation->sum > 0)
                $operation->sum = Wallet::convertToDollar($operation->sum);
        }

        return response()->json($operations);
    }

    //list of referrals
    public function referrals()
    {
        $referrals = DB::table('users')
            ->select('id', 'first_name', 'last_name', 'username', 'email', 'created_at')
            ->where('path', 'LIKE', Auth::user()->path . '.%')
            ->get();

        return response()->json($referrals);
    }

    public function deposits()
    {
        $plans = DB::table('user_plans')
            ->where('user_id', Auth::id())
            ->get()
            ->groupBy('plan_id');

        foreach ($plans as $plan) {
            foreach ($plan as $item) {
                if ($item->available >= 0)
                    $item->initial_money = Wallet::convertToDollar($item->initial_money);
                    $item->avilable = Wallet::convertToDollar($item->available);
            }
        }

        return response()->json($plans);
    }

    public function withdraw(Request $request)
    {
        //Getting post data
        $user_id = \Auth::id();
        $balance = Wallet::convertToCent($request->get('balance'));
        $payment_alias = $request->get('payment_alias');

        $wallet = Wallet::getUserWallet($user_id, $payment_alias);
        $commission = Payment::getCommission($payment_alias, $balance);

        if ($balance <= $wallet->balance)
        {
            $status = Operation::STATUS_PENDING;
            $typeOperation = Operation::TYPE_WITHDRAW;
            Wallet::deductMoney($user_id, $payment_alias, $balance);
            Operation::newOperation($user_id, $wallet->id, $payment_alias, $balance, $commission, $typeOperation, $status);

            $operations = Operation::with('payment')
                ->orderBy('user_id', 'desc')
                ->first();

            $operations->sum = Wallet::convertToDollar($operations->sum);
            $operations->payment = $operations->payment->title;

            return response()->json($operations);
        } else {
            return response(['errors' => ['There is not enough money on your wallet']], 405);
        }
    }

    public function cancelWithdraw(Request $request)
    {
        $operation = Operation::findOrFail($request->get('id'));
        if ($operation && $operation->type === Operation::TYPE_WITHDRAW)
        {
            $operation->status = Operation::STATUS_CANCELED;
            $operation->comment = Operation::COMMENT_CANCELED;

            Wallet::accrualsMoney($operation->user_id, $operation->payment_alias, $operation->sum);

            $operation->save();

            return response()->json('The request has been successfully canceled');
        } else return response()->json('Something is wrong!');
    }

    public function payFromAccount(Request $request)
    {
        $payment_alias = $request->get('payment_alias');
        $initialMoney = $request->get('initial_money');
        $plan_id = $request->get('plan_id');

        $plan = Plan::where('id', $plan_id)->first();
        $wallet = Wallet::getUserWallet(Auth::id(), $payment_alias);

        //converting to cents
        $initialMoney = Wallet::convertToCent($initialMoney);

        if ($plan->id === 1)
            $sum = (int) $initialMoney + Wallet::getPercent($initialMoney, $plan->percent);
        else
            $sum = (int)($initialMoney * $plan->percent) / $plan->term;

        $dateEnd = new \DateTime();
        date_add($dateEnd, date_interval_create_from_date_string($plan->term.' days'));

        if ($wallet)
        {
            if ($wallet->balance >= $initialMoney)
            {
                $userPlan = new UserPlan();
                $userPlan->user_id = Auth::id();
                $userPlan->wallet_id = $wallet->id;
                $userPlan->plan_id = $plan_id;
                $userPlan->initial_money = $initialMoney;
                $userPlan->accumulation = $sum / $plan->term;
                $userPlan->earned = 0;
                $userPlan->date = (new \DateTime())->format("Y-m-d");
                $userPlan->status = UserPlan::STATUS_ACTIVE;
                $userPlan->available = $sum;
                $userPlan->date_end = $dateEnd->format("Y-m-d");

                Wallet::deductMoney(Auth::id(), $payment_alias, $initialMoney);

                $userPlan->save();

                //add money to inviters
                Wallet::accrualsReferralsBonus(Auth::id(), $initialMoney);

                $userPlan->initial_money = Wallet::convertToDollar($initialMoney);
                return response()->json($userPlan);
            } else {
                return response()->json('There is not enough money on your account');
            }
        } else return response()->json('No wallets found...');
    }
    public function changeprofile(){
        $user = file_get_contents("php://input"); 
		$user = json_decode($user, true);
        $select_user = DB::table('users')
            ->select( 'username', 'email')
            ->where('users.id', Auth::id())
            ->first(); 
        if($user["username"] == $select_user->username && $user["email"] == $select_user->email){
            $valid = Validator::make($user,[
                'first_name' => 'required', 
                'last_name' => 'required']);
        }else if(isset($user["username"]) && $user["username"] == $select_user->username){
            $valid = Validator::make($user,[
                'first_name' => 'required', 
                'last_name' => 'required',
                'email' => 'required|email|unique:users']);
        }else if(isset($user["email"]) && $user["email"] == $select_user->email){
            $valid = Validator::make($user,[
                'first_name' => 'required', 
                'last_name' => 'required',
                'username' => 'required|unique:users']);
        }else{
            $valid = Validator::make($user,[
                'first_name' => 'required', 
                'last_name' => 'required',
                'username' => 'required|unique:users',
                'email' => 'required|email|unique:users']);
        }
        
    	if($valid->fails())
		{
			return Response::make(['error' => $valid->errors()], 406);
		}else{
            
            $user = DB::table('users')->where('id', Auth::id())
                                      ->update(['first_name' => $user['first_name'], 'last_name' => $user['last_name'], 'username' => $user['username'], 'remember_token' => "", 'email' => $user['email']]);
            return response()->json('OK');
        }                         
    }
    
    public function changepassword(){
        $user = file_get_contents("php://input"); 
		$user = json_decode($user, true); 
        $valid = Validator::make($user,[
                'old_password' => 'required|string|min:6', 
                'password' => 'required|string|min:6']);
		if($valid->fails()){
			return Response::make(['error' => $valid->errors()], 406);
		}else{
            $getuser = DB::table('users')->select('password')->where('id', Auth::id())->first();
            if(Hash::check($user['old_password'], $getuser->password)){
                $user = DB::table('users')->where('id', Auth::id())
                ->update(['password' => bcrypt($user['password']), 'remember_token' => ""]);
                return response()->json('OK');
            }else{
                return response()->json('Not a valid old password');
            }
        }
    }
}
