<?php

namespace App\Http\Controllers;

use App\Models\UserPlan;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function data(Request $request)
    {
        $payment = Payment::where('alias', $request->get('payment_alias'))->first();
        if($payment->alias === 'perfect_money')
        {
            $array = json_decode($payment->config, true);
            return response()->json([
                'action' => 'https://perfectmoney.is/api/step1.asp',
                'fields' => [
                    'PAYEE_ACCOUNT' => $array['perfect_money_account_number'],
                    'PAYEE_NAME' => $array['perfect_money_recipient_name'],
                    'PAYMENT_AMOUNT' => $request->get('amount'),
                    'PAYMENT_UNITS' => 'EUR',  //USD
                    'STATUS_URL' => 'https://localhost:3000/api/perfect_money',
                    'PAYMENT_URL' => 'https://localhost:3000/profile',
                    'NOPAYMENT_URL' => 'https://localhost:3000/404',
                    'PAYMENT_ALIAS' => $payment->alias,
                    'PLAN_ID' => $request->get('plan_id'),
                ],

            ]);
        }
        elseif ($payment->alias === 'payeer')
        {
            $array = json_decode($payment->config, true);

            $m_shop = $array['payeer_id'];
            $m_order_id = '1';
            $m_amount = number_format($request->get('amount'), 2, '.', '');
            $m_curr = 'EUR';
            $m_desc = base64_encode('test');
            $m_key = $array['payeer_secure_key'];

            $arHash = array(
                $m_shop,
                $m_order_id,
                $m_amount,
                $m_curr,
                $m_desc,
                $m_key);

            $sign = strtoupper(hash('sha256', implode(':', $arHash)));

            return response()->json([
                'action' => 'https://payeer.com/merchant/',
                'fields' => [
                    'm_shop' => $m_shop,
                    'm_orderid' => $m_order_id,
                    'm_amount' => $m_amount,
                    'm_curr' => $m_curr,
                    'm_desc' => $m_desc,
                    'm_sign' => $sign,
                    'form[ps]' => '2609',
                    'form[curr[2609]]' => 'EUR',
                    'payment_alias' => $payment->alias,
                    'plan_id' => $request->get('plan_id'),
                ],
            ]);
        }
        elseif ($payment->alias === 'advanced_cash')
        {
            $array = json_decode($payment->config, true);

            return response()->json([
                'action' => 'https://wallet.advcash.com/sci/',
                //'action' => 'https://wallet.advcash.com/sci/paymentRequest.jsf',
                'fields' => [
                    'ac_account_email' => $array['advanced_cash_email'],
                    'ac_sci_name' => $array['advanced_cash_sci_name'],
                    'ac_amount' => $request->get('amount'),
                    'ac_currency' => 'EUR',
                    'ac_order_id' => '1',
                    'ac_sign' => 'najfblaVFefbvVFLGFBfgbefwas',
                    'payment_alias' => $payment->alias,
                    'plan_id' => $request->get('plan_id'),
                ],
            ]);
        }
    }

    public function perfectMoney()
    {
        $secret = strtoupper(md5('19owL918kpqOwSUKcrlXaSMIT'));

        $hash = $_POST['PAYMENT_ID'].':'.
                $_POST['PAYEE_ACCOUNT'].':'.
                $_POST['PAYMENT_AMOUNT'].':'.
                $_POST['PAYMENT_UNITS'].':'.
                $_POST['PAYMENT_BATCH_NUM'].':'.
                $_POST['PAYER_ACCOUNT'].':'.
                $secret.':'.
                $_POST['TIMESTAMPGMT'];

        $hash = strtoupper(md5($hash));

        if ($hash != $_POST['V2_HASH'])
            return response()->json('Error...');
        else {
            UserPlan::activatePlan($_POST['PAYMENT_AMOUNT'], $_POST['PAYER_PAYMENT_ALIAS'], $_POST['PLAN_ID']);
        }

    }

    public function advanced_cash()
    {
        $key = hash('sha256',
            $_POST['ac_transfer'].':'.
            $_POST['ac_start_date'].':'.
            $_POST['ac_sci_name'].':'.
            $_POST['ac_src_wallet'].':'.
            $_POST['ac_dest_wallet'].':'.
            $_POST['ac_order_id'].':'.
            $_POST['ac_amount'].':'.
            $_POST['ac_merchant_currency'].':'.
            'msdjgnbkas'
        );

        if ($key != $_POST['ac_hash'])
            return response()->json('Error');
        else UserPlan::activatePlan($_POST['PAYMENT_AMOUNT'], $_POST['PAYER_PAYMENT_ALIAS'], $_POST['PLAN_ID']);
    }

    public function payeer()
    {
        if (!in_array($_SERVER['REMOTE_ADDR'], array('185.71.65.92', '185.71.65.189', '149.202.17.210'))) return;

        if (isset($_POST['m_operation_id']) && isset($_POST['m_sign']))
        {
            $m_key = 'Ваш секретный ключ';

            $arHash = array(
                $_POST['m_operation_id'],
                $_POST['m_operation_ps'],
                $_POST['m_operation_date'],
                $_POST['m_operation_pay_date'],
                $_POST['m_shop'],
                $_POST['m_orderid'],
                $_POST['m_amount'],
                $_POST['m_curr'],
                $_POST['m_desc'],
                $_POST['m_status'],
            );

            if (isset($_POST['m_params']))
            {
                $arHash[] = $_POST['m_params'];
            }

            $arHash[] = $m_key;

            $sign_hash = strtoupper(hash('sha256', implode(':', $arHash)));

            if ($_POST['m_sign'] == $sign_hash && $_POST['m_status'] == 'success')
            {
                UserPlan::activatePlan($_POST['PAYMENT_AMOUNT'], $_POST['PAYER_PAYMENT_ALIAS'], $_POST['PLAN_ID']);
            } else echo $_POST['m_orderid'].'|error';
        }
    }
}
