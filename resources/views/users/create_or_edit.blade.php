@extends('layouts.app')

@section('content')
    @component('components.content_header', ['text' => $user ? 'Edit administrator' : 'Add administrator']) @endcomponent

    @component('components.alerts') @endcomponent

    <div class="mt-2 col-4">
        <form action="{{ $user ? route('users.update', ['user' => $user]) : route('users.store') }}" method="post">
            @csrf
            @if($user)
                @method('put')
            @endif
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user ? $user->name : null }}">
            </div>
            <div class="form-group">
                <label for="login">Login</label>
                <input type="text" class="form-control" id="login" name="login" value="{{ $user ? $user->login : null }}">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" class="form-control" id="password" name="password">
            </div>
            <button type="submit" class="btn btn-success">{{ $user ? 'Save' : 'Add' }}</button>
        </form>
    </div>
@endsection
