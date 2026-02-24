@extends('layouts.default')


@section('title', 'User Manager')

@section('content')  
<h1>User Management </h1>
    


    <a href="{{ route('CreateUser') }}">
    <button type="button">Create New User</button>

        <a href="{{ route('ChangeUserPassword') }}">
    <button type="button">Change User Password</button>

        <a href="{{ route('EditUser') }}">
    <button type="button">Edit Existing User</button>


@endsection
