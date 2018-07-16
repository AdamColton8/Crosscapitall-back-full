<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Payment;
use Validator, Input, Redirect;
use Response;

class ConfigController extends Controller
{
    public function email(Request $request)
    {

        $email = Config::where('key' , 'like', 'email_settings%')->get();
        if ($request->isMethod('POST'))
        {
            $email_setting = $request->request->all();
            $valid = Validator::make($email_setting,[
                Config::ES_EMAIL_TO_SEND => 'required|max:255|email',
                Config::ES_NAME_TO_SEND => 'required|max:255',
                Config::ES_SMTP_LOGIN => 'required|max:255',
                Config::ES_SMTP_PASSWORD => 'required|max:255',
                Config::ES_SMTP_HOST => 'required|max:255',
            ]);
            if($valid->fails()){
			   return Response::make(['error' => $valid->errors()], 406);
            }
            $data = $request->except('_token');
            if(!isset($data["email_settings_is_smtp"])){
               $data["email_settings_is_smtp"] = 0;
            }
            foreach ($email as $item){
                $item->value = $data[$item->key];
                $item->save();
            }
            
        }
        $fields = [];
        foreach ($email as $item)
        { $fields[$item->key] = $item->value; }

        return view('admin.config.email', compact('fields'));
    }

    public function referralLevels(Request $request)
    {
        $levels = Config::where('key', 'like', 'referral_program_level%')->get();

        if ($request->isMethod('POST'))
        {
            $data = $request->except('_token');
            foreach ($levels as $item)
            {
                $item->value = $data[$item->key];
                $item->save();
            }
        }

        $fields = [];
        foreach ($levels as $item)
        {
            $fields[$item->key] = $item->value;
        }

        return view('admin.config.referral', compact('fields'));
    }

    public function payments(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $payment = Payment::where('alias', '=', $request->get('payment_type'))->first();
            $json = json_encode($this->{$payment->alias}($request));
            $payment->config = $json;
            $payment->save();

            return redirect()->route('payment');
        }

        $payments = Payment::take(3)->get();
        return view('admin.payments.payment_system', compact('payments'));
    }

    private function perfect_money($request)
    {
        $perfect_money_config = [
            Config::PM_ACCOUNT_NUMBER => $request->get(Config::PM_ACCOUNT_NUMBER),
            Config::PM_RECIPIENT_NAME => $request->get(Config::PM_RECIPIENT_NAME),
            Config::PM_CODE_PHRASE => $request->get(Config::PM_CODE_PHRASE),
            Config::PM_ACCOUNT_ID => $request->get(Config::PM_ACCOUNT_ID),
            Config::PM_ACCOUNT_PASSWORD => $request->get(Config::PM_ACCOUNT_PASSWORD),
            Config::PM_COMMISSION => $request->get(Config::PM_COMMISSION),
        ];

        return $perfect_money_config;
    }

    private function advanced_cash($request)
    {
        $advanced_cash_config = [
            Config::AC_EMAIL => $request->get(Config::AC_EMAIL),
            Config::AC_SCI_NAME => $request->get(Config::AC_SCI_NAME),
            Config::AC_SCI_PASSWORD => $request->get(Config::AC_SCI_PASSWORD),
            Config::AC_API_NAME => $request->get(Config::AC_API_NAME),
            Config::AC_API_PASSWORD => $request->get(Config::AC_API_PASSWORD),
            Config::AC_COMMISSION => $request->get(Config::AC_COMMISSION),
        ];

        return $advanced_cash_config;
    }

    private function payeer($request)
    {
        $payeer_config = [
            Config::P_ID => $request->get(Config::P_ID),
            Config::P_SECURE_KEY => $request->get(Config::P_SECURE_KEY),
            Config::P_ACCOUNT_NUMBER => $request->get(Config::P_ACCOUNT_NUMBER),
            Config::P_API_ID => $request->get(Config::P_API_ID),
            Config::P_API_KEY => $request->get(Config::P_API_KEY),
            Config::P_COMMISSION => $request->get(Config::P_COMMISSION),
        ];

        return $payeer_config;
    }
}
