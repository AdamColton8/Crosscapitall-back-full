@extends('admin.layouts.app')
@section('title', 'Редактировать - FAQ Менеджер')
@section('content')

        <div class="row">
            <h1 class="ui huge header">FAQ Менеджер</h1>
        </div>
        <div class="ui divider"></div>
        <div class="three column row doubling stackable">
            <div class="column">
                <div class="ui segments">
                    <div class="ui segment">
                        <div class="ui big label">Редактировать</div>
                    </div>
                    <div class="ui secondary segment">
                        {!! Form::open(['url' => route('faq.update', $item->id), 'class' => 'ui form one column row', 'method' => 'put'])!!}
                        <form class="ui form one column row">
                            <div class="column">
                                <div class="field">
                                    <label>Вопрос</label>
                                    {!! Form::text('question', $item->question, ['placeholder' => 'Напишите вопрос...']) !!}
                                </div>
                                <div class="field">
                                    <label>Ответ</label>
                                    {!! Form::textarea('answer', $item->answer, ['placeholder' => 'Напишите ответ к вопросу...']) !!}
                                </div>
                                {{ Form::submit('Сохранить', ['class' => 'ui button blue']) }}
                            </div>
                        </form>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
@endsection