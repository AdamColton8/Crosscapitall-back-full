@extends('admin.layouts.app')
@section('title', 'Настройки Email')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Реферальные награды</h1>
    </div>
    <div class="ui divider"></div>
    {!! Form::open(['url' => route('referral'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
        <div class="column">
            <div class="field">
                <label>1 уровень</label>
                {!! Form::text(\App\Models\Config::RP_level_1, $fields[\App\Models\Config::RP_level_1] , ['placeholder' => 'Email']) !!}
            </div>
            <div class="field">
                <label>2 уровень</label>
                {!! Form::text(\App\Models\Config::RP_level_2, $fields[\App\Models\Config::RP_level_2] , ['placeholder' => 'Email']) !!}
            </div>
            <div class="field">
                <label>3 уровень</label>
                {!! Form::text(\App\Models\Config::RP_level_3, $fields[\App\Models\Config::RP_level_3] , ['placeholder' => 'Email']) !!}
            </div>
            <button class="ui button" type="submit">Submit</button>
        </div>
    {!! Form::close() !!}
@endsection