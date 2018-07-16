@extends('admin.layouts.app')
@section('title', 'Отменить запрос')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Отменить запрос</h1>
    </div>
    <div class="ui divider"></div>
    <div class="three column row doubling stackable">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <div class="ui big label">Отменить запрос</div>
                </div>
                <div class="ui secondary segment">
                    {!! Form::open(['url' => route('cancel', $id), 'class' => 'ui form one column row', 'method' => 'post'])!!}
                    <form class="ui form one column row">
                        <div class="column">
                            <div class="field">
                                <label>Причина отмены</label>
                                {!! Form::textarea('comment', old('title'), ['placeholder' => 'Опишите причину отмены запроса...']) !!}
                            </div>
                            <div class="field">
                                {!! Form::hidden('user_id', $id) !!}
                            </div>
                            {{ Form::submit('Отменить', ['class' => 'ui button blue']) }}
                        </div>
                    </form>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection