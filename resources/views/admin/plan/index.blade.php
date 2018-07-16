@extends('admin.layouts.app')
@section('title', 'Планы')
@section('content')

<div class="row">
    <h1 class="ui huge header">Trade plans</h1>
</div>
<div class="ui divider"></div>
<div class="three column row doubling stackable">
    @foreach($plans as $plan)
    <div class="column">
        <div class="ui segments">
            <div class="ui segment">
                <div class="ui big label">Start</div>
            </div>
            <div class="ui secondary segment">
                {!! Form::open(['url' => route('plan.update', $plan->id), 'class' => 'ui form one column row', 'method' => 'put'])!!}
                    <div class="column">
                        <div class="field">
                            <label>Название</label>
                            {!! Form::text('name', $plan->name) !!}
                        </div>
                        <div class="ui fields">
                            <div class="field">
                                <label>От</label>
                                {!! Form::text('deposit_min', $plan->deposit_min) !!}
                            </div>
                            <div class="field">
                                <label>До</label>
                                {!! Form::text('deposit_max', $plan->deposit_max) !!}
                            </div>
                        </div>
                        <div class="field">
                            <label>Длительность, дней</label>
                            {!! Form::text('term', $plan->term) !!}
                        </div>
                        <div class="field">
                            <label>%</label>
                            {!! Form::text('percent', $plan->percent) !!}
                        </div>
                        <button class="ui button blue" type="submit">Сохранить</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection