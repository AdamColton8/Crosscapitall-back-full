<?php

namespace App\Http\Controllers\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator, Input, Redirect;
use DB;
use Response;
use App\Models\WalletUser;
use Auth;


class WalletController extends Controller
{
     public function changewallet(){
        $wallet = file_get_contents("php://input"); 
		$wallet = json_decode($wallet, true);
        if($wallet == ""){$wallet = [];}
        $valid = Validator::make($wallet,[
                'wallet_key' => 'required',
                'wallet_name' => 'required',
                'payment_alias' => 'required']);
        if($valid->fails())
		{
	        return Response::make(['error' => $valid->errors()], 406);
		}else{
		    $userWallet = WalletUser::where(['user_id' => Auth::id(), 'payment_alias' => $wallet['payment_alias']])->firstOrFail();
            $userWallet->wallet_key = $wallet['wallet_key'];
            $userWallet->wallet_name = $wallet['wallet_name'];
            $userWallet->updated_at = (new \DateTime());
            $userWallet->save();
            return Response::json(array('success' => "OK", 'wallet' => $userWallet), 200);
        }  
     }
     
     public function gettransactional(){
        $get_operation = [];
        $get_operations = DB::table('operations')
            ->join('users', 'users.id', '=', 'operations.user_id')
            ->SelectRaw(DB::getTablePrefix().'operations.*,'.DB::getTablePrefix().'users.username')
            ->orderBy('operations.id', 'desc')->take(3)->get();
        $count_get_operations = count($get_operations);
        $need_face_operations = 20 - $count_get_operations;
        $get_operations_face = DB::table('operations_face')
            ->join('users', 'users.id', '=', 'operations_face.user_id')
            ->SelectRaw(DB::getTablePrefix().'operations_face.*,'.DB::getTablePrefix().'users.username')
            ->orderBy('operations_face.id', 'desc')->take($need_face_operations)->get();
        $get_operation["operations"] = $get_operations;
        $get_operation["operations_face"] = $get_operations_face; 
        
        $get_user_plans = DB::table('user_plans')
            ->join('users', 'users.id', '=', 'user_plans.user_id')
            ->join('wallets', 'wallets.id', '=', 'user_plans.wallet_id')
            ->SelectRaw(DB::getTablePrefix().'user_plans.*,'.DB::getTablePrefix().'users.username,'.DB::getTablePrefix().'wallets.payment_alias')
            ->orderBy('user_plans.id', 'desc')->take(3)->get();
        $count_get_user_plans = count($get_user_plans);
        $need_user_plans = 20 - $count_get_user_plans;
        $get_user_plans_face = DB::table('user_plans_face')
            ->join('users', 'users.id', '=', 'user_plans_face.user_id')
            ->join('wallets', 'wallets.id', '=', 'user_plans_face.wallet_id')
            ->SelectRaw(DB::getTablePrefix().'user_plans_face.*,'.DB::getTablePrefix().'users.username,'.DB::getTablePrefix().'wallets.payment_alias')
            ->orderBy('user_plans_face.id', 'desc')->take($need_user_plans)->get();
        $get_operation["user_plans"] = $get_user_plans;
        $get_operation["user_plans_face"] = $get_user_plans_face;  
        
        return response()->json($get_operation);   
    }
    public function statistics(){
        $date = DB::table('operations')->SelectRaw('date')->orderBy('operations.date', 'asc')->first();
        $date = new \DateTime($date->date); 
        $today = new \DateTime();
        $interval = $date->diff($today);
        $statisctic['started']  = $date->format('d M Y');
        $statisctic['running_days'] = (int)$interval->format('%a');
        $total_deposited_operations = DB::table('operations')->sum('sum');
        $total_deposited_operations_face = DB::table('operations_face')->sum('sum');
        $statisctic['total_deposited'] = $total_deposited_operations + $total_deposited_operations_face;
        $total_withdraw_operations = DB::table('user_plans')->sum('available');
        $total_withdraw_operations_face = DB::table('user_plans_face')->sum('available');
        $statisctic['total_withdraw'] = $total_withdraw_operations + $total_withdraw_operations_face;  
        $statisctic['total_accounts'] = DB::table('users')->count();
        $statisctic['today_deposited'] = DB::table('operations')->where('date', '=', $today)->sum('sum');
        $last_deposited = DB::table('operations')->SelectRaw('sum')->orderBy('operations.id', 'desc')->first();
        $statisctic['last_deposited'] = $last_deposited->sum; 
        $last_deposited = DB::table('user_plans')->SelectRaw('available')->orderBy('user_plans.id', 'desc')->first();
        $statisctic['last_withdraw'] = $last_deposited->available; 
        return response()->json($statisctic);  
    }
}