@extends('admin.layouts.app')
@section('title', 'Настройки Email')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Настройки Email</h1>
    </div>
    <div class="ui divider"></div>
    {!! Form::open(['url' => route('email'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
        <div class="column">
            <div class="field">
                <label>Email для отправки</label>
                {!! Form::text(\App\Models\Config::ES_EMAIL_TO_SEND, $fields[\App\Models\Config::ES_EMAIL_TO_SEND] , ['placeholder' => 'Email']) !!}
            </div>
            <div class="field">
                <label>Имя для отправки</label>
                {!! Form::text(\App\Models\Config::ES_NAME_TO_SEND, $fields[\App\Models\Config::ES_NAME_TO_SEND] , ['placeholder' => 'Name']) !!}
            </div>
            <div class="field">
                <div class="ui checkbox">
                    {{ Form::checkbox(\App\Models\Config::ES_IS_SMTP) }}
                    <label for="smtp">SMTP</label>
                </div>
            </div>
            <div class="field">
                <label>SMTP логин</label>
                {!! Form::text(\App\Models\Config::ES_SMTP_LOGIN, $fields[\App\Models\Config::ES_SMTP_LOGIN] , ['placeholder' => 'Smtp login']) !!}
            </div>
            <div class="field">
                <label>SMTP пароль</label>
                {!! Form::text(\App\Models\Config::ES_SMTP_PASSWORD, $fields[\App\Models\Config::ES_SMTP_PASSWORD] , ['placeholder' => 'Smtp password']) !!}
            </div>
            <div class="field">
                <label>SMTP хост</label>
                {!! Form::text(\App\Models\Config::ES_SMTP_HOST, $fields[\App\Models\Config::ES_SMTP_HOST] , ['placeholder' => 'Smtp host...']) !!}
            </div>
            <button class="ui button" type="submit">Submit</button>
        </div>
    {!! Form::close() !!}
@endsection