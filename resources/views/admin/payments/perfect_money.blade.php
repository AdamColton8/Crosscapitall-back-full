<?php $data = json_decode($payment->config, true);  ?>

{!! Form::open(['url' => route('payment'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
<div class="column">
    <div class="field">
        <label>Номер аккаунта</label>
        {!! Form::text(\App\Models\Config::PM_ACCOUNT_NUMBER, $data[\App\Models\Config::PM_ACCOUNT_NUMBER] , ['placeholder' => 'Email']) !!}
    </div>
    <div class="field">
        <label>Наименование получателя</label>
        {!! Form::text(\App\Models\Config::PM_RECIPIENT_NAME, $data[\App\Models\Config::PM_RECIPIENT_NAME]) !!}
    </div>
    <div class="field">
        <label>Альтернативная кодовая фраза</label>
        {!! Form::text(\App\Models\Config::PM_CODE_PHRASE, $data[\App\Models\Config::PM_CODE_PHRASE]) !!}
    </div>
    <div class="field">
        <label>ID аккаунта</label>
        {!! Form::text(\App\Models\Config::PM_ACCOUNT_ID, $data[\App\Models\Config::PM_ACCOUNT_ID]) !!}
    </div>
    <div class="field">
        <label>Пароль аккаунта</label>
        {!! Form::text(\App\Models\Config::PM_ACCOUNT_PASSWORD, $data[\App\Models\Config::PM_ACCOUNT_PASSWORD]) !!}
    </div>
    <div class="field">
        <label>Кроцент коммисионых</label>
        {!! Form::text(\App\Models\Config::PM_COMMISSION, $data[\App\Models\Config::PM_COMMISSION]) !!}
    </div>
    <div class="field">
        {!! Form::hidden('payment_type', $payment->alias) !!}
    </div>
    <button class="ui button" type="submit">Сохранить</button>
</div>
{!! Form::close() !!}