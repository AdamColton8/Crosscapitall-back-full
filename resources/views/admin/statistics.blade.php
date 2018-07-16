@extends('admin.layouts.app')
@section('content')
        <div class="row">
            <h1 class="ui huge header">Статистика</h1>
        </div>
        <div class="ui divider"></div>
        {!! Form::open(['url' => route('stat'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
            <div class="column">
                <div class="fields inline ui">
                    <div class="field">
                        <label>C</label>
                        {!! Form::date('date_from', old('title'), ['placeholder' => 'Напишите вопрос ...']) !!}
                    </div>
                    <div class="field">
                        <label>До</label>
                        {!! Form::date('date_to', old('title'), ['placeholder' => 'Напишите вопрос ...']) !!}
                    </div>
                    <div class="field">
                        <button class="ui button" type="submit">Сформировать</button>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
        <div class="row">
            <div class="ui accordion statistic_acordion">
                <div class="title">
                    <table class="ui celled striped table">
                        <thead>
                        <tr>
                            <th colspan="3"><i class="dropdown icon"></i>Получено всего средств
                                <strong class="ui header teal">- {{ $total_amount }}$</strong>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="content">
                    <table class="ui celled striped table">
                        <tbody>
                        @foreach($received_total_funds as $amount)
                        <tr>
                            <td>{{ $amount->payment_alias }}</td>
                            <td class="right aligned collapsing"><strong>{{ \App\Models\Wallet::convertToDollar($amount->sum) }}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="title">
                    <table class="ui celled striped table">
                        <thead>
                        <tr>
                            <th colspan="3"><i class="dropdown icon"></i>Выплачено средств всего (за вычетом комиссии)

                                <strong class="ui header teal">- {{ $total_without_commission }}$</strong>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="content">
                    <table class="ui celled striped table">
                        <tbody>
                        @foreach($without_commission as $sum)
                        <tr>
                            <td>{{ $sum->payment_alias }}</td>
                            <td class="right aligned collapsing"><strong>{{ \App\Models\Wallet::convertToDollar($sum->sum) }}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="title">
                    <table class="ui celled striped table">
                        <thead>
                        <tr>
                            <th colspan="3">
                                Суммарная комиссия с пользователей за вывод средств
                                <strong class="ui header teal">- {{ $commission }}$</strong>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="content" style="padding: 0; margin: 0;"></div>
                <div class="title">
                    <table class="ui celled striped table">
                        <thead>
                        <tr>
                            <th colspan="3"><i class="dropdown icon"></i>Приобретено программ всего<strong class="ui header teal">- {{ $plans }}</strong></th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="content">
                    <table class="ui celled striped table">
                        <tbody>
                        @foreach($count_plans as $count_plan)
                        <tr>
                            <td>{{ $count_plan->name }}</td>
                            <td class="right aligned collapsing"><strong>{{ $count_plan->cnt }}</strong></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="title">
                    <table class="ui celled striped table">
                        <thead>
                        <tr>
                            <th colspan="3"><i class="dropdown icon"></i>Приобретено программ через платежные системы

                                <strong class="ui header teal">- 430</strong>
                            </th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <div class="content">
                    <table class="ui celled striped table">
                        <tbody>
                        <tr>
                            <td>Pro</td>
                            <td class="right aligned collapsing"><strong>2,204</strong></td>
                        </tr>
                        <tr>
                            <td>Medium</td>
                            <td class="right aligned"><strong>1,546</strong></td>
                        </tr>
                        <tr>
                            <td>Start</td>
                            <td class="right aligned"><strong>1,546</strong></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection