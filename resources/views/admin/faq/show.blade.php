@extends('admin.layouts.app')
@section('content')
    <p>{{ $question->question }}</p>
    <p>{{ $question->answer }}</p>
@endsection
