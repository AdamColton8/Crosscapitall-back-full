@extends('admin.layouts.app')
@section('title', 'FAQ Менеджер')
@section('content')
        <div class="row">
            <h1 class="ui huge header">FAQ Менеджер</h1>

        </div>
        <div class="ui divider"></div>
        <div class="row table-responsive">
            <table class="ui single line striped selectable table">
                <thead>
                <tr>
                    <th>№</th>
                    <th>Вопрос</th>
                    <th>Ответ</th>
                    <th><a href="{{ route('faq.create') }}" class="tiny ui button">Создать новый QA</a></th>
                </tr>
                </thead>
                <tbody>
                @foreach($faq as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->question }}</td>
                    <td>{{ $item->answer }}</td>
                    <td>
                        <a href="{{ route('faq.show', $item->id) }}" class="tiny ui button">Посмотреть</a>
                        <a href="{{ route('faq.edit', $item->id) }}" class="tiny ui button">Редактировать</a>
                        {!! Form::open(['method' => 'DELETE','route' => ['faq.destroy', $item->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Удалить', ['class' => 'tiny ui button']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        @if ($faq->lastPage() > 1)
            <div class="ui pagination menu">
                <a href="{{ $faq->previousPageUrl() }}" class="{{ ($faq->currentPage() == 1) ? ' disabled' : '' }} item">
                    Предыдущая
                </a>
                @for ($i = 1; $i <= $faq->lastPage(); $i++)
                    <a href="{{ $faq->url($i) }}" class="{{ ($faq->currentPage() == $i) ? ' active' : '' }} item">
                        {{ $i }}
                    </a>
                @endfor
                <a href="{{ $faq->nextPageUrl() }}" class="{{ ($faq->currentPage() == $faq->lastPage()) ? ' disabled' : '' }} item">
                    Следующая
                </a>
            </div>
        @endif
@endsection