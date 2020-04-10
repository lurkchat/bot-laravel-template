@extends('layouts.app')

@section('content')
    @component('components.content_header', ['text' => 'Administrators']) @endcomponent

    @component('components.alerts') @endcomponent

    <div class="mt-2">
        <a href="{{ route('users.create') }}" class="btn btn-success mb-2">Add</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created at</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td class="d-flex ">
                            <a href="{{ route('users.edit', ['user' => $user]) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('users.destroy', ['user' => $user]) }}" method="post">
                                @method('delete')
                                @csrf
                                <button type="submit" class="btn btn-danger ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
