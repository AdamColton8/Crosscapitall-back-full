<?php

namespace App\Http\Controllers\Admin;

use App\Models\Config;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Operation;
use App\Models\Payment;
use App\Models\WalletUser;
use DB;
class AdminController extends Controller
{
    public function index()
    {
        return view('admin.layouts.app');
    }

    public function withdrawQueries()
    {
        /*$queries = Operation::where('type', '=', Operation::TYPE_WITHDRAW)
            ->where('status', '=', Operation::STATUS_PENDING)
            ->with('user', 'wallet')
            ->orderBy('id', 'desc')
            ->get();
         */ 
        $queries = DB::table('operations')
        ->leftJoin('wallet_users', function($join)
             {
                 $join->on('operations.user_id', '=', 'wallet_users.user_id');
                 $join->on('operations.payment_alias', '=', 'wallet_users.payment_alias');
             })
        ->join('users', 'operations.user_id', '=', 'users.id')
        ->join('wallets', 'operations.wallet_id', '=', 'wallets.id')
        ->join('payment_systems', 'operations.payment_alias', '=', 'payment_systems.alias')    
        ->where('operations.type', '=', Operation::TYPE_WITHDRAW)
        ->where('operations.status', '=', Operation::STATUS_PENDING)
        ->orderBy('operations.id', 'desc')
        ->get();
        return view('admin.withdraw.index', compact('queries'));
    }

    public function stat(Request $request)
    {
        if ($request->isMethod('POST'))
        {
            $dateFrom = new \DateTime($request->get('date_from'));
            $dateTo = new \DateTime($request->get('date_to'));

//            $dateTo->modify('+1 day');
            //set datetime format
            $dateFromFormatted = $dateFrom->format('Y-m-d');
            $dateToFormatted = $dateTo->format('Y-m-d');

            //Received total funds
            $received_total_funds = DB::table('operations')
                ->select(DB::raw('operations.payment_alias, sum(sum) as sum'))
                ->where('date', '>=', $dateFromFormatted)
                ->where('date', '<=', $dateToFormatted)
                ->groupBy('payment_alias')
                ->get();

            $total_amount = 0;
            foreach ($received_total_funds as $item)
            { $total_amount += Wallet::convertToDollar($item->sum); }

            // sum without commission
            $without_commission = DB::table('operations')
                ->where('type', '=', Operation::TYPE_WITHDRAW)
                ->where('date', '>=', $dateFromFormatted)
                ->where('date', '<=', $dateToFormatted)
                ->select(DB::raw('operations.payment_alias, sum(sum - operations.commission) as sum'))
                ->groupBy('payment_alias')
                ->get();
            $total_without_commission = 0;
            foreach ($without_commission as $item)
            { $total_without_commission += Wallet::convertToDollar($item->sum);}

            $commission = DB::table('operations')
                ->where('type', '=', Operation::TYPE_WITHDRAW)
                ->where('date', '>=', $dateFromFormatted)
                ->where('date', '<=', $dateToFormatted)
                ->sum('commission');

            //count plans
            $count_plans = DB::table('plans')
                ->select(DB::raw('plans.name, x.cnt'))
                ->join(DB::raw(
                    '(SELECT user_plans.plan_id, count(*) cnt FROM user_plans where user_plans.date >= \''. $dateFromFormatted .'\' and user_plans.date <= \''. $dateToFormatted .'\' GROUP BY user_plans.plan_id) x '),
                    'plans.id',
                    '=',
                    'x.plan_id',
                    'left outer')
                ->get();

            $plans = 0;
            foreach ($count_plans as $count_plan)
            { $plans += $count_plan->cnt; }
        } else {
            //Received total funds
            $received_total_funds = DB::table('operations')
                ->select(DB::raw('operations.payment_alias, sum(sum) as sum'))
                ->groupBy('payment_alias')
                ->get();
            $total_amount = 0;
            foreach ($received_total_funds as $item)
            { $total_amount += Wallet::convertToDollar($item->sum); }

            // sum without commission
            $without_commission = DB::table('operations')
                ->where('type', '=', Operation::TYPE_WITHDRAW)
                ->select(DB::raw('operations.payment_alias, sum(sum - operations.commission) as sum'))
                ->groupBy('payment_alias')
                ->get();
            $total_without_commission = 0;
            foreach ($without_commission as $item)
            { $total_without_commission += Wallet::convertToDollar($item->sum);}

            //
            $commission = DB::table('operations')
                ->where('type', '=', Operation::TYPE_WITHDRAW)
                ->sum('commission');

            //count plans
            $count_plans = DB::table('plans')
                ->select(DB::raw('plans.name, x.cnt'))
                ->join(DB::raw('(SELECT user_plans.plan_id, count(*) cnt FROM user_plans GROUP BY user_plans.plan_id) x '), 'plans.id', '=', 'x.plan_id', 'left outer')
                ->get();
            $plans = 0;
            foreach ($count_plans as $count_plan)
            { $plans += $count_plan->cnt; }
        }


        return view('admin.statistics', [
            // Received total funds
            'received_total_funds' => $received_total_funds,
            'total_amount' => $total_amount,

            'without_commission' => $without_commission,
            'total_without_commission' => $total_without_commission,

            'commission' => Wallet::convertToDollar($commission),

            'count_plans' => $count_plans,
            'plans' => $plans,
        ]);
    }

    public function statistics()
    {
        //Received total funds
        $received_total_funds = DB::table('operations')
            ->select(DB::raw('operations.payment_alias, sum(sum) as sum'))
            ->groupBy('payment_alias')
            ->get();
        $total_amount = 0;
        foreach ($received_total_funds as $item)
        { $total_amount += Wallet::convertToDollar($item->sum); }

        // sum without commission
        $without_commission = DB::table('operations')
            ->where('type', '=', Operation::TYPE_WITHDRAW)
            ->select(DB::raw('operations.payment_alias, sum(sum - operations.commission) as sum'))
            ->groupBy('payment_alias')
            ->get();
        $total_without_commission = 0;
        foreach ($without_commission as $item)
        { $total_without_commission += Wallet::convertToDollar($item->sum);}

        //
        $commission = DB::table('operations')
            ->where('type', '=', Operation::TYPE_WITHDRAW)
            ->sum('commission');

        //count plans
        $count_plans = DB::table('plans')
            ->select(DB::raw('plans.name, x.cnt'))
            ->join(DB::raw('(SELECT user_plans.plan_id, count(*) cnt FROM user_plans GROUP BY user_plans.plan_id) x '), 'plans.id', '=', 'x.plan_id', 'left outer')
            ->get();
        $plans = 0;
        foreach ($count_plans as $count_plan)
        { $plans += $count_plan->cnt; }

        return view('admin.statistics', [
            // Received total funds
            'received_total_funds' => $received_total_funds,
            'total_amount' => $total_amount,

            'without_commission' => $without_commission,
            'total_without_commission' => $total_without_commission,

            'commission' => Wallet::convertToDollar($commission),

            'count_plans' => $count_plans,
            'plans' => $plans,
        ]);
    }
}