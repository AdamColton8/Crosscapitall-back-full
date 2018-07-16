@extends('admin.layouts.app')
@section('content')
    <div class="row">
        <h1 class="ui huge header">{{ $user->username }}</h1>
    </div>
    <div class="ui divider"></div>
    <div class="one column row">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <p class="ui medium header">User trade plans</p>
                </div>
                <div class="ui secondary segment">
                    <table class="ui celled table">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Trade plan</th>
                            <th>Initial money, €</th>
                            <th>Earned, €</th>
                            <th>Progress, days</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user_plans as $plan)
                        <tr>
                            <td>{{ $plan->date }}</td>
                            <td>{{ $plan->plan->name }}</td>
                            <td>{{ \App\Models\Wallet::ConvertToDollar($plan->initial_money) }}</td>
                            <td>{{ \App\Models\Wallet::ConvertToDollar($plan->earned) }}</td>
                            <td>{{ \App\Models\UserPlan::getDifference($plan->date) . '/'. $plan->plan->term }}</td>
                            <td>{{ $plan->status }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="one column row">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <p class="ui medium header">Выводы денег</p>
                </div>
                <div class="ui secondary segment">
                    <div class="row table-responsive">
                        <table class="ui single line striped selectable table">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Платежная система</th>
                                    <th>Кошелек</th>
                                    <th>Баланс</th>
                                    <th>К списанию</th>
                                    <th>Сума выплаты</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($outputs as $output)
                            <tr>
                                <td>{{ $output->date }}</td>
                                <td>Perfect Money</td>
                                <td>Главный</td>
                                <td>{{ \App\Models\Wallet::convertToDollar($output->wallet->balance) }}</td>
                                <td>{{ \App\Models\Wallet::convertToDollar($output->sum) }}</td>
                                <td>{{ \App\Models\Wallet::convertToDollar($output->sum - $output->commission) }}</td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="one column row">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment title">
                    <p class="ui medium header">Referrals</p>
                </div>
                <div class="ui secondary segment">
                    <div class="one column row ui accordion user__referrals">
                        @foreach($referrals as $referral)
                        <div class="column">
                            <div class="ui segments">
                                <div class="ui segment title">
                                    <p class="ui medium header"><i class="dropdown icon"></i>
                                        <a href="/user-page.html">{{ $referral->username }} -</a>
                                        <span>540 €</span>
                                    </p>
                                </div>
                                <div class="ui secondary segment content">
                                    <div class="row table-responsive">
                                        <table class="ui single line striped selectable table">
                                            <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Referal Line</th>
                                                <th>%</th>
                                                <th>Payment system</th>
                                                <th>Trade plan</th>
                                                <th>Sum, €</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>14/05/2018</td>
                                                <td>First line</td>
                                                <td>7%</td>


                                                <td>Perfect money</td>
                                                <td>Pro</td>
                                                <td>165</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ui section divider"></div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
