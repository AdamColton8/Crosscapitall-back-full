@extends('admin.layouts.app')
@section('title', 'Начислить бонус')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Начисления бонусов</h1>
    </div>
    <div class="ui divider"></div>
    <div class="three column row doubling stackable">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <div class="ui big label">Начислить бонусы</div>
                </div>
                <div class="ui secondary segment">
                    {!! Form::open(['url' => route('bonus', $id), 'class' => 'ui form one column row', 'method' => 'post'])!!}
                    <form class="ui form one column row">
                        <div class="column">
                            <div class="field">
                                <label>Сумма в $</label>
                                {!! Form::text('sum', old('title'), ['placeholder' => 'Введите сумму ...']) !!}
                            </div>
                            <div class="field">
                                {!! Form::hidden('user_id', $id) !!}
                            </div>
                            {{ Form::submit('Начислить', ['class' => 'ui button blue']) }}
                        </div>
                    </form>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection