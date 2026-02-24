@extends('layouts.default')


@section('title', 'Pupil Manager')

@section('content')  
<h1>Pupil Manager </h1>
    

        <a href="{{ route('CreatePupil') }}">
    <button type="button">Create New Pupil </button>

@endsection
