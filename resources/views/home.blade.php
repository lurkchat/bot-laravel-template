@extends('layouts.app')

@section('content')
    @component('components.content_header', ['text' => 'Dashboard']) @endcomponent

    <div class="card-body">
        Welcome to bot admin panel
    </div>
@endsection
