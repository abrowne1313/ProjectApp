@extends('layouts.app')

@section('content')
<h2>Search results for "{{ $query }}" ({{ ucfirst($type) }})</h2>

@if($results->isEmpty())
    <p>No results found.</p>
@else
    <ul>
        @foreach($results as $result)
            <li>
                {{ $result->FirstName }} {{ $result->Surname }}
            </li>
        @endforeach
    </ul>
@endif
@endsection
