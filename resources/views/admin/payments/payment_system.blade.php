@extends('admin.layouts.app')
@section('title', 'Настройки')
@section('content')
    <div class="row">
        <h1 class="ui huge header">Платежные системы</h1>
    </div>
    <div class="ui divider"></div>
    <div class="three column row doubling stackable">
        @foreach($payments as $payment)
        <div class="column">
            <div class="ui segments">
                <div class="ui segment">
                    <div class="ui big label">{{ $payment->title }}</div>
                </div>
                <div class="ui secondary segment">
                    @include('admin.payments.'.$payment->alias)
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection