@extends('layouts.default')

@section('title', 'Teacher Dashboard')
@section('content')
<h1>Welcome {{ $firstName ?? 'User' }}</h1>


<h2>Find the list of classes below</h2>
@if ($classes->isEmpty())
    <p>You are not assigned to any classes.</p>
@else
    <ul>
        @foreach ($classes as $class)
            <li>
                <a href="{{ route('class.pupils', $class->id) }}">
                    {{ $class->ClassName }} {{ $class->Subject }}
                </a>
            </li>
        @endforeach
    </ul>
@endif
@endsection