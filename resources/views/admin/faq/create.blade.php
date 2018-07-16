@extends('admin.layouts.app')
@section('title', 'Создать новый QA')
@section('content')
    <div class="row">
        <h1 class="ui huge header">FAQ</h1>
    </div>
    <div class="ui divider"></div>
    <div class="three column row doubling stackable">
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <div class="ui big label">Создать новый вопрос и ответ</div>
                </div>
                <div class="ui secondary segment">
                    {!! Form::open(['url' => route('faq.store'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
                    <form class="ui form one column row">
                        <div class="column">
                            <div class="field">
                                <label>Вопрос</label>
                                {!! Form::text('question', old('title'), ['placeholder' => 'Напишите вопрос ...']) !!}
                            </div>
                            <div class="field">
                                <label>Ответ</label>
                                {!! Form::textarea('answer', old('subtitle'), ['placeholder' => 'Напишите ответ к вопросу...']) !!}
                            </div>
                            {{ Form::submit('Создать', ['class' => 'ui button blue']) }}
                        </div>
                    </form>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

