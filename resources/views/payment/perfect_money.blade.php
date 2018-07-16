@extends('layouts.app')
@section('content')
    <div class="row">
        <h1 class="ui huge header">FAQ</h1>
    </div>
    <div class="ui divider"></div>
    <div class="three column row doubling stackable">
        <div class="column">
            <div class="ui segments">
                <div class="ui secondary segment">
                    {!! Form::open(['url' => route('addMoney_success'), 'class' => 'ui form one column row', 'method' => 'post'])!!}
                    <form class="ui form one column row">
                        <div class="column">
                            <div class="field">
                                {!! Form::hidden('id', Auth::id()) !!}
                                {!! Form::select('payment_type', $payments, null, ['class' => 'form-control form-control-success', 'placeholder' => 'Select payment system please...']) !!}
                            </div>
                            <div class="field">
                                <label>Sum</label>
                                {!! Form::text('sum', old('subtitle'), ['placeholder' => 'Enter sum...']) !!}
                            </div>
                            {{ Form::submit('Create', ['class' => 'ui button blue']) }}
                        </div>
                    </form>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection


<form action="https://perfectmoney.is/api/step1.asp" method="POST">
    <input type="hidden" name="PAYEE_ACCOUNT" value="U7414842">
    <input type="hidden" name="PAYEE_NAME" value="TradeCompany">
    <input type="text" name="PAYMENT_AMOUNT" placeholder="Input sum">
    <input type="hidden" name="PAYMENT_UNITS" value="EUR">
    <input type="hidden" name="STATUS_URL" value="{{ route('addMoney') }}">
    <input type="hidden" name="PAYMENT_URL" value="{{ route('addMoney_success') }}">
    <input type="hidden" name="NOPAYMENT_URL" value="{{ route('addMoney_error') }}">
    <incleput type="hidden" name="PAYMENT_ID" value="{{ Auth::id() }}">
    <input type="submit" name="PAYMENT_METHOD" value="Оплатить">
</form>

