<?php $data = json_decode($payment->config, true);  ?>

{!! Form::open(['url' => route('payment'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
<div class="column">
    <div class="field">
        <label>Номер аккаунта</label>
        {!! Form::text(\App\Models\Config::P_ID, $data[\App\Models\Config::P_ID] , ['placeholder' => 'Email']) !!}
    </div>
    <div class="field">
        <label>Наименование получателя</label>
        {!! Form::text(\App\Models\Config::P_SECURE_KEY, $data[\App\Models\Config::P_SECURE_KEY]) !!}
    </div>
    <div class="field">
        <label>Альтернативная кодовая фраза</label>
        {!! Form::text(\App\Models\Config::P_ACCOUNT_NUMBER, $data[\App\Models\Config::P_ACCOUNT_NUMBER]) !!}
    </div>
    <div class="field">
        <label>ID аккаунта</label>
        {!! Form::text(\App\Models\Config::P_API_ID, $data[\App\Models\Config::P_API_ID]) !!}
    </div>
    <div class="field">
        <label>Пароль аккаунта</label>
        {!! Form::text(\App\Models\Config::P_API_KEY, $data[\App\Models\Config::P_API_KEY]) !!}
    </div>
    <div class="field">
        <label>Кроцент коммисионых</label>
        {!! Form::text(\App\Models\Config::P_COMMISSION, $data[\App\Models\Config::P_COMMISSION]) !!}
    </div>
    <div class="field">
        {!! Form::hidden('payment_type', $payment->alias) !!}
    </div>
    <button class="ui button" type="submit">Сохранить</button>
</div>
{!! Form::close() !!}