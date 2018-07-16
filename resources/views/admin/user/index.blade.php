@extends('admin.layouts.app')
@section('content')
<div class="row">
    <h1 class="ui huge header">Пользователи</h1>

</div>
<div class="row">
    <div class="ui icon input">
        <i class="search icon"></i>
        <input type="text" placeholder="Search...">
    </div>
</div>
<div class="ui divider"></div>
<div class="row table-responsive">
    <table class="ui single line striped selectable table">
        <thead>
        <tr>
            <th>№</th>
            <th>Логин</th>
            <th>Email</th>
            <th>Имя</th>
            <th>Фамилия</th>
            <th>Группа</th>
            <th>Статус</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('show_user', $user->id) }}">{{ $user->username }}</a></td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->first_name }}</td>
                <td>{{ $user->last_name }}</td>
                <td>{{ $user->group }}</td>
                <td>{{ $user->status }}</td>
                <td><a href="{{ route('bonus', $user->id) }}" class="tiny ui button">Начислить бонусы</a></td>
                <td>
                    <button class="circular ui icon button user_setting"
                            data-html="<div class=&quot;user-settings__popup&quot;><div>
                            <a href='{{ route('bonus', $user->id) }}'>Начислить на главный кошелек</a></div><div>
                            <a href='#'>Обнулить кошелек</a></div><div>
                            <a href='{{ route('setStatusAdmin', $user->id) }}'>Дать права администратора</a></div><div>

                            </div>
                            </div>"><i class="ellipsis vertical icon"></i>
                    </button>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="row">
    @if ($users->lastPage() > 1)
        <div class="ui pagination menu">
            <a href="{{ $users->previousPageUrl() }}" class="{{ ($users->currentPage() == 1) ? ' disabled' : '' }} item">
                Предыдущая
            </a>
            @for ($i = 1; $i <= $users->lastPage(); $i++)
                <a href="{{ $users->url($i) }}" class="{{ ($users->currentPage() == $i) ? ' active' : '' }} item">
                    {{ $i }}
                </a>
            @endfor
            <a href="{{ $users->nextPageUrl() }}" class="{{ ($users->currentPage() == $users->lastPage()) ? ' disabled' : '' }} item">
                Следующая
            </a>
        </div>
    @endif
</div>
@endsection