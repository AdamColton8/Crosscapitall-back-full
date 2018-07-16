<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Models\Wallet;
use App\Models\Operation;

class WalletController extends Controller
{
    public function refill(Request $request)
    {
        //Getting post data
        $user_id = \Auth::id();
        $balance = Wallet::convertToCent($request->get('balance'));
        $payment_alias = $request->get('payment_alias');
        $typeOperation = $request->get('type_operation');
        $wallet = Wallet::getUserWallet($user_id, $payment_alias);

        if ($typeOperation === Operation::TYPE_REFILL)
        {
            $commission = 0;
            //if user don't have wallet, create a new one or update exists
            If (null === $wallet)
                Wallet::createWallet($user_id, $payment_alias, $balance);
            else
                Wallet::updateWalletBalance($wallet, $balance);

            $status = Operation::STATUS_DONE;
            Operation::newOperation($user_id, $wallet->id, $payment_alias, $balance, $commission, $typeOperation, $status);

            return response()->json('ok');
        } else return response()->json('oops, incorrect type operations...');
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
        } else {
            return response(['errors' => ['There is not enough money on your wallet']]);
        }
        return response()->json('Your request has been sent successfully to confirm by the administrator');
    }
}
