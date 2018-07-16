@extends('admin.layouts.app')
@section('title', 'Запросы на вывод')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Запросы на вывод</h1>
    </div>
    <div class="row">
        <div class="ui icon input">
            <i class="search icon"></i>
            <input type="text" placeholder="Search...">
        </div>
    </div>
    <div class="ui divider"></div>
    <div class="row table-responsive">
        <table class="ui single line striped selectable table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Дата</th>
                <th>user ID</th>
                <th>user Email</th>
                <th>Платежная система</th>
                <th>Кошелек</th>
                <th>Баланс</th>
                <th>К списанию</th>
                <th>Сума выплаты</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            @foreach($queries as $query)
            <tr>
                <td>{{ $query->id }}</td>
                <td>{{ $query->date }}</td>
                <td>{{ $query->user_id }}</td>
                <td>{{ $query->email }}</td>
                <td>{{ $query->name }}</td>
                <td>Главный</td>
                <td>${{ \App\Models\Wallet::convertToDollar($query->balance) }}</td>
                <td>${{ \App\Models\Wallet::convertToDollar($query->sum) }}</td>
                <td>${{ \App\Models\Wallet::convertToDollar($query->sum - $query->commission) }}</td>
                <td>
                <a class="circular ui icon button negative" href="{{ route('cancel', $query->id) }}"><i class="reply icon"></i></a>
                <?php $array = json_decode($query->config, true); 
                    if($query->payment_alias == 'perfect_money'){ ?>
                    <form action="https://perfectmoney.is/api/step1.asp" method="POST">
                        <input type="hidden" name="PAYEE_ACCOUNT" value="{{$query->wallet_key}}"/>
                        <input type="hidden" name="PAYEE_NAME" value="{{$query->wallet_name}}"/>
                        <input type="hidden" name="PAYMENT_AMOUNT" value="{{$query->sum/100}}"/>
                        <input type="hidden" name="PAYMENT_UNITS" value="EUR"/>
                        <input type="hidden" name="PAYMENT_BATCH_NUM" value="0"/>
                        <input type="hidden" name="PAYER_ACCOUNT" value="{{$array['perfect_money_account_number']}}"/>
                        <input type="hidden" name="PAYMENT_ID" value="NULL"/>
                        <input type="hidden" name="PAYMENT_URL" value="http://app.crosscapital.link/admin/queries/{{ $query->id }}/done"/>
                        <input type="hidden" name="NOPAYMENT_URL" value="http://app.crosscapital.link/admin/queries/"/>
                        <input class="circular ui icon button positive" type="submit" name="send" value="OK"/>
                    </form>
                <?php }else if($query->payment_alias == 'advanced_cash'){ ?>
                     <form method="POST" action="https://wallet.advcash.com/sci/">
                       <input type="hidden" name="ac_account_email" value="{{$array['advanced_cash_email']}}"/>
                       <input type="hidden" name="ac_src_wallet" value="{{$array['advanced_cash_account_number']}}"/> 
                       <input type="hidden" name="ac_dest_wallet" value="{{$query->wallet_key}}"/>
                       <input type="hidden" name="ac_sci_name" value="{{$array['advanced_cash_sci_name']}}"/>
                       <input type="hidden" name="ac_amount" value="{{$query->sum/100}}"/>
                       <input type="hidden" name="ac_currency" value="EUR"/>
                       <input type="hidden" name="ac_order_id" value="123456"/>
                       <input class="circular ui icon button positive" type="submit" name="send" value="OK"/>
                    </form> 
                <?php } else{ ?>
                    <form method="POST" action="https://payeer.com/merchant/">
                        <input type="hidden" name="m_shop" value="{{$query->wallet_key}}"/>
                        <input type="hidden" name="m_orderid" value="1"/>
                        <input type="hidden" name="m_amount" value="{{$query->sum/100}}"/>
                        <input type="hidden" name="m_curr" value="EUR"/>
                        <input type="hidden" name="m_desc" value="dGVzdA=="/>
                        <input type="hidden" name="form[ps]" value="2609"/>
                        <input type="hidden" name="form[curr[2609]]" value="EUR"/>
                        <input type="hidden" name="m_sign"/>
                       <input class="circular ui icon button positive" type="submit" name="send" value="OK"/>
                    </form> 
                <?php } ?>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
         
     <!--a class="circular ui icon button positive" href="{{ route('done', $query->id) }}"><i class="paper plane icon"></i></a-->          
    <div class="row">
        <div class="ui right floated pagination menu"><a class="icon item"><i class="left chevron icon"></i></a><a class="item">1</a><a class="item">2</a><a class="item">3</a><a class="item">4</a><a class="icon item"><i class="right chevron icon"></i></a></div>
    </div>
@endsection
