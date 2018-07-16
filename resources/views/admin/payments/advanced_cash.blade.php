<?php $data = json_decode($payment->config, true) ?>

{!! Form::open(['url' => route('payment'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
<div class="column">
    <div class="field">
        <label>Email аккаунта</label>
        {!! Form::text(\App\Models\Config::AC_EMAIL, $data[\App\Models\Config::AC_EMAIL]) !!}
    </div>
    <div class="field">
        <label>Наименование SCI</label>
        {!! Form::text(\App\Models\Config::AC_SCI_NAME, $data[\App\Models\Config::AC_SCI_NAME]) !!}
    </div>
    <div class="field">
        <label>Пароль SCI</label>
        {!! Form::text(\App\Models\Config::AC_SCI_PASSWORD, $data[\App\Models\Config::AC_SCI_PASSWORD]) !!}
    </div>
    <div class="field">
        <label>Имя API</label>
        {!! Form::text(\App\Models\Config::AC_API_NAME, $data[\App\Models\Config::AC_API_NAME]) !!}
    </div>
    <div class="field">
        <label>Пароль API</label>
        {!! Form::text(\App\Models\Config::AC_API_PASSWORD, $data[\App\Models\Config::AC_API_PASSWORD]) !!}
    </div>
    <div class="field">
        <label>Комиссия</label>
        {!! Form::text(\App\Models\Config::AC_COMMISSION, $data[\App\Models\Config::AC_COMMISSION]) !!}
    </div>
    <div class="field">
        {!! Form::hidden('payment_type', $payment->alias) !!}
    </div>
    <button class="ui button" type="submit">Сохранить</button>
</div>
{!! Form::close() !!}