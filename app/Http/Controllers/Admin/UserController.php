<?php

namespace App\Http\Controllers\Admin;

use App\Models\Operation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPlan;
use DB;
use App\Models\Wallet;
use App\Models\Payment;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.user.index', ['users' => User::paginate(10)]);
    }

    public function cancelRequest(Request $request ,$id)
    {
        if ($request->isMethod('POST'))
        {
            $operation = Operation::findOrFail($id);
            $operation->status = Operation::STATUS_CANCELED;
            $operation->comment = $request->get('comment');
            Wallet::accrualsMoney($operation->user_id, $operation->payment_alias, $operation->sum);

            $operation->save();

            return redirect()->route('queries');
        }

        return view('admin.withdraw.abolition', compact('id'));
    }
    
    public function doneRequest(Request $request ,$id)
    {
            $operation = Operation::findOrFail($id);
            $payment = Payment::where('alias', $operation->payment_alias)->first();
            $array = json_decode($payment->config, true);
            $payee_account = DB::table('wallet_users')
                ->select('wallet_key')
                ->where('user_id', $operation->user_id)
                ->where('payment_alias', $operation->payment_alias)
                ->first();
            
            $operation->status = Operation::STATUS_DONE;
            Wallet::deductMoney($operation->user_id, $operation->payment_alias, $operation->sum);
            $operation->save();
            return redirect()->route('queries');
    }

    public function sendMoney()
    {

    }
    public function showUser($id)
    {
        $user = User::findOrFail($id);
        $user_plans = UserPlan::where('user_id', '=', $id)->with('plan')->get();
        $outputs = Operation::where('user_id', '=', $id)
            ->where('type', '=', Operation::TYPE_WITHDRAW)
            ->with('wallet')
            ->get();

        $referrals = DB::table('users')->where('path', 'LIKE', $id . '.%')->get();

        return view('admin.user.show', compact('user', 'user_plans', 'outputs', 'referrals'));
    }

    public function accrualsBonus(Request $request, $id)
    {
        if ($request->isMethod('POST'))
        {
            $user_id = $request->get('user_id');
            $sum = Wallet::convertToCent($request->get('sum'));
            $wallet = Wallet::getUserWallet($user_id, Wallet::WALLET_BONUS);

            if ($wallet === null)
                Wallet::createWallet($user_id, Wallet::WALLET_BONUS, $sum);
            else Wallet::updateWalletBalance($wallet, $sum);

            return redirect()->route('users');
        }

        return view('admin.user.bonus', compact('id'));
    }

    //change status user
    public function changeUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'blocked';
        $user->save();
    }

    public function makeAdmin($id)
    {
        $user = User::findOrFail($id);
        $user->group = User::ROLE_ADMIN;
        $user->save();

        return redirect()->back();
    }
}